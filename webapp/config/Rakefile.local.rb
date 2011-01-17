#!/usr/bin/env rake

# carrotユーティリティタスク
#
# @package jo.co.commons.forms.mail
# @author 小石達也 <tkoishi@b-shock.co.jp>
# @version $Id$

$KCODE = 'u'

namespace :local do
  task :init => ['database:init']

  namespace :database do
    task :init => []
  end
end