#!/usr/local/bin/ruby -Ku
# encoding: utf-8

# 記事の送信
#
# @package jp.co.commons.forms.mail
# @author 小石達也 <tkoishi@b-shock.co.jp>

path = File.expand_path(__FILE__)
while (File.ftype(path) == 'link')
  path = File.expand_path(File.readlink(path))
end
ROOT_DIR = File.dirname(File.dirname(path))
$LOAD_PATH.push(ROOT_DIR + '/lib/ruby')
$LOAD_PATH.push(ROOT_DIR)

require 'carrot/batch_action'

actions = BatchAction.new
actions.register('ConsoleArticle', 'Publish')
actions.execute
