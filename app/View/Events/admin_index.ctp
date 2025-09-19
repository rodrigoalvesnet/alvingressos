<?php echo $this->Html->link('<i class="fas fa-plus"></i> Adicionar Novo', ['action' => 'add'], ['class' => 'btn btn-primary mb-2', 'escape' => false]); ?>

<div class="card">
    <?php
    echo $this->Form->create('Filtro');
    ?>
    <div class="card-body">
        <div class="row">
            <div class="col-lg-4">
                <?php
                echo $this->Form->input(
                    'title',
                    array(
                        'label' => 'Título',
                        'class' => 'form-control',
                    )
                );
                ?>
            </div>
            <div class="col-lg-4">
                <?php
                echo $this->Form->input(
                    'unidade_id',
                    array(
                        'label' => 'Unidade',
                        'options' => $unidades,
                        'class' => 'form-control',
                        'div' => 'form-group',
                        'required' => false,
                        'empty' => ''
                    )
                );
                ?>
            </div>
            <div class="col-lg-4">
                <?php
                echo $this->Form->input(
                    'status',
                    array(
                        'label' => 'Situação',
                        'options' => $status,
                        'class' => 'form-control',
                        'empty' => 'Qualquer',
                        // 'default' => 'scheduled'
                    )
                );
                ?>
            </div>
        </div>
    </div>
    <div class="card-footer border-top">
        <?php
        echo $this->Form->submit(
            'Pesquisar',
            array(
                'type'    => 'submit',
                'class' => 'btn btn-primary',
                'div'    => false,
                'label' => false
            )
        );
        echo $this->Html->link(
            'Limpar',
            array(
                'controller' => $this->request->params['controller'],
                'action' => $this->request->params['action'],
                'limpar:1'
            ),
            array(
                'class' => 'btn btn-outline-secondary mx-2',
                'escape' => false
            )
        );
        ?>
    </div>
    <?php echo $this->Form->end(); ?>
</div>

<?php
if (!empty($registros)) { ?>
    <div class="card">
        <div class="table-responsive" style="min-height: 300px;">
            <table class="table sortable table-striped">
                <thead>
                    <tr>
                        <th scope="col">Ações</th>
                        <th scope="col"><?php echo $this->Paginator->sort('Event.title', 'Título'); ?></th>
                        <th scope="col"><?php echo $this->Paginator->sort('Unidade.name', 'Unidade'); ?></th>
                        <th scope="col">Vendidos</th>
                        <th scope="col"><?php echo $this->Paginator->sort('Event.start_date', 'Início'); ?></th>
                        <th scope="col"><?php echo $this->Paginator->sort('Event.end_date', 'Fim'); ?></th>
                        <th scope="col"><?php echo $this->Paginator->sort('Event.status', 'Situação'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $userId = AuthComponent::user('id');
                    foreach ($registros as $registro) {
                        $isAdmin = false;
                        $isAjudante = false;
                        //Se é um COMPRADOR
                        if (AuthComponent::user('role_id') == 3) {
                            //Verifica se o usuário logado é admin ou ajudante do evento
                            if (!empty($registro['Admin'])) {
                                foreach ($registro['Admin'] as $admin) {
                                    if ($admin['id'] == $userId) {
                                        $isAdmin = true;
                                        break;
                                    }
                                }
                            }
                            if (!empty($registro['User'])) {
                                foreach ($registro['User'] as $user) {
                                    if ($user['id'] == $userId) {
                                        $isAjudante = true;
                                        break;
                                    }
                                }
                            }
                        } else {
                            $isAdmin = true;
                        }
                    ?>
                        <tr>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-cog"></i>
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        <?php
                                        if ($isAdmin || $isAjudante) {
                                            if ($registro['Event']['start_date'] <= date('Y-m-d') && $registro['Event']['end_date'] >= date('Y-m-d')) {
                                                echo $this->Html->link(
                                                    '<i class="fas fa-qrcode"></i> Check-In',
                                                    array(
                                                        'controller' => 'Checkins',
                                                        'action' => 'add',
                                                        $registro['Event']['id']
                                                    ),
                                                    array(
                                                        'class' => 'dropdown-item',
                                                        'title' => 'Fazer Checkin',
                                                        'escape' => false
                                                    )
                                                );
                                            }
                                        }
                                        if ($isAdmin) {

                                            echo $this->Html->link(
                                                '<i class="fas fa-edit"></i> Editar',
                                                array(
                                                    'controller' => 'Events',
                                                    'action' => 'edit',
                                                    $registro['Event']['id']
                                                ),
                                                array(
                                                    'title' => 'Editar',
                                                    'class' => 'dropdown-item',
                                                    'escape' => false
                                                )
                                            );

                                            echo $this->Html->link(
                                                '<i class="fas fa-file-excel"></i> Exportar',
                                                array(
                                                    'controller' => 'Reports',
                                                    'action' => 'by_event',
                                                    $registro['Event']['id']
                                                ),
                                                array(
                                                    'class' => 'dropdown-item',
                                                    'target' => '_blank',
                                                    'title' => 'Exportar Inscritos',
                                                    'escape' => false
                                                )
                                            );
                                        }

                                        
                                        ?>
                                    </div>
                                </div>
                            </td>
                            <td><?php echo $registro['Event']['title']; ?></td>
                            <td><?php echo $registro['Unidade']['name']; ?></td>
                            <td>
                                <?php
                                $total = isset($orders[$registro['Event']['id']]) ? $orders[$registro['Event']['id']] : 0;
                                echo $total;
                                ?>
                            </td>
                            <td><?php echo $this->Alv->tratarData($registro['Event']['start_date'], 'pt'); ?></td>
                            <td><?php echo $this->Alv->tratarData($registro['Event']['end_date'], 'pt'); ?></td>
                            <td>
                                <?php
                                $badgeClass = 'primary';
                                if ($registro['Event']['status'] == 'sketch') {
                                    $badgeClass = 'secondary';
                                }
                                if ($registro['Event']['status'] == 'oculto') {
                                    $badgeClass = 'info';
                                }
                                if ($registro['Event']['status'] == 'soldoff') {
                                    $badgeClass = 'warning';
                                }
                                if ($registro['Event']['status'] == 'closed') {
                                    $badgeClass = 'dark';
                                }
                                if ($registro['Event']['status'] == 'canceled') {
                                    $badgeClass = 'danger';
                                }
                                ?>
                                <span class="badge rounded-pill bg-<?php echo $badgeClass; ?>"><?php echo $status[$registro['Event']['status']]; ?></span>
                            </td>
                        </tr>
                    <?php } ?>

                </tbody>
            </table>
        </div>
    </div>
    <?php echo $this->element('paginador'); ?>
<?php } else { ?>
    <div class="alert alert-primary" role="alert">Nenhum registro encontrado</div>
<?php } ?>