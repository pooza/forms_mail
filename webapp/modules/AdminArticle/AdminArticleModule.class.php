<?php
/**
 * AdminArticleモジュール
 *
 * @package jp.co.commons.forms.mail
 * @subpackage AdminArtile
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 * @version $Id: AdminPageModule.class.php 4863 2010-07-20 03:58:46Z pooza $
 */
class AdminArticleModule extends BSModule {

	/**
	 * 接続を返す
	 *
	 * @access public
	 * @return Connection 接続
	 */
	public function getConnection () {
		$module = BSModule::getInstance('AdminConnection');
		if (!$module->getRecord()) {
			if ($article = $this->getRecord()) {
				$module->setRecordID($article->getConnection());
			}
		}
		return $module->getRecord();
	}
}

/* vim:set tabstop=4: */
