#!/usr/local/bin/ruby -Ku

# cronolog/rotatelogsの代用ツール
#
# 設置例:
# LogFormat "%V %h %l %u %t \"%r\" %>s %b \"%{Referer}i\" \"%{User-Agent}i\"" vhost_combined
# <VirtualHost *>
#   VirtualDocumentRoot /home/carrot/sites/%0/www
#   CustomLog "|/root/bin/divlog.rb" vhost_combined
# </VirtualHost>
#
# @package org.carrot-framework
# @author 小石達也 <tkoishi@b-shock.co.jp>
# @version $Id: divlog.rb 2301 2010-08-24 09:18:49Z pooza $

LOG_DIR = '/var/log/httpd/'

def dig (target_path)
  path = ''
  path_array = target_path.split('/')
  path_array.shift
  path_array.pop
  path_array.each do |part|
    path += '/' + part
    begin
      Dir.mkdir(path)
    rescue
    end
  end
end

ARGF.each do |line|
  entry = line.split(' ')
  path = LOG_DIR + entry.shift + '/' + Time.now.strftime('%Y/%m/access_%Y%m%d') + '.log'
  dig(path)

  File.open(path, 'a') do |file|
    file.puts entry.join(' ')
  end
end

