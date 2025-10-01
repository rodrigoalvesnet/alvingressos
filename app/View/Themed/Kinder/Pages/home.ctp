<?php echo $this->element('site/slider', [
    'banners' => $banners
]);
?>
<section id="services" class="mt-4">
    <div class="container">
        <div class="section-header">
            <a href="/comprar-ingresso" class="btn btn-secondary btn-effect"><i class="bi bi-cart icon-enter"></i> Comprar Passaporte</a>
        </div>
        <div class="row">
            <div class="col-lg-4 col-md-6 col-xs-12">
                <div class="item-boxes services-item wow fadeInDown" data-wow-delay="0.2s">
                    <div class="icon color-1">
                        <i class="bi bi-shop"></i>
                    </div>
                    <h4>Lanchonete no Local</h4>
                    <p>Venha experimentar vários lanches e porções deliciosas feitos com carinho para você!</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-xs-12">
                <div class="item-boxes services-item wow fadeInDown" data-wow-delay="0.4s">
                    <div class="icon color-2">
                        <i class="bi bi-star"></i>
                    </div>
                    <h4>O maior parque Indoor do Brasil</h4>
                    <p>Diversão para toda Família e principalmente para os pequeninos! Venha se divertir!</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-xs-12">
                <div class="item-boxes services-item wow fadeInDown" data-wow-delay="0.6s">
                    <div class="icon color-3">
                        <i class="bi bi-rocket"></i>
                    </div>
                    <h4>Faça sua festa com a gente!</h4>
                    <p>Tem aniversariante e está buscando um local para fazer uma diversão incrível e memorável?</p>
                </div>
            </div>
        </div>
    </div>
</section>
<?php echo $this->element('site/galeria', array('id' => 1)); ?>
<?php //echo $this->element('site/atracoes'); ?>
<?php echo $this->element('site/video'); ?>
<?php echo $this->element('site/festas'); ?>
<?php echo $this->element('site/depoimentos'); ?>
<?php //echo $this->element('site/eventos'); ?>