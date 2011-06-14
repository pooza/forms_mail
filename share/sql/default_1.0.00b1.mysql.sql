-- @package jp.co.b-shock.forms.mail
-- @author 小石達也 <tkoishi@b-shock.co.jp>
-- @version $Id: default_2.1.16.sqlite.sql 5943 2011-02-08 12:47:25Z pooza $

SET NAMES 'utf8';
ALTER TABLE `article` ADD `body_mobile` text NULL DEFAULT NULL  AFTER `body`;
