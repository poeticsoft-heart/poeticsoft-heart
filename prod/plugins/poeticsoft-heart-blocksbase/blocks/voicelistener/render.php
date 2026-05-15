<?php
/**
 * Renderizado del bloque voicelistener para el ecosistema Poeticsoft Heart.
 *
 * @package PoeticsoftHeart\BlocksBase
 */

$button_label = isset( $attributes['buttonLabel'] ) ? $attributes['buttonLabel'] : 'Iniciar Escucha';
$placeholder  = isset( $attributes['placeholder'] ) ? $attributes['placeholder'] : 'El texto reconocido aparecerá aquí...';
$block_id     = isset( $attributes['blockId'] ) ? $attributes['blockId'] : uniqid();

$wrapper_attributes = get_block_wrapper_attributes( [
	'class' => 'psh-voice-listener',
	'id'    => 'psh-voice-' . $block_id,
] );
?>

<div <?php echo $wrapper_attributes; ?>>
	<button class="psh-voice-btn">
		<span class="psh-btn-icon">🎙️</span>
		<span class="psh-btn-text"><?php echo esc_html( $button_label ); ?></span>
	</button>
	<div class="psh-voice-status"></div>
	<textarea 
		class="psh-voice-output" 
		placeholder="<?php echo esc_attr( $placeholder ); ?>"
		readonly
	></textarea>
</div>
