<?php
/**
 * Renderizado del bloque Interfaz de Voz.
 */

$placeholder = isset($attributes['placeholderText']) ? $attributes['placeholderText'] : 'Pulsa para hablar';
$wrapper_attributes = get_block_wrapper_attributes([
    'class' => 'psh-voiceinterface-wrapper',
    'data-placeholder' => esc_attr($placeholder)
]);
?>
<div <?php echo $wrapper_attributes; ?>>
    <div class="psh-orb-container">
        <div class="psh-orb" title="Activar micrófono"></div>
    </div>
    <p class="psh-voice-status"><?php echo esc_html($placeholder); ?></p>
    <div class="psh-voice-transcript"></div>
</div>
