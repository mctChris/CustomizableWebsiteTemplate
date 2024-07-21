<?php   
if ( ! function_exists('asset_admin'))
{
    function asset_admin($url) {
        return asset('assets/admin/' . $url);
    }
}

if ( ! function_exists('asset_frontend_common'))
{
    function asset_frontend_common($url) {
        return asset('assets/frontend/common/' . $url);
    }
}

if ( ! function_exists('asset_frontend'))
{
    function asset_frontend($url) {
        return asset_frontend_common($url);
    }
}

if ( ! function_exists('asset_frontend_lang'))
{
    function asset_frontend_lang($url) {
        return asset('assets/frontend/' . app()->getLocale() . '/' . $url);
    }
}

if ( ! function_exists('format_file_size'))
{
    function format_file_size($bytes)
    {
        if ($bytes >= 1073741824)
        {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        }
        elseif ($bytes >= 1048576)
        {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        }
        elseif ($bytes >= 1024)
        {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        }
        elseif ($bytes > 1)
        {
            $bytes = $bytes . ' bytes';
        }
        elseif ($bytes == 1)
        {
            $bytes = $bytes . ' byte';
        }
        else
        {
            $bytes = '0 bytes';
        }

        return $bytes;
    }
}
if ( ! function_exists('currentRouteAction'))
{
    function currentRouteAction() {
        $action = explode('@', \Route::currentRouteAction());
        return end($action);
    }
}
if ( ! function_exists('redirect_now'))
{
    function redirect_now($url, $code = 302)
    {
        try {
            \App::abort($code, '', ['Location' => $url]);
        } catch (\Exception $exception) {
            // the blade compiler catches exceptions and rethrows them
            // as ErrorExceptions :(
            //
            // also the __toString() magic method cannot throw exceptions
            // in that case also we need to manually call the exception
            // handler
            $previousErrorHandler = set_exception_handler(function () {
            });
            restore_error_handler();
            call_user_func($previousErrorHandler, $exception);
            die;
        }
    }
}

if ( ! function_exists('lang'))
{
    function lang($string_key){
        if($translation = app('lang')->where('string_key', $string_key)->first()){
            if(substr_count($translation->description, '</p>') == 1){
                return preg_replace("/(^<p>|<\/p>$)/", '', $translation->description);
            }
            return $translation->description;
        }
        return $string_key;
    }
}

if ( ! function_exists('current_lang'))
{
    function current_lang(){
        return app('current_lang');
    }
}

if ( ! function_exists('active_langs'))
{
    function active_langs(){
        return app('active_langs');
    }
}

function color($color_json, $default = [245, 166, 35, 0.8]){
    $color = $color_json ? array_values(json_decode($color_json, true)) : $default;
    list($r, $g, $b, $a) = $color;
    return "rgba($r, $g, $b, $a)";
}

function colorHex($color){
    if(!preg_match("/^#/", $color)){
        $color = '#' . $color;
    }

    return $color;
}

function editor($html){
    $html = preg_replace("/src=\"(storage[^\"]+)\"/", "src=\"" . env('APP_URL') . "/$1" . "\"", $html);
    return $html;
}
function hex2Rgb($hex, $opacity = false){
    list($r, $g, $b) = sscanf($hex, "#%02x%02x%02x");
    if($opacity !== false){
        return "rgba($r, $g, $b, $opacity)";
    }
    return "rgb($r, $g, $b)";
}
function hexA2Rgb($hex, $a){
    list($r, $g, $b) = sscanf($hex, "#%02x%02x%02x");
    $r = ((1 - $a) * 255) + ($a * $r);
    $g = ((1 - $a) * 255) + ($a * $g);
    $b = ((1 - $a) * 255) + ($a * $b);
    return "rgb($r, $g, $b)";
}

function slug($string, $separator) {
    $slug = mb_strtolower(
        preg_replace('/([?]|\p{P}|\s)+/u', $separator, $string)
    );
    return trim($slug, $separator);
}

