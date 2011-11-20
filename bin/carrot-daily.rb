#!/usr/bin/env ruby -Ku

# 1日ごとに実行する処理
#
# @package org.carrot-framework
# @author 小石達也 <tkoishi@b-shock.co.jp>

path = File.expand_path(__FILE__)
while (File.ftype(path) == 'link')
  path = File.expand_path(File.readlink(path))
end
ROOT_DIR = File.dirname(File.dirname(path))
$LOAD_PATH.push(ROOT_DIR + '/lib/ruby')

require 'carrot/batch_action'

actions = BatchAction.new
actions.register('Console', 'Purge')
actions.execute
