<?php
/**
 * page_head.php
 *
 * Author: fornace Header and Sidebar of each page
 *
 */


$template['active_page'] = Route::currentRouteName();
$template['active_page'] = str_replace(".create", "", $template['active_page']);
$template['active_page'] = str_replace(".update", "", $template['active_page']);


//        dd($primary_nav());
?>

<!-- Preloader -->
<!-- Preloader functionality (initialized in js/app.js) - pageLoading() -->
<!-- Used only if page preloader is enabled from inc/config (PHP version) or the class 'page-loading' is added in body element (HTML version) -->
<div class="preloader themed-background">
    <h1 class="push-top-bottom text-light text-center"><strong><?php echo $template['name']?></strong> ACP</h1>
    <div class="inner">
        <h3 class="text-light visible-lt-ie9 visible-lt-ie10"><strong>Loading..</strong></h3>
        <div class="preloader-spinner hidden-lt-ie9 hidden-lt-ie10"></div>
    </div>
</div>
<!-- END Preloader -->

<!-- Page Container -->
<!-- In the PHP version you can set the following options from inc/config file -->
<!--
    Available #page-container classes:

    '' (None)                                       for a full main and alternative sidebar hidden by default (> 991px)

    'sidebar-visible-lg'                            for a full main sidebar visible by default (> 991px)
    'sidebar-partial'                               for a partial main sidebar which opens on mouse hover, hidden by default (> 991px)
    'sidebar-partial sidebar-visible-lg'            for a partial main sidebar which opens on mouse hover, visible by default (> 991px)

    'sidebar-alt-visible-lg'                        for a full alternative sidebar visible by default (> 991px)
    'sidebar-alt-partial'                           for a partial alternative sidebar which opens on mouse hover, hidden by default (> 991px)
    'sidebar-alt-partial sidebar-alt-visible-lg'    for a partial alternative sidebar which opens on mouse hover, visible by default (> 991px)

    'sidebar-partial sidebar-alt-partial'           for both sidebars partial which open on mouse hover, hidden by default (> 991px)

    'sidebar-no-animations'                         add this as extra for disabling sidebar animations on large screens (> 991px) - Better performance with heavy pages!

    'style-alt'                                     for an alternative main style (without it: the default style)
    'footer-fixed'                                  for a fixed footer (without it: a static footer)

    'disable-menu-autoscroll'                       add this to disable the main menu auto scrolling when opening a submenu

    'header-fixed-top'                              has to be added only if the class 'navbar-fixed-top' was added on header.navbar
    'header-fixed-bottom'                           has to be added only if the class 'navbar-fixed-bottom' was added on header.navbar
-->
<?php
    $page_classes = '';

    if ($template['header'] == 'navbar-fixed-top') {
        $page_classes = 'header-fixed-top';
    } else if ($template['header'] == 'navbar-fixed-bottom') {
        $page_classes = 'header-fixed-bottom';
    }

    if ($template['sidebar']) {
        $page_classes .= (($page_classes == '') ? '' : ' ') . $template['sidebar'];
    }

    if ($template['main_style'] == 'style-alt')  {
        $page_classes .= (($page_classes == '') ? '' : ' ') . 'style-alt';
    }

    if ($template['footer'] == 'footer-fixed')  {
        $page_classes .= (($page_classes == '') ? '' : ' ') . 'footer-fixed';
    }

    if (!$template['menu_scroll'])  {
        $page_classes .= (($page_classes == '') ? '' : ' ') . 'disable-menu-autoscroll';
    }
