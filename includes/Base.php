<?php

namespace HulkPlugins\woo\tmcecd;

class Base extends Singleton {

	public function init_hooks(): void {
		add_filter( 'plugin_row_meta', [ $this, 'plugin_row_meta' ], 10, 2 );
	}

	/**
	 * Add plugin meta
	 *
	 * @param $links
	 * @param $file
	 *
	 * @return array|mixed
	 */
	public function plugin_row_meta( $links, $file ) {

		if ( strpos( $file, 'rich-text-editor-tinymce-for-woocommerce.php' ) !== false ) {

			$row_meta['docs'] = sprintf(
			/** @lang text */                '<a target="_blank" href="%1$s" title="%2$s">%2$s</a>',
				esc_url( DOCUMENTATION ),
				esc_html__( 'Docs', 'rich-text-editor-tinymce-for-woocommerce' )
			);

			$row_meta['support'] = sprintf(
			/** @lang text */                '<a target="_blank" href="%1$s">%2$s</a>',
				esc_url( SUPPORT ),
				esc_html__( 'Help &amp; Support', 'rich-text-editor-tinymce-for-woocommerce' )
			);

			$links = array_merge( $links, $row_meta );
		}

		return $links;
	}
}
