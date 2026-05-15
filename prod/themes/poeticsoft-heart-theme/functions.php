<?php

/**
 * Functions and definitions for Poeticsoft Heart Theme.
 *
 * @package PoeticsoftHeart
 */

if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

// Cargar el autoloader de Composer.
require_once get_template_directory() . '/vendor/autoload.php';

use Poeticsoft\Heart\Theme;

/**
 * Instanciamos la clase principal para inicializar todo el tema.
 */
new Theme();
