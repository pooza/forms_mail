/**
 * アプリケーション ユーティリティ関数
 *
 * @package jp.co.commons.forms.mail
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 */

var FormsMailLib = {
  setCriteriaStatus: function (id, flag) {
    var container = $('criteria_' + id);
    $$('#' + container.id + ' .choices input').each(function (element) {
      element.checked = !!flag;
    });
  },

  initialized: true
};
