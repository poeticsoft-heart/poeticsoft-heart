<?php
/**
 * Plugin Name: Poeticsoft Heart BlocksBase
 * Description: Librería base de bloques de Gutenberg para el ecosistema Poeticsoft Heart.
 * Version: 1.0.0
 * Author: Poeticsoft
 * Text Domain: poeticsoft-heart-blocksbase
 *
 * @package PoeticsoftHeart\BlocksBase
 */

namespace Poeticsoft\Heart\BlocksBase;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Carga del Autoloader de Composer.
if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
	require_once __DIR__ . '/vendor/autoload.php';
}

// Inicializar el plugin en el hook 'plugins_loaded'.
add_action( 'plugins_loaded', function() {
	new Blocks_Registry();
} );
