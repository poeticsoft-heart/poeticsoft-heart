<?php
/**
 * Clase para el registro dinámico de bloques del plugin.
 *
 * @package PoeticsoftHeart\Blocks
 */

namespace Poeticsoft\Heart\BlocksBase;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Clase Blocks_Registry
 */
class Blocks_Registry {

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'init', [ $this, 'register_blocks' ] );
		add_filter( 'block_categories_all', [ $this, 'add_block_categories' ], 9, 2 );
	}

	/**
	 * Añade categorías personalizadas al insertador de bloques.
	 *
	 * @param array $categories Categorías existentes.
	 * @param WP_Block_Editor_Context $block_editor_context Contexto del editor.
	 */
	public function add_block_categories( $categories, $block_editor_context ) {
		$psh_category = [
			'slug'  => 'poeticsoft-heart-base',
			'title' => __( 'Poeticsoft Heart Base', 'poeticsoft-heart-base' ),
			'icon'  => 'admin-site',
		];

		if ( ! is_array( $categories ) ) {
			return [ $psh_category ];
		}

		// Insertar al principio.
		array_unshift( $categories, $psh_category );

		return $categories;
	}

	/**
	 * Registra los bloques personalizados del plugin.
	 */
	public function register_blocks() {
		$blocks_dir = plugin_dir_path( dirname( __FILE__ ) ) . 'blocks/';

		error_log( 'PSH Debug: Scanning blocks dir: ' . $blocks_dir );

		if ( ! is_dir( $blocks_dir ) ) {
			error_log( 'Plugin Error: Blocks directory not found in ' . $blocks_dir );
			return;
		}

		// Escaneamos el directorio de bloques para registrarlos automáticamente.
		$blocks = array_diff( scandir( $blocks_dir ), [ '..', '.' ] );

		foreach ( $blocks as $block_name ) {
			$block_path = $blocks_dir . $block_name;
			if ( is_dir( $block_path ) && file_exists( $block_path . '/block.json' ) ) {
				error_log( 'PSH Debug: Attempting to register block from path: ' . $block_path );
				$result = register_block_type( $block_path );
				if ( is_wp_error( $result ) ) {
					error_log( 'Plugin Error: Failed to register block ' . $block_name . ': ' . $result->get_error_message() );
				} else {
					error_log( 'PSH Debug: Successfully registered block: ' . $block_name );
				}
			}
		}
	}
}
