<div class="inner-bg" style="background-image: url({{ asset_frontend('images/bg2x-p-1080.jpeg') }});"></div>

@if(Request::is('/'))
<div id="footer" class="home-footer">
    <div>Copyright © {{ now()->year }} {{ $site_title['page_title'] }}. All Rights Reserved. <a href="#" class="footer-link txt-color-white">Web Design</a> by EPA</div>
</div>
@else
<div class="footer">
    <div class="text-block">Copyright © {{ now()->year }} {{ $site_title['page_title'] }}. All Rights Reserved. <a href="#" class="footer-link">Web Design</a> by EPA</div>
</div>
@endif