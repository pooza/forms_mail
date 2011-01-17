/**
 * エレベータ処理
 *
 * @package org.carrot-framework
 * @author 小石達也 <tkoishi@b-shock.co.jp>
 * @version $Id: elevator.js 1808 2010-02-03 03:55:50Z pooza $
 */

function Elevator (element, x, yMin, yMargin) {
  new PeriodicalExecuter(move, 0.1);

  function move () {
    if (Prototype.Browser.IE){
      var y = (document.body.scrollTop || document.documentElement.scrollTop);
    } else  {
      var y = self.pageYOffset;
    }
    if (y < yMin) {
      y = yMin;
    } else {
      y = y + yMargin;
    }

    element.style.position = 'absolute';
    element.style.left = x + 'px';
    element.style.top = y + 'px';
  }
}
