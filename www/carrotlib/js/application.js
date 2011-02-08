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

  putTemplateField: function (field, name) {
    var tag = '{$registration.' + name + '}';
    if (Prototype.Browser.IE) {
      field.focus();
      field.document.selection.createRange().text = tag;
    } else {
      var position = field.selectionStart;
      field.value = field.value.substr(0, position)
        + tag
        + field.value.substr(field.selectionEnd, field.value.length);
      field.selectionStart = position + tag.length;
      field.selectionEnd = field.selectionStart;
    }
  },

  initialized: true
};
