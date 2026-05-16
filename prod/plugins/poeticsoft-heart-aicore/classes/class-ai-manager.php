<?php
namespace Poeticsoft\Heart\AICore;

use Poeticsoft\Heart\AICore\Providers\Gemini_Provider;

/**
 * Orquestador principal de las operaciones de IA.
 */
class AI_Manager {
    private $providers = [];
    private $store;
    private $current_system_instruction = null;

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
     * Establece la instrucción de sistema para la próxima generación.
     * Puede recibir el nombre de un archivo en el store o un string directo.
     */
    public function set_system_instruction( $instruction_or_slug ) {
        // Intentar cargar desde el store
        $instruction = $this->store->get_prompt( $instruction_or_slug );
        
        // Si no existe en el store, usar el string tal cual
        $this->current_system_instruction = $instruction ? $instruction : $instruction_or_slug;
    }

    /**
     * Generación de contenido simple (no streaming).
     */
    public function generate_content( $prompt, $provider_name = 'gemini' ) {
        $provider = $this->get_provider( $provider_name );
        
        if ( ! $provider ) {
            throw new \Exception( "Proveedor de IA no encontrado: {$provider_name}" );
        }

        // Si hay una instrucción de sistema, aplicarla
        if ( $this->current_system_instruction ) {
            $provider->set_system_instruction( $this->current_system_instruction );
        }

        return $provider->generate_content( $prompt );
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
