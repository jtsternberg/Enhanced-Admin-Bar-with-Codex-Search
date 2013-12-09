<?php
/*
Plugin Name: Enhanced Admin Bar with Codex Search
Plugin URI: http://dsgnwrks.pro/enhanced-admin-bar-with-codex-search/
Description: This plugin adds convenient search fields to provide easy access to the codex, wpbeginner, and common wp-admin areas via the 3.1 Admin Bar.
Author URI: http://dsgnwrks.pro
Author: DsgnWrks
Donate link: http://j.ustin.co/rYL89n
Stable tag: 2.0.5.2
Version: 2.0.5.2
*/


add_action('admin_init', 'dsgnwrks_adminbar_init');
function dsgnwrks_adminbar_init() {

	// Register plugin options
    register_setting('enhanced-admin-bar', 'eab-codex-search-submenu');
    register_setting('enhanced-admin-bar', 'eab-admin-searches');
    register_setting('enhanced-admin-bar', 'eab-wp-forums');
    register_setting('enhanced-admin-bar', 'eab-wp-beginner');
    register_setting('enhanced-admin-bar', 'eab-custom-menu');
    if ( function_exists( 'genesis' ) ) register_setting('enhanced-admin-bar', 'eab-genesis-menu');

	// Set default plugin options
	add_option( 'eab-codex-search-submenu', 'yes' );
	add_option( 'eab-admin-searches', 'yes' );
	if ( function_exists( 'genesis' ) ) add_option( 'eab-genesis-menu', 'yes' );

}
add_action('admin_menu', 'dsgnwrks_adminbar_settings');
function dsgnwrks_adminbar_settings() {
    add_options_page('Enhanced Admin Bar Settings', 'Enhanced Admin Bar Settings', 'manage_options', 'eab-importer-settings', 'eab_importer_settings');
}

function eab_importer_settings() { require_once('eab-settings.php'); }

add_filter( 'admin_body_class', 'dweab_body_class' );
function dweab_body_class( $classes ) {
	if ( version_compare( $GLOBALS['wp_version'], '3.7.9', '>' ) || is_plugin_active( 'mp6/mp6.php' ) )
		$classes .= ' dwmp6';
	return $classes;
}

// Enqueue Styles
add_action('wp_enqueue_scripts', 'dsgnwrks_adminbar_search_css');
add_action('admin_enqueue_scripts', 'dsgnwrks_adminbar_search_css');
function dsgnwrks_adminbar_search_css() {
	wp_enqueue_style('adminbar_search_css', plugins_url('css/adminbar_search.css', __FILE__));
}

add_action('admin_head', 'dsgnwrks_adminbar_genesis_fix');
function dsgnwrks_adminbar_genesis_fix() {
	// Adds styles that compensates for a Genesis issue with Admin Bar dropdowns.  As a result, fixes admin bar issues for those using Genesis
?>
<style type="text/css">#wpadminbar .quicklinks li:hover ul ul { left: auto; }</style>
<?php
}

// Add Custom Menu Option
add_action('init', 'dsgnwrks_adminbar_nav');
function dsgnwrks_adminbar_nav() {

	// Add custom menu option if selected
	if ( get_option( 'eab-custom-menu' ) ) {
		register_nav_menus( array(
			'admin_bar_nav' => __( 'Admin Bar Custom Navigation Menu' ),
		) );
	}

}

// Add Custom Menu to the Admin bar
add_action('admin_bar_init', 'dsgnwrks_adminbar_menu_init');
function dsgnwrks_adminbar_menu_init() {
	//if (!is_super_admin() || !is_admin_bar_showing() )
	if ( ! is_admin_bar_showing() )
		return;

 	add_action( 'admin_bar_menu', 'dsgnwrks_admin_bar_menu', 1000 );
}

