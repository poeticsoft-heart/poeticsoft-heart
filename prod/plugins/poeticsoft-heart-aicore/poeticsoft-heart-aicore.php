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

if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
	require_once __DIR__ . '/vendor/autoload.php';
}

add_action( 'init', function() {
	// Aquí se inicializará el AI_Manager
} );