function adjustBrightness($hex, $steps) {
    // Steps should be between -255 and 255. Negative = darker, positive = lighter
    $steps = max(-255, min(255, $steps));

    // Normalize into a six character long hex string
    $hex = str_replace('#', '', $hex);
    if (strlen($hex) == 3) {
        $hex = str_repeat(substr($hex,0,1), 2).str_repeat(substr($hex,1,1), 2).str_repeat(substr($hex,2,1), 2);
    }

    // Split into three parts: R, G and B
    $color_parts = str_split($hex, 2);
    $return = '#';

    foreach ($color_parts as $color) {
        $color   = hexdec($color); // Convert to decimal
        $color   = max(0,min(255,$color + $steps)); // Adjust color
        $return .= str_pad(dechex($color), 2, '0', STR_PAD_LEFT); // Make two char hex code
    }

    return $return;
}

function svg_encode($svg){
    $svg = preg_replace("/</", urlencode("<"), $svg);
    $svg = preg_replace("/>/", urlencode(">"), $svg);
    $svg = preg_replace("/\"/", "\\'", $svg);
    return $svg;
}

function video_url($url, $query = ''){
    //This is a general function for generating an embed link of an FB/Vimeo/Youtube Video.
    $finalUrl = '';
    if(strpos($url, 'facebook.com/') !== false) {
        //it is FB video
        $finalUrl.='https://www.facebook.com/plugins/video.php?href='.rawurlencode($url).'&show_text=1&width=200';
    }else if(strpos($url, 'vimeo.com/') !== false) {
        //it is Vimeo video
        $videoId = explode("vimeo.com/",$url)[1];
        if(strpos($videoId, '&') !== false){
            $videoId = explode("&",$videoId)[0];
        }
        $finalUrl.='https://player.vimeo.com/video/'.$videoId;
    }else if(strpos($url, 'youtube.com/') !== false) {
        //it is Youtube video
        $videoId = explode("v=",$url)[1];
        if(strpos($videoId, '&') !== false){
            $videoId = explode("&",$videoId)[0];
        }
        $finalUrl.='https://www.youtube.com/embed/'.$videoId;
    }else if(strpos($url, 'youtu.be/') !== false){
        //it is Youtube video
        $videoId = explode("youtu.be/",$url)[1];
        if(strpos($videoId, '&') !== false){
            $videoId = explode("&",$videoId)[0];
        }
        $finalUrl.='https://www.youtube.com/embed/'.$videoId;
    }else{
        //Enter valid video URL
    }

    if(!empty($query)){
        $finalUrl .= '?' . $query;
    }

    return $finalUrl;
}

function youtube_thumbnail($url){
    preg_match("#(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+(?=\?)|(?<=v=)[^&\n]+|(?<=youtu.be/)[^&\n]+#", $url, $matches);
    // var_dump($matches);
    $id = $matches[0] ?? '';
    return sprintf("https://img.youtube.com/vi/%s/0.jpg", $id);
}

function base_url($path = ''){
    return url($path);
}

function base_url_lang($path = ''){
    return base_url(\App::getLocale() . '/' . $path);
}

function url_path($data, $withTarget = false){
    if(empty($data)){
        return "";
    }
    $target = false;
    if(preg_match('/^https?/', $data) or empty($data)){
        $result = $data;
        $target = '_blank';
    }else{
        $result = active_langs()->count() == 1 ? base_url($data) : base_url_lang($data);
    }

    if($withTarget  and !empty($data)){
        if($target){
            $result = "target='$target' href='".$result."'";
        }else{
            $result = "href='".$result."'";
        }
    }

    return $result;
}
function validGoogleReCaptcha($recaptcha){

    /* Google reCaptcha */
    // Get resource
    $curl = curl_init();

    // Configure options, incl. post-variables to send.
    curl_setopt_array($curl, array(
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_URL => 'https://www.google.com/recaptcha/api/siteverify',
        CURLOPT_POST => 1,
        CURLOPT_POSTFIELDS => array(
            'secret' => config('appcustom.google_recaptcha_secret_key'),
            'response' => $recaptcha
        )
    ));

    // Send request. Due to CURLOPT_RETURNTRANSFER, this will return reply as string.
    $resp = curl_exec($curl);

    // Free resources.
    curl_close($curl);
    /* end Google reCaptcha */

    // Validate response
    if(strpos($resp, '"success": true') !== FALSE) {
       return true;
    }

    return false;
}

function arrStringToDotString($string){
    $string = preg_replace("/\[/", ".", $string);
    $string = preg_replace("/\]/", "", $string);
    return $string;
}

function isAdminRoute(){
    return preg_match("/^admin\//", \Route::current()->uri);
}