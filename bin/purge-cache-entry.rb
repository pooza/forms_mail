#!/usr/local/bin/ruby -Ku

# mod_disk_cacheの古いキャッシュをパージ
#
# @package org.carrot-framework
# @author 小石達也 <tkoishi@b-shock.co.jp>
# @version $Id: purge-cache-entry.rb 2296 2010-08-19 09:10:24Z pooza $

PURGE_DIR = '/home/*/proxy/*'
PURGE_CMD = '/usr/local/apache2/bin/htcacheclean'
LIMIT = '512M'

Dir.glob(PURGE_DIR).each do |path|
  system(PURGE_CMD + ' -n -t -p' + path + ' -l' + LIMIT)
end