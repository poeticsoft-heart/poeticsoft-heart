<?php
/**
 * Plugin Name: Poeticsoft Heart Core
 * Description: Núcleo del ecosistema Poeticsoft Heart. Gestiona dependencias compartidas y recursos base.
 * Version: 1.0.0
 * Author: Poeticsoft
 * Text Domain: poeticsoft-heart-core
 *
 * @package PoeticsoftHeart\Core
 */

namespace Poeticsoft\Heart\Core;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * El autoloader de este plugin servirá para todo el ecosistema.
 */
if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
	require_once __DIR__ . '/vendor/autoload.php';
}

/**
 * Función de utilidad global para obtener el autoloader del core.
 */
function get_core_autoloader_path() {
	return __DIR__ . '/vendor/autoload.php';
}
