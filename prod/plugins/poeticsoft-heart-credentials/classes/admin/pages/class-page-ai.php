<?php
/**
 * Página de configuración de Agentes e IA.
 *
 * @package PoeticsoftHeart\Credentials\Admin\Pages
 */

namespace Poeticsoft\Heart\Credentials\Admin\Pages;

use Poeticsoft\Heart\Credentials\Admin\Settings_Page_Base;
use Poeticsoft\Heart\Credentials\Admin\Admin_Menu;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Clase Page_AI
 */
class Page_AI extends Settings_Page_Base {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->page_slug    = 'psh-ai';
		$this->page_title   = __( 'Agentes y Modelos IA', 'poeticsoft-heart-credentials' );
		$this->option_group = 'psh_ai_group';
		parent::__construct();
		add_action( 'admin_menu', [ $this, 'add_submenu_page' ] );
	}

	/**
	 * Añade la subpágina al menú principal.
	 */
	public function add_submenu_page() {
		add_submenu_page(
			Admin_Menu::PARENT_SLUG,
			$this->page_title,
			__( 'Agentes e IA', 'poeticsoft-heart-credentials' ),
			'manage_options',
			$this->page_slug,
			[ $this, 'render_page' ]
		);
	}

	/**
	 * Registra los ajustes.
	 */
	public function register_settings() {
		register_setting( $this->option_group, 'psh_ai_openai_key' );
		register_setting( $this->option_group, 'psh_ai_gemini_key' );
		register_setting( $this->option_group, 'psh_ai_anthropic_key' );

		add_settings_section(
			'psh_ai_section',
			__( 'API Keys de Modelos de Inteligencia Artificial', 'poeticsoft-heart-credentials' ),
			null,
			$this->page_slug
		);

		$fields = [
			'psh_ai_openai_key'    => __( 'OpenAI API Key', 'poeticsoft-heart-credentials' ),
			'psh_ai_gemini_key'    => __( 'Gemini API Key', 'poeticsoft-heart-credentials' ),
			'psh_ai_anthropic_key' => __( 'Anthropic API Key', 'poeticsoft-heart-credentials' ),
		];

		foreach ( $fields as $id => $title ) {
			add_settings_field(
				$id,
				$title,
				[ $this, 'render_text_field' ],
				$this->page_slug,
				'psh_ai_section',
				[ 
					'label_for' => $id,
					'type'      => 'password'
				]
			);
		}
	}
}
