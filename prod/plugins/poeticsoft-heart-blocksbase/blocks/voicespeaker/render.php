<?php
/**
 * Renderizado del bloque voicespeaker para el ecosistema Poeticsoft Heart.
 *
 * @package PoeticsoftHeart\BlocksBase
 */

$button_label = isset( $attributes['buttonLabel'] ) ? $attributes['buttonLabel'] : 'Escuchar Texto';
$content      = isset( $attributes['content'] ) ? $attributes['content'] : '';
$block_id     = isset( $attributes['blockId'] ) ? $attributes['blockId'] : uniqid();
$pitch        = isset( $attributes['pitch'] ) ? $attributes['pitch'] : 1;
$rate         = isset( $attributes['rate'] ) ? $attributes['rate'] : 1;
$voiceURI     = isset( $attributes['voiceURI'] ) ? $attributes['voiceURI'] : '';

$wrapper_attributes = get_block_wrapper_attributes( [
	'class'         => 'psh-voice-speaker',
	'id'            => 'psh-speaker-' . $block_id,
	'data-pitch'    => esc_attr( $pitch ),
	'data-rate'     => esc_attr( $rate ),
	'data-voiceuri' => esc_attr( $voiceURI )
] );
?>

<div <?php echo $wrapper_attributes; ?>>
	<button class="psh-speaker-btn">
		<span class="psh-btn-icon">🔊</span>
		<span class="psh-btn-text"><?php echo esc_html( $button_label ); ?></span>
	</button>
	<div class="psh-speaker-content" contenteditable="true" spellcheck="false">
		<?php echo wp_kses_post( $content ); ?>
	</div>
	<small class="psh-speaker-hint"><?php echo esc_html__( '(Puedes editar este texto para probar la voz)', 'poeticsoft-heart' ); ?></small>
</div>
