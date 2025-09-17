<?php
//Meta tags
$this->start('meta');
echo $this->Html->meta('description', $page['Page']['description']);
echo $this->Html->meta('keywords', $page['Page']['keywords']);
$this->end(); ?>

<?php
//Se tem banner
if (!empty($page['Page']['banner_desktop'])) {
?>
    <!-- BANNER -->
    <section id="banner">
        <?php
        $imgBanner = '/uploads/page-' . $page['Page']['id'] . '/' . $page['Page']['banner_desktop'];
        ?>
        <img src="<?php echo $imgBanner; ?>" class="img-fluid w-100" alt="<?php echo $page['Page']['title']; ?>" title="<?php echo $page['Page']['title']; ?>">
    </section>
<?php } ?>
<!-- INFORMAÇÕES -->
<section id="info" class="section">
    <div class="<?php echo $page['Page']['fluid'] ? 'container-fluid' : 'container' ?>">
        <?php if ($page['Page']['show_title']) { ?>
            <div class="section-header">
                <h2 class="section-title"><?php echo $page['Page']['title']; ?></h2>
                <span><?php echo $page['Page']['title']; ?></span>
            </div>
        <?php } ?>
        <div class="justify-content-center">
            <?php echo $this->Shortcode->parse($page['Page']['content']); ?>
            <?php if (!empty($page['Page']['youtube'])) { ?>
                <div class="video-container">
                    <iframe src="<?php echo $page['Page']['youtube']; ?>"
                        title="<?php echo $page['Page']['title']; ?>"
                        frameborder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                        allowfullscreen>
                    </iframe>
                </div>
            <?php } ?>
        </div>
    </div>
</section>