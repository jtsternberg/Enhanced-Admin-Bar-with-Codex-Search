<?php
    $codex_search_submenu = get_option( 'eab-codex-search-submenu' );
    $eab_admin_searches = get_option( 'eab-admin-searches' );
    $eab_wp_forums = get_option( 'eab-wp-forums' );
    $eab_wp_beginner = get_option( 'eab-wp-beginner' );
    $eab_custom_menu = get_option( 'eab-custom-menu' );
    if ( function_exists( 'genesis' ) ) $eab_genesis_menu = get_option( 'eab-genesis-menu' );
    $eab_dash_widget = get_option( 'eab-dash-widget' );
?>

<div class="wrap">
    <h2>Enhanced Admin Bar Options</h2>

    <form class="enhanced-admin-bar" method="post" action="options.php">
        <?php settings_fields('enhanced-admin-bar'); ?>
        <p>Thanks for using the Enhanced Admin Bar with Codex search! You'll find options below for selecting which functionality you would like enabled.</p>

        <table class="form-table">
            <tr valign="top">
            <th scope="row">
            <strong>Admin bar codex search and submenu (Front-end)</strong>
            <p>Adds a <a href="http://codex.wordpress.org/" target="_blank">codex</a> search box to the admin bar (if you have it enabled) on the front-end of your site as well as several sub-menu items for handy access to admin areas.<p>
            </th>
            <td><input type="checkbox" name="eab-codex-search-submenu" value="yes"<?php echo $codex_search_submenu == 'yes' ? ' checked' : '';?> /></td>
            </tr>

            <tr valign="top">
            <th scope="row">
            <strong>Admin bar codex search and search submenus (admin-side)</strong>
            <p>Adds a <a href="http://codex.wordpress.org/" target="_blank">codex</a> search box to the admin bar (if you have it enabled) on the admin side as well as several sub-menu quick search boxes for searching the admin area.<p>
            </th>
            <td><input type="checkbox" name="eab-admin-searches" value="yes"<?php echo $eab_admin_searches == 'yes' ? ' checked' : '';?> /></td>
            </tr>

            <tr valign="top">
            <th scope="row">
            <strong>Search WordPress Support Forums</strong>
            <p>Adds a search box menu item to the admin bar that takes you to <a href="http://wordpress.org/support/" target="_blank">http://wordpress.org/support/</a>, the best place to get support for WordPress.<p>
            </th>
            <td><input type="checkbox" name="eab-wp-forums" value="yes"<?php echo $eab_wp_forums == 'yes' ? ' checked' : '';?> /></td>
            </tr>

            <tr valign="top">
            <th scope="row">
            <strong>Search WPBeginner</strong>
            <p>Adds a search box menu item to the admin bar that takes you to <a href="http://www.wpbeginner.com/" target="_blank">http://www.wpbeginner.com/</a>, a great resource for WordPress tips and tricks.<p>
            </th>
            <td><input type="checkbox" name="eab-wp-beginner" value="yes"<?php echo $eab_wp_beginner == 'yes' ? ' checked' : '';?> /></td>
            </tr>

            <tr valign="top">
            <th scope="row">
            <strong>Add custom menu option for admin bar</strong>
            <p>Adds ability to add an additional menu to the admin bar using WordPress' built in menu functionality.<p>
            </th>
            <td><input type="checkbox" name="eab-custom-menu" value="yes"<?php echo $eab_custom_menu == 'yes' ? ' checked' : '';?> /></td>
            </tr>

            <?php if ( function_exists( 'genesis' ) ) { ?>
                <tr valign="top">
                <th scope="row">
                <strong>Add Genesis theme menu</strong>
                <p>Adds a top-level menu item with dropdowns to access the Genesis framework settings pages. (for more enhanced functionality regarding Genesis admin bar menus, see <a href="http://profiles.wordpress.org/users/GaryJ/">GaryJ</a>'s excellent plugin, <a href="http://wordpress.org/extend/plugins/genesis-admin-bar-plus/">Genesis Admin Bar Plus</a>)<p>
                </th>
                <td><input type="checkbox" name="eab-genesis-menu" value="yes"<?php echo $eab_genesis_menu == 'yes' ? ' checked' : '';?> /></td>
                </tr>
            <?php } ?>

            <tr valign="top">
            <th scope="row">
            <strong>Add theme info dashboard widget</strong>
            <p>Displays info about current theme.<p>
            </th>
            <td><input type="checkbox" name="eab-dash-widget" value="yes"<?php echo $eab_dash_widget == 'yes' ? ' checked' : '';?> /></td>
            </tr>

        </table>

        <p class="submit">
        <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
        </p>

    </form>
    <p class="jtsocial"><a class="jtpaypal" href="http://j.ustin.co/rYL89n" target="_blank">Contribute<span></span></a>
        <a class="jttwitter" href="http://j.ustin.co/wUfBD3" target="_blank">Follow me on Twitter<span></span></a>
        <a class="jtemail" href="http://j.ustin.co/scbo43" target="blank">Contact Me<span></span></a>
    </p>

</div>