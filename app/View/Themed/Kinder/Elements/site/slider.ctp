<?php if (count($banners) > 0) { ?>
    <header id="slider-area">
        <!-- Main Carousel Section -->
        <div id="carousel-area">
            <div id="carousel-slider" class="carousel slide carousel-fade" data-ride="carousel">
                <?php if (count($banners) > 1) { ?>
                    <ol class="carousel-indicators">
                        <?php foreach ($banners as $k => $banner) { ?>
                            <li data-target="#carousel-slider" data-slide-to="<?php echo $k; ?>" class="<?php echo $k == 0 ? 'active' : ''; ?>"></li>
                        <?php } ?>
                    </ol>
                <?php } ?>
                <div class="carousel-inner" role="listbox">
                    <?php foreach ($banners as $k => $banner) { ?>
                        <?php
                        $imgBanner = '/uploads/banners/' . $banner['Banner']['image'];
                        $imgBannerMobile = $imgBanner;
                        if (!empty($banner['Banner']['image_mobile'])) {
                            $imgBannerMobile = '/uploads/banners/' . $banner['Banner']['image_mobile'];
                        }
                        ?>
                        <div class="carousel-item <?php echo $k == 0 ? 'active' : ''; ?>">
                            <?php if ($banner['Banner']['linkbanner']) { ?>
                                <?php if (!empty($banner['Banner']['button_link'])) { ?>
                                    <a href="<?php echo $banner['Banner']['button_link']; ?>">
                                    <?php } ?>
                                <?php } ?>
                                <img class="d-none d-md-block img-fluid w-100" src="<?php echo $imgBanner; ?>" alt="<?php echo $banner['Banner']['title']; ?>" title="<?php echo $banner['Banner']['title']; ?>">
                                <img class="d-block d-md-none img-fluid w-100" src="<?php echo $imgBannerMobile; ?>" alt="<?php echo $banner['Banner']['title']; ?>" title="<?php echo $banner['Banner']['title']; ?>">
                                <?php if ($banner['Banner']['showtitle']) { ?>
                                    <div class="carousel-caption text-<?php echo $banner['Banner']['position']; ?>">
                                        <?php if (!empty($banner['Banner']['title'])) { ?>
                                            <h2 class="wow fadeInRight" data-wow-delay="0.4s"><?php echo $banner['Banner']['title']; ?></h2>
                                        <?php } ?>
                                        <?php if (!empty($banner['Banner']['subtitle'])) { ?>
                                            <h4 class="wow fadeInRight" data-wow-delay="0.6s"><?php echo $banner['Banner']['subtitle']; ?></h4>
                                        <?php } ?>
                                        <?php if (!empty($banner['Banner']['button_link'])) { ?>
                                            <a href="<?php echo $banner['Banner']['button_link']; ?>" class="btn btn-lg btn-effect wow fadeInRight <?php echo !empty($banner['Banner']['button_class']) ? $banner['Banner']['button_class'] : 'btn-primary'; ?>" data-wow-delay="0.9s"><?php echo $banner['Banner']['button_title']; ?></a>
                                        <?php } ?>
                                    </div>
                                <?php } ?>
                                <?php if ($banner['Banner']['linkbanner']) { ?>
                                    <?php if (!empty($banner['Banner']['button_link'])) { ?>
                                    </a>
                                <?php } ?>
                            <?php } ?>
                        </div>
                    <?php } ?>
                </div>
                <?php if (count($banners) > 1) { ?>
                    <a class="carousel-control-prev" href="#carousel-slider" role="button" data-slide="prev">
                        <span class="carousel-control" aria-hidden="true"><i class="bi bi-chevron-left"></i></span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="carousel-control-next" href="#carousel-slider" role="button" data-slide="next">
                        <span class="carousel-control" aria-hidden="true"><i class="bi bi-chevron-right"></i></span>
                        <span class="sr-only">Next</span>
                    </a>
                <?php } ?>
            </div>
        </div>

    </header>
<?php } ?>