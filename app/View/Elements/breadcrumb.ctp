<?php if (!isset($notitle) && !isset($nobc)) { ?>
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-12 d-flex no-block align-items-center">
                <?php if (!isset($notitle)) { ?>
                    <h4 class="page-title"><?php echo $title_for_layout; ?></h4>
                <?php } ?>
                <?php if (!isset($nobc)) { ?>
                    <div class="ms-auto text-end">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="/admin/dash">Início</a></li>
                                <?php if (!empty($bcLinks)) { ?>
                                    <?php foreach ($bcLinks as $bcLabel => $bcLink) { ?>
                                        <li class="breadcrumb-item">
                                            <a href="<?php echo $bcLink; ?>"><?php echo $bcLabel; ?></a>
                                        </li>
                                    <?php } ?>
                                <?php } ?>
                                <li class="breadcrumb-item active" aria-current="page">
                                    <?php echo $title_for_layout; ?>
                                </li>
                            </ol>
                        </nav>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
<?php } ?>