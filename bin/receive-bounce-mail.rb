#!/usr/local/bin/ruby -Ku

# 空メールの受信
#
# @package jp.co.commons.forms.mail
# @author 小石達也 <tkoishi@b-shock.co.jp>

path = File.expand_path(__FILE__)
while (File.ftype(path) == 'link')
  path = File.expand_path(File.readlink(path))
end
ROOT_DIR = File.dirname(File.dirname(path))
$LOAD_PATH.push(ROOT_DIR + '/lib/ruby')

require 'digest/sha1'
require 'net/http'
require 'uri'
require 'mailparser'
require 'carrot/constants'
require 'carrot/environment'

PATTERNS = [
  /Remote host said: 550 Unknown user (.+)/,
  /Remote host said: 550 Invalid recipient: \<([^>]+)\>/,
  /Final-Recipient: rfc822; (.+)/,
  /\<([^>]+)\>/,
]

def get_address (mail)
  PATTERNS.each do |pattern|
    if (matches = mail.body.match(pattern))
      return matches[1].chomp
    end
    mail.part.each do |part|
      if (matches = part.body.match(pattern))
        return matches[1].chomp
      end
    end
  end
  raise 'E-mail address not found.'
end

begin
  mail = MailParser::Message.new(STDIN)
  from = get_address(mail)
  to = mail.to[0].addr_spec.to_s.chomp
rescue
  raise 'Could not parse this email.'
end

path = '/AgentRecipient/Resign'
params = []
params.push('from=' + URI.encode(from))
params.push('to=' + URI.encode(to))
params.push('api_key=' + Digest::SHA1.hexdigest("0\t" + path + Constants.new['BS_CRYPT_SALT']))

Net::HTTP.start(Environment.name) do |http|
  response = http.get(path + '?' + params.join('&'))
  if response.code.to_i != 200
    raise response['status']
  end
end
