# Enhanced Admin Bar with Codex Search and Custom Menus #

**Contributors:** jtsternberg, underblob  
**Plugin Name:** Enhanced Admin Bar with Codex Search and Custom Menus  
**Plugin URI:** http://dsgnwrks.pro/enhanced-admin-bar-with-codex-search/  
**Tags:** Admin Bar, Codex Search, Search, Admin, adminbar, bar, topbar, plugin search, wpbeginner, custom menu, menus, forum search, genesis  
**Author URI:** http://dsgnwrks.pro/enhanced-admin-bar-with-codex-search/  
**Author:** DsgnWrks  
**Donate link:** http://j.ustin.co/rYL89n  
**Requires at least:** 3.4  
**Tested up to:** 4.3.1  
**Stable tag:** 2.0.7  
**Version:** 2.0.7  
**License:** GPLv2 or later  
**License URI:** http://www.gnu.org/licenses/gpl-2.0.html  
  

This plugin adds convenient search fields to provide easy access to the codex, wpbeginner, WordPress support forums and common wp-admin areas via the WordPress Admin Bar. Also, add your own custom menu to the Admin Bar. options can now be controlled from an options page.

## Description ##

This simple plugin enhances the default WordPress admin bar by adding a new menus that includes search fields for searching the Codex, wpbeginner.com, WordPress support forums, plugins repository, themes repository etc. Links and search fields to common areas in wp-admin (posts, pages, users, custom post types, plugins, media, settings) are included in the drop down menu when you are not in the WordPress admin area.

If that's not enough link goodness for you, the plugin now has the option for a custom WordPress menu in the WordPress Admin Bar. (Props to [@wpsnipp](https://twitter.com/wpsnipp) - [wpsnipp.com](http://wpsnipp.com)) If you go to wp-admin/nav-menus.php you'll see a theme location, "Admin Bar Custom Navigation Menu" where you can attach a custom menu.

Other plugin features:
	* if you use the Genesis theme framework, a custom Genesis menu can be added to the admin bar. (for more enhanced functionality regarding Genesis admin bar menus, see [GaryJ](http://profiles.wordpress.org/users/GaryJ)'s excellent plugin, [Genesis Admin Bar Plus](http://wordpress.org/extend/plugins/genesis-admin-bar-plus))
	* Turn wpbeginner.com search and WordPress support forums search on/off.
	* Turn on/off admin bar features on the front-end or the admin side.


## Installation ##

1. Upload the `enhanced-admin-bar-with-codex-search` directory to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Visit the plugin settings page (`/wp-admin/options-general.php?page=eab-importer-settings`) to verify the plugin options you want enabled.
4. If you have enabled the custom menu option, navigate to "Menus" under "Appearance." You'll see a theme location, "Admin Bar Custom Navigation Menu" where you can attach a custom menu.

## Frequently Asked Questions ##

### ?? ###
If you run into a problem or have a question, contact me ([contact form](http://j.ustin.co/scbo43) or [@jtsternberg on twitter](http://j.ustin.co/wUfBD3)). I'll add them here.

## Screenshots ##

###1. Menu view from the front-end of your site.
###
![Menu view from the front-end of your site.
](https://raw.github.com/jtsternberg/Enhanced-Admin-Bar-with-Codex-Search/master/screenshot-1.png)

###2. Menu view from WordPress Admin back-end.
###
![Menu view from WordPress Admin back-end.
](https://raw.github.com/jtsternberg/Enhanced-Admin-Bar-with-Codex-Search/master/screenshot-2.png)

###3. Options page for selecting which features are enabled.
###
![Options page for selecting which features are enabled.
](https://raw.github.com/jtsternberg/Enhanced-Admin-Bar-with-Codex-Search/master/screenshot-3.png)

###4. View of WordPress built-in menu system with a custom menu added to the Admin Bar.
###
![View of WordPress built-in menu system with a custom menu added to the Admin Bar.
](https://raw.github.com/jtsternberg/Enhanced-Admin-Bar-with-Codex-Search/master/screenshot-4.png)

###5. View of Admin Bar with custom menu added.
###
![View of Admin Bar with custom menu added.
](https://raw.github.com/jtsternberg/Enhanced-Admin-Bar-with-Codex-Search/master/screenshot-5.png)

###6. Codex search functions like new search box in 3.3 admin bar.
###
![Codex search functions like new search box in 3.3 admin bar.
](https://raw.github.com/jtsternberg/Enhanced-Admin-Bar-with-Codex-Search/master/screenshot-6.png)

###7. View of admin bar on front-end with Genesis menu option enabled.
###
![View of admin bar on front-end with Genesis menu option enabled.
](https://raw.github.com/jtsternberg/Enhanced-Admin-Bar-with-Codex-Search/master/screenshot-7.png)


## Changelog ##

### 2.0.7 ###
* Update text-domain.

### 2.0.6 ###
* Do not enqueue CSS if admin bar is not showing.

### 2.0.5.3 ###
* Fixes bug that was keeping codex search from working. Minor adjustments for new admin style. Remove dashboard widget. i18n the text strings.

### 2.0.5.2 ###
* Removed deprecated functions. Plugin now requires 3.4.

### 2.0.5.1 ###
* Moves style block to correct spot (in admin_head).

### 2.0.5 ###
* Fixes bug where only one custom post-type would display. Also now works for user roles other than Admins. Props to [@underblob](https://twitter.com/underblob)

### 2.0.4 ###
* Fixed broken image link, and fixed some WP_DEBUG errors.

### 2.0.3 ###
* Updates for WordPress 3.3.

### 2.0.2 ###
* Remove empty variables.

### 2.0.1 ###
* Updated Genesis menu icon css.

### 2.0 ###
* Added options page to enable/disable plugin features.
* Added a WordPress Forum search box option.
* Added Genesis framework menu option.
* Removed features incompatible with WP 3.3.

### 1.5.3 ###
* Fixed bug with duplicate menu classes. Also removed search forms from admin side. *future release: options page to enable/disable plugin features

### 1.5.2 ###
* Fixed bug that displayed labels intended for screenreaders on the screen.

### 1.5.1 ###
* Fixed small bug that didn't remove main menu item when a custom menu was removed.

### 1.5 ###
* Plugins search now takes you to the WordPress internal plugins search page rather than wordpress.org.
* Added search for: wpbeginner.com Themes, Media, Pages, Posts, Custom Post Types.
* Added an "Upload" menu item to Plugins, Themes, and Media.
* Added an "Add New" menu item to Pages, Posts, Custom Post Types.
* Added Settings sub-menus to the Settings dropdown in the Admin Bar Menu.
* Added an option to add a custom menu to the Admin Bar from the built-in WordPress menu system.
