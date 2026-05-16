<?php
/**
 * Clase orquestadora del plugin de credenciales.
 *
 * @package PoeticsoftHeart\Credentials
 */

namespace Poeticsoft\Heart\Credentials;

use Poeticsoft\Heart\Credentials\Admin\Admin_Menu;
use Poeticsoft\Heart\Credentials\Admin\Pages\Page_Communications;
use Poeticsoft\Heart\Credentials\Admin\Pages\Page_AI;
use Poeticsoft\Heart\Credentials\Admin\Pages\Page_Instagram;
use Poeticsoft\Heart\Credentials\Integrations\SMTP_Configurator;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Clase Credentials_Manager
 */
class Credentials_Manager {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->init_admin();
		$this->init_integrations();
	}

	/**
	 * Inicializa la interfaz de administración.
	 */
	private function init_admin() {
		new Admin_Menu();
		new Page_Communications();
		new Page_AI();
		new Page_Instagram();
	}

	/**
	 * Inicializa las integraciones (SMTP, etc).
	 */
	private function init_integrations() {
		new SMTP_Configurator();
	}
}
