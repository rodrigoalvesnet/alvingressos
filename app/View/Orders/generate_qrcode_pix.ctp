<?php
App::import('Vendor', 'QrcodeGen', array('file' => 'QrcodeGen/QrcodeGen.php'));
$qrcode = new QrcodeGen();
$description = AuthComponent::user('name') . ' - ' . $this->data['Order']['description'];
$value = ($this->data['Order']['value'] / $this->data['Order']['installments']);

//chama as classes necessárias
$image = $qrcode->chavePix(
    trim($this->data['Order']['pix']),
    trim($description),
    number_format($value, 2, '.', '')
);

echo $this->Form->input(
    'Order.pix',
    array(
        'type' => 'text',
        'label' => 'Chave PIX',
        'value' => trim($this->data['Order']['pix']),
        'class' => 'form-control',
        'div' => 'form-group',
        'disabled' => true,
    )
);
?>
<img src="data:image/png;base64, <?php echo base64_encode($image) ?>" class="img-thumbnail img-fluid">