#!/usr/bin/perl

#===============================================================================
# �Х��󥹥᡼������ץ����
#
# ��ȯ�ԡ� b-shock. ����ã�� <tkoishi@b-shock.org>
# ʸ�������ɡ� EUC
# ���ԥ����ɡ� LF
# �ߴ����� Perl 5.x
# ɬ�פʥ⥸�塼��: DBD::Sybase
#
# ��������
#
# 1.0.1 (2004/08/09)
#  ��DB��³�ѥ�᡼����������
#
# 1.0.0 (2004/08/09)
#  ������
#===============================================================================

# ��� =========================================================================

# DB��³
use DBI;
$ENV{'SYBASE'} = '/usr/local/sybase';
my $DB_SERVERNAME = 'usen-221x117x83x22.ap-US01.usen.ad.jp';
my $DB_SERVERPORT = '5930';
my $DB_DBNAME = 'tomouke';
my $DB_USERNAME = 'sa';
my $DB_PASSWORD = '';

# �Х��󥹥᡼��ѥ�����
my @arrayPtn = (
	[ # Subject�إå�
		qr/^Subject: failure notice$/,
		qr/^Subject: Mail System Error \- Returned Mail$/,
	],
	[ # ��ʸ�����ħŪ��1��
		qr/^I\'m afraid I wasn\'t able to deliver your message/,
		qr/^The user\(s\) account is disabled\./,
	],
	[ # ��ߤ��륢�ɥ쥹��ޤ��
		qr/^Remote host said: 550 Unknown user (.+)$/,
		qr/^Remote host said: 550 Invalid recipient: \<([^\>]+)\>$/,
		qr/^\<([^\>]+)\>: user unknown$/,
		qr/^\<([^\>]+)\>$/,
	],
);

# ���֥롼���� =================================================================

sub updatedb {
	my $addr = shift;

	# ��³ʸ����
	my $DB_CONNECT_NAME = 'dbi:Sybase:'
		. 'server=' . $DB_SERVERNAME . ':' . $DB_SERVERPORT . ';'
		. 'database=' . $DB_DBNAME;

	# ��³
	my $db = DBI->connect($DB_CONNECT_NAME, $DB_USERNAME, $DB_PASSWORD);
	if (!defined($db)) {
		die $DBI::errstr;
	}
	my $sql = 'UPDATE tomouke_users '
		. 'SET error=1 '
		. 'WHERE mail=\'' . $addr . '\'';
	my $sth = $db->prepare($sql);
	$sth->execute;

	# ����
	$db->disconnect();
}

# �������� =====================================================================

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