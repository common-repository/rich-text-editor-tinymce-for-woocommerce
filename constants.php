<?php

namespace HulkPlugins\woo\tmcecd;

const VERSION = '1.0.0';

define( 'HulkPlugins\woo\tmcecd\PLUGIN_DIR_PATH', plugin_dir_path( __FILE__ ) );
define( 'HulkPlugins\woo\tmcecd\PLUGIN_DIR_URL', plugin_dir_url( __FILE__ ) );

const SUPPORT       = 'https://hulkplugins.com/support/';
const DOCUMENTATION = 'https://hulkplugins.gitbook.io/rich-text-editor-tinymce-for-woocommerce/';

const SUPPORTED_TAXONOMIES = [
	'product_cat',
	'product_tag',
];
