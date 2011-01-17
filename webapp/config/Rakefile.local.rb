#!/usr/bin/env rake

# carrotユーティリティタスク
#
# @package __PACKAGE__
# @author 小石達也 <tkoishi@b-shock.co.jp>
# @version $Id: Rakefile.local.rb 2276 2010-08-15 04:55:47Z pooza $

$KCODE = 'u'

namespace :local do
  task :init => ['database:init']

  namespace :database do
    task :init => []
  end
end