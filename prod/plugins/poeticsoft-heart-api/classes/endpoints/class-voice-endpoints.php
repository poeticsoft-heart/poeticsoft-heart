<?php
namespace Poeticsoft\Heart\API\Endpoints;

use WP_REST_Request;
use WP_REST_Response;

class Voice_Endpoints extends Endpoint_Base {

    public function __construct() {
        $this->namespace = 'psh/v1';
    }

    public function register_routes() {
        register_rest_route( $this->namespace, '/voice/stream', [
            'methods'             => 'POST',
            'callback'            => [ $this, 'stream_response' ],
            'permission_callback' => [ $this, 'check_public_permissions' ],
        ]);
    }

    /**
     * Valida permisos para el acceso público al stream de voz.
     */
    public function check_public_permissions( WP_REST_Request $request ) {
        // Permitir acceso total a administradores/editores
        if ( current_user_can( 'edit_posts' ) ) {
            return true;
        }

        // Para el público general, aplicar límites estrictos:
        // 5 peticiones cada 60 segundos.
        return $this->is_public_request_safe( $request, 'voice_stream', 5, 60 );
    }

    public function stream_response( WP_REST_Request $request ) {
        $input_text = $request->get_param( 'text' );

        // Configurar cabeceras para Streaming
        header('Content-Type: text/event-stream');
        header('Cache-Control: no-cache');
        header('Connection: keep-alive');
        header('X-Accel-Buffering: no');

        global $psh_ai_manager;
        
        if ( ! $psh_ai_manager ) {
            echo "data: " . json_encode(['text' => 'Error: AI Manager no inicializado.']) . "\n\n";
            echo "data: [DONE]\n\n";
            exit;
        }

        $provider_name = 'gemini';
        $provider      = $psh_ai_manager->get_provider( $provider_name );

        if ( ! $provider ) {
            echo "data: " . json_encode(['text' => 'Error: Proveedor Gemini no encontrado o sin API Key.']) . "\n\n";
            echo "data: [DONE]\n\n";
            exit;
        }

        // Preparación del contexto dinámico desde el Store
        $store = $psh_ai_manager->get_store();
        $context_data = [
            'system_instruction' => $store->get_part( 'prompt', 'SYSTEM-INSTRUCTIONS' ),
            'parts'              => $store->get_section_parts( 'context' )
        ];

        try {
            $provider->stream_chat( $input_text, $context_data, function( $chunk ) {
                echo "data: " . json_encode(['text' => $chunk]) . "\n\n";
                if ( ob_get_level() > 0 ) {
                    ob_flush();
                }
                flush();
            });
        } catch ( \Exception $e ) {
            echo "data: " . json_encode(['text' => 'Error de IA: ' . $e->getMessage()]) . "\n\n";
        }

        echo "data: [DONE]\n\n";
        exit;
    }
}
