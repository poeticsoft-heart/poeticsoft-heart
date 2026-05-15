<?php
namespace Poeticsoft\Heart\AICore;

use Poeticsoft\Heart\AICore\Providers\Gemini_Provider;

class AI_Manager {
    private $providers = [];
    private $store;

    public function __construct() {
        $this->register_provider( new Gemini_Provider() );
        $this->store = new Store_Manager();
    }

    public function get_store() {
        return $this->store;
    }

    public function register_provider( $provider ) {
        $this->providers[ $provider->get_name() ] = $provider;
    }

    /**
     * Obtiene un proveedor configurado con los ajustes globales.
     */
    public function get_provider( $name ) {
        $provider = $this->providers[ $name ] ?? null;
        if ( $provider ) {
            $config = [
                'api_key'   => get_option( 'psh_ai_' . $name . '_key' ),
                'model'     => get_option( 'psh_ai_' . $name . '_model' ),
                'cache_ttl' => get_option( 'psh_ai_cache_ttl' ),
            ];
            $provider->set_config( $config );
        }
        return $provider;
    }
}
