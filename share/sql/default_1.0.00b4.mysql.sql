-- @package jp.co.b-shock.forms.mail
-- @author 小石達也 <tkoishi@b-shock.co.jp>

SET NAMES 'utf8';
ALTER TABLE `connection` ADD `replyto_email` varchar(64) NULL DEFAULT NULL  AFTER `sender_email`;
