<style>
    /* #custCarousel .carousel-indicators {
        position: static;
    }
    #custCarousel .carousel-indicators>li {
        width: 100px;
    }
    #custCarousel .carousel-indicators li img {
        display: block;
        opacity: 0.5;
    }
    #custCarousel .carousel-indicators li.active img {
        opacity: 1;
    }
    #custCarousel .carousel-indicators li:hover img {
        opacity: 0.75;
    } */
</style>
<div class="row">
    <div class="col-lg-12">
        <div id="custCarousel" class="carousel" data-ride="carousel">
            <div class="carousel-inner">
                <!-- slides -->
                <?php
                //Se tem imagem para exibir
                if (isset($product['ProductsImage']) && !empty($product['ProductsImage'])) {
                    foreach ($product['ProductsImage'] as $k => $image) {
                        $class = $k == 0 ? 'active' : '';
                        echo '<div class="carousel-item ' . $class . '">';
                        echo $this->Html->image(
                            '/uploads/large/' . $image['filename'],
                            array(
                                'class' => 'img-fluid',
                                'alt' => $image['name']
                            )
                        );
                        echo '</div>';
                    }
                }
                ?>
            </div>

            <!-- Left right -->
            <a class="carousel-control-prev" href="#custCarousel" data-slide="prev">
                <span class="carousel-control-prev-icon"></span>
            </a>
            <a class="carousel-control-next" href="#custCarousel" data-slide="next">
                <span class="carousel-control-next-icon"></span>
            </a>

            <!-- Thumbnails -->
            <ol class="carousel-indicators list-inline">
                <?php
                //Se tem imagem para exibir
                if (isset($product['ProductsImage']) && !empty($product['ProductsImage'])) {
                    foreach ($product['ProductsImage'] as $k => $image) {
                ?>
                        <li class="list-inline-item active">
                            <a id="carousel-selector-<?php echo $k; ?>" class="selected" data-slide-to="<?php echo $k; ?>" data-target="#custCarousel">
                                <img src="<?php echo '/uploads/small/' . $image['filename']; ?>" class="img-fluid">
                            </a>
                        </li>
                <?php
                    }
                }
                ?>
            </ol>
        </div>
        <div class="">
            <br />
            <h2><?php echo $product['Product']['name']; ?></h2>
            <h3>R$ <?php echo $this->Alv->tratarValor($product['Product']['price'], 'pt'); ?></h3>
            <div class="">
                <?php echo $product['Product']['description']; ?>
            </div>
        </div>

        <hr />
        <?php
        echo $this->Form->button(
            '<span class="fa fa-times"></span> Fechar',
            array(
                'type' => 'button',
                'class' => 'btn btn-sm btn-danger mx-2',
                'escape' => false,
                'onclick' => 'hideProductModal()'
            )
        );
        ?>
    </div>
</div>