#!/usr/bin/perl

#===============================================================================
# バウンスメール処理プログラム
#
# 開発者： b-shock. 小石達也 <tkoishi@b-shock.org>
# 文字コード： EUC
# 改行コード： LF
# 互換性： Perl 5.x
# 必要なモジュール: DBD::Sybase
#
# 更新履歴
#
# 1.0.1 (2004/08/09)
#  ・DB接続パラメータを整理｝
#
# 1.0.0 (2004/08/09)
#  ・作成
#===============================================================================

# 定義 =========================================================================

# DB接続
use DBI;
$ENV{'SYBASE'} = '/usr/local/sybase';
my $DB_SERVERNAME = 'usen-221x117x83x22.ap-US01.usen.ad.jp';
my $DB_SERVERPORT = '5930';
my $DB_DBNAME = 'tomouke';
my $DB_USERNAME = 'sa';
my $DB_PASSWORD = '';

# バウンスメールパターン
my @arrayPtn = (
	[ # Subjectヘッダ
		qr/^Subject: failure notice$/,
		qr/^Subject: Mail System Error \- Returned Mail$/,
	],
	[ # 本文中の特徴的な1行
		qr/^I\'m afraid I wasn\'t able to deliver your message/,
		qr/^The user\(s\) account is disabled\./,
	],
	[ # 停止するアドレスを含む行
		qr/^Remote host said: 550 Unknown user (.+)$/,
		qr/^Remote host said: 550 Invalid recipient: \<([^\>]+)\>$/,
		qr/^\<([^\>]+)\>: user unknown$/,
		qr/^\<([^\>]+)\>$/,
	],
);

# サブルーチン =================================================================

sub updatedb {
	my $addr = shift;

	# 接続文字列
	my $DB_CONNECT_NAME = 'dbi:Sybase:'
		. 'server=' . $DB_SERVERNAME . ':' . $DB_SERVERPORT . ';'
		. 'database=' . $DB_DBNAME;

	# 接続
	my $db = DBI->connect($DB_CONNECT_NAME, $DB_USERNAME, $DB_PASSWORD);
	if (!defined($db)) {
		die $DBI::errstr;
	}
	my $sql = 'UPDATE tomouke_users '
		. 'SET error=1 '
		. 'WHERE mail=\'' . $addr . '\'';
	my $sth = $db->prepare($sql);
	$sth->execute;

	# 切断
	$db->disconnect();
}

# 処理開始 =====================================================================

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