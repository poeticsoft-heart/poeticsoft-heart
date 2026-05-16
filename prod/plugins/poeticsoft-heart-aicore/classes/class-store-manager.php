<?php
namespace Poeticsoft\Heart\AICore;

/**
 * Gestor de almacenamiento de prompts y contextos basados en archivos Markdown.
 */
class Store_Manager {
    private $base_path;

    public function __construct() {
        $this->base_path = plugin_dir_path( dirname( __FILE__ ) ) . 'store/';
    }

    /**
     * Obtiene el contenido de todas las partes en una sección específica (contexto).
     */
    public function get_section_parts( $section ) {
        $section_path = $this->base_path . $section . '/';
        $parts = [];

        if ( ! is_dir( $section_path ) ) {
            return $parts;
        }

        $files = glob( $section_path . '*.md' );
        foreach ( $files as $file ) {
            $content = file_get_contents( $file );
            if ( $content !== false ) {
                $parts[] = $content;
            }
        }

        return $parts;
    }

    /**
     * Obtiene una parte específica por sección y nombre.
     */
    public function get_part( $section, $name ) {
        $file_path = $this->base_path . $section . '/' . $name . '.md';
        if ( file_exists( $file_path ) ) {
            return file_get_contents( $file_path );
        }
        return '';
    }

    /**
     * Obtiene un prompt específico de la carpeta 'prompt/'.
     */
    public function get_prompt( $name ) {
        return $this->get_part( 'prompt', $name );
    }
}
