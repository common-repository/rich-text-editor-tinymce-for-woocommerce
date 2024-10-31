<?php
/**
 * Plugin Name:       Term Description: Rich Text Editor (Powered by TinyMCE) for WooCommerce
 * Plugin URI:        https://hulkplugins.com/rich-text-editor-tinymce-for-woocommerce
 * Description:       This plugin enables the TinyMCE (WYSIWYG) editor for WooCommerce product categories and tags "description". This lets you easily add text formats like H1, and H2 titles, bullet points, images, and bold text. This would be great for SEO purposes.
 * Author:            Hulk Plugins
 * Author URI:        https://hulkplugins.com
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       rich-text-editor-tinymce-for-woocommerce
 * Domain Path:       /languages
 * Version:           1.0.0
 * Requires PHP:      7.4
 * Requires at least: 6.2
 * Requires Plugins:  woocommerce
 *
 * @package         HulkPlugins
 */

namespace HulkPlugins\woo\tmcecd;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Constants
require_once plugin_dir_path( __FILE__ ) . 'constants.php';

// Composer autoload
require_once PLUGIN_DIR_PATH . 'vendor/autoload.php';

// Init
I18n::get_instance()->init_hooks();
Base::get_instance()->init_hooks();
TinyMCE::get_instance()->init_hooks();
