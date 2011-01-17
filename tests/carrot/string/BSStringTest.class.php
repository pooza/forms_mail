<?php
/**
 * @package org.carrot-framework
 */

/**
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 * @version $Id: BSStringTest.class.php 2460 2011-01-14 08:01:54Z pooza $
 * @abstract
 */
class BSStringTest extends BSTest {
	public function execute () {
		$string = BSString::convertWrongCharacters('㈱㈲');
		$this->assert('convertWrongCharacters', $string == '(株)(有)');

		$file = BSFileUtility::getDirectory('sample')->getEntry('dirty.csv', 'BSCSVFile');
		$records = BSString::convertWrongCharacters($file->getEngine()->getRecords());
		$this->assert('convertWrongCharacters', $records[0][1] == '(有)');
		$this->assert('convertWrongCharacters', $records[2][0] == '(2)');
	}
}

/* vim:set tabstop=4: */
