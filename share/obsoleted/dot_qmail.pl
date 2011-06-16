#!/usr/bin/perl

#===============================================================================
# ¥Ğ¥¦¥ó¥¹¥á¡¼¥ë½èÍı¥×¥í¥°¥é¥à
#
# ³«È¯¼Ô¡§ b-shock. ¾®ÀĞÃ£Ìé <tkoishi@b-shock.org>
# Ê¸»ú¥³¡¼¥É¡§ EUC
# ²ş¹Ô¥³¡¼¥É¡§ LF
# ¸ß´¹À­¡§ Perl 5.x
# É¬Í×¤Ê¥â¥¸¥å¡¼¥ë: DBD::Sybase
#
# ¹¹¿·ÍúÎò
#
# 1.0.1 (2004/08/09)
#  ¡¦DBÀÜÂ³¥Ñ¥é¥á¡¼¥¿¤òÀ°Íı
#
# 1.0.0 (2004/08/09)
#  ¡¦ºîÀ®
#===============================================================================

# ÄêµÁ =========================================================================

# DBÀÜÂ³
use DBI;
$ENV{'SYBASE'} = '/usr/local/sybase';
my $DB_SERVERNAME = 'usen-221x117x83x22.ap-US01.usen.ad.jp';
my $DB_SERVERPORT = '5930';
my $DB_DBNAME = 'tomouke';
my $DB_USERNAME = 'sa';
my $DB_PASSWORD = '';

# ¥Ğ¥¦¥ó¥¹¥á¡¼¥ë¥Ñ¥¿¡¼¥ó
my @arrayPtn = (
	[ # Subject¥Ø¥Ã¥À
		qr/^Subject: failure notice$/,
		qr/^Subject: Mail System Error \- Returned Mail$/,
	],
	[ # ËÜÊ¸Ãæ¤ÎÆÃÄ§Åª¤Ê1¹Ô
		qr/^I\'m afraid I wasn\'t able to deliver your message/,
		qr/^The user\(s\) account is disabled\./,
	],
	[ # Ää»ß¤¹¤ë¥¢¥É¥ì¥¹¤ò´Ş¤à¹Ô
		qr/^Remote host said: 550 Unknown user (.+)$/,
		qr/^Remote host said: 550 Invalid recipient: \<([^\>]+)\>$/,
		qr/^\<([^\>]+)\>: user unknown$/,
		qr/^\<([^\>]+)\>$/,
	],
);

# ¥µ¥Ö¥ë¡¼¥Á¥ó =================================================================

sub updatedb {
	my $addr = shift;

	# ÀÜÂ³Ê¸»úÎó
	my $DB_CONNECT_NAME = 'dbi:Sybase:'
		. 'server=' . $DB_SERVERNAME . ':' . $DB_SERVERPORT . ';'
		. 'database=' . $DB_DBNAME;

	# ÀÜÂ³
	my $db = DBI->connect($DB_CONNECT_NAME, $DB_USERNAME, $DB_PASSWORD);
	if (!defined($db)) {
		die $DBI::errstr;
	}
	my $sql = 'UPDATE tomouke_users '
		. 'SET error=1 '
		. 'WHERE mail=\'' . $addr . '\'';
	my $sth = $db->prepare($sql);
	$sth->execute;

	# ÀÚÃÇ
	$db->disconnect();
}

# ½èÍı³«»Ï =====================================================================

my $progress = 0;
while (<STDIN>) {
	my $line = $_;
	$line =~ s/[\r\n]//g;

	for my $i (0...$#{$arrayPtn[$progress]}) {
		if ($line =~ $arrayPtn[$progress][$i]) {
			if ($progress < 2) {
				$progress ++;
			} else {
				updatedb($1);
			}
		}
	}
}
exit(0);