<?php
/**
 * Plugin Name: Poeticsoft Heart Telemetry
 * Description: Sistema centralizado de logs y telemetría para WordPress.
 * Version: 1.0.0
 * Author: Poeticsoft
 * Text Domain: poeticsoft-heart-telemetry
 *
 * @package PoeticsoftHeart\Telemetry
 */

namespace Poeticsoft\Heart\Telemetry;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
	require_once __DIR__ . '/vendor/autoload.php';
}

add_action( 'init', function() {
	// Aquí se inicializará el Logger
} );