$eab_go_button = '<input type="submit" value="Go" class="button dw_search_go"  /></form>';
function dsgnwrks_admin_bar_menu() {
	global $wp_admin_bar;
	global $eab_go_button;

	// Add a custom menu option
	if ( $eab_custom_menu = get_option( 'eab-custom-menu' ) ) {
		$menu_name = 'admin_bar_nav';
		if ( ( $locations = get_nav_menu_locations() ) && isset( $locations[ $menu_name ] ) ) {
			$menu = wp_get_nav_menu_object( $locations[ $menu_name ] );

		    $menu_items = wp_get_nav_menu_items( $menu->term_id );
		    if ( $menu_items ) {
			    $wp_admin_bar->add_menu( array(
					'id' => 'dsgnwrks-admin-menu-0',
					'title' => 'Enhanced Admin Bar Custom Menu',
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
		dsgnwrks_menu_init( $eab_wp_forums, $eab_wp_beginner, $eab_go_button );
	} elseif ( $codex_search_submenu ) {
		dsgnwrks_menu_init( $eab_wp_forums, $eab_wp_beginner, $eab_go_button );
	}

	$show_menus = (
		( ! is_admin() && $codex_search_submenu ) ||
		$eab_admin_searches
	);
	if ( $show_menus ) {
		dsgnwrks_add_plugins_menus();
		dsgnwrks_add_themes_menus();
		dsgnwrks_add_media_menus();
		dsgnwrks_add_users_menus();
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
	if ( empty( $actions ) )
		return;

	foreach ( $actions as $link => $action ) {

		$post_searchform 	= '
			<strong style="display:none;">Search ' . $action[ 0 ] . '</strong>
			<form method="get" action="' . admin_url( 'edit.php' ) . '"  class="alignleft dw_search" >
			<input type="hidden" name="post_status" value="all"/>
			<input type="hidden" name="post_type" value="' . $action[ 4 ] . '"/>
			<input type="text" placeholder="Search ' . $action[ 0 ] . '" onblur="this.value=(this.value==\'\') ? \'Search ' . $action[ 0 ] . '\' : this.value;" onfocus="this.value=(this.value==\'Search ' . $action[ 0 ] . '\') ? \'\' : this.value;" value="Search ' . $action[ 0 ] . '" name="s" value="' . esc_attr( 'Search ' . $action[ 0 ] ) . '" class="text dw_search_input" />
			' . $eab_go_button;

		if ( is_admin() && $eab_admin_searches ) {

			$wp_admin_bar->add_menu( array(
				'id' 		=> 'dsgnwrks_help_menu_search_' . $action[4],
				'parent' 	=> 'dsgnwrks_help_menu',
				'title' 	=> __( $post_searchform ),
				'href' 		=> '#'
			) );

		} elseif ( $codex_search_submenu ) {

			$wp_admin_bar->add_menu( array(
				'id' 		=> $action[2],
				'parent' 	=> 'dsgnwrks_help_menu',
				'title' 	=> $action[0],
				'href' 		=> admin_url($action[5])
			) );
			$wp_admin_bar->add_menu( array(
				'id' 		=> $action[2].'_search_'.$action[4],
				'parent' 	=> $action[2],
				'title' 	=> __( $post_searchform ),
				'href' 		=> '#'
			) );
			$wp_admin_bar->add_menu( array(
				'id' 		=> $action[2].'_addnew_'.$action[4],
				'parent' 	=> $action[2],
				'title' 	=> 'Add New '.$action[3],
				'href' 		=> admin_url($link)
			) );

		}
	}

	// Only add remaining menu items if we're not in wp-admin.
	if ( is_admin() )
	return;

	if ( $codex_search_submenu ) {

		$wp_admin_bar->add_menu( array(
		'id' => 'settings_stuff',
		'parent' => 'dsgnwrks_help_menu',
		'title' => __( 'Settings'),
		'href' => admin_url('options-general.php') ) );

		$wp_admin_bar->add_menu( array(
		'id' => 'settings_stuff_writing',
		'parent' => 'settings_stuff',
		'title' => __( 'Writing'),
		'href' => admin_url('options-writing.php') ) );

		$wp_admin_bar->add_menu( array(
		'id' => 'settings_stuff_reading',
		'parent' => 'settings_stuff',
		'title' => __( 'Reading'),
		'href' => admin_url('options-reading.php') ) );

		$wp_admin_bar->add_menu( array(
		'id' => 'settings_stuff_discussion',
		'parent' => 'settings_stuff',
		'title' => __( 'Discussion'),
		'href' => admin_url('options-discussion.php') ) );

		$wp_admin_bar->add_menu( array(
		'id' => 'settings_stuff_media',
		'parent' => 'settings_stuff',
		'title' => __( 'Media'),
		'href' => admin_url('options-media.php') ) );

		$wp_admin_bar->add_menu( array(
		'id' => 'settings_stuff_privacy',
		'parent' => 'settings_stuff',
		'title' => __( 'Privacy'),
		'href' => admin_url('options-privacy.php') ) );

		$wp_admin_bar->add_menu( array(
		'id' => 'settings_stuff_permalinks',
		'parent' => 'settings_stuff',
		'title' => __( 'Permalinks'),
		'href' => admin_url('options-permalink.php') ) );

	}
}

function dsgnwrks_menu_init( $eab_wp_forums='', $eab_wp_beginner='', $eab_go_button ) {
	global $wp_admin_bar;

	// Add codex and plugin search menu items
	if ( is_super_admin () ) {
		$wp_admin_bar->add_menu( array(
		'id' => 'dsgnwrks_help_menu',
		'title' => '<span class="dw_search_input" id="dwspacer">Search the Codex</span>',
		'href' => '#' ) );

		$wp_admin_bar->add_menu( array(
		'id' => 'dsgnwrks_help_menu_search_codex',
		'parent' => 'dsgnwrks_help_menu',
		'title' => '
		<strong style="display:none;">Search the Codex</strong>
		<form target="_blank" action="http://wordpress.org/search/" method="get" class="alignleft dw_search admin-bar-search">
			<input type="text" onblur="this.value=(this.value==\'\') ? \'Search the Codex\' : this.value;" onfocus="this.value=(this.value==\'Search the Codex\') ? \'\' : this.value;" value="Search the Codex" name="s" class="text dw_search_input adminbar-input" >
			<input type="submit" class="button dw_search_go" value="Go">
		</form>
		',
		'href' => '#' ) );
		if ( $eab_wp_forums ) {
			$wp_admin_bar->add_menu( array(
			'id' => 'dsgnwrks_help_menu_search_forum',
			'parent' => 'dsgnwrks_help_menu',
			'title' => __( '
			<strong style="display:none;">Search WordPress Support Forums</strong>
			<form target="_blank" method="get" action="http://wordpress.org/search/" class="alignleft dw_search" >
				<input type="text" onblur="this.value=(this.value==\'\') ? \'Search WP Forums\' : this.value;" onfocus="this.value=(this.value==\'Search WP Forums\') ? \'\' : this.value;" value="Search WP Forums" name="s" class="text dw_search_input" >
			'.$eab_go_button),
			'href' => '#' ) );
		}

		if ( $eab_wp_beginner ) {
			$wp_admin_bar->add_menu( array(
			'id' => 'dsgnwrks_help_menu_wpbeginner',
			'parent' => 'dsgnwrks_help_menu',
			'title' => __( '
			<strong style="display:none;">Search wpbeginner.com</strong>
			<form target="_blank" method="get" action="http://www.wpbeginner.com/" class="alignleft dw_search" >
				<input type="text" onblur="this.value=(this.value==\'\') ? \'Search wpbeginner.com\' : this.value;" onfocus="this.value=(this.value==\'Search wpbeginner.com\') ? \'\' : this.value;" value="Search wpbeginner.com" name="s" class="text dw_search_input" >
			'.$eab_go_button),
			'href' => '#' ) );
		}

		if ( !is_admin() && function_exists( 'genesis' ) && get_option( 'eab-genesis-menu' ) ) {
			// Add genesis admin pages menu
			$wp_admin_bar->add_menu( array(
			'id' => 'dsgnwrks_genesis_menu',
			'title' => __( 'Genesis' ),
			'href' => admin_url('admin.php?page=genesis')
			) );

			$wp_admin_bar->add_menu( array(
			'id' => 'dsgnwrks_genesis_menu_theme_settings',
			'parent' => 'dsgnwrks_genesis_menu',
			'title' => __( 'Theme Settings' ),
			'href' => admin_url('admin.php?page=genesis')
			) );

			$wp_admin_bar->add_menu( array(
			'id' => 'dsgnwrks_genesis_menu_seo_settings',
			'parent' => 'dsgnwrks_genesis_menu',
			'title' => __( 'SEO Settings' ),
			'href' => admin_url('admin.php?page=seo-settings')
			) );

			$wp_admin_bar->add_menu( array(
			'id' => 'dsgnwrks_genesis_menu_import_export',
			'parent' => 'dsgnwrks_genesis_menu',
			'title' => __( 'Import/Export' ),
			'href' => admin_url('admin.php?page=genesis-import-export')
			) );

		}

	} else {
		$wp_admin_bar->add_menu( array(
			'id' 		=> 'dsgnwrks_help_menu',
			'title' 	=> __( '&emsp;Quick Search&emsp;' ),
			'href' 		=> '#'
		) );

	}



}

function dsgnwrks_add_plugins_menus() {
	if ( ! current_user_can( 'edit_plugins' ) ) 	return;

	global $wp_admin_bar;
	global $eab_go_button;

	$plugins_title 			= 'Plugins';
	$plugins_upload 		= 'Upload Plugin';
	$plugins_searchform 	= '
		<strong style="display:none;">Search Plugins</strong>
		<form method="get" action="' . admin_url( 'plugin-install.php?tab=search' ) . '"  class="alignleft dw_search" >
		<input type="hidden" name="tab" value="search"/>
		<input type="hidden" name="type" value="term"/>
		<input type="text" placeholder="Search Plugins" onblur="this.value=(this.value==\'\') ? \'Search Plugins\' : this.value;" onfocus="this.value=(this.value==\'Search Plugins\') ? \'\' : this.value;" value="Search Plugins" name="s" value="' . esc_attr( 'Search Plugins' ) . '" class="text dw_search_input" />
		' . $eab_go_button;

	if ( is_admin() ) {
		$wp_admin_bar->add_menu( array(
			'id' 		=> 'plugins_stuff',
			'parent' 	=> 'dsgnwrks_help_menu',
			'title' 	=> __( $plugins_searchform ),
			'href' 		=> '#'
		) );
		$wp_admin_bar->add_menu( array(
			'id' 		=> 'upload_plugins_stuff',
			'parent' 	=> 'plugins_stuff',
			'title' 	=> __( $plugins_upload ),
			'href' 		=> admin_url( 'plugin-install.php?tab=upload' )
		) );
	} else {
		$wp_admin_bar->add_menu( array (
			'id' 		=> 'plugins_stuff',
			'parent' 	=> 'dsgnwrks_help_menu',
			'title' 	=> __( $plugins_title ),
			'href' 		=> admin_url( 'plugins.php' )
		) );
		$wp_admin_bar->add_menu( array (
			'id' 		=> 'plugins_stuff_search',
			'parent' 	=> 'plugins_stuff',
			'title' 	=> __( $plugins_searchform ),
			'href' 		=> '#'
		) );
		$wp_admin_bar->add_menu( array(
			'id' 		=> 'plugins_stuff_upload',
			'parent' 	=> 'plugins_stuff',
			'title' 	=> __( $plugins_upload ),
			'href' 		=> admin_url( 'plugin-install.php?tab=upload' )
		) );
	}

}

function dsgnwrks_add_themes_menus() {
	if ( ! current_user_can( 'edit_themes' ) ) 		return;

	global $wp_admin_bar;
	global $eab_go_button;

	$themes_title 		= 'Themes';
	$themes_upload 		= 'Upload Theme';
	$themes_searchform 	= '
		<strong style="display:none;">Search Themes</strong>
		<form method="get" action="'.admin_url('theme-install.php?tab=search').'"  class="alignleft dw_search" >
		<input type="hidden" name="tab" value="search"/>
		<input type="hidden" name="type" value="term"/>
		<input type="text" placeholder="Search Themes" onblur="this.value=(this.value==\'\') ? \'Search Themes\' : this.value;" onfocus="this.value=(this.value==\'Search Themes\') ? \'\' : this.value;" value="Search Themes" name="s" value="' . esc_attr('Search Themes') . '" class="text dw_search_input" />
		' . $eab_go_button;

	if ( is_admin() ) {
		$wp_admin_bar->add_menu( array(
			'id' 		=> 'themes_stuff',
			'parent' 	=> 'dsgnwrks_help_menu',
			'title' 	=> __( $themes_searchform ),
			'href' 		=> '#'
		) );
		$wp_admin_bar->add_menu( array(
			'id' 		=> 'themes_stuff_upload',
			'parent' 	=> 'themes_stuff',
			'title' 	=> __( $themes_upload ),
			'href' 		=> admin_url( 'theme-install.php?tab=upload' )
		) );
	} else {
		$wp_admin_bar->add_menu( array(
			'id' 		=> 'themes_stuff',
			'parent' 	=> 'dsgnwrks_help_menu',
			'title' 	=> __( $themes_title ),
			'href' 		=> admin_url( 'themes.php' )
		) );
		$wp_admin_bar->add_menu( array(
			'id' 		=> 'themes_stuff_search',
			'parent' 	=> 'themes_stuff',
			'title' 	=> __( $themes_searchform ),
			'href' 		=> '#'
		) );
		$wp_admin_bar->add_menu( array(
			'id' 		=> 'themes_stuff_upload',
			'parent' 	=> 'themes_stuff',
			'title' 	=> __( $themes_upload ),
			'href' 		=> admin_url( 'theme-install.php?tab=upload' )
		) );
	}
}

function dsgnwrks_add_media_menus() {
	if ( ! current_user_can( 'upload_files' ) ) 	return;

	global $wp_admin_bar;
	global $eab_go_button;

	$media_title 		= 'Media';
	$media_upload 		= 'Upload Media';
	$media_searchform 	= '
		<strong style="display:none;">Search Media</strong>
		<form method="get" action="' . admin_url( 'upload.php?tab=search' ) . '"  class="alignleft dw_search" >
		<input type="text" placeholder="Search Media"
			onblur="this.value=(this.value==\'\') ? \'Search Media\' : this.value;"
			onfocus="this.value=(this.value==\'Search Media\') ? \'\' : this.value;"
			name="s" value="Search Media" class="text dw_search_input" />
		' . $eab_go_button;

	if ( is_admin() ) {
		$wp_admin_bar->add_menu( array(
			'id' 		=> 'media_stuff',
			'parent' 	=> 'dsgnwrks_help_menu',
			'title' 	=> __( $media_searchform ),
			'href' 		=> '#'
		) );
		$wp_admin_bar->add_menu( array(
			'id' 		=> 'media_stuff_upload',
			'parent' 	=> 'media_stuff',
			'title' 	=> __( $media_upload ),
			'href' 		=> admin_url('media-new.php')
		) );
	} else {
		$wp_admin_bar->add_menu( array(
			'id' 		=> 'media_stuff',
			'parent' 	=> 'dsgnwrks_help_menu',
			'title' 	=> __( $media_title ),
			'href' 		=> admin_url( 'upload.php' )
		) );
		$wp_admin_bar->add_menu( array(
			'id' 		=> 'media_stuff_search',
			'parent' 	=> 'media_stuff',
			'title' 	=> __( $media_searchform ),
			'href' 		=> '#'
		) );
		$wp_admin_bar->add_menu( array(
			'id' 		=> 'media_stuff_upload',
			'parent' 	=> 'media_stuff',
			'title' 	=> __( $media_upload ),
			'href' 		=> admin_url('media-new.php')
		) );
	}
}

function dsgnwrks_add_users_menus() {
	if ( ! current_user_can( 'edit_users' ) ) 		return;

	global $wp_admin_bar;
	global $eab_go_button;

	$users_title 		= 'Users';
	$users_add 			= 'Add New User';
	$users_searchform 	= '
		<strong style="display:none;">Search Users</strong>
		<form method="get" action="' . admin_url( 'users.php?tab=search' ) . '"  class="alignleft dw_search" >
		<input type="text" placeholder="Search Users" onblur="this.value=(this.value==\'\') ? \'Search Users\' : this.value;" onfocus="this.value=(this.value==\'Search Users\') ? \'\' : this.value;" value="Search Users" name="s" value="' . esc_attr( 'Search Users' ) . '" class="text dw_search_input" />
		' . $eab_go_button;

	if ( is_admin() ) {
		$wp_admin_bar->add_menu( array(
			'id' 		=> 'user_stuff',
			'parent' 	=> 'dsgnwrks_help_menu',
			'title' 	=> __( $users_searchform ),
			'href' 		=> admin_url( 'users.php' )
		) );
		$wp_admin_bar->add_menu( array(
			'id' 		=> 'user_stuff_add',
			'parent' 	=> 'user_stuff',
			'title' 	=> __( $users_add ),
			'href' 		=> admin_url( 'user-new.php' )
		) );
	} else {
		$wp_admin_bar->add_menu( array(
			'id' 		=> 'user_stuff',
			'parent' 	=> 'dsgnwrks_help_menu',
			'title' 	=> __( $users_title ),
			'href' 		=> admin_url( 'users.php' )
		) );
		$wp_admin_bar->add_menu( array(
			'id' 		=> 'user_stuff_search',
			'parent' 	=> 'user_stuff',
			'title' 	=> __( $users_searchform ),
			'href' 		=> '#'
		) );
		$wp_admin_bar->add_menu( array(
			'id' 		=> 'user_stuff_add',
			'parent' 	=> 'user_stuff',
			'title' 	=> __( $users_add ),
			'href' 		=> admin_url( 'user-new.php' )
		) );
	}
}


?>
