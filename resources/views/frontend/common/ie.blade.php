<div class="outdated_ie_popup" style="display:none;width:639px;height:460px;">
    <img src="<?=asset_frontend_common('images/ie.png')?>" alt="" usemap="#Map">
    <map name="Map" id="Map">
      <area shape="rect" coords="355,370,420,460" href="http://windows.microsoft.com/zh-hk/internet-explorer/download-ie" target="_blank" />
      <area shape="rect" coords="290,370,350,460" href="http://www.mozilla.org/en-US/firefox/new/" target="_blank" />
      <area shape="rect" coords="215,370,285,460" href="https://www.google.com/intl/zh-TW/chrome/browser/" target="_blank" />
    </map>
</div>
<script>
    jQuery(document).ready(function($){
        if (navigator.userAgent.indexOf("MSIE") >= 0) {
            $('.outdated_ie_popup').bPopup({
                modalColor: '#ffffff',
                opacity: 0.9
            }, function(){
                $(window).resize();
            });
        }
    })
</script>