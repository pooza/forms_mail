{*
GoogleAnalyticsテンプレート

@package org.carrot-framework
@author 小石達也 <tkoishi@b-shock.co.jp>
@version $Id: GoogleAnalytics.tpl 2444 2010-12-13 05:40:44Z pooza $
*}

<script type="text/javascript">
var _gaq = _gaq || [];
_gaq.push(['_setAccount', 'UA-{$google_analytics.id}']);
_gaq.push(['_trackPageview']);

(function() {ldelim}
  var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
  ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
  var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
{rdelim})();
</script>

{* vim: set tabstop=4: *}
