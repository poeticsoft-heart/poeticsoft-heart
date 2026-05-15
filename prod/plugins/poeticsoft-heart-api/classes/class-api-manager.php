<?php
/**
 * Orquestador principal de la API REST.
 *
 * @package PoeticsoftHeart\API
 */

namespace Poeticsoft\Heart\API;

use Poeticsoft\Heart\API\Endpoints\System_Endpoints;
use Poeticsoft\Heart\API\Endpoints\Mail_Endpoints;
use Poeticsoft\Heart\API\Endpoints\Content_Endpoints;

if ( ! defined( 'ABSPATH' ) ) {
        exit;
}

/**
 * Clase API_Manager
 */
class API_Manager {

        /**
         * Constructor.
         */
        public function __construct() {
                add_action( 'rest_api_init', [ $this, 'init_endpoints' ] );
        }

        /**
         * Instancia y registra todos los controladores de endpoints.
         */
        public function init_endpoints() {
                $controllers = [
                        new System_Endpoints(),
                        new Mail_Endpoints(),
                        new Content_Endpoints(),
                ];
		foreach ( $controllers as $controller ) {
			$controller->register_routes();
		}
	}
}
