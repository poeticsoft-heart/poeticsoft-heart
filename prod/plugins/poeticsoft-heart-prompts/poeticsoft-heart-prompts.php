<?php
/**
 * Plugin Name: Poeticsoft Heart Prompts
 * Description: Gestión de prompts para el ecosistema Poeticsoft Heart con soporte nativo de Markdown.
 * Version: 1.0.0
 * Author: Poeticsoft
 * Namespace: Poeticsoft\Heart\Prompts
 */

namespace Poeticsoft\Heart\Prompts;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Cargar el autoloader de dependencias externas (Core)
$core_autoloader = dirname( __DIR__ ) . '/poeticsoft-heart-core/vendor/autoload.php';
if ( file_exists( $core_autoloader ) ) {
	require_once $core_autoloader;
}

// Cargar el autoloader interno del plugin
$plugin_autoloader = __DIR__ . '/vendor/autoload.php';
if ( file_exists( $plugin_autoloader ) ) {
	require_once $plugin_autoloader;
}

/**
 * Inicialización del plugin
 */
add_action( 'plugins_loaded', function() {
	// Inicializar el gestor de prompts
	if ( class_exists( 'Poeticsoft\Heart\Prompts\Prompts_Manager' ) ) {
		new Prompts_Manager();
	}
	
	// Inicializar el generador de Markdown
	if ( class_exists( 'Poeticsoft\Heart\Prompts\Markdown_Generator' ) ) {
		new Markdown_Generator();
	}
} );
