-- @package jp.co.b-shock.forms.mail
-- @author 小石達也 <tkoishi@b-shock.co.jp>

SET NAMES 'utf8';
ALTER TABLE `article` ADD `body_mobile` text NULL DEFAULT NULL  AFTER `body`;
