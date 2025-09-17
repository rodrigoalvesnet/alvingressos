<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse"
    aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
    <i class="bi bi-list"></i>
</button>

<div class="collapse navbar-collapse" id="navbarCollapse">
    <ul class="navbar-nav mr-auto w-100 justify-content-end">
        <?php foreach ($menus as $menu): ?>
            <?php 
                $hasChildren = !empty($menu['ChildMenu']); 
            ?>
            <li class="nav-item <?php echo $hasChildren ? 'dropdown' : ''; ?>">
                <?php if ($hasChildren): ?>
                    <a class="nav-link dropdown-toggle" href="<?php echo $menu['Menu']['link'] ?: '#'; ?>" 
                       id="menu<?php echo $menu['Menu']['id']; ?>" data-toggle="dropdown" 
                       aria-haspopup="true" aria-expanded="false">
                        <?php echo h($menu['Menu']['title']); ?>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="menu<?php echo $menu['Menu']['id']; ?>">
                        <?php foreach ($menu['ChildMenu'] as $child): ?>
                            <a class="dropdown-item" href="<?php echo $child['link']; ?>">
                                <?php echo h($child['title']); ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <a class="nav-link" href="<?php echo $menu['Menu']['link']; ?>">
                        <?php echo h($menu['Menu']['title']); ?>
                    </a>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>

        <!-- Menu de conta do usuário -->
        <li class="nav-item dropdown">
            <?php if (AuthComponent::user('id')): ?>
                <a class="nav-link dropdown-toggle" href="#" id="accountMenu" data-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false">
                    <i class="bi bi-person icon-navbar"></i> <?php echo AuthComponent::user('name'); ?>
                </a>
                <div class="dropdown-menu" aria-labelledby="accountMenu">
                    <a class="dropdown-item" href="/users/account"><i class="bi bi-person"></i> Minha Conta</a>
                    <a class="dropdown-item" href="/orders/my_tickets"><i class="bi bi-ticket"></i> Meus Pedidos</a>
                    <?php if (AuthComponent::user('role_id') == 1 || AuthComponent::user('role_id') == 2): ?>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="/admin/dash"><i class="bi bi-gear"></i> Administração</a>
                    <?php endif; ?>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item text-danger" href="/users/logout"><i class="bi bi-box-arrow-right"></i> Sair</a>
                </div>
            <?php else: ?>
                <a class="nav-link" href="/Users/login">
                    <i class="bi bi-person icon-navbar"></i> Entrar
                </a>
            <?php endif; ?>
        </li>
    </ul>
</div>
