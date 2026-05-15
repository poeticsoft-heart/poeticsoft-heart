<?php
/**
 * Endpoints para la gestión y conversión de contenidos.
 *
 * @package PoeticsoftHeart\API\Endpoints
 */

namespace Poeticsoft\Heart\API\Endpoints;

use Poeticsoft\Heart\API\Endpoints\Endpoint_Base;
use Poeticsoft\Heart\ContentArchitect\Block_Converter;
use WP_REST_Request;
use WP_REST_Response;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Clase Content_Endpoints
 */
class Content_Endpoints extends Endpoint_Base {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->namespace = 'psh/v1';
		$this->rest_base = 'content';
	}

	/**
	 * Registra las rutas de contenido.
	 */
	public function register_routes() {
		// Endpoint 1: Solo conversión (Devuelve los bloques)
		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base . '/convert',
			[
				[
					'methods'             => 'POST',
					'callback'            => [ $this, 'convert_content' ],
					'permission_callback' => [ $this, 'check_permission' ],
					'args'                => [
						'markdown' => [
							'required'          => true,
							'type'              => 'string',
							'description'       => 'Texto en formato Markdown a convertir.',
							'sanitize_callback' => 'wp_kses_post',
						],
					],
				],
			]
		);

		// Endpoint 2: Crear Página Real
		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base . '/create',
			[
				[
					'methods'             => 'POST',
					'callback'            => [ $this, 'create_page' ],
					'permission_callback' => [ $this, 'check_permission' ],
					'args'                => [
						'title' => [
							'required'          => true,
							'type'              => 'string',
							'description'       => 'Título de la página.',
							'sanitize_callback' => 'sanitize_text_field',
						],
						'markdown' => [
							'required'          => true,
							'type'              => 'string',
							'description'       => 'Contenido en Markdown.',
							'sanitize_callback' => 'wp_kses_post',
						],
						'post_type' => [
							'required'          => false,
							'type'              => 'string',
							'default'           => 'page',
							'sanitize_callback' => 'sanitize_key',
						],
						'post_status' => [
							'required'          => false,
							'type'              => 'string',
							'default'           => 'draft',
							'sanitize_callback' => 'sanitize_key',
						],
					],
				],
			]
		);
	}

	/**
	 * Callback para convertir Markdown a bloques.
	 */
	public function convert_content( WP_REST_Request $request ) {
		$markdown = $request->get_param( 'markdown' );

		if ( ! class_exists( 'Poeticsoft\Heart\ContentArchitect\Block_Converter' ) ) {
			return new WP_REST_Response( [
				'success' => false,
				'message' => 'El plugin Content Architect no está activo o la clase de conversión no existe.',
			], 500 );
		}

		$converter = new Block_Converter();
		$blocks    = $converter->convert_markdown_to_blocks( $markdown );

		return new WP_REST_Response( [
			'success' => true,
			'data'    => [
				'blocks' => $blocks,
			],
		], 200 );
	}

	/**
	 * Callback para crear una página real desde Markdown.
	 */
	public function create_page( WP_REST_Request $request ) {
		$title       = $request->get_param( 'title' );
		$markdown    = $request->get_param( 'markdown' );
		$post_type   = $request->get_param( 'post_type' );
		$post_status = $request->get_param( 'post_status' );

		if ( ! class_exists( 'Poeticsoft\Heart\ContentArchitect\Block_Converter' ) ) {
			return $this->response( false, 'El plugin Content Architect no está activo.', [], 500 );
		}

		$converter = new Block_Converter();
		$post_id   = $converter->create_post( $title, $markdown, $post_type, $post_status );

		if ( is_wp_error( $post_id ) ) {
			return $this->response( false, 'Error al crear la página: ' . $post_id->get_error_message(), [], 500 );
		}

		return $this->response( true, 'Página generada con éxito.', [
			'post_id'   => $post_id,
			'edit_url'  => get_edit_post_link( $post_id, 'raw' ),
			'permalink' => get_permalink( $post_id ),
		] );
	}

	/**
	 * Verificación de permisos (Simplificada para desarrollo).
	 */
	public function check_permission() {
		return current_user_can( 'edit_posts' );
	}
}
