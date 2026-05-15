<?php

$wrapper_attributes = get_block_wrapper_attributes([]);
echo '<div 
  id="' . $attributes['blockId'] . '" ' .
  $wrapper_attributes .
'>' .
($attributes['showIcon'] ? '🚀' : '') .
$attributes['message'] .
'</div>';