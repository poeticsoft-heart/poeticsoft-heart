<?php
/**
 * Clase principal del Tema Poeticsoft Heart.
 *
 * @package PoeticsoftHeart
 */

namespace Poeticsoft\Heart;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Clase Theme
 */
class Theme {

	/**
	 * Constructor de la clase.
	 */
	public function __construct() {
		$this->init_hooks();
	}

	/**
	 * Inicializa los hooks de WordPress.
	 */
	private function init_hooks() {
		add_action( 'after_setup_theme', [ $this, 'theme_setup' ] );
	}

	/**
	 * Configuración básica del tema.
	 */
	public function theme_setup() {
		// Soporte para títulos dinámicos.
		add_theme_support( 'title-tag' );

		// Soporte para miniaturas de post.
		add_theme_support( 'post-thumbnails' );

		// Soporte para alineaciones anchas en bloques.
		add_theme_support( 'align-wide' );
	}
	}