<?php
/**
 * template_start.php
 *
 * Author: fornace The first block of code used in every page of the template
 *
 */
?>
<!DOCTYPE html>
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if IE 9]>         <html class="no-js lt-ie10"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">

        <title><?php echo @$title ?></title>

        <meta name="author" content="<?php echo @$template['author'] ?>">
        <meta name="robots" content="<?php echo @$template['robots'] ?>">

        <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1.0">
        <!-- Stylesheets -->
        <!-- Bootstrap is included in its original form, unaltered -->
        <link rel="stylesheet" href="/assets/ACP/css/bootstrap.min.css">
        <link href="/assets/ACP/css/bootstrap-dialog.min.css" rel="stylesheet" type="text/css" />
        <link href="/assets/ACP/css/fileinput.min.css" rel="stylesheet" type="text/css" />

        <!-- Related styles of various icon packs and plugins -->
        <link rel="stylesheet" href="/assets/ACP/css/plugins.css">

        <!-- The main stylesheet of this template. All Bootstrap overwrites are defined in here -->
        <link rel="stylesheet" href="/assets/ACP/css/main.css">

        <!-- Include a specific file here from css/themes/ folder to alter the default theme of the template -->
        <?php if (@$template['theme']) { ?><link id="theme-link" rel="stylesheet" href="/assets/ACP/css/themes/<?php echo $template['theme']; ?>.css"><?php } ?>

        <!-- The themes stylesheet of this template (for using specific theme color in individual elements - must included last) -->
        <link rel="stylesheet" href="/assets/ACP/css/themes.css">
        <!-- END Stylesheets -->

        <!-- Modernizr (browser feature detection library) & Respond.js (Enable responsive CSS code on browsers that don't support it, eg IE8) -->
        <script src="/assets/ACP/js/vendor/modernizr-2.7.1-respond-1.4.2.min.js"></script>

                
        <!-- Include Jquery library from Google's CDN but if something goes wrong get Jquery from local file (Remove 'http:' if you have SSL) -->
        <!-- <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script> -->
        <script>!window.jQuery && document.write(decodeURI('%3Cscript src="/assets/ACP/js/vendor/jquery-1.11.1.min.js"%3E%3C/script%3E'));</script>


        {{--<link rel="stylesheet" href="/assets/ACP/css/bootstrap-colorpicker.min.css">--}}
        {{--<script src="/assets/ACP/js/bootstrap-colorpicker.min.js"></script>--}}

        <script src='/assets/ACP/js/spectrum/spectrum.js'></script>
        <link rel='stylesheet' href='/assets/ACP/js/spectrum/spectrum.css' />

        <!-- include summernote css/js-->
<!--        <link href="/assets/ACP/js/summernote/summernote.css" rel="stylesheet">-->
<!--        <script src="/assets/ACP/js/summernote/summernote.min.js"></script>-->


        <link rel="stylesheet" href="/assets/ACP/js/bootstrap-iconpicker/bootstrap-iconpicker/css/bootstrap-iconpicker.min.css"/>
        <link rel="stylesheet" href="/assets/ACP/css/fonts/furnicon/styles.css"/>
    </head>
    <!-- In the PHP version you can set the following options from inc/config file -->
    <!--
        Available body classes:

        'page-loading'      enables page preloader
    -->
    <body<?php if (@$template['page_preloader']) { echo ' class="page-loading"'; } ?>>
    <script>
        var _csrf_token = '{{ csrf_token() }}';
    </script>