?>
<div id="page-container"<?php if ($page_classes) { echo ' class="' . $page_classes . '"'; } ?>>
    

    <!-- Main Sidebar -->
    <div id="sidebar">
        <!-- Wrapper for scrolling functionality -->
        <div class="sidebar-scroll">
            <!-- Sidebar Content -->
            <div class="sidebar-content">
                <!-- Brand -->
                
                <!-- END Brand -->

                <!-- User Info -->
                <div class="sidebar-section sidebar-user clearfix" style="padding-left: 20px;">
                    <div class="sidebar-user-name"><i class="fa fa-user"></i> <?php echo \Auth::user()->name ?></div>
                    <div class="sidebar-user-links" style="margin-top:5px;">
                        <a href="#modal-user-settings" data-toggle="modal" class="enable-tooltip" data-placement="bottom" title=""><i class="gi gi-cogwheel"></i>&nbsp; Settings</a><br/>
                        
                        <a href="/acp/auth/logout" data-toggle="tooltip" data-placement="bottom" title=""><i class="gi gi-exit"></i>&nbsp; Logout</a>
                    </div>
                </div>
                <!-- END User Info -->

               

                <?php if ($primary_nav = $primary_nav()) { ?>
                <!-- Sidebar Navigation -->
                <ul class="sidebar-nav">
                    <?php
                    foreach( $primary_nav as $key => $link ) {
                        $link_class = '';
                        $li_active  = '';
                        $menu_link  = '';

                        // Get 1st level link's vital info
                        $url        = (isset($link['url']) && $link['url']) ? $link['url'] : '#';
                        $active     = (isset($link['url']) && ($template['active_page'] == $link['url'])) ? ' active' : '';
                        $icon       = (isset($link['icon']) && $link['icon']) ? '<i class="' . $link['icon'] . ' sidebar-nav-icon"></i>' : '';


                        if(substr($url, 0, 1) != '@') {
                            $url = route($url);
                        }else{
                            $url = substr($url, 1);
                        }

                        // Check if the link has a submenu
                        if (isset($link['sub']) && $link['sub']) {
                            // Since it has a submenu, we need to check if we have to add the class active
                            // to its parent li element (only if a 2nd or 3rd level link is active)
                            foreach ($link['sub'] as $sub_link) {
                                if (in_array($template['active_page'], $sub_link)) {
                                    $li_active = ' class="active"';
                                    break;
                                }

                                // 3rd level links
                                if (isset($sub_link['sub']) && $sub_link['sub']) {
                                    foreach ($sub_link['sub'] as $sub2_link) {
                                        if (in_array($template['active_page'], $sub2_link)) {
                                            $li_active = ' class="active"';
                                            break;
                                        }
                                    }
                                }
                            }

                            $menu_link = 'sidebar-nav-menu';
                        }

                        // Create the class attribute for our link
                        if ($menu_link || $active) {
                            $link_class = ' class="'. $menu_link . $active .'"';
                        }

                    ?>
                    <?php if ($url == 'header') { // if it is a header and not a link ?>
                    <li class="sidebar-header">
                        <?php if (isset($link['opt']) && $link['opt']) { // If the header has options set ?>
                        <span class="sidebar-header-options clearfix"><?php echo $link['opt']; ?></span>
                        <?php } ?>
                        <span class="sidebar-header-title"><?php echo $link['name']; ?></span>
                    </li>
                    <?php } else { // If it is a link ?>
                    <li<?php echo $li_active; ?> class="active">
                        <a id="menu-<?php echo strtolower(str_replace('&', '-',str_replace(' ', '-', $link['name']))) ?>" href="<?php
                        echo $url; ?>"<?php echo $link_class; ?>><?php if (isset($link['sub']) && $link['sub']) { // if the link has a submenu ?><i class="fa fa-angle-down sidebar-nav-indicator"></i><?php } echo $icon . $link['name']; ?></a>
                        <?php if (isset($link['sub']) && $link['sub'] && count($link['sub']) > 0) { // if the link has a submenu ?>
                        <ul>
                            <?php foreach ($link['sub'] as $sub_link) {
                                $link_class = '';
                                $li_active = '';
                                $submenu_link = '';

                                // Get 2nd level link's vital info
                                $url        = (isset($sub_link['url']) && $sub_link['url']) ? $sub_link['url'] : '#';
                                $active     = (isset($sub_link['url']) && ($template['active_page'] == $sub_link['url'])) ? ' active' : '';


//                                die('@'.Request::url().'?page=' . Input::get('page') . ' - ' . $sub_link['url']);
                                if(substr($url, 0, 1) != '@'){
                                    $url = route($url);
                                }else{
                                    $url = substr($url, 1);
                                }

                                // Check if the link has a submenu
                                if (isset($sub_link['sub']) && $sub_link['sub']) {
                                    // Since it has a submenu, we need to check if we have to add the class active
                                    // to its parent li element (only if a 3rd level link is active)
                                    foreach ($sub_link['sub'] as $sub2_link) {
                                        if (in_array($template['active_page'], $sub2_link)) {
                                            $li_active = ' class="active"';
                                            break;
                                        }
                                    }

                                    $submenu_link = 'sidebar-nav-submenu';
                                }

                                if ($submenu_link || $active) {
                                    $link_class = ' class="'. $submenu_link . $active .'"';
                                }
                            ?>
                            <li<?php echo $li_active; ?>>
                                <a id="menu-<?php echo strtolower(str_replace('&', '-',str_replace(' ', '-', $sub_link['name']))) ?>" href="<?php echo $url; ?>"<?php echo $link_class; ?>><i class="<?php echo $sub_link['icon']?>"></i> &nbsp; &nbsp;<?php if (isset($sub_link['sub']) && $sub_link['sub']) { ?><i class="fa fa-angle-down sidebar-nav-indicator"></i><?php } echo $sub_link['name']; ?></a>
                                <?php if (isset($sub_link['sub']) && $sub_link['sub']) { ?>
                                    <ul>
                                        <?php foreach ($sub_link['sub'] as $sub2_link) {
                                            // Get 3rd level link's vital info
                                            $url    = (isset($sub2_link['url']) && $sub2_link['url']) ? $sub2_link['url'] : '#';
                                            $active = (isset($sub2_link['url']) && ($template['active_page'] == $sub2_link['url'])) ? ' class="active"' : '';


                                            if(substr($url, 0, 1) != '@'){
                                                $url = route($url);
                                            }else{
                                                $url = substr($url, 1);
                                            }

                                            $url .= @$sub2_link['url_ext'];

                                            ?>
                                        <li>
                                            <a id="menu-<?php echo strtolower(str_replace('&', '-',str_replace(' ', '-', $sub2_link['name']))) ?>" href="<?php echo $url; ?>"<?php //echo $active ?>><?php echo $sub2_link['name']; ?></a>
                                        </li>
                                        <?php } ?>
                                    </ul>
                                <?php } ?>
                            </li>
                            <?php } ?>
                        </ul>
                        <?php } ?>
                    </li>
                    <?php } ?>
                    <?php } ?>
                </ul>
                <!-- END Sidebar Navigation -->
                <?php } ?>

               
            </div>
            <!-- END Sidebar Content -->
        </div>
        <!-- END Wrapper for scrolling functionality -->
    </div>
    <!-- END Main Sidebar -->
    
    <!-- Main Container -->
    <div id="main-container">
        <!-- Header -->
        <!-- In the PHP version you can set the following options from inc/config file -->
        <!--
            Available header.navbar classes:

            'navbar-default'            for the default light header
            'navbar-inverse'            for an alternative dark header

            'navbar-fixed-top'          for a top fixed header (fixed sidebars with scroll will be auto initialized, functionality can be found in js/app.js - handleSidebar())
                'header-fixed-top'      has to be added on #page-container only if the class 'navbar-fixed-top' was added

            'navbar-fixed-bottom'       for a bottom fixed header (fixed sidebars with scroll will be auto initialized, functionality can be found in js/app.js - handleSidebar()))
                'header-fixed-bottom'   has to be added on #page-container only if the class 'navbar-fixed-bottom' was added
        -->
        <header class="visible-sm-block visible-xs-block navbar<?php if ($template['header_navbar']) { echo ' ' . $template['header_navbar']; } ?><?php if ($template['header']) { echo ' '. $template['header']; } ?>">
            <ul class="nav navbar-nav-custom">
                <!-- Main Sidebar Toggle Button -->
                <li>
                    <a href="javascript:void(0)" onclick="App.sidebar('toggle-sidebar');">
                        <i class="fa fa-bars fa-fw"></i>
                    </a>
                </li>
                <!-- END Main Sidebar Toggle Button -->

            </ul>
        </header>
        
