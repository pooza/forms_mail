<?php
/**
 * @package org.carrot-framework
 * @subpackage xml.feed.rss20
 */

/**
 * RSS2.0文書
 *
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 * @version $Id: BSRSS20Document.class.php 1812 2010-02-03 15:15:09Z pooza $
 */
class BSRSS20Document extends BSRSS09Document {
	protected $version = '2.0';

	/**
	 * 妥当な文書か？
	 *
	 * @access public
	 * @return boolean 妥当な文書ならTrue
	 */
	public function validate () {
		return (BSXMLDocument::validate()
			&& $this->query('/rss/channel/title')
			&& $this->query('/rss/channel/description')
			&& $this->query('/rss/channel/link')
		);
	}
}

/* vim:set tabstop=4: */
