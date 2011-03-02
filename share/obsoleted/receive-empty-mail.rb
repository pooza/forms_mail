#!/usr/local/bin/ruby -Ku
#===============================================================================
# 空メール受信スクリプト
# 2010/04/14 小石達也 <tkoishi@b-shock.co.jp>
#===============================================================================

require 'net/smtp'
require 'socket'
require 'kconv'
require 'mailparser'

SMTP_HOST = 'localhost'
SMTP_PORT = 25
MAIL_SUBJECT = '無題'
MAIL_TIMEZONE = '+0900'
MAIL_BODY_FILE = '/home/minc/empty-mail/test/reply-message.txt'
OUTPUT_FILE = '/home/minc/empty-mail/test/senders.txt'

#== 関数 =======================================================================

def send_message (from, to)
  headers = {
    'Subject' => encode_header(MAIL_SUBJECT),
    'From' => from,
    'To' => to,
    'X-Mailer' => '$Id$',
    'Message-ID' => format('<%0.10f@%s>', Time::now.to_f, Socket.gethostname),
    'Date' => Time::now.strftime('%a, %d %b %Y %H:%M:%S') + ' ' + MAIL_TIMEZONE,
    'Mime-Version' => '1.0',
    'Content-Transfer-Encoding' => '7bit',
    'Content-Type' => 'text/plain; charset=iso-2022-jp',
  }
  contents = []
  headers.each do |key, value|
    contents.push(format('%s: %s', key, value))
  end
  contents.push('')
  contents.push(Kconv.tojis(File.read(MAIL_BODY_FILE)))

  smtp = Net::SMTPSession.new(SMTP_HOST, SMTP_PORT)
  smtp.start
  smtp.sendmail(contents.join("\r\n"), from, to)
  smtp.finish
end

def encode_header (str)
  return '=?ISO-2022-JP?B?' + Kconv.tojis(str).split(//,1).pack('m').chomp + '?='
end

def output (address)
  File.open(OUTPUT_FILE, 'a') do |f|
    f.write(address + "\n")
  end
end

#== 処理開始 ===================================================================

mail = MailParser::Message.new(STDIN)
from = mail.from.addr_spec.to_s
to = mail.to[0].addr_spec.to_s
send_message(to, from)
output(from)
