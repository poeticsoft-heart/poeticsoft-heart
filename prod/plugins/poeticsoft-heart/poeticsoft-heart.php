<?php
/**
 * Plugin Name: Poeticsoft Heart
 * Description: Poeticsoft Heart Ecosystem Core Plugin.
 * Version: 2.0.0
 * Author: Poeticsoft
 * Text Domain: poeticsoft-heart
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * composer dump-autoload -o
 */

require_once __DIR__ . '/vendor/autoload.php';

new \Poeticsoft\Heart\Main();
