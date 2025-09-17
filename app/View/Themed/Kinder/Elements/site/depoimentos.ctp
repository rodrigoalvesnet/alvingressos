<?php
$clientes = $this->requestAction(array(
    'controller' => 'Depoimentos',
    'action' => 'depoimentos'
));
if (!empty($clientes)) {
?>
    <section class="testimonial section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Nossos Clientes</h2>
                <p class="section-subtitle">1,736 Avaliações no Google</p>
            </div>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div id="testimonials" class="touch-slider owl-carousel">
                        <?php foreach ($clientes as $cliente): ?>
                            <div class="item">
                                <div class="testimonial-item">
                                    <div class="author">
                                        <div class="img-thumb">
                                            <img src="/uploads/depoimentos/<?php echo $cliente['Depoimento']['foto']; ?>" title="<?php echo $cliente['Depoimento']['nome']; ?>" alt="<?php echo $cliente['Depoimento']['nome']; ?>">
                                        </div>
                                        <div class="author-info">
                                            <h2><?php echo $cliente['Depoimento']['nome']; ?></h2>
                                        </div>
                                    </div>
                                    <div class="content-inner">
                                        <p class="description"><?php echo $cliente['Depoimento']['texto']; ?></p>
                                        <?php 
                                        $counter = 1;
                                        $stars = $cliente['Depoimento']['estrelas'];
                                        while ($stars >= $counter) {
                                            echo '<span><i class="lni-star-filled"></i></span>';
                                            $counter++;
                                        }
                                        while ($counter < 6) {
                                            echo '<span><i class="lni-star"></i></span>';
                                            $counter++;
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php } ?>