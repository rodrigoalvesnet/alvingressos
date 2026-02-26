<?php
$roleId = $_SESSION['Auth']['User']['role_id'];
/**
 * 1 = Admin
 * 2 = Gerente
 * 3 = Comprador
 * 4 = Organizador
 */
?>
<aside class="left-sidebar" data-sidebarbg="skin5">
    <!-- Sidebar scroll-->
    <div class="scroll-sidebar">
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav">
            <ul id="sidebarnav" class="pt-4">
                <li class="sidebar-item">
                    <a class="sidebar-link waves-effect waves-dark sidebar-link" href="/admin/dash" aria-expanded="false"><i class="mdi mdi-view-dashboard"></i><span class="hide-menu">Início</span></a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="mdi mdi-ticket"></i><span class="hide-menu">Passaportes </span></a>
                    <ul aria-expanded="false" class="collapse first-level">
                        <li class="sidebar-item">
                            <a href="/admin/tickets/index" class="sidebar-link"><i class="mdi mdi-format-align-justify"></i><span class="hide-menu"> Listar </span></a>
                        </li>
                    </ul>
                </li>
                <?php if ($roleId == 1 || $roleId == 2) { ?>
                    <li class="sidebar-item">
                        <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="mdi mdi-cart"></i><span class="hide-menu">Pedidos </span></a>
                        <ul aria-expanded="false" class="collapse first-level">
                            <li class="sidebar-item">
                                <a href="/admin/orders/index" class="sidebar-link"><i class="mdi mdi-format-align-justify"></i><span class="hide-menu"> Listar </span></a>
                            </li>
                        </ul>
                    </li>
                    <li class="sidebar-item">
                        <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="mdi mdi-web"></i><span class="hide-menu">Website </span></a>
                        <ul aria-expanded="false" class="collapse first-level">
                            <li class="sidebar-item">
                                <a href="/admin/site" class="sidebar-link"><i class="mdi mdi-format-align-justify"></i><span class="hide-menu"> Informações </span></a>
                            </li>
                            <li class="sidebar-item">
                                <a href="/admin/pages/index" class="sidebar-link"><i class="mdi mdi-web"></i><span class="hide-menu"> Páginas </span></a>
                            </li>
                            <li class="sidebar-item">
                                <a href="/admin/galerias/index" class="sidebar-link"><i class="mdi mdi-image"></i><span class="hide-menu"> Galerias de Fotos </span></a>
                            </li>
                            <li class="sidebar-item">
                                <a href="/admin/depoimentos/index" class="sidebar-link"><i class="mdi mdi-message"></i><span class="hide-menu"> Depoimentos </span></a>
                            </li>
                            <li class="sidebar-item">
                                <a href="/admin/menus/index" class="sidebar-link"><i class="mdi mdi-format-align-justify"></i><span class="hide-menu"> Menus </span></a>
                            </li>
                        </ul>
                    </li>
                    <!-- <li class="sidebar-item">
                        <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="mdi mdi-package"></i><span class="hide-menu">Produtos </span></a>
                        <ul aria-expanded="false" class="collapse first-level">
                            <li class="sidebar-item">
                                <a href="/admin/produtos/index" class="sidebar-link"><i class="mdi mdi-format-align-justify"></i><span class="hide-menu"> Listar </span></a>
                            </li>
                            <li class="sidebar-item">
                                <a href="/admin/produtos_categorias/index" class="sidebar-link"><i class="mdi mdi-format-align-justify"></i><span class="hide-menu"> Categorias </span></a>
                            </li>
                        </ul>
                    </li> -->
                    <li class="sidebar-item">
                        <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="mdi mdi-calendar"></i><span class="hide-menu">Eventos </span></a>
                        <ul aria-expanded="false" class="collapse first-level">
                            <li class="sidebar-item">
                                <a href="/admin/events/index" class="sidebar-link"><i class="mdi mdi-format-align-justify"></i><span class="hide-menu"> Listar </span></a>
                            </li>
                            <?php
                            //Se é Admin
                            if ($roleId == 1) {
                            ?>
                                <li class="sidebar-item">
                                    <a href="/admin/events/add" class="sidebar-link"><i class="mdi mdi-plus"></i><span class="hide-menu"> Adicionar </span></a>
                                </li>
                            <?php } ?>
                        </ul>
                    </li>
                    <li class="sidebar-item">
                        <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="mdi mdi-account-key"></i><span class="hide-menu">Usuários </span></a>
                        <ul aria-expanded="false" class="collapse first-level">
                            <li class="sidebar-item">
                                <a href="/admin/users/index" class="sidebar-link"><i class="mdi mdi-format-align-justify"></i><span class="hide-menu"> Listar </span></a>
                            </li>
                            <?php
                            //Se é Admin
                            if ($roleId == 1) {
                            ?>
                                <li class="sidebar-item">
                                    <a href="/admin/users/add" class="sidebar-link"><i class="mdi mdi-plus"></i><span class="hide-menu"> Adicionar </span></a>
                                </li>
                            <?php } ?>
                        </ul>
                    </li>
                <?php } ?>

                <?php
                //Se é Admin
                if ($roleId == 1) {
                ?>
                    <li class="sidebar-item">
                        <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="mdi mdi-clock"></i><span class="hide-menu">Estadias </span></a>
                        <ul aria-expanded="false" class="collapse first-level">
                            <li class="sidebar-item">
                                <a href="/admin/estadias/dashboard" class="sidebar-link"><i class="mdi mdi-view-dashboard"></i><span class="hide-menu"> Dashboard </span></a>
                            </li>
                            <li class="sidebar-item">
                                <a href="/admin/estadias" class="sidebar-link"><i class="mdi mdi-clock"></i><span class="hide-menu"> Estadias </span></a>
                            </li>
                            <li class="sidebar-item">
                                <a href="/admin/adicionals" class="sidebar-link"><i class="mdi mdi-format-align-justify"></i><span class="hide-menu"> Adicionais </span></a>
                            </li>
                            <li class="sidebar-item">
                                <a href="/admin/atracoes" class="sidebar-link"><i class="mdi mdi-gamepad-variant"></i><span class="hide-menu"> Atrações </span></a>
                            </li>
                            <li class="sidebar-item">
                                <a href="/admin/tarifas" class="sidebar-link"><i class="mdi mdi-currency-usd"></i><span class="hide-menu"> Tarifas </span></a>
                            </li>
                            <li class="sidebar-item">
                                <a href="/admin/formas_pagamentos" class="sidebar-link"><i class="mdi mdi-currency-usd"></i><span class="hide-menu"> Formas de Pagamentos </span></a>
                            </li>
                        </ul>
                    </li>
                <?php } ?>

                <?php
                //Se é Admin
                if ($roleId == 1) {
                ?>
                    <li class="sidebar-item">
                        <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="mdi mdi-factory"></i><span class="hide-menu">Unidades </span></a>
                        <ul aria-expanded="false" class="collapse first-level">
                            <li class="sidebar-item">
                                <a href="/admin/unidades/index" class="sidebar-link"><i class="mdi mdi-format-align-justify"></i><span class="hide-menu"> Listar </span></a>
                            </li>
                            <li class="sidebar-item">
                                <a href="/admin/unidades/add" class="sidebar-link"><i class="mdi mdi-plus"></i><span class="hide-menu"> Adicionar </span></a>
                            </li>
                        </ul>
                    </li>
                <?php } ?>

                <?php
                //Se é Admin
                if ($roleId == 1) {
                ?>
                    <li class="sidebar-item">
                        <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="mdi mdi-settings"></i><span class="hide-menu">Admin </span></a>
                        <ul aria-expanded="false" class="collapse first-level">
                            <li class="sidebar-item">
                                <a href="/acl_manager/acl" class="sidebar-link"><i class="mdi mdi-key"></i><span class="hide-menu"> Permissões </span></a>
                            </li>
                            <li class="sidebar-item">
                                <a href="/admin/roles/index" class="sidebar-link"><i class="fas fa-users"></i><span class="hide-menu"> Grupos </span></a>
                            </li>
                        </ul>
                    </li>
                <?php } ?>
                <li class="sidebar-item">
                    <a class="sidebar-link waves-effect waves-dark sidebar-link text-danger" href="/users/logout" aria-expanded="false"><i class="mdi mdi-logout text-danger"></i><span class="hide-menu">Sair</span></a>
                </li>
            </ul>
        </nav>
        <!-- End Sidebar navigation -->
    </div>
    <!-- End Sidebar scroll-->
</aside>