<?php
/**
 * Clase para convertir Markdown/HTML a bloques nativos de Gutenberg.
 *
 * @package PoeticsoftHeart\ContentArchitect
 */

namespace Poeticsoft\Heart\ContentArchitect;

use League\CommonMark\GithubFlavoredMarkdownConverter;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Clase Block_Converter
 */
class Block_Converter {

	/**
	 * Convertidor de Markdown.
	 * @var GithubFlavoredMarkdownConverter
	 */
	private $markdown_converter;

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->markdown_converter = new GithubFlavoredMarkdownConverter([
			'html_input' => 'strip',
			'allow_unsafe_links' => false,
		]);
	}

	/**
	 * Convierte Markdown a bloques de Gutenberg.
	 *
	 * @param string $markdown Texto en formato Markdown.
	 * @return string Contenido listo para wp_content formateado como bloques.
	 */
	public function convert_markdown_to_blocks( $markdown ) {
		$html = $this->markdown_converter->convert( $markdown )->getContent();
		return $this->convert_html_to_blocks( $html );
	}

	/**
	 * Crea un post/página en WordPress a partir de Markdown.
	 *
	 * @param string $title       Título de la página.
	 * @param string $markdown    Contenido en Markdown.
	 * @param string $post_type   Tipo de post (page, post, etc.).
	 * @param string $post_status Estado (draft, publish, etc.).
	 * @return int|\WP_Error ID del post creado o WP_Error en caso de fallo.
	 */
	public function create_post( $title, $markdown, $post_type = 'page', $post_status = 'draft' ) {
		$blocks = $this->convert_markdown_to_blocks( $markdown );
		
		$post_data = [
			'post_title'   => wp_strip_all_tags( $title ),
			'post_content' => $blocks,
			'post_status'  => $post_status,
			'post_type'    => $post_type,
		];

		return wp_insert_post( $post_data, true );
	}

	/**
	 * Convierte HTML plano a bloques de Gutenberg (Motor Simple).
	 *
	 * @param string $html HTML plano.
	 * @return string HTML envuelto en comentarios de Gutenberg.
	 */
	public function convert_html_to_blocks( $html ) {
		$dom = new \DOMDocument();
		// Usamos libxml_use_internal_errors para evitar warnings con HTML5
		libxml_use_internal_errors(true);
		// Forzamos UTF-8
		$dom->loadHTML('<?xml encoding="UTF-8">' . $html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
		libxml_clear_errors();

		$output = '';

		foreach ($dom->childNodes as $node) {
			$output .= $this->process_node($node);
		}

		return $output;
	}

	/**
	 * Procesa un nodo DOM y lo convierte en su bloque equivalente.
	 */
	private function process_node($node) {
		if ($node->nodeType !== XML_ELEMENT_NODE) {
			return '';
		}

		$tag     = strtolower($node->nodeName);
		$content = $this->get_inner_html($node);

		switch ($tag) {
			case 'p':
				return "<!-- wp:paragraph -->\n<p>{$content}</p>\n<!-- /wp:paragraph -->\n\n";

			case 'h1':
			case 'h2':
			case 'h3':
			case 'h4':
			case 'h5':
			case 'h6':
				$level = substr($tag, 1);
				return "<!-- wp:heading {\"level\":{$level}} -->\n<{$tag} class=\"wp-block-heading\">{$content}</{$tag}>\n<!-- /wp:heading -->\n\n";

			case 'ul':
				return "<!-- wp:list -->\n<ul>{$content}</ul>\n<!-- /wp:list -->\n\n";

            case 'ol':
                return "<!-- wp:list {\"ordered\":true} -->\n<ol>{$content}</ol>\n<!-- /wp:list -->\n\n";

			case 'li':
				return "<li>{$content}</li>";

			case 'blockquote':
				return "<!-- wp:quote -->\n<blockquote class=\"wp-block-quote\"><p>{$content}</p></blockquote>\n<!-- /wp:quote -->\n\n";

			default:
				// Si no conocemos el bloque, lo devolvemos como párrafo o HTML plano si es necesario
				return "<!-- wp:paragraph -->\n<p>{$content}</p>\n<!-- /wp:paragraph -->\n\n";
		}
	}

	/**
	 * Helper para obtener el HTML interno de un nodo.
	 */
	private function get_inner_html($node) {
		$inner = '';
		foreach ($node->childNodes as $child) {
			$inner .= $node->ownerDocument->saveHTML($child);
		}
		return trim($inner);
	}
}
