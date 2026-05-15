<?php
/**
 * Plugin Name: Poeticsoft Heart API
 * Description: Sistema central de API REST para el ecosistema Poeticsoft Heart.
 * Version: 1.0.0
 * Author: Poeticsoft
 * Text Domain: poeticsoft-heart-api
 *
 * @package PoeticsoftHeart\API
 */

namespace Poeticsoft\Heart\API;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Carga del Autoloader de Composer.
if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
	require_once __DIR__ . '/vendor/autoload.php';
}

// Inicializar el orquestador de la API en el hook 'init'.
add_action( 'init', function() {
	new API_Manager();
} );
