<?php
/**
 * Clase abstracta base para todos los controladores de endpoints.
 *
 * @package PoeticsoftHeart\API\Endpoints
 */

namespace Poeticsoft\Heart\API\Endpoints;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Clase Endpoint_Base
 */
abstract class Endpoint_Base {

	/**
	 * Namespace base de la API.
	 *
	 * @var string
	 */
	protected $namespace = 'poeticsoft/heart';

	/**
	 * Versión de la API.
	 *
	 * @var string
	 */
	protected $version = 'v1';

	/**
	 * Base de la ruta REST.
	 *
	 * @var string
	 */
	protected $rest_base;
	/**
	 * Método obligatorio para registrar las rutas del controlador.
	 */
	abstract public function register_routes();

	/**
	 * Obtiene el namespace completo (ej. poeticsoft/heart/v1).
	 *
	 * @return string
	 */
	protected function get_full_namespace() {
		return $this->namespace . '/' . $this->version;
	}

	/**
	 * Método auxiliar para crear respuestas estandarizadas.
	 *
	 * @param bool   $success Si la operación fue exitosa.
	 * @param string $message Mensaje de respuesta.
	 * @param array  $data    Datos adicionales.
	 * @param int    $status  Código HTTP.
	 *
	 * @return \WP_REST_Response
	 */
	protected function response( $success, $message = '', $data = [], $status = 200 ) {
		return new \WP_REST_Response(
			[
				'success' => $success,
				'message' => $message,
				'data'    => $data,
				'time'    => current_time( 'mysql' ),
			],
			$status
		);
	}
}
