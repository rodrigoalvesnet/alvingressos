<?php echo $this->Html->link('Adicionar Novo', ['action' => 'add'], ['class' => 'btn btn-primary mb-2']); ?>
<style>

</style>
<div class="card">
    <style>
        .cke_notification_warning {
            display: none;
        }
    </style>
    <div class="card-body">
        <?php
        echo $this->Form->create(
            'Event',
            array(
                'type' => 'file'
            )
        );
        echo $this->Form->hidden('Event.id');
        ?>
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="event-details" data-bs-toggle="tab" data-bs-target="#details" type="button" role="tab" aria-controls="home" aria-selected="true"><i class="fas fa-info-circle"></i> Detalhes</button>
            </li>
            <?php
            //Se está editando
            if ($this->action == 'admin_edit') {
            ?>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="event-lots" data-bs-toggle="tab" data-bs-target="#lots" type="button" role="tab" aria-controls="lots" aria-selected="false"><i class="fas fa-tag"></i> Modalidades/Lotes</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="event-coupons" data-bs-toggle="tab" data-bs-target="#coupons" type="button" role="tab" aria-controls="coupons" aria-selected="false"><i class="fas fa-percent"></i> Cupons</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="event-dates" data-bs-toggle="tab" data-bs-target="#dates" type="button" role="tab" aria-controls="dates" aria-selected="false"><i class="fas fa-calendar"></i> Datas Disponíveis</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="event-fields" data-bs-toggle="tab" data-bs-target="#fields" type="button" role="tab" aria-controls="fields" aria-selected="false"><i class="fas fa-question"></i> Perguntas</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="event-products" data-bs-toggle="tab" data-bs-target="#products" type="button" role="tab" aria-controls="products" aria-selected="false"><i class="fas fa-gift"></i> Produtos</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="event-users" data-bs-toggle="tab" data-bs-target="#users" type="button" role="tab" aria-controls="users" aria-selected="false"><i class="fas fa-key"></i> Permissões</button>
                </li>
            <?php } ?>
        </ul>
        <div class="tab-content mt-3" id="myTabContent">
            <!-- DETALHES DO EVENTO -->
            <div class="tab-pane fade show active" id="details" role="tabpanel" aria-labelledby="event-details">
                <div class="row">
                    <div class="col-lg-12">
                        <?php
                        echo $this->Form->input(
                            'Event.title',
                            array(
                                'label' => 'Título do evento',
                                'class' => 'form-control',
                                'div' => 'form-group',
                                'required' => true,
                            )
                        );
                        ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-2">
                        <?php
                        echo $this->Form->input(
                            'Event.start_date',
                            array(
                                'type' => 'text',
                                'label' => 'Data de início',
                                'class' => 'form-control datepicker',
                                'div' => 'form-group',
                                'required' => true,
                            )
                        );
                        ?>
                    </div>
                    <div class="col-lg-2">
                        <?php
                        echo $this->Form->input(
                            'Event.end_date',
                            array(
                                'type' => 'text',
                                'label' => 'Data de encerramento',
                                'class' => 'form-control datepicker',
                                'div' => 'form-group',
                                'required' => true,
                            )
                        );
                        ?>
                    </div>
                    <div class="col-lg-3">
                        <?php
                        echo $this->Form->input(
                            'Event.display_date',
                            array(
                                'type' => 'text',
                                'label' => 'Exibir "Em breve" a partir de:',
                                'class' => 'form-control datepicker',
                                'div' => 'form-group',
                            )
                        );
                        ?>
                    </div>
                    <div class="col-lg-3">
                        <?php
                        echo $this->Form->input(
                            'Event.status',
                            array(
                                'label' => 'Situação',
                                'options' => $status,
                                'class' => 'form-control',
                                'required' => true,
                                'div' => 'form-group',
                                'empty' => false
                            )
                        );
                        ?>
                    </div>
                    <div class="col-lg-2">
                        <?php
                        echo $this->Form->input(
                            'Event.priority',
                            array(
                                'label' => 'Prioridade',
                                'options' => array(
                                    0 => 'Alta',
                                    1 => 'Média',
                                    2 => 'Normal',
                                    3 => 'Baixa',
                                ),
                                'default' => 2,
                                'class' => 'form-control',
                                'required' => true,
                                'div' => 'form-group',
                                'empty' => false
                            )
                        );
                        ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6">
                        <?php
                        echo $this->Form->input(
                            'Event.locale',
                            array(
                                'label' => 'Local do evento',
                                'class' => 'form-control',
                                'required' => true,
                                'div' => 'form-group'
                            )
                        );
                        ?>
                    </div>
                    <div class="col-lg-6">
                        <?php
                        echo $this->Form->input(
                            'unidade_id',
                            array(
                                'label' => 'Unidade Organizadora (Sera utilizada os dados dela para a cobrança)',
                                'options' => $unidades,
                                'class' => 'form-control select2',
                                'div' => 'form-group',
                                'required' => true,
                                'empty' => ''
                            )
                        );
                        ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <?php
                        echo $this->Form->input(
                            'Event.description',
                            array(
                                'type' => 'textarea',
                                'label' => 'Descrição do evento:',
                                'class' => 'form-control ckeditor',
                                'div' => 'form-group'
                            )
                        );
                        ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6">
                        <?php
                        echo $this->Form->input(
                            'Event.new_banner_desktop',
                            array(
                                'type' => 'file',
                                'label' => 'Banner para a exibição no desktop (1920px por 1080px)',
                                'class' => 'form-control',
                                'div' => 'form-group'
                            )
                        );
                        //Se tem imagem para exibir
                        if (isset($this->data['Event']['banner_desktop']) && !empty($this->data['Event']['banner_desktop'])) {
                            echo $this->Html->image(
                                '/uploads/event-' . $this->data['Event']['id'] . '/' . $this->data['Event']['banner_desktop'],
                                array(
                                    'class' => 'img-thumbnail'
                                )
                            );
                        }
                        ?>
                    </div>
                    <div class="col-lg-6">
                        <?php
                        echo $this->Form->input(
                            'Event.new_banner_mobile',
                            array(
                                'type' => 'file',
                                'label' => 'Banner para a exibição no mobile (510x por 350px)',
                                'class' => 'form-control',
                                'div' => 'form-group'
                            )
                        );
                        //Se tem imagem para exibir
                        if (isset($this->data['Event']['banner_mobile']) && !empty($this->data['Event']['banner_mobile'])) {
                            echo $this->Html->image(
                                '/uploads/event-' . $this->data['Event']['id'] . '/small/' . $this->data['Event']['banner_mobile'],
                                array(
                                    'class' => 'img-thumbnail'
                                )
                            );
                        }
                        ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6">
                        <?php
                        echo $this->Form->input(
                            'Event.youtube',
                            array(
                                'label' => 'Link do vídeo do Youtube',
                                'class' => 'form-control',
                                'required' => false,
                                'div' => 'form-group',
                                'type' => 'text'
                            )
                        );
                        ?>
                    </div>
                </div>
            </div>
            <?php
            //Se está editando
            if ($this->action == 'admin_edit') {
            ?>
                <!-- LOTE -->
                <div class="tab-pane fade" id="lots" role="tabpanel" aria-labelledby="event-lots">
                    <div class="d-flex flex-row-reverse">
                        <?php
                        echo $this->Html->link(
                            '<i class="fas fa-plus"></i> Cadastrar Novo',
                            array(
                                'controller' => 'Lots',
                                'action' => 'add',
                                $this->data['Event']['id']
                            ),
                            array(
                                'class' => 'btn btn-sm btn-secondary mb-2',
                                'escape' => false
                            )
                        );
                        ?>
                    </div>
                    <?php
                    //Se tem registros
                    if (!empty($this->data['Lot'])) { ?>
                        <table class="table sortable table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">Nome/Modalidade</th>
                                    <th scope="col">Valor</th>
                                    <th scope="col">Qtde.</th>
                                    <th scope="col">Data de Início</th>
                                    <th scope="col">Data de Fim</th>
                                    <th scope="col">Tipo de Pagamento</th>
                                    <th scope="col" class="sorter-false">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($this->data['Lot'] as $lot) { ?>
                                    <tr>
                                        <td><?php echo $lot['name']; ?></td>
                                        <td>R$ <?php echo $this->Alv->tratarValor($lot['value'], 'pt'); ?></td>
                                        <td><?php echo $lot['quantity']; ?></td>
                                        <td><?php echo $this->Alv->tratarData($lot['start_date'], 'pt'); ?></td>
                                        <td><?php echo $this->Alv->tratarData($lot['end_date'], 'pt'); ?></td>
                                        <td>
                                            <ul>
                                                <?php
                                                $paymentsType = unserialize($lot['payments_type']);
                                                foreach ($paymentsType as $k => $type) {
                                                    if (isset($type['active']) && $type['active']) {
                                                        echo '<li>' . $type['label'] . '</li>';
                                                    }
                                                }
                                                ?>
                                            </ul>
                                        </td>
                                        <td>
                                            <?php
                                            echo $this->Html->link(
                                                '<i class="fas fa-edit"></i>',
                                                array(
                                                    'controller' => 'Lots',
                                                    'action' => 'edit',
                                                    $this->data['Event']['id'],
                                                    $lot['id']
                                                ),
                                                array(
                                                    'class' => 'btn btn-action mx-1',
                                                    'title' => 'Editar',
                                                    'escape' => false
                                                )
                                            );
                                            echo $this->Html->link(
                                                '<i class="fas fa-trash"></i>',
                                                array(
                                                    'controller' => 'Lots',
                                                    'action' => 'delete',
                                                    $lot['id']
                                                ),
                                                array(
                                                    'confirm' => 'Tem certeza que deseja excluir este Lote?',
                                                    'title' => 'EXCLUIR',
                                                    'class' => 'btn btn-action text-danger mx-1',
                                                    'escape' => false
                                                )
                                            );
                                            ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    <?php } else { ?>
                        <div class="alert alert-info" role="alert">Nenhum registro encontrado</div>
                    <?php } ?>
                </div>
                <!-- CUPONS -->
                <div class="tab-pane fade" id="coupons" role="tabpanel" aria-labelledby="event-coupons">
                    <div class="d-flex flex-row-reverse">
                        <?php
                        echo $this->Html->link(
                            '<i class="fas fa-plus"></i> Cadastrar Novo',
                            array(
                                'controller' => 'Coupons',
                                'action' => 'add',
                                $this->data['Event']['id']
                            ),
                            array(
                                'class' => 'btn btn-sm btn-secondary mb-2',
                                'escape' => false
                            )
                        );
                        ?>
                    </div>

                    <?php
                    //Se tem cupons
                    if (!empty($this->data['Coupon'])) { ?>
                        <table class="table sortable table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">Cupom (Código)</th>
                                    <th scope="col">Tipo</th>
                                    <th scope="col">Valor</th>
                                    <th scope="col">Descrição</th>
                                    <th scope="col">Por usuário</th>
                                    <th scope="col">Ativo</th>
                                    <th scope="col" class="sorter-false">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($this->data['Coupon'] as $coupon) { ?>
                                    <tr>
                                        <td><?php echo $coupon['code']; ?></td>
                                        <td><?php echo $coupon['type']  == 'money' ? 'Em Dinheiro' : 'Percentual'; ?></td>
                                        <td>
                                            <?php
                                            if ($coupon['type'] == 'money') {
                                                echo 'R$ ' . $this->Alv->tratarValor($coupon['value'], 'pt');
                                            } else {
                                                echo '%' . $coupon['percent'];
                                            }
                                            ?>
                                        </td>
                                        <td><?php echo $coupon['description']; ?></td>
                                        <td><?php echo $coupon['unique_by_user'] ? 'Sim' : 'Não'; ?></td>
                                        <td><?php echo $coupon['active'] ? 'Sim' : 'Não' ?></td>
                                        <td>
                                            <?php
                                            echo $this->Html->link(
                                                '<i class="fas fa-edit"></i>',
                                                array(
                                                    'controller' => 'Coupons',
                                                    'action' => 'edit',
                                                    $this->data['Event']['id'],
                                                    $coupon['id']
                                                ),
                                                array(
                                                    'class' => 'btn btn-action mx-1',
                                                    'title' => 'Editar',
                                                    'escape' => false
                                                )
                                            );
                                            echo $this->Html->link(
                                                '<i class="fas fa-trash"></i>',
                                                array(
                                                    'controller' => 'Coupons',
                                                    'action' => 'delete',
                                                    $coupon['id']
                                                ),
                                                array(
                                                    'confirm' => 'Tem certeza que deseja excluir este Preletor?',
                                                    'title' => 'EXCLUIR',
                                                    'class' => 'btn btn-action text-danger mx-1',
                                                    'escape' => false
                                                )
                                            );
                                            ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    <?php } else { ?>
                        <div class="alert alert-info" role="alert">Nenhum registro encontrado</div>
                    <?php } ?>
                </div>
                <!-- DATAS DISPONÍVEIS -->
                <div class="tab-pane fade" id="dates" role="tabpanel" aria-labelledby="event-dates">
                    <div id="calendar"></div>
                    <input type="hidden" id="datasBloqueadas" name="datasBloqueadas" value='<?php echo !empty($blockedDates) ? $blockedDates : json_encode([]); ?>'>
                </div>
                <!-- PERGUNTAS DO EVENTO -->
                <div class="tab-pane fade" id="fields" role="tabpanel" aria-labelledby="event-fields">
                    <div class="d-flex flex-row-reverse">
                        <?php
                        echo $this->Html->link(
                            '<i class="fas fa-plus"></i> Cadastrar Novo',
                            array(
                                'controller' => 'Fields',
                                'action' => 'add',
                                $this->data['Event']['id']
                            ),
                            array(
                                'class' => 'btn btn-sm btn-secondary mb-2',
                                'escape' => false
                            )
                        );
                        ?>
                    </div>

                    <?php
                    //Se tem preletores
                    if (!empty($this->data['Field'])) { ?>
                        <table class="table sortable table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">Pergunta</th>
                                    <th scope="col">Tipo</th>
                                    <th scope="col">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($this->data['Field'] as $field) { ?>
                                    <tr>
                                        <td><?php echo $field['question']; ?></td>
                                        <td><?php echo $field['type']; ?></td>
                                        <td>
                                            <?php
                                            echo $this->Html->link(
                                                '<i class="fas fa-edit"></i>',
                                                array(
                                                    'controller' => 'Fields',
                                                    'action' => 'edit',
                                                    $this->data['Event']['id'],
                                                    $field['id']
                                                ),
                                                array(
                                                    'class' => 'btn btn-action mx-1',
                                                    'title' => 'Editar',
                                                    'escape' => false
                                                )
                                            );
                                            echo $this->Html->link(
                                                '<i class="fas fa-trash"></i>',
                                                array(
                                                    'controller' => 'Fields',
                                                    'action' => 'delete',
                                                    $field['id']
                                                ),
                                                array(
                                                    'confirm' => 'Tem certeza que deseja excluir esta pergunta?',
                                                    'title' => 'EXCLUIR',
                                                    'class' => 'btn btn-action text-danger mx-1',
                                                    'escape' => false
                                                )
                                            );
                                            ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    <?php } else { ?>
                        <div class="alert alert-info" role="alert">Nenhum registro encontrado</div>
                    <?php } ?>
                </div>
                <!-- USUÁRIOS PERMITIDOS -->
                <div class="tab-pane fade" id="users" role="tabpanel" aria-labelledby="event-users">

                    <div class="alert alert-info" role="alert">Selecione abaixo os usuários que ajudarão na leitura do QRCode deste evento.</div>

                    <?php
                    //Pega os usuário que já foram selecionados anteriormente
                    $usersSelecteds = array();
                    if (isset($this->data['User']) && !empty($this->data['User'])) {
                        foreach ($this->data['User'] as $selected) {
                            $usersSelecteds[] = array(
                                'value' => $selected['id'],
                                'label' => $selected['name'],
                            );
                        }
                    }
                    $initialtags = json_encode($usersSelecteds);
                    echo $this->Form->input(
                        'Users',
                        array(
                            'label' => 'Ajudantes do Evento',
                            'class' => 'form-control',
                            'div' => 'form-group',
                            'placeholder' => 'Digite os nomes das pessoas...',
                            'required' => false,
                            'value' => $initialtags
                        )
                    );

                    ?>

                    <script>
                        var _initialUsers = <?php echo json_encode($initialtags); ?>;
                        _initialUsers = JSON.parse(_initialUsers);
                        var _inputUsers = document.querySelector('#EventUsers');
                        var tagifyUsers = new Tagify(_inputUsers, {
                            tagTextProp: 'label',
                            whitelist: _initialUsers,
                            enforceWhitelist: true,
                            dropdown: {
                                enabled: 1,
                                searchKeys: ['label'],
                                mapValueTo: 'label',
                                classname: 'tags-users-id',
                                closeOnSelect: false, // Manter o dropdown aberto após a seleção
                                highlightFirst: true
                            },
                        });

                        // Evento de entrada (quando o usuário digita)
                        tagifyUsers.on('input', function(e) {
                            var value = e.detail.value; // Termo de busca
                            // Chamada AJAX ao servidor para buscar sugestões
                            fetch('/Users/autocomplete?term=' + value)
                                .then(response => response.json())
                                .then(function(suggestions) {
                                    if (Array.isArray(suggestions)) {
                                        tagifyUsers.settings.whitelist.length = 0; // Limpa as sugestões anteriores
                                        tagifyUsers.settings.whitelist.push(...suggestions); // Adiciona novas sugestões
                                        tagifyUsers.dropdown.show.call(tagifyUsers, value); // Mostra o dropdown com as novas sugestões
                                    } else {
                                        console.error("A resposta não é um array:", suggestions);
                                    }
                                })
                                .catch(function(error) {
                                    console.error("Erro ao buscar sugestões:", error);
                                });
                        });
                    </script>

                    <hr />

                    <div class="alert alert-info" role="alert">Selecione abaixo os usuários que terão permissão para editar este evento e visualizar os relatórios.</div>

                    <?php
                    //Pega os usuário que já foram selecionados anteriormente
                    $usersSelecteds = array();
                    if (isset($this->data['Admin']) && !empty($this->data['Admin'])) {
                        foreach ($this->data['Admin'] as $selected) {
                            $usersSelecteds[] = array(
                                'value' => $selected['id'],
                                'label' => $selected['name'],
                            );
                        }
                    }
                    $initialtags = json_encode($usersSelecteds);
                    echo $this->Form->input(
                        'Admins',
                        array(
                            'label' => 'Administradores do Evento',
                            'class' => 'form-control',
                            'div' => 'form-group',
                            'placeholder' => 'Digite os nomes das pessoas...',
                            'required' => false,
                            'value' => $initialtags
                        )
                    );

                    ?>

                    <script>
                        var _initialTags = <?php echo json_encode($initialtags); ?>;
                        _initialTags = JSON.parse(_initialTags);
                        var _inputAdmins = document.querySelector('#EventAdmins');
                        var tagifyAdmins = new Tagify(_inputAdmins, {
                            tagTextProp: 'label',
                            whitelist: _initialTags,
                            enforceWhitelist: true,
                            dropdown: {
                                enabled: 1,
                                searchKeys: ['label'],
                                mapValueTo: 'label',
                                classname: 'tags-users-id',
                                closeOnSelect: false, // Manter o dropdown aberto após a seleção
                                highlightFirst: true
                            },
                        });

                        // Evento de entrada (quando o usuário digita)
                        tagifyAdmins.on('input', function(e) {
                            var value = e.detail.value; // Termo de busca
                            // Chamada AJAX ao servidor para buscar sugestões
                            fetch('/Users/autocomplete?term=' + value)
                                .then(response => response.json())
                                .then(function(suggestions) {
                                    if (Array.isArray(suggestions)) {
                                        tagifyAdmins.settings.whitelist.length = 0; // Limpa as sugestões anteriores
                                        tagifyAdmins.settings.whitelist.push(...suggestions); // Adiciona novas sugestões
                                        tagifyAdmins.dropdown.show.call(tagifyAdmins, value); // Mostra o dropdown com as novas sugestões
                                    } else {
                                        console.error("A resposta não é um array:", suggestions);
                                    }
                                })
                                .catch(function(error) {
                                    console.error("Erro ao buscar sugestões:", error);
                                });
                        });
                    </script>
                </div>

            <?php } ?>
        </div>
    </div>
    <div class="card-footer border-top">
        <?php
        echo $this->Form->submit(
            'Salvar',
            array(
                'type'    => 'submit',
                'class' => 'btn btn-primary',
                'div'    => false,
                'label' => false
            )
        );
        ?>
    </div>
    <?php echo $this->Form->end(); ?>
</div>

<?php
echo $this->Html->script('events', array('block' => 'scriptBottom'));
