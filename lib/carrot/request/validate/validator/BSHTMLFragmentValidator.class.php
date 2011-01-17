<?php
/**
 * @package org.carrot-framework
 * @subpackage request.validate.validator
 */

/**
 * HTMLフラグメントバリデータ
 *
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 * @version $Id: BSHTMLFragmentValidator.class.php 2035 2010-04-25 04:03:04Z pooza $
 */
class BSHTMLFragmentValidator extends BSValidator {
	private $allowedTags;
	private $invalidNode;

	/**
	 * 初期化
	 *
	 * @access public
	 * @param string[] $params パラメータ配列
	 */
	public function initialize ($params = array()) {
		$this['element_error'] = '許可されていない要素又は属性が含まれています。';
		$this['allowed_tags'] = 'a,br,div,li,ol,p,span,ul';
		$this['javascript_allowed'] = false;
		return parent::initialize($params);
	}

	/**
	 * 実行
	 *
	 * @access public
	 * @param mixed $value バリデート対象
	 * @return boolean 妥当な値ならばTrue
	 */
	public function execute ($value) {
		try {
			$body = str_replace('&', '', $value); //実体参照を無視
			$body = '<div>' . $body . '</div>';
			$element = new BSXMLElement;
			$element->setContents($body);
			if (!self::isValidElement($element)) {
				$message = new BSStringFormat('%s (%s)');
				$message[] = $this['element_error'];
				$message[] = $this->invalidNode;
				throw new BSXMLException($message);
			}
		} catch (BSXMLException $e) {
			$this->error = $e->getMessage();
			return false;
		}
		return true;
	}

	/**
	 * 許可された要素と属性だけで構成されているか？
	 *
	 * @access private
	 * @param BSXMLElement $element 評価対象のフラグメント
	 * @return boolean 問題なしならTrue
	 */
	private function isValidElement (BSXMLElement $element) {
		if (!!$element->getElements()->count()) {
			foreach ($element as $child) {
				if (!self::isValidElement($child)) {
					return false;
				}
			}
		}

		$tags = $this->getAllowedTags();
		if (!!$tags->count() && !$tags->isContain($element->getName())) {
			$this->invalidNode = $element->getName() . '要素';
			return false;
		}
		if (!$this->isJavaScriptAllowed()) {
			if (BSString::toLower($element->getName()) == 'script') {
				$this->invalidNode = $element->getName() . '要素';
				return false;
			}
			foreach ($element->getAttributes() as $name => $value) {
				if (mb_eregi('^on', $name) || mb_eregi('javascript:', $value)) {
					$this->invalidNode = sprintf('%s要素/%s属性', $element->getName(), $name);
					return false;
				}
			}
		}

		return true;
	}

	/**
	 * 許可された要素名を配列で帰す
	 *
	 * @access private
	 * @return BSArray 許可された要素名の配列
	 */
	private function getAllowedTags () {
		if (!$this->allowedTags) {
			$this->allowedTags = new BSArray;
			if ($tags = $this['allowed_tags']) {
				$this->allowedTags[] = 'div';
				$this->allowedTags[] = 'span';
	
				if (!is_array($tags)) {
					$tags = BSString::explode(',', $tags);
				}
				$this->allowedTags->merge($tags);
				$this->allowedTags->trim();
				$this->allowedTags->uniquize();
			}
		}
		return $this->allowedTags;
	}

	/**
	 * JavaScriptは許可されているか？
	 *
	 * @access private
	 * @return boolean 許可されているならTrue
	 */
	private function isJavaScriptAllowed () {
		return !!$this['javascript_allowed'];
	}
}

/* vim:set tabstop=4: */
