<?php
namespace Poeticsoft\Heart\AICore\Providers;

interface AI_Provider_Interface {
    public function set_config( array $config );
    public function get_name(): string;
    public function stream_chat( string $prompt, array $context_data, callable $callback );
}
