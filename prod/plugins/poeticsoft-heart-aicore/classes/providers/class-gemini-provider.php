<?php
namespace Poeticsoft\Heart\AICore\Providers;

use Gemini;
use Gemini\Data\Content;
use Gemini\Enums\Role;

class Gemini_Provider implements AI_Provider_Interface {
    private $client;
    private $config = [];
    private $system_instruction = null;

    public function set_config( array $config ) {
        $this->config = $config;
        if ( ! empty( $config['api_key'] ) ) {
            $this->client = Gemini::client( $config['api_key'] );
        }
    }

    public function get_name(): string {
        return 'gemini';
    }

    /**
     * Establece la instrucción de sistema.
     */
    public function set_system_instruction( $instruction ) {
        $this->system_instruction = $instruction;
    }

    /**
     * Generación de contenido síncrona.
     */
    public function generate_content( string $prompt ): string {
        if ( ! $this->client ) {
            throw new \Exception( "Cliente Gemini no inicializado (¿Falta API Key?)" );
        }

        $model_name = $this->config['model'] ?? 'gemini-1.5-flash';
        $model = $this->client->generativeModel( model: $model_name );

        if ( ! empty( $this->system_instruction ) ) {
            $model = $model->withSystemInstruction( Content::parse( $this->system_instruction ) );
        }

        $result = $model->generateContent( $prompt );
        return $result->text();
    }

    public function stream_chat( string $prompt, array $context_data, callable $callback ) {
        if ( ! $this->client ) {
            return;
        }

        $model_name = $this->config['model'] ?? 'gemini-1.5-flash';
        $ttl        = (int) ($this->config['cache_ttl'] ?? 0);

        // Prioridad a la instrucción del contexto si existe
        $system_instr = $context_data['system_instruction'] ?? $this->system_instruction;

        // Si hay TTL y datos de contexto, usamos Context Caching
        if ( $ttl > 0 && ! empty( $context_data['parts'] ) ) {
            
            $cache = $this->client->cachedContents()->create(
                model: 'models/' . $model_name,
                systemInstruction: Content::parse( $system_instr ?? '' ),
                parts: array_map( fn($p) => Content::parse($p), $context_data['parts'] ),
                ttl: $ttl . 's',
                displayName: 'PSH Context Cache'
            );

            $model = $this->client->generativeModel( model: $model_name )
                ->withCachedContent( $cache->name );

        } else {
            // Sin caché, flujo normal
            $model = $this->client->generativeModel( model: $model_name );
            if ( ! empty( $system_instr ) ) {
                $model = $model->withSystemInstruction( Content::parse( $system_instr ) );
            }
        }

        $stream = $model->streamGenerateContent( $prompt );

        foreach ( $stream as $response ) {
            $text = $response->text();
            if ( $text ) {
                $callback( $text );
            }
        }
    }
}
