/**
 * carrot汎用 JavaScript
 *
 * @package org.carrot-framework
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 * @version $Id: carrot.js 2352 2010-09-20 07:15:23Z pooza $
 */

var CarrotLib = {
  redirect: function (module, action, id) {
    var url = '/' + module + '/' + action;
    if (id) {
      url += '/' + id;
    }
    window.location.href = url;
  },

  confirmDelete: function (module, action, recordType, id) {
    if (confirm('この' + recordType + 'を削除しますか？')) {
      CarrotLib.redirect(module, action, id);
    }
  },

  openPictogramPallet: function (id) {
    window.open(
      '/AdminUtility/Pictogram?field=' + id,
      'pictogram',
      'width=240,height=300,scrollbars=yes'
    );
  },

  putSmartTag: function (tag, field, name, params) {
    var tag = '[[' + tag;
    if (name) {
      name = name.gsub(':', '\\:').gsub('[', '\\[').gsub(']', '\\]');
      name = name.gsub('：', '\\:').gsub('［', '\\[').gsub('］', '\\]'); //全角
      tag += ':' + name;
      if (params) {
        var encoded = [];
        for(var key in params) {
          if (params[key] != null) {
            encoded.push(key + '=' + encodeURIComponent(params[key]));
          }
        }
        if (0 < encoded.length) {
          tag += ':' + encoded.join(';');
        }
      }
    }
    tag += ']]';
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

  handleUploadProgress: function (element) {
    var progress = new JS_BRAMUS.jsProgressBar(element, 0);
    function updateProgress (request) {
      if (request.responseText) {
        var json = request.responseText.evalJSON();
        progress.setPercentage(json.current / json.total * 100);
      }
    }
    new PeriodicalExecuter(function () {
      new Ajax.Request('/UploadProgress', {
        method: 'get',
        parameters: 'd=' + new Date().getTime(),
        onComplete: updateProgress
      });
    }, 1);
  },

  denyTakeOut: function (selector_name) {
    var doNothing = function () {return false;}
    var configureElement = function (element) {
      if (!element.oncontextmenu) {
        element.oncontextmenu = doNothing;
        element.onselectstart = doNothing;
        element.onmousedown = doNothing;
        element.unselectable = 'on';
        element.galleryimg = 'no';
      }
      if (Prototype.Browser.MobileSafari) {
        var cover = document.createElement('img');
        cover.src = '/carrotlib/images/spacer.gif';
        Element.setStyle(cover, {
          'left': element.offsetLeft + 'px',
          'top': element.offsetTop + 'px',
          'width': element.width + 'px',
          'height': element.height + 'px',
          'position': 'absolute'
        });
        element.parentNode.appendChild(cover);
      }
    }
  
    if (!selector_name) {
      selector_name = '.deny_take_out';
    }
    var elements = $$(selector_name);
    for (var i = 0 ; i < elements.length ; i ++) {
      configureElement(elements[i]);
    }
  },

  // @link http://memorandum.char-aznable.com/web_design/javascript.html
  backToTop: function () {
    var x1 = x2 = x3 = 0;
    var y1 = y2 = y3 = 0;
    if (document.documentElement) {
      x1 = document.documentElement.scrollLeft || 0;
      y1 = document.documentElement.scrollTop || 0;
    }
    if (document.body) {
      x2 = document.body.scrollLeft || 0;
      y2 = document.body.scrollTop || 0;
    }
    x3 = window.scrollX || 0;
    y3 = window.scrollY || 0;
    var x = Math.max(x1, Math.max(x2, x3));
    var y = Math.max(y1, Math.max(y2, y3));
    window.scrollTo(Math.floor(x / 2), Math.floor(y / 2));
    if (x > 0 || y > 0) {
      window.setTimeout('CarrotLib.backToTop()', 25);
    }
  },

  initialized: true
};

if (!String.prototype.trim) {
  String.prototype.trim = function () {
    return this.replace(/^[ ]+|[ ]+$/g, '');
  }
}
if (!Number.prototype.suppressZero) {
  Number.prototype.suppressZero = function (n) {
    var str = '';
    var zerolen = n - ('' + this).length;
    for (var i = 0 ; i < zerolen ; i ++) {
      str += '0';
    }
    str += this;
    return str;
  }
}

document.observe('dom:loaded', function () {
  try {
    AjaxZip3.JSONDATA = document.location.protocol
      + '//ajaxzip3.googlecode.com/svn/trunk/ajaxzip3/zipdata';
  } catch (e) {
  }
});
