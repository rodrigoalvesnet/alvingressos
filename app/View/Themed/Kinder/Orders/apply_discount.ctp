<?php
$couponId = 0;
//Se tem mensagem
if (!empty($result['message'])) {
    if ($result['success']) {
        $class = 'alert-success';
        $couponId = $result['coupon_id'];
    } else {
        $class = 'alert-warning';
        
    }
?>
    <div class="alert <?php echo $class; ?>" role="alert"><?php echo $result['message']; ?></div>
<?php } 
echo $this->Form->hidden('Order.coupon_id', array(
    'value' => $couponId
));
?>