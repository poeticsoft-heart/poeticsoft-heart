<?php
/**
 * Renderizado en el servidor para el block-base.
 *
 * @var array $attributes Los atributos del bloque.
 */

$message   = $attributes['message'] ?? 'Hello from Block Base!';
$show_icon = $attributes['showIcon'] ?? true;
?>
<div <?php echo get_block_wrapper_attributes(); ?>>
	<div class="psh-block-content">
		<?php if ( $show_icon ) : ?>
			<span class="psh-icon">🚀</span>
		<?php endif; ?>
		<p><?php echo wp_kses_post( $message ); ?></p>
	</div>
</div>
