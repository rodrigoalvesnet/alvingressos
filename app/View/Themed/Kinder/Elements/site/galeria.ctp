<?php
$galeria = $this->requestAction(array(
    'controller' => 'Galerias',
    'action' => 'galeria',
    $id
));
if (!empty($galeria)) {
    // pr($galeria);
?>
    <section id="blog" class="section galeria-<?php echo $galeria['Galeria']['id']; ?> <?php echo isset($class) ? $class : 'bg-grey'; ?>">
        <div class="container">
            <?php if (!isset($showtitle) || (isset($showtitle) && !$showtitle)) { ?>
                <div class="section-header">
                    <h2 class="section-title"><?php echo $galeria['Galeria']['title']; ?></h2>
                    <p class="section-subtitle"><?php echo $galeria['Galeria']['description']; ?></p>
                </div>
            <?php } else {
                //
            } ?>
            <?php
            $counter = 0;
            foreach ($galeria['GaleriasFoto'] as $item) {
                if ($counter == 0) {
                    echo '<div class="row mb-4">';
                }
            ?>

                <div class="col-lg-4 col-md-6 col-xs-12 blog-item">
                    <div class="blog-item-wrapper">
                        <div class="blog-item-img">
                            <a href="/uploads/<?php echo $item['image']; ?>" data-toggle="lightbox" data-gallery="galeria" data-title="<?php echo h($item['title']); ?>">
                                <img src="/uploads/<?php echo $item['image']; ?>" alt="">
                            </a>
                        </div>
                        <?php if (!empty($item['title'])) { ?>
                            <div class="blog-item-text">
                                <h3 class="text-center"><?php echo $item['title']; ?></h3>
                                <?php if (!empty($item['description'])) { ?>
                                    <p class="text-center"><?php echo $item['description']; ?></p>
                                <?php } ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>

                <?php
                if ($counter == 2) {
                    echo '</div>';
                    $counter = 0;
                } else {
                    $counter++;
                } ?>
            <?php } ?>
        </div>
    </section>

<?php } ?>