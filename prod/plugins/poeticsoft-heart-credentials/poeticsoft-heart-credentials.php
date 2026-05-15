<?php
/**
 * Plugin Name: Poeticsoft Heart Credentials
 * Description: Gestión centralizada de credenciales y configuración para el ecosistema Poeticsoft Heart.
 * Version: 1.0.0
 * Author: Poeticsoft
 * Text Domain: poeticsoft-heart-credentials
 *
 * @package PoeticsoftHeart\Credentials
 */

namespace Poeticsoft\Heart\Credentials;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Carga del Autoloader de Composer.
if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
	require_once __DIR__ . '/vendor/autoload.php';
}

// Inicializar el orquestador de credenciales en el hook 'init'.
add_action( 'init', function() {
	new Credentials_Manager();
} );
