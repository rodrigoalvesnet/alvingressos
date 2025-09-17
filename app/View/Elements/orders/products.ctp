<hr class="my-3" />
<h4><i class="fas fa-gift"></i> Produtos adicionais</h4>
<div class="row products">
    <?php 
    foreach ($event['Product'] as $k => $product) {
        echo $this->Form->hidden('OrdersProduct.' . $k . '.product_id', array(
            'value' => $product['id']
        ));
        echo $this->Form->hidden('OrdersProduct.' . $k . '.price', array(
            'value' => $product['price']
        ));
    ?>
        <div class="col-md-6 col-lg-3 product-item">
            <div class="product-photo">
                <?php
                $imgSrc = 'no-photo.png';
                //Se tem imagem para exibir
                if (isset($product['ProductsImage']) && !empty($product['ProductsImage'])) {
                    $imgSrc = '/uploads/medium/' . $product['ProductsImage'][0]['filename'];
                }
                echo $this->Html->image(
                    $imgSrc,
                    array(
                        'class' => 'img-fluid',
                        'onclick' => 'showProduct(' . $product['id'] . ')'
                    )
                );
                ?>
            </div>
            <div class="product-name"><?php echo $product['name']; ?></div>
            <div class="product-price">R$ <?php echo $this->Alv->tratarValor($product['price'], 'pt'); ?></div>
            <div class="product-quantity">
                <?php
                echo $this->element('inputquantity', array(
                    'name' => 'OrdersProduct.' . $k . '.quantity',
                    'id' => 'OrdersProduct' . $k . 'Quantity',
                ));
                ?>
            </div>
        </div>
    <?php } ?>
</div>
<div id="divSumProducts">
</div>