<?php
$acp = array(
	/* Template variables */
	'template' => array(
		'name' => env('APP_NAME'),
		'version' => '1.0',
		'author' => 'Fornace',
		'robots' => 'noindex, nofollow',
		// true                     enable page preloader
		// false                    disable page preloader
		'page_preloader' => false,
		// true                     enable main menu auto scrolling when opening a submenu
		// false                    disable main menu auto scrolling when opening a submenu
		'menu_scroll' => true,
		// 'navbar-default'         for a light header
		// 'navbar-inverse'         for a dark header
		'header_navbar' => 'navbar-default',
		// ''                       empty for a static layout
		// 'navbar-fixed-top'       for a top fixed header / fixed sidebars
		// 'navbar-fixed-bottom'    for a bottom fixed header / fixed sidebars
		'header' => '',
		// ''                                               for a full main and alternative sidebar hidden by default (> 991px)
		// 'sidebar-visible-lg'                             for a full main sidebar visible by default (> 991px)
		// 'sidebar-partial'                                for a partial main sidebar which opens on mouse hover, hidden by default (> 991px)
		// 'sidebar-partial sidebar-visible-lg'             for a partial main sidebar which opens on mouse hover, visible by default (> 991px)
		// 'sidebar-alt-visible-lg'                         for a full alternative sidebar visible by default (> 991px)
		// 'sidebar-alt-partial'                            for a partial alternative sidebar which opens on mouse hover, hidden by default (> 991px)
		// 'sidebar-alt-partial sidebar-alt-visible-lg'     for a partial alternative sidebar which opens on mouse hover, visible by default (> 991px)
		// 'sidebar-partial sidebar-alt-partial'            for both sidebars partial which open on mouse hover, hidden by default (> 991px)
		// 'sidebar-no-animations'                          add this as extra for disabling sidebar animations on large screens (> 991px) - Better performance with heavy pages!
		'sidebar' => 'sidebar-partial sidebar-visible-lg sidebar-no-animations',
		// ''                       empty for a static footer
		// 'footer-fixed'           for a fixed footer
		'footer' => '',
		// ''                       empty for default style
		// 'style-alt'              for an alternative main style (affects main page background as well as blocks style)
		'main_style' => '',
		// 'night', 'amethyst', 'modern', 'autumn', 'flatie', 'spring', 'fancy', 'fire' or '' leave empty for the Default Blue theme
		'theme' => 'material',
		// ''                       for default content in header
		// 'horizontal-menu'        for a horizontal menu in header
		// This option is just used for feature demostration and you can remove it if you like. You can keep or alter header's content in page_head.php
		'header_content' => '',
		'active_page' => basename($_SERVER['PHP_SELF']),
	),

	/* Primary navigation array (the primary navigation will be created automatically based on this array, up to 3 levels deep) */
	'area' => [
		'acp' => [
			'name' => 'Dashboard',
			'icon' => 'gi gi-stopwatch',
			'roles' => ['root'],
			'menu' => true,
			'children' => [

			]
		],
		'@acp.data' => [
			'name' => 'Data',
			'icon' => 'fa fa-th',
			'roles' => ['root', 'admin'],
			'menu' => true,
			'children' => [
				'acp.image' => [
					'name' => 'Images',
					'icon' => 'fa fa-angle-right',
					'roles' => ['root', 'admin'],
					'menu' => true,
					'children' => [

					]
				],

			]
		],

	]
);



return $acp;