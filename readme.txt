=== Athena Shortcodes Plugin ===
Contributors: ucfwebcom
Tags: athena-framework, shortcodes
Requires at least: 4.5.3
Tested up to: 5.3
Stable tag: 0.5.3
Requires PHP: 5.4
License: GPLv3 or later
License URI: http://www.gnu.org/copyleft/gpl-3.0.html

Provides shortcodes that support the use of the [Athena Framework](https://github.com/UCF/Athena-Framework).


== Description ==

Provides shortcodes that support the use of the [Athena Framework](https://github.com/UCF/Athena-Framework).


== Documentation ==

Head over to the [Athena Shortcodes Plugin wiki](https://github.com/UCF/Athena-Shortcodes-Plugin/wiki) for more detailed information about this plugin, including available shortcodes.


== Installation ==

= Manual Installation =
1. Upload the plugin files (unzipped) to the `/wp-content/plugins` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the "Plugins" screen in WordPress

= WP CLI Installation =
1. `$ wp plugin install --activate https://github.com/UCF/Athena-Shortcodes-Plugin/archive/master.zip`.  See [WP-CLI Docs](http://wp-cli.org/commands/plugin/install/) for more command options.


== Changelog ==

= 0.5.3 =
Enhancements:
* Added plugin option that turns off the plugin's ability to strip `width`/`height` attributes from `<img>` elems (enabled by default for backward compatibility)
* Added absolute file includes in main plugin file
* Upgraded packages

Bug Fixes:
* Fixed issue with default plugin options not applying correctly
* Added fix for images within `<figure>` elems in IE11
* Fixed notices with PHP 7.4+

= 0.5.2 =
Documentation:
* Updated the contributing doc to reflect the switch from slack to teams.

= 0.5.1 =
Enhancements:
* Upgraded packages, including Athena Framework, to latest versions

= 0.5.0 =
Enhancements:
* Added the ability to let TinyMCE automatically add opt-in content classes (`.table` on `<table>`s, `.blockquote` on `<blockquote>`s.  By default this is enabled, but it can be disabled via plugin settings in case of third-party component conflicts.
* Updated the "Formats" dropdown in the WYSIWYG editor to exclude `.table` and `.blockquote` classes when automatic opt-in class insertion is enabled.
* Added a new `labelledby` shortcode attribute for `[tab-pane]`.
* Upgraded packages

Bug fixes:
* Fixed non-functional `option_{$option->option_name}` filters.  Plugin options are now properly formatted when returned via `get_option()`.
* Replaced `break` with `continue` in `ATHENA_SC_TinyMCE_Config::get_format_options()` to allow null format options to be passed in without breaking all other format options.

= 0.4.2 =
Enhancements:
* Add Github URI

= 0.4.1 =
Enhancements:
* Removed usage of `create_function()` in the plugin to support newer versions of PHP.
* Added a minimum PHP requirement for the plugin.

= 0.4.0 =
Enhancements:
* Adds a new settings page for the plugin, which allows users to adjust the plugin's TinyMCE + image formatting option, as well as a new option for enabling responsive wrappers around oEmbeds and videos.

  Responsive embeds are disabled by default to avoid conflicts with sites that already have `.embed-responsive` wrapper divs added around their embeds manually.  Site administrators should enable this setting on new sites, or audit and update any existing posts/pages to ensure embeds won't be double-wrapped after enabling this setting.

= 0.3.4 =
Enhancements:
* Added cache-busting for the WYSIWYG editor stylesheet
* Upgraded packages

= 0.3.3 =
Enhancements:
* Upgraded packages, linter configs, and gulpfile; added Github issue/PR templates and CONTRIBUTING file to the repo

Bug fixes:
* Removed alignment classes from `.figure-img` images (images with captions, within `<figure>`s); fixes various vertical spacing and float clearing issues when left- or right-alignment is set on the `<figure>`.
* Fixed incorrect class name `font-weight-italic` in WYSIWYG Formats dropdown > Font Weight/Style (now `font-italic`)

= 0.3.2 =
Enhancements:
* Added ID attribute support to container, row and col shortcodes.
* Added WYSIWYG format option support for mark and small classes.
* Renames the `[block-link]` shortcode to `[link]` for clarity, since this shortcode doesn't technically generate a block-level link. To maintain backward compatibility, the alias block-link has been added, so either shortcode name can be used interchangeably.

Bug fixes:
* Setting the `element_type` to "button" on the shortcode now generates a button element instead of an `<a>` tag.

= 0.3.1 =
Bug fixes:
* Added missing `max-width` styling on `<figure>` element markup modified by this plugin, which prevents figure caption text from spanning a width greater than the image's width.

= 0.3.0 =
Enhancements:
* Added shortcodes for alerts: `[alert]`, `[alert-heading]` and `[alert-link]`.
* Updated npm packages list.
* Upgraded admin css to latest verion of Athena Framework.

= 0.2.0 =
Enhancements:
* Added `[media-background-container]` and `[media-background]` shortcodes. `<img>` and `<video>` media backgrounds are currently supported.
* Added `[block-link]` shortcode for easier inclusion of generic block-level links in WordPress content.
* Added `[icon]` shortcode for easier inclusion of font icons. The shortcode is library-agnostic/relevant classes for FontAwesome, etc will need to be added to the `class` attribute wherever this shortcode is used.
* Added WP Shortcode Interface group name support for existing shortcodes.
* Upgraded admin css to latest version of Athena Framework.

= 0.1.0 =
* Initial release


== Upgrade Notice ==

n/a


== Development ==

Note that compiled, minified css files are included within the repo.  Changes to these files should be tracked via git (so that users installing the plugin using traditional installation methods will have a working plugin out-of-the-box.)

[Enabling debug mode](https://codex.wordpress.org/Debugging_in_WordPress) in your `wp-config.php` file is recommended during development to help catch warnings and bugs.

= Requirements =
* node
* gulp-cli

= Instructions =
1. Clone the Athena-Shortcodes-Plugin repo into your local development environment, within your WordPress installation's `plugins/` directory: `git clone https://github.com/UCF/Athena-Shortcodes-Plugin.git`
2. `cd` into the new Athena-Shortcodes-Plugin directory, and run `npm install` to install required packages for development into `node_modules/` within the repo
3. Optional: If you'd like to enable [BrowserSync](https://browsersync.io) for local development, or make other changes to this project's default gulp configuration, copy `gulp-config.template.json`, make any desired changes, and save as `gulp-config.json`.

    To enable BrowserSync, set `sync` to `true` and assign `syncTarget` the base URL of a site on your local WordPress instance that will use this plugin, such as `http://localhost/wordpress/my-site/`.  Your `syncTarget` value will vary depending on your local host setup.

    The full list of modifiable config values can be viewed in `gulpfile.js` (see `config` variable).
3. Run `gulp default` to process front-end assets.
4. If you haven't already done so, create a new WordPress site on your development environment to test this plugin against.
5. Activate this plugin on your development WordPress site.
6. Run `gulp watch` to continuously watch changes to scss files.  If you enabled BrowserSync in `gulp-config.json`, it will also reload your browser when plugin files change.

= Other Notes =
* This plugin's README.md file is automatically generated. Please only make modifications to the README.txt file, and make sure the `gulp readme` command has been run before committing README changes.  See the [contributing guidelines](https://github.com/UCF/Athena-Shortcodes-Plugin/blob/master/CONTRIBUTING.md) for more information.


== Contributing ==

Want to submit a bug report or feature request?  Check out our [contributing guidelines](https://github.com/UCF/Athena-Shortcodes-Plugin/blob/master/CONTRIBUTING.md) for more information.  We'd love to hear from you!
