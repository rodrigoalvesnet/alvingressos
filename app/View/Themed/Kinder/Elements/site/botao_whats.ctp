<?php
$title = isset($title) ? $title : 'CHAMAR NO WHATSAPP';
$phone = isset($phone) ? $phone : '554133679803';
$text  = isset($text)  ? $text  : 'Olá! Vim pelo site do Kinder Park!';
$link  = "https://api.whatsapp.com/send?phone=$phone&text=" . urlencode($text);
$class = isset($class) ? $class : '';
?>
<a class="btn btn-success <?php echo $class; ?>" href="<?php echo $link; ?>" target="_blank">
    <i class="bi bi-whatsapp"></i>
    <?php echo h($title); ?>
</a>