<?php
$title = isset($title) ? $title : 'CHAMAR NO WHATSAPP';
$text  = isset($text)  ? $text  : 'Meu Botão';
$link  = isset($link)  ? $link  : '#';
$class = isset($class) ? $class : 'btn-primary';
$target = isset($target) ? $target : '';
$icon = isset($icon) ? '<i class="bi bi-' . $icon . '"></i>' : '';
?>
<a class="btn <?php echo $class; ?>" href="<?php echo $link; ?>" target="_blank">
    <?php echo $icon; ?>
    <?php echo h($title); ?>
</a>