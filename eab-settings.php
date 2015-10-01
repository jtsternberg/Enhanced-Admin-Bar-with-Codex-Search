<?php
    $codex_search_submenu = get_option( 'eab-codex-search-submenu' );
    $eab_admin_searches = get_option( 'eab-admin-searches' );
    $eab_wp_forums = get_option( 'eab-wp-forums' );
    $eab_wp_beginner = get_option( 'eab-wp-beginner' );
    $eab_custom_menu = get_option( 'eab-custom-menu' );
    $eab_genesis_menu = function_exists( 'genesis' ) ? get_option( 'eab-genesis-menu' ) : '';
?>

<div class="wrap">
    <h2><?php _e( 'Enhanced Admin Bar Options', 'enhanced-admin-bar-with-codex-search' ); ?></h2>

    <form class="enhanced-admin-bar" method="post" action="options.php">
        <?php settings_fields('enhanced-admin-bar'); ?>
        <p><?php _e( 'Thanks for using the Enhanced Admin Bar with Codex search! You\'ll find options below for selecting which functionality you would like enabled.', 'enhanced-admin-bar-with-codex-search' ); ?></p>

        <table class="form-table">
            <tr valign="top">
            <th scope="row">
            <strong><?php _e( 'Admin bar codex search and submenu (Front-end)', 'enhanced-admin-bar-with-codex-search' ); ?></strong>
            <p><?php printf( __( 'Adds a %s search box to the admin bar (if you have it enabled) on the front-end of your site as well as several sub-menu items for handy access to admin areas.', 'enhanced-admin-bar-with-codex-search' ), '<a href="http://codex.wordpress.org/" target="_blank">'. __( 'codex', 'enhanced-admin-bar-with-codex-search' ) .'</a>' ); ?><p>
            </th>
            <td><input type="checkbox" name="eab-codex-search-submenu" value="yes"<?php echo $codex_search_submenu == 'yes' ? ' checked' : '';?> /></td>
            </tr>

            <tr valign="top">
            <th scope="row">
            <strong><?php _e( 'Admin bar codex search and search submenus (admin-side)', 'enhanced-admin-bar-with-codex-search' ); ?></strong>
            <p><?php printf( __( 'Adds a %s search box to the admin bar (if you have it enabled) on the admin side as well as several sub-menu quick search boxes for searching the admin area.', 'enhanced-admin-bar-with-codex-search' ), '<a href="http://codex.wordpress.org/" target="_blank">'. __( 'codex', 'enhanced-admin-bar-with-codex-search' ) .'</a>' ); ?><p>
            </th>
            <td><input type="checkbox" name="eab-admin-searches" value="yes"<?php echo $eab_admin_searches == 'yes' ? ' checked' : '';?> /></td>
            </tr>

            <tr valign="top">
            <th scope="row">
            <strong><?php _e( 'Search WordPress Support Forums', 'enhanced-admin-bar-with-codex-search' ); ?></strong>
            <p><?php printf( __( 'Adds a search box menu item to the admin bar that takes you to %s, the best place to get support for WordPress.', 'enhanced-admin-bar-with-codex-search' ), '<a href="http://wordpress.org/support/" target="_blank">wordpress.org/support</a>' ); ?><p>
            </th>
            <td><input type="checkbox" name="eab-wp-forums" value="yes"<?php echo $eab_wp_forums == 'yes' ? ' checked' : '';?> /></td>
            </tr>

            <tr valign="top">
            <th scope="row">
            <strong><?php _e( 'Search WPBeginner', 'enhanced-admin-bar-with-codex-search' ); ?></strong>
            <p><?php printf( __( 'Adds a search box menu item to the admin bar that takes you to %s, a great resource for WordPress tips and tricks.', 'enhanced-admin-bar-with-codex-search' ), '<a href="http://www.wpbeginner.com/" target="_blank">wpbeginner.com</a>' ); ?><p>
            </th>
            <td><input type="checkbox" name="eab-wp-beginner" value="yes"<?php echo $eab_wp_beginner == 'yes' ? ' checked' : '';?> /></td>
            </tr>

            <tr valign="top">
            <th scope="row">
            <strong><?php _e( 'Add custom menu option for admin bar', 'enhanced-admin-bar-with-codex-search' ); ?></strong>
            <p><?php _e( 'Adds ability to add an additional menu to the admin bar using WordPress\' built in menu functionality.', 'enhanced-admin-bar-with-codex-search' ); ?><p>
            </th>
            <td><input type="checkbox" name="eab-custom-menu" value="yes"<?php echo $eab_custom_menu == 'yes' ? ' checked' : '';?> /></td>
            </tr>

            <?php if ( function_exists( 'genesis' ) ) { ?>
                <tr valign="top">
                <th scope="row">
                <strong><?php _e( 'Add Genesis theme menu', 'enhanced-admin-bar-with-codex-search' ); ?></strong>
                <p><?php printf( __( 'Adds a top-level menu item with dropdowns to access the Genesis framework settings pages. (for more enhanced functionality regarding Genesis admin bar menus, see %s\'s excellent plugin, %s)', 'enhanced-admin-bar-with-codex-search' ), '<a href="http://profiles.wordpress.org/users/GaryJ/">GaryJ</a>', '<a href="http://wordpress.org/extend/plugins/genesis-admin-bar-plus/">'. __( 'Genesis Admin Bar Plus', 'enhanced-admin-bar-with-codex-search' ) .'</a>' ); ?><p>
                </th>
                <td><input type="checkbox" name="eab-genesis-menu" value="yes"<?php echo $eab_genesis_menu == 'yes' ? ' checked' : '';?> /></td>
                </tr>
            <?php } ?>

        </table>

        <p class="submit">
        <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
        </p>

    </form>
    <p class="jtsocial"><a class="jtpaypal" href="http://j.ustin.co/rYL89n" target="_blank"><?php _e( 'Contribute', 'enhanced-admin-bar-with-codex-search' ); ?><span></span></a>
        <a class="jttwitter" href="http://j.ustin.co/wUfBD3" target="_blank"><?php _e( 'Follow me on Twitter', 'enhanced-admin-bar-with-codex-search' ); ?><span></span></a>
        <a class="jtemail" href="http://j.ustin.co/scbo43" target="blank"><?php _e( 'Contact Me', 'enhanced-admin-bar-with-codex-search' ); ?><span></span></a>
    </p>

</div>
