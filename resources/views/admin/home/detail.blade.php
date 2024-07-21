@extends('layouts.admin')
@section('content')

<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr class="php">
        <td class="img"><span></span></td>
        <td class="content"><p>Server require PHP version 7.2 or newer</p><p>Current Version: <span class="highlight"><?php echo phpversion()?></span></p></td>
    </tr>

    <tr class="php-gd">
        <td class="img"><span></span></td>
        <td class="content"><p>Enable GD Library for image resizing function</p>Current Setting: <span class="highlight"><?php $gdInfo = gd_info(); echo empty($gdInfo['GD Version']) ? 'Disable' : 'Enable ('.$gdInfo['GD Version'].')'?></span></td>
    </tr>

    <tr class="php-upload">
        <td class="img"><span></span></td>
        <td class="content"><p>file_uploads configuration of system file upload function.</p><p>Current Setting: <span class="highlight"><?php echo ini_get('file_uploads') ? 'On' : 'Off'?></span> (normally server default is ON)</p></td>
    </tr>

    <tr class="php-file">
        <td class="img"><span></span></td>
        <td class="content">
            <p>max_file_uploads define the maximum number of files allowed to be uploaded simultaneously.</p><p>Current Setting: <span class="highlight"><?php echo ini_get('max_file_uploads')?></span></p>
            <p class="smaller">If you want to upload files more than <?php echo ini_get('max_file_uploads')?> simultaneously, please modify this value to meet your demand with your web hosting company.</p>
        </td>
    </tr>
    <tr class="php-filesize">
        <td class="img"><span></span></td>
        <td class="content">
            <p>upload_max_filesize define the maximum size of an uploaded file.</p><p>Current Setting: <span class="highlight"><?php echo ini_get('upload_max_filesize')?></span></p>
            <p class="smaller"><div>If you want to upload a file which is larger than <?php echo ini_get('upload_max_filesize')?>,</div><div>please modify this value to meet your demand with your web hosting company.</div></p>
        </td>
    </tr>

    <tr class="php-post">
        <td class="img"><span></span></td>
        <td class="content">
            <p>post_max_size define the maximum size of post data allowed.</p><p>Current Setting: <span class="highlight"><?php echo ini_get('post_max_size')?></span></p>
            <p class="smaller">If you modify upload_max_filesize, please also set post_max_size same as upload_max_filesize.</p>
        </td>
    </tr>
</table>
@endsection
