<?php
/**
 * Plugin Name: Poeticsoft Heart ContentArchitect
 * Description: Gestión de estructuras de datos y conversión de contenidos a bloques.
 * Version: 1.0.0
 * Author: Poeticsoft
 * Text Domain: poeticsoft-heart-contentarchitect
 *
 * @package PoeticsoftHeart\ContentArchitect
 */

namespace Poeticsoft\Heart\ContentArchitect;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Cargar el autoloader del CORE (compartido)
$core_autoloader = dirname( __DIR__ ) . '/poeticsoft-heart-core/vendor/autoload.php';
if ( file_exists( $core_autoloader ) ) {
	require_once $core_autoloader;
}

// Cargar el autoloader LOCAL (específico)
if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
	require_once __DIR__ . '/vendor/autoload.php';
}

add_action( 'plugins_loaded', function() {
	// Inicialización
} );
