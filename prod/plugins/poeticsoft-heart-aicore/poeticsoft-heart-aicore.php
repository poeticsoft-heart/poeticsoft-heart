<?php
/**
 * Plugin Name: Poeticsoft Heart AICore
 * Description: Capa de abstracción y orquestación para modelos de Inteligencia Artificial.
 * Version: 1.0.0
 * Author: Poeticsoft
 * Text Domain: poeticsoft-heart-aicore
 *
 * @package PoeticsoftHeart\AICore
 */

namespace Poeticsoft\Heart\AICore;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Carga del Autoloader de CORE (Dependencias compartidas)
$core_autoload = dirname( __DIR__ ) . '/poeticsoft-heart-core/vendor/autoload.php';
if ( file_exists( $core_autoload ) ) {
	require_once $core_autoload;
}

// Carga del Autoloader interno del plugin
if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
	require_once __DIR__ . '/vendor/autoload.php';
}

add_action( 'init', function() {
	global $psh_ai_manager;
	$psh_ai_manager = new AI_Manager();
} );
