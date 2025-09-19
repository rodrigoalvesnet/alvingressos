<?php if (!empty($menus)) { ?>
    <ul class="menu">
        <?php foreach ($menus as $menu) { ?>
            <li><a href="<?php echo $menu['Menu']['link']; ?>"><?php echo $menu['Menu']['title']; ?></a></li>
        <?php } ?>
    </ul>
<?php } ?>