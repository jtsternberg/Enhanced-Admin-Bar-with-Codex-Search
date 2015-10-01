<?php
/*
Plugin Name: Enhanced Admin Bar with Codex Search
Plugin URI: http://dsgnwrks.pro/enhanced-admin-bar-with-codex-search/
Description: This plugin adds convenient search fields to provide easy access to the codex, wpbeginner, and common wp-admin areas via the 3.1 Admin Bar.
Author URI: http://dsgnwrks.pro
Author: DsgnWrks
Donate link: http://j.ustin.co/rYL89n
Stable tag: 2.0.7
Version: 2.0.7
Text Domain: enhanced-admin-bar-with-codex-search
Domain Path: languages
*/

function eab_adminbar_init() {
	// Register plugin options
	register_setting( 'enhanced-admin-bar', 'eab-codex-search-submenu' );
	register_setting( 'enhanced-admin-bar', 'eab-admin-searches' );
	register_setting( 'enhanced-admin-bar', 'eab-wp-forums' );
	register_setting( 'enhanced-admin-bar', 'eab-wp-beginner' );
	register_setting( 'enhanced-admin-bar', 'eab-custom-menu' );
	if ( function_exists( 'genesis' ) ) {
		register_setting( 'enhanced-admin-bar', 'eab-genesis-menu' );
	}

	// Set default plugin options
	add_option( 'eab-codex-search-submenu', 'yes' );
	add_option( 'eab-admin-searches', 'yes' );
	if ( function_exists( 'genesis' ) ) {
		add_option( 'eab-genesis-menu', 'yes' );
	}

}
add_action( 'admin_init', 'eab_adminbar_init' );

function eab_register_settings() {
    add_options_page( __( 'Enhanced Admin Bar Settings', 'enhanced-admin-bar-with-codex-search' ),  __( 'Enhanced Admin Bar Settings', 'enhanced-admin-bar-with-codex-search' ), 'manage_options', 'eab-importer-settings', 'eab_importer_settings_page' );
}
add_action( 'admin_menu', 'eab_register_settings' );

function eab_importer_settings_page() {
	require_once( 'eab-settings.php' );
}

function dweab_body_class( $classes ) {
	if ( version_compare( $GLOBALS['wp_version'], '3.7.9', '>' ) || is_plugin_active( 'mp6/mp6.php' ) ) {
		$classes .= ' dwmp6';
	}
	return $classes;
}
add_filter( 'admin_body_class', 'dweab_body_class' );

// Enqueue Styles
function eab_search_css() {
	if ( is_admin_bar_showing() ) {
		wp_enqueue_style( 'adminbar_search', plugins_url( 'css/adminbar_search.css', __FILE__), null, '2.0.5.3' );
	}
}
add_action( 'wp_enqueue_scripts', 'eab_search_css' );
add_action( 'admin_enqueue_scripts', 'eab_search_css' );

function eab_adminbar_genesis_fix() {
	// Adds styles that compensates for a Genesis issue with Admin Bar dropdowns.  As a result, fixes admin bar issues for those using Genesis
?>
<style type="text/css">#wpadminbar .quicklinks li:hover ul ul { left: auto; }</style>
<?php
}
add_action( 'admin_head', 'eab_adminbar_genesis_fix' );

// Add Custom Menu Option
function eab_adminbar_nav() {

	// Add custom menu option if selected
	if ( get_option( 'eab-custom-menu' ) ) {
		register_nav_menus( array(
			'admin_bar_nav' => __( 'Admin Bar Custom Navigation Menu', 'enhanced-admin-bar-with-codex-search' ),
		) );
	}

}
add_action( 'init', 'eab_adminbar_nav' );

// Add Custom Menu to the Admin bar
function eab_adminbar_menu_init() {
	//if (!is_super_admin() || !is_admin_bar_showing() )
	if ( is_admin_bar_showing() ) {
 		add_action( 'admin_bar_menu', 'eab_admin_bar_menu', 1000 );
	}
}
add_action( 'admin_bar_init', 'eab_adminbar_menu_init' );

function eab_admin_bar_menu() {
	global $wp_admin_bar;

	// Add a custom menu option
	if ( $eab_custom_menu = get_option( 'eab-custom-menu' ) ) {
		$menu_name = 'admin_bar_nav';
		if ( ( $locations = get_nav_menu_locations() ) && isset( $locations[ $menu_name ] ) ) {
			$menu = wp_get_nav_menu_object( $locations[ $menu_name ] );

		    $menu_items = wp_get_nav_menu_items( $menu->term_id );
		    if ( $menu_items ) {
			    $wp_admin_bar->add_menu( array(
					'id' => 'dsgnwrks-admin-menu-0',
					'title' => __( 'Enhanced Admin Bar Custom Menu', 'enhanced-admin-bar-with-codex-search' ),
					'href' => '#' )
			    );
			    foreach ( $menu_items as $menu_item ) {
			        $wp_admin_bar->add_menu( array(
							'id'     => 'dsgnwrks-admin-menu-' . $menu_item->ID,
							'parent' => 'dsgnwrks-admin-menu-' . $menu_item->menu_item_parent,
							'title'  => $menu_item->title,
							'href'   => $menu_item->url,
							'meta'   => array(
			                'title' => $menu_item->attr_title,
			                'target' => $menu_item->target,
			                'class' => implode( ' ', $menu_item->classes ),
			            ),
			        ) );
			    }
		    }
		}
	}

	$admin_url = get_admin_url();

	$codex_search_submenu = get_option( 'eab-codex-search-submenu' );
	$eab_admin_searches = get_option( 'eab-admin-searches' );
	$eab_wp_forums = get_option( 'eab-wp-forums' );
	$eab_wp_beginner = get_option( 'eab-wp-beginner' );

	if ( is_admin() && $eab_admin_searches ) {
		eab_menu_init( $eab_wp_forums, $eab_wp_beginner );
	} elseif ( $codex_search_submenu ) {
		eab_menu_init( $eab_wp_forums, $eab_wp_beginner );
	}

	if (
		( ! is_admin() && $codex_search_submenu )
		|| $eab_admin_searches
	) {
		eab_add_plugins_menus();
		eab_add_themes_menus();
		eab_add_media_menus();
		eab_add_users_menus();
	}

	$actions = array();
	foreach ( (array) get_post_types( array( 'show_ui' => true ), 'objects' ) as $ptype_obj ) {
		if ( true !== $ptype_obj->show_in_menu || ! current_user_can( $ptype_obj->cap->edit_posts ) )
			continue;

		$actions[ 'post-new.php?post_type=' . $ptype_obj->name ] = array(
			$ptype_obj->labels->name,
			$ptype_obj->cap->edit_posts,
			'eab-new-' . $ptype_obj->name,
			$ptype_obj->labels->singular_name,
			$ptype_obj->name,
			'edit.php?post_type=' . $ptype_obj->name
		);
	}
	if ( empty( $actions ) ) {
		return;
	}

	foreach ( $actions as $link => $action ) {

		$post_searchform 	= '
			<strong style="display:none;">Search ' . $action[ 0 ] . '</strong>
			<form method="get" action="' . admin_url( 'edit.php' ) . '"  class="alignleft dw_search" >
			<input type="hidden" name="post_status" value="all"/>
			<input type="hidden" name="post_type" value="' . $action[ 4 ] . '"/>
			<input type="text" placeholder="Search ' . $action[ 0 ] . '" onblur="this.value=(this.value==\'\' ) ? \'Search ' . $action[ 0 ] . '\' : this.value;" onfocus="this.value=(this.value==\'Search ' . $action[ 0 ] . '\' ) ? \'\' : this.value;" value="Search ' . $action[ 0 ] . '" name="s" value="' . esc_attr( 'Search ' . $action[ 0 ] ) . '" class="text dw_search_input" />
			<input type="submit" value="' . __( 'Go', 'enhanced-admin-bar-with-codex-search' ) . '" class="button dw_search_go" /></form>';

		if ( is_admin() && $eab_admin_searches ) {

			$wp_admin_bar->add_menu( array(
				'id'     => 'dsgnwrks_help_menu_search_' . $action[4],
				'parent' => 'dsgnwrks_help_menu',
				'title'  => $post_searchform,
				'href'   => '#',
			) );

		} elseif ( $codex_search_submenu ) {

			$wp_admin_bar->add_menu( array(
				'id'     => $action[2],
				'parent' => 'dsgnwrks_help_menu',
				'title'  => $action[0],
				'href'   => admin_url($action[5])
			) );
			$wp_admin_bar->add_menu( array(
				'id'     => $action[2].'_search_'.$action[4],
				'parent' => $action[2],
				'title'  => $post_searchform,
				'href'   => '#'
			) );
			$wp_admin_bar->add_menu( array(
				'id'     => $action[2].'_addnew_'.$action[4],
				'parent' => $action[2],
				'title'  => 'Add New '.$action[3],
				'href'   => admin_url($link)
			) );

		}
	}

	// Only add remaining menu items if we're not in wp-admin.
	if ( is_admin() ) {
		return;
	}

	if ( $codex_search_submenu ) {

		$wp_admin_bar->add_menu( array(
			'id'     => 'settings_stuff',
			'parent' => 'dsgnwrks_help_menu',
			'title'  => __( 'Settings' ),
			'href'   => admin_url( 'options-general.php' ),
		) );

		$wp_admin_bar->add_menu( array(
			'id'     => 'settings_stuff_writing',
			'parent' => 'settings_stuff',
			'title'  => __( 'Writing' ),
			'href'   => admin_url( 'options-writing.php' ),
		) );

		$wp_admin_bar->add_menu( array(
			'id'     => 'settings_stuff_reading',
			'parent' => 'settings_stuff',
			'title'  => __( 'Reading' ),
			'href'   => admin_url( 'options-reading.php' ),
		) );

		$wp_admin_bar->add_menu( array(
			'id'     => 'settings_stuff_discussion',
			'parent' => 'settings_stuff',
			'title'  => __( 'Discussion' ),
			'href'   => admin_url( 'options-discussion.php' ),
		) );

		$wp_admin_bar->add_menu( array(
			'id'     => 'settings_stuff_media',
			'parent' => 'settings_stuff',
			'title'  => __( 'Media' ),
			'href'   => admin_url( 'options-media.php' ),
		) );

		$wp_admin_bar->add_menu( array(
			'id'     => 'settings_stuff_privacy',
			'parent' => 'settings_stuff',
			'title'  => __( 'Privacy' ),
			'href'   => admin_url( 'options-privacy.php' ),
		) );

		$wp_admin_bar->add_menu( array(
			'id'     => 'settings_stuff_permalinks',
			'parent' => 'settings_stuff',
			'title'  => __( 'Permalinks' ),
			'href'   => admin_url( 'options-permalink.php' ),
		) );

	}
}

function eab_menu_init( $eab_wp_forums='', $eab_wp_beginner='' ) {
	global $wp_admin_bar;

	// Add codex and plugin search menu items
	if ( is_super_admin () ) {
		$wp_admin_bar->add_menu( array(
		'id' => 'dsgnwrks_help_menu',
		'title' => '<span class="dw_search_input" id="dwspacer">'. __( 'Search the Codex', 'enhanced-admin-bar-with-codex-search' ) .'</span>',
		'href' => '#' ) );

		$wp_admin_bar->add_menu( array(
		'id' => 'dsgnwrks_help_menu_search_codex',
		'parent' => 'dsgnwrks_help_menu',
		'title' => '
		<strong style="display:none;">'. __( 'Search the Codex', 'enhanced-admin-bar-with-codex-search' ) .'</strong>
		<form target="_blank" action="http://wordpress.org/search/" method="get" class="alignleft dw_search admin-bar-search">
			<input type="text" onblur="this.value=(this.value==\'\' ) ? \''. __( 'Search the Codex', 'enhanced-admin-bar-with-codex-search' ) .'\' : this.value;" onfocus="this.value=(this.value==\''. __( 'Search the Codex', 'enhanced-admin-bar-with-codex-search' ) .'\' ) ? \'\' : this.value;" value="'. __( 'Search the Codex', 'enhanced-admin-bar-with-codex-search' ) .'" name="s" class="text dw_search_input adminbar-input" >
			<input type="submit" class="button dw_search_go" value="' . __( 'Go', 'enhanced-admin-bar-with-codex-search' ) . '">
		</form>
		',
		'href' => '#' ) );
		if ( $eab_wp_forums ) {
			$wp_admin_bar->add_menu( array(
			'id' => 'dsgnwrks_help_menu_search_forum',
			'parent' => 'dsgnwrks_help_menu',
			'title' => __( '
			<strong style="display:none;">'. __( 'Search WordPress Support Forums', 'enhanced-admin-bar-with-codex-search' ) .'</strong>
			<form target="_blank" method="get" action="http://wordpress.org/search/" class="alignleft dw_search" >
				<input type="text" onblur="this.value=(this.value==\'\' ) ? \''. __( 'Search WP Forums', 'enhanced-admin-bar-with-codex-search' ) .'\' : this.value;" onfocus="this.value=(this.value==\''. __( 'Search WP Forums', 'enhanced-admin-bar-with-codex-search' ) .'\' ) ? \'\' : this.value;" value="'. __( 'Search WP Forums', 'enhanced-admin-bar-with-codex-search' ) .'" name="s" class="text dw_search_input" >
			<input type="submit" value="' . __( 'Go', 'enhanced-admin-bar-with-codex-search' ) . '" class="button dw_search_go" /></form>' ),
			'href' => '#' ) );
		}

		if ( $eab_wp_beginner ) {
			$wp_admin_bar->add_menu( array(
			'id' => 'dsgnwrks_help_menu_wpbeginner',
			'parent' => 'dsgnwrks_help_menu',
			'title' => __( '
			<strong style="display:none;">'. __( 'Search wpbeginner.com', 'enhanced-admin-bar-with-codex-search' ) .'</strong>
			<form target="_blank" method="get" action="http://www.wpbeginner.com/" class="alignleft dw_search" >
				<input type="text" onblur="this.value=(this.value==\'\' ) ? \''. __( 'Search wpbeginner.com', 'enhanced-admin-bar-with-codex-search' ) .'\' : this.value;" onfocus="this.value=(this.value==\''. __( 'Search wpbeginner.com', 'enhanced-admin-bar-with-codex-search' ) .'\' ) ? \'\' : this.value;" value="'. __( 'Search wpbeginner.com', 'enhanced-admin-bar-with-codex-search' ) .'" name="s" class="text dw_search_input" >
			<input type="submit" value="' . __( 'Go', 'enhanced-admin-bar-with-codex-search' ) . '" class="button dw_search_go" /></form>' ),
			'href' => '#' ) );
		}

		if ( !is_admin() && function_exists( 'genesis' ) && get_option( 'eab-genesis-menu' ) ) {
			// Add genesis admin pages menu
			$wp_admin_bar->add_menu( array(
				'id'    => 'dsgnwrks_genesis_menu',
				'title' => __( 'Genesis' ),
				'href'  => admin_url( 'admin.php?page=genesis' ),
			) );

			$wp_admin_bar->add_menu( array(
				'id'     => 'dsgnwrks_genesis_menu_theme_settings',
				'parent' => 'dsgnwrks_genesis_menu',
				'title'  => __( 'Theme Settings' ),
				'href'   => admin_url( 'admin.php?page=genesis' ),
			) );

			$wp_admin_bar->add_menu( array(
				'id'     => 'dsgnwrks_genesis_menu_seo_settings',
				'parent' => 'dsgnwrks_genesis_menu',
				'title'  => __( 'SEO Settings' ),
				'href'   => admin_url( 'admin.php?page=seo-settings' ),
			) );

			$wp_admin_bar->add_menu( array(
				'id'     => 'dsgnwrks_genesis_menu_import_export',
				'parent' => 'dsgnwrks_genesis_menu',
				'title'  => __( 'Import/Export' ),
				'href'   => admin_url( 'admin.php?page=genesis-import-export' ),
			) );

		}

	} else {
		$wp_admin_bar->add_menu( array(
			'id'    => 'dsgnwrks_help_menu',
			'title' => __( '&emsp;Quick Search&emsp;', 'enhanced-admin-bar-with-codex-search' ),
			'href'  => '#',
		) );
	}

}

function eab_add_plugins_menus() {
	if ( ! current_user_can( 'edit_plugins' ) ) 	return;

	global $wp_admin_bar;

	$plugins_title      = __( 'Plugins' );
	$plugins_upload     = __( 'Upload Plugin' );
	$plugins_searchform = '
		<strong style="display:none;">'. __( 'Search Plugins', 'enhanced-admin-bar-with-codex-search' ) .'</strong>
		<form method="get" action="' . admin_url( 'plugin-install.php?tab=search' ) . '"  class="alignleft dw_search" >
		<input type="hidden" name="tab" value="search"/>
		<input type="hidden" name="type" value="term"/>
		<input type="text" placeholder="'. __( 'Search Plugins', 'enhanced-admin-bar-with-codex-search' ) .'" onblur="this.value=(this.value==\'\' ) ? \''. __( 'Search Plugins', 'enhanced-admin-bar-with-codex-search' ) .'\' : this.value;" onfocus="this.value=(this.value==\''. __( 'Search Plugins', 'enhanced-admin-bar-with-codex-search' ) .'\' ) ? \'\' : this.value;" value="'. __( 'Search Plugins', 'enhanced-admin-bar-with-codex-search' ) .'" name="s" value="' . esc_attr( ''. __( 'Search Plugins', 'enhanced-admin-bar-with-codex-search' ) .'' ) . '" class="text dw_search_input" />
		<input type="submit" value="' . __( 'Go', 'enhanced-admin-bar-with-codex-search' ) . '" class="button dw_search_go" /></form>';

	if ( is_admin() ) {
		$wp_admin_bar->add_menu( array(
			'id'     => 'plugins_stuff',
			'parent' => 'dsgnwrks_help_menu',
			'title'  => $plugins_searchform,
			'href'   => '#',
		) );
		$wp_admin_bar->add_menu( array(
			'id'     => 'upload_plugins_stuff',
			'parent' => 'plugins_stuff',
			'title'  => $plugins_upload,
			'href'   => admin_url( 'plugin-install.php?tab=upload' ),
		) );
	} else {
		$wp_admin_bar->add_menu( array (
			'id'     => 'plugins_stuff',
			'parent' => 'dsgnwrks_help_menu',
			'title'  => $plugins_title,
			'href'   => admin_url( 'plugins.php' ),
		) );
		$wp_admin_bar->add_menu( array (
			'id'     => 'plugins_stuff_search',
			'parent' => 'plugins_stuff',
			'title'  => $plugins_searchform,
			'href'   => '#',
		) );
		$wp_admin_bar->add_menu( array(
			'id'     => 'plugins_stuff_upload',
			'parent' => 'plugins_stuff',
			'title'  => $plugins_upload,
			'href'   => admin_url( 'plugin-install.php?tab=upload' ),
		) );
	}

}

function eab_add_themes_menus() {
	if ( ! current_user_can( 'edit_themes' ) ) 		return;

	global $wp_admin_bar;

	$themes_title      = __( 'Themes' );
	$themes_upload     = __( 'Upload Theme' );
	$themes_searchform = '
		<strong style="display:none;">'. __( 'Search Themes', 'enhanced-admin-bar-with-codex-search' ) .'</strong>
		<form method="get" action="'.admin_url( 'theme-install.php?tab=search' ).'"  class="alignleft dw_search" >
		<input type="hidden" name="tab" value="search"/>
		<input type="hidden" name="type" value="term"/>
		<input type="text" placeholder="'. __( 'Search Themes', 'enhanced-admin-bar-with-codex-search' ) .'" onblur="this.value=(this.value==\'\' ) ? \''. __( 'Search Themes', 'enhanced-admin-bar-with-codex-search' ) .'\' : this.value;" onfocus="this.value=(this.value==\''. __( 'Search Themes', 'enhanced-admin-bar-with-codex-search' ) .'\' ) ? \'\' : this.value;" value="'. __( 'Search Themes', 'enhanced-admin-bar-with-codex-search' ) .'" name="s" value="' . esc_attr( ''. __( 'Search Themes', 'enhanced-admin-bar-with-codex-search' ) .'' ) . '" class="text dw_search_input" />
		<input type="submit" value="' . __( 'Go', 'enhanced-admin-bar-with-codex-search' ) . '" class="button dw_search_go" /></form>';

	if ( is_admin() ) {
		$wp_admin_bar->add_menu( array(
			'id'     => 'themes_stuff',
			'parent' => 'dsgnwrks_help_menu',
			'title'  => $themes_searchform,
			'href'   => '#',
		) );
		$wp_admin_bar->add_menu( array(
			'id'     => 'themes_stuff_upload',
			'parent' => 'themes_stuff',
			'title'  => $themes_upload,
			'href'   => admin_url( 'theme-install.php?tab=upload' ),
		) );
	} else {
		$wp_admin_bar->add_menu( array(
			'id'     => 'themes_stuff',
			'parent' => 'dsgnwrks_help_menu',
			'title'  => $themes_title,
			'href'   => admin_url( 'themes.php' ),
		) );
		$wp_admin_bar->add_menu( array(
			'id'     => 'themes_stuff_search',
			'parent' => 'themes_stuff',
			'title'  => $themes_searchform,
			'href'   => '#',
		) );
		$wp_admin_bar->add_menu( array(
			'id'     => 'themes_stuff_upload',
			'parent' => 'themes_stuff',
			'title'  => $themes_upload,
			'href'   => admin_url( 'theme-install.php?tab=upload' ),
		) );
	}
}

function eab_add_media_menus() {
	if ( ! current_user_can( 'upload_files' ) ) 	return;

	global $wp_admin_bar;

	$media_title      = __( 'Media' );
	$media_upload     = __( 'Upload Media' );
	$media_searchform = '
		<strong style="display:none;">'. __( 'Search Media', 'enhanced-admin-bar-with-codex-search' ) .'</strong>
		<form method="get" action="' . admin_url( 'upload.php?tab=search' ) . '"  class="alignleft dw_search" >
		<input type="text" placeholder="'. __( 'Search Media', 'enhanced-admin-bar-with-codex-search' ) .'"
			onblur="this.value=(this.value==\'\' ) ? \''. __( 'Search Media', 'enhanced-admin-bar-with-codex-search' ) .'\' : this.value;"
			onfocus="this.value=(this.value==\''. __( 'Search Media', 'enhanced-admin-bar-with-codex-search' ) .'\' ) ? \'\' : this.value;"
			name="s" value="'. __( 'Search Media', 'enhanced-admin-bar-with-codex-search' ) .'" class="text dw_search_input" />
		<input type="submit" value="' . __( 'Go', 'enhanced-admin-bar-with-codex-search' ) . '" class="button dw_search_go" /></form>';

	if ( is_admin() ) {
		$wp_admin_bar->add_menu( array(
			'id'     => 'media_stuff',
			'parent' => 'dsgnwrks_help_menu',
			'title'  => $media_searchform,
			'href'   => '#',
		) );
		$wp_admin_bar->add_menu( array(
			'id'     => 'media_stuff_upload',
			'parent' => 'media_stuff',
			'title'  => $media_upload,
			'href'   => admin_url( 'media-new.php' ),
		) );
	} else {
		$wp_admin_bar->add_menu( array(
			'id'     => 'media_stuff',
			'parent' => 'dsgnwrks_help_menu',
			'title'  => $media_title,
			'href'   => admin_url( 'upload.php' ),
		) );
		$wp_admin_bar->add_menu( array(
			'id'     => 'media_stuff_search',
			'parent' => 'media_stuff',
			'title'  => $media_searchform,
			'href'   => '#',
		) );
		$wp_admin_bar->add_menu( array(
			'id'     => 'media_stuff_upload',
			'parent' => 'media_stuff',
			'title'  => $media_upload,
			'href'   => admin_url( 'media-new.php' ),
		) );
	}
}

function eab_add_users_menus() {
	if ( ! current_user_can( 'edit_users' ) ) {
		return;
	}

	global $wp_admin_bar;

	$users_title      = __( 'Users' );
	$users_add        = __( 'Add New User' );
	$users_searchform = '
		<strong style="display:none;">'. __( 'Search Users', 'enhanced-admin-bar-with-codex-search' ) .'</strong>
		<form method="get" action="' . admin_url( 'users.php?tab=search' ) . '"  class="alignleft dw_search" >
		<input type="text" placeholder="'. __( 'Search Users', 'enhanced-admin-bar-with-codex-search' ) .'" onblur="this.value=(this.value==\'\' ) ? \''. __( 'Search Users', 'enhanced-admin-bar-with-codex-search' ) .'\' : this.value;" onfocus="this.value=(this.value==\''. __( 'Search Users', 'enhanced-admin-bar-with-codex-search' ) .'\' ) ? \'\' : this.value;" value="'. __( 'Search Users', 'enhanced-admin-bar-with-codex-search' ) .'" name="s" value="' . esc_attr( ''. __( 'Search Users', 'enhanced-admin-bar-with-codex-search' ) .'' ) . '" class="text dw_search_input" />
		<input type="submit" value="' . __( 'Go', 'enhanced-admin-bar-with-codex-search' ) . '" class="button dw_search_go" /></form>';

	if ( is_admin() ) {
		$wp_admin_bar->add_menu( array(
			'id'     => 'user_stuff',
			'parent' => 'dsgnwrks_help_menu',
			'title'  => $users_searchform,
			'href'   => admin_url( 'users.php' ),
		) );
		$wp_admin_bar->add_menu( array(
			'id'     => 'user_stuff_add',
			'parent' => 'user_stuff',
			'title'  => $users_add,
			'href'   => admin_url( 'user-new.php' ),
		) );
	} else {
		$wp_admin_bar->add_menu( array(
			'id'     => 'user_stuff',
			'parent' => 'dsgnwrks_help_menu',
			'title'  => $users_title,
			'href'   => admin_url( 'users.php' ),
		) );
		$wp_admin_bar->add_menu( array(
			'id'     => 'user_stuff_search',
			'parent' => 'user_stuff',
			'title'  => $users_searchform,
			'href'   => '#',
		) );
		$wp_admin_bar->add_menu( array(
			'id'     => 'user_stuff_add',
			'parent' => 'user_stuff',
			'title'  => $users_add,
			'href'   => admin_url( 'user-new.php' ),
		) );
	}
}
