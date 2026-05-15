<?php
/**
 * Plugin Name: Poeticsoft Heart UICore
 * Description: Recursos globales, estilos y componentes de interfaz para el ecosistema.
 * Version: 1.0.0
 * Author: Poeticsoft
 * Text Domain: poeticsoft-heart-uicore
 *
 * @package PoeticsoftHeart\UICore
 */

namespace Poeticsoft\Heart\UICore;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
	require_once __DIR__ . '/vendor/autoload.php';
}

add_action( 'init', function() {
	// Aquí se inicializará el Assets_Manager
} );
