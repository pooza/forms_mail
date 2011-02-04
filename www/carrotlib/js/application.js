/**
 * アプリケーション ユーティリティ関数
 *
 * @package jp.co.commons.forms.mail
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 */

var FormsMailLib = {
  setCriteriaActivity: function (id, flag) {
    if (Prototype.Browser.IE) {
      return;
    }
    var container = $('criteria_' + id);
    if (container) {
      $$('#' + container.id + ' .choices input').each(function (element) {
        element.disabled = !flag;
      });
      $('criteria_' + id + '_checkall').disabled = !flag;
      $('criteria_' + id + '_uncheckall').disabled = !flag;
    }
  },

  setCriteriaStatus: function (id, flag) {
    var container = $('criteria_' + id);
    if (container) {
      $$('#' + container.id + ' .choices input').each(function (element) {
        if (!element.disabled) {
          element.checked = !!flag;
        }
      });
    }
  },

  initialized: true
};
