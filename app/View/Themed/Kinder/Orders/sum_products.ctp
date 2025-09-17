<?php
echo $this->Form->hidden('Order.products_total', array(
    'value' => $result['total']
));
?>
<hr class="my-3" />
<i class="fas fa-gift"></i> Total de produtos: R$ <strong><?php echo $this->Alv->tratarValor($result['total'], 'pt'); ?></strong>