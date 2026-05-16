<?php
namespace Poeticsoft\Heart\AICore\Providers;

/**
 * Interfaz obligatoria para todos los proveedores de IA en el ecosistema.
 */
interface AI_Provider_Interface {
    
    /**
     * Configura el proveedor con las credenciales y modelos necesarios.
     */
    public function set_config( array $config );

    /**
     * Devuelve el identificador único del proveedor (ej: 'gemini', 'openai').
     */
    public function get_name(): string;

    /**
     * Establece una instrucción de sistema global para las próximas peticiones.
     */
    public function set_system_instruction( $instruction );

    /**
     * Generación de contenido síncrona (devuelve el string completo).
     */
    public function generate_content( string $prompt ): string;

    /**
     * Generación de contenido en streaming (SSE).
     */
    public function stream_chat( string $prompt, array $context_data, callable $callback );
}
