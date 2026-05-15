<?php
namespace Poeticsoft\Heart\AICore\Providers;

use Gemini;
use Gemini\Data\Content;
use Gemini\Enums\Role;

class Gemini_Provider implements AI_Provider_Interface {
    private $client;
    private $config = [];

    public function set_config( array $config ) {
        $this->config = $config;
        if ( ! empty( $config['api_key'] ) ) {
            $this->client = Gemini::client( $config['api_key'] );
        }
    }

    public function get_name(): string {
        return 'gemini';
    }

    public function stream_chat( string $prompt, array $context_data, callable $callback ) {
        if ( ! $this->client ) {
            return;
        }

        $model_name = $this->config['model'] ?? 'gemini-1.5-flash';
        $ttl        = (int) ($this->config['cache_ttl'] ?? 0);

        // Si hay TTL y datos de contexto, usamos Context Caching (Native Gemini Feature)
        if ( $ttl > 0 && ! empty( $context_data['parts'] ) ) {
            
            $cache = $this->client->cachedContents()->create(
                model: 'models/' . $model_name,
                systemInstruction: Content::parse( $context_data['system_instruction'] ?? '' ),
                parts: array_map( fn($p) => Content::parse($p), $context_data['parts'] ),
                ttl: $ttl . 's',
                displayName: 'PSH Context Cache'
            );

            $model = $this->client->generativeModel( model: $model_name )
                ->withCachedContent( $cache->name );

        } else {
            // Sin caché, flujo normal
            $model = $this->client->generativeModel( model: $model_name );
            if ( ! empty( $context_data['system_instruction'] ) ) {
                $model = $model->withSystemInstruction( Content::parse( $context_data['system_instruction'] ) );
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
