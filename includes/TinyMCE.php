<?php

namespace HulkPlugins\woo\tmcecd;

use WP_Screen;

class TinyMCE extends Singleton {

	private array $taxonomies = [];

	/**
	 * Initialize the hooks for the class.
	 *
	 * @return void
	 */
	public function init_hooks(): void {

		// Get the supported taxonomies from the constant and allow them to be filtered.
		$this->taxonomies = apply_filters(
			'hulk_woo_tmcecd_supported_taxonomies',
			SUPPORTED_TAXONOMIES
		);

		// Add the hooks for the current screen.
		add_action( 'current_screen', [ $this, 'current_screen' ] );

		// Add hooks for the term insertion and modification.
		add_filter( 'pre_insert_term', [ $this, 'pre_insert_term' ], 10, 2 );
		add_action( 'admin_enqueue_scripts', [ $this, 'admin_scripts' ] );

		// Add hooks for the TinyMCE editor.
		add_action( 'print_default_editor_scripts', [ $this, 'after_tiny_mce' ] );
		add_action( 'after_wp_tiny_mce', [ $this, 'after_tiny_mce' ] );

		// Add hooks for each supported taxonomy.
		foreach ( $this->taxonomies as $taxonomy ) {
			// Add a filter for retrieving the term.
			add_filter( "get_$taxonomy", [ $this, 'get_term' ], 10, 2 );

			// Add a filter for modifying the term description.
			add_filter( "pre_{$taxonomy}_description", [ $this, 'pre_term_description' ] );

			// Add hooks for the term add and edit forms.
			add_action( "{$taxonomy}_add_form_fields", [ $this, 'add_term_fields' ] );
			add_action( "{$taxonomy}_edit_form_fields", [ $this, 'edit_term_fields' ] );
		}
	}

	/**
	 * Remove the existing filters in the description field.
	 *
	 * @param string $taxonomy
	 *
	 * @return void
	 */
	public function remove_existing_filters( string $taxonomy ) {
		if ( in_array( $taxonomy, $this->taxonomies, true ) && is_admin() ) {
			remove_filter( 'pre_term_description', 'wp_filter_kses' );
			remove_filter( 'term_description', 'wp_kses_data' );
		}
	}

	/**
	 * Remove the existing filters in the description field when the
	 * current screen is a taxonomy edit screen.
	 *
	 * @param WP_Screen $screen
	 *
	 * @return void
	 */
	public function current_screen( WP_Screen $screen ) {
		$this->remove_existing_filters( $screen->taxonomy );
	}

	/**
	 * Remove the existing filters in the description field when inserting a new term.
	 *
	 * This is needed because the filters are not removed when inserting a new term.
	 *
	 * @param $term
	 * @param $taxonomy
	 *
	 * @return mixed
	 */
	public function pre_insert_term( $term, $taxonomy ) {
		$this->remove_existing_filters( $taxonomy );

		return $term;
	}

	/**
	 * Remove the existing filters in the description field when retrieving a term.
	 *
	 * This is needed because the filters are not removed when retrieving a term.
	 *
	 * @param $term
	 * @param $taxonomy
	 *
	 * @return mixed
	 */
	public function get_term( $term, $taxonomy ) {
		if ( is_ajax() ) {
			$this->remove_existing_filters( $taxonomy );
		}

		return $term;
	}

	/**
	 * Filter the term description before it is saved in the database.
	 *
	 * @param $value
	 *
	 * @return string
	 */
	public function pre_term_description( $value ): string {
		/*
		 * Use wp_kses_post to remove any malicious code from the term description.
		 * This is needed because the TinyMCE editor allows users to enter HTML code.
		 */
		return wp_kses_post( $value );
	}

	/**
	 * Enqueue admin scripts
	 *
	 * Enqueues the admin tags script and stylesheet.
	 *
	 * @return void
	 */
	public function admin_scripts() {
		// Get the asset file for the admin tags script
		$admin_tags = require_once PLUGIN_DIR_PATH . 'build/admin/tags/index.asset.php';

		// Register the stylesheet for the TinyMCE editor
		wp_register_style(
			'hulk-woo-tmcecd',
			PLUGIN_DIR_URL . 'build/admin/tags/index.css',
			[ 'wp-components' ],
			$admin_tags['version']
		);

		// Register the script for the TinyMCE editor
		wp_register_script(
			'hulk-woo-tmcecd',
			PLUGIN_DIR_URL . 'build/admin/tags/index.js',
			// Dependencies
			array_merge(
				[ 'jquery', 'editor', 'wp-tinymce', 'wp-hooks' ],
				$admin_tags['dependencies']
			),
			$admin_tags['version'],
			true
		);

		// Load translation files for the javascript
		wp_set_script_translations(
			'hulk-woo-tmcecd',
			'rich-text-editor-tinymce-for-woocommerce',
			PLUGIN_DIR_PATH . 'languages'
		);
	}

	/**
	 * Enqueue TinyMCE styles and scripts
	 *
	 * @param string $editorId The ID of the TinyMCE editor.
	 *
	 * @return void
	 */
	public function enqueue_editor( string $editorId ) {
		// Enqueue the media library
		wp_enqueue_media();

		// Enqueue the editor
		wp_enqueue_editor();

		// Enqueue the stylesheet for the TinyMCE editor
		wp_enqueue_style( 'hulk-woo-tmcecd' );

		// Enqueue the script for the TinyMCE editor
		wp_enqueue_script( 'hulk-woo-tmcecd' );

		// Localize the script with the editor ID
		wp_localize_script(
			'hulk-woo-tmcecd',
			'hulk_woo_tmcecd_settings',
			[
				'editorId' => esc_attr( $editorId ),
			]
		);
	}

	/**
	 * Add term field.
	 *
	 * Enqueues the TinyMCE editor on the 'Add New' term page.
	 *
	 * @return void
	 */
	public function add_term_fields() {
		$this->enqueue_editor( 'tag-description' );
	}

	/**
	 * Edit term field.
	 *
	 * Enqueues the TinyMCE editor on the 'Edit' term page.
	 *
	 * @return void
	 */
	public function edit_term_fields() {
		$this->enqueue_editor( 'description' );
	}

	/**
	 * Fires when the editor scripts are loaded for later initialization,
	 * after all scripts and settings are printed.
	 * @link https://developer.wordpress.org/reference/hooks/admin_print_footer_scripts/
	 *
	 * This hook is used to trigger the initialization of the TinyMCE editor.
	 * The `hulk_woo_tmcecd_after_tiny_mce` hook is used to initialize the editor.
	 *
	 * @return void
	 */
	public function after_tiny_mce() {
		echo /** @lang text */ "<script id='hulk-woo-tmcecd-js-after' type='text/javascript'>\n";
		echo 'wp.hooks.doAction("hulk_woo_tmcecd_after_tiny_mce");';
		echo /** @lang text */ "\n</script>";
	}
}
