<section id="account" class="section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Ingresso</h2>
            <p class="section-subtitle">Abaixo estão os seus dados do seu ingresso</p>
        </div>
        <div class="justify-content-center">
            <div class="ticket-buy">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            Número: <strong><?php echo str_pad($this->data['Order']['id'], 5, '0', STR_PAD_LEFT); ?></strong>
                        </div>
                        <div class="col-md-3">
                            Data: <strong><?php echo date('d/m/Y H:i', strtotime($this->data['Order']['created'])); ?></strong>
                        </div>
                        <div class="col-md-3">
                            Usuário: <strong><?php echo $this->data['User']['name']; ?></strong>
                        </div>
                        <div class="col-md-3">
                            <?php
                            $badgeClass = 'primary';
                            if ($this->data['Order']['status'] == 'rejected') {
                                $badgeClass = 'danger';
                            }
                            if ($this->data['Order']['status'] == 'approved') {
                                $badgeClass = 'success';
                            }
                            if ($this->data['Order']['status'] == 'canceled') {
                                $badgeClass = 'secondary';
                            }
                            ?>
                            Situação: <strong><span style="font-size: 1em;" class="badge rounded-pill text-white bg-<?php echo $badgeClass; ?>"><?php echo $status[$this->data['Order']['status']]; ?></span></strong>
                        </div>
                    </div>
                    <?php
                    if ($this->data['Order']['status'] == 'rejected') {
                    ?>
                        <div class="row">
                            <div class="col-md-3">
                                Motivo: <strong><span class="text-danger"><?php echo $this->data['Order']['reason']; ?></span></strong>
                            </div>
                        </div>
                    <?php } ?>
                    <hr class="my-3" />
                    <h4><i class="bi bi-person"></i> Comprador do Ingresso</h4>
                    <div class="row">
                        <div class="col-md-6">
                            <?php
                            echo $this->Form->input(
                                'Order.name',
                                array(
                                    'label' => 'Nome completo da pessoa',
                                    'class' => 'form-control',
                                    'div' => 'form-group',
                                    'disabled' => true,
                                )
                            );
                            ?>
                        </div>
                        <div class="col-md-3">
                            <?php
                            echo $this->Form->input(
                                'Order.cpf',
                                array(
                                    'label' => 'CPF',
                                    'class' => 'form-control cpf',
                                    'div' => 'form-group',
                                    'disabled' => true,
                                )
                            );
                            ?>
                        </div>
                        <div class="col-md-3">
                            <?php
                            echo $this->Form->input(
                                'Order.birthday',
                                array(
                                    'type' => 'text',
                                    'label' => 'Data de nascimento',
                                    'class' => 'form-control datepicker',
                                    'div' => 'form-group',
                                    'disabled' => true,
                                )
                            );
                            ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <?php
                            echo $this->Form->input(
                                'Order.phone',
                                array(
                                    'label' => 'Telefone (whatsapp)',
                                    'class' => 'form-control fone',
                                    'div' => 'form-group',
                                    'disabled' => true,
                                )
                            );
                            ?>
                        </div>
                        <div class="col-md-4">
                            <?php
                            echo $this->Form->input(
                                'Order.email',
                                array(
                                    'type' => 'email',
                                    'label' => 'E-mail',
                                    'class' => 'form-control',
                                    'div' => 'form-group',
                                    'disabled' => true,
                                )
                            );
                            ?>
                        </div>
                        <div class="col-md-4">
                            <?php
                            $unidades[] = 'Outra ou Nenhuma';
                            echo $this->Form->input(
                                'Order.unidade_id',
                                array(
                                    'label' => 'Unidade',
                                    'options' => $unidades,
                                    'class' => 'form-control',
                                    'div' => 'form-group',
                                    'empty' => '',
                                    'disabled' => true
                                )
                            );
                            ?>
                        </div>
                    </div>
                    <?php
                    //Se tem campos adicionais
                    if (isset($this->data['Event']['Field']) && !empty($this->data['Event']['Field'])) {
                        // pr($this->data);
                    ?>
                        <hr class="my-3" />
                        <h4><i class="bi bi-info-circle"></i> Informações Adicionais</h4>
                        <div class="row">
                            <?php
                            foreach ($this->data['Event']['Field'] as $k => $field) {
                                $fieldId = $field['id'];
                                //Monta as opções básicas
                                $fieldOptions = array(
                                    'label' => $field['question'],
                                    'class' => 'form-control',
                                    'div' => $field['size'],
                                    'disabled' => true
                                );
                                //Se é obrigatório
                                if ($field['mandatory']) {
                                    $fieldOptions['required'] = true;
                                }
                                //Se é uma lista
                                if ($field['type'] == 'list') {
                                    $options = explode(PHP_EOL, $field['options']);
                                    $listOptions = array();
                                    foreach ($options as $option) {
                                        $option = rtrim($option);
                                        $option = preg_replace('/([\r\n\t])/', '', $option);
                                        $listOptions[$option] = $option;
                                    }
                                    $fieldOptions['options'] = $listOptions;
                                    $fieldOptions['empty'] = '';
                                    //Se é obrigatório
                                    if ($field['mandatory']) {
                                        $fieldOptions['empty'] = false;
                                    }
                                    //Se tem respostas
                                    if (!empty($this->data['Response'])) {
                                        $arrayColumn = array_column($this->data['Response'], 'field_id');
                                        $responseKey = array_search($fieldId, $arrayColumn);
                                        $fieldOptions['value'] = rtrim($this->data['Response'][$responseKey]['response']);
                                    }
                                } else {
                                    $fieldOptions['type'] = $field['type'];
                                }
                                echo $this->Form->input(
                                    'Response.' . $k . '.response',
                                    $fieldOptions
                                );
                                echo $this->Form->hidden(
                                    'Response.' . $k . '.field_id',
                                    array(
                                        'value' => $fieldId,
                                        'disabled' => true
                                    )
                                );
                            }
                            ?>
                        </div>
                    <?php } ?>
                    <?php
                    //Se tem produtos
                    if (isset($this->data['Product']) && !empty($this->data['Product'])) {
                    ?>
                        <hr class="my-3" />
                        <h4><i class="bi bi-gift"></i> Produtos adicionais</h4>
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th scope="col" style="width: 120px" class="sorter-false"> Foto</th>
                                            <th scope="col" class="sorter-text">Nome</th>
                                            <th scope="col" class="sorter-text">Valor</th>
                                            <th scope="col" class="sorter-text">Quantidade</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $totalProducts = 0;
                                        foreach ($this->data['Product'] as $product) {
                                            $totalProducts += $product['price'];
                                        ?>
                                            <tr style="vertical-align: middle">
                                                <td>
                                                    <?php
                                                    $imgSrc = 'no-photo.png';
                                                    //Se tem imagem para exibir
                                                    if (isset($product['photo']) && !empty($product['photo'])) {
                                                        $imgSrc = '/uploads/small/' . $product['photo'];
                                                    }
                                                    echo $this->Html->image(
                                                        $imgSrc,
                                                        array(
                                                            'class' => 'img-thumbnail'
                                                        )
                                                    );
                                                    ?>
                                                </td>
                                                <td><?php echo $product['name']; ?></td>
                                                <td>R$ <?php echo $this->Alv->tratarValor($product['OrdersProduct']['price'], 'pt'); ?></td>
                                                <td><?php echo $product['OrdersProduct']['quantity']; ?></td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                                <div id="divSumProducts">
                                    <div class="row">
                                        <div class="col-md-12">
                                            Total de produtos: R$ <strong><?php echo $this->Alv->tratarValor($totalProducts, 'pt'); ?></strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    <?php
                    //Se tem tickets
                    if (isset($this->data['Ticket']) && !empty($this->data['Ticket'])) {
                        // pr($this->data);
                    ?>
                        <hr class="my-3" />
                        <h4><i class="bi bi-ticket-detailed"></i> Participantes</h4>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Nome</th>
                                        <th>Telefone</th>
                                        <th>E-mail</th>
                                        <th>CPF</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($this->data['Ticket'] as $k => $ticket) { ?>
                                        <tr>
                                            <td><?php echo $ticket['id']; ?></td>
                                            <td><?php echo $ticket['nome']; ?></td>
                                            <td><?php echo $ticket['telefone']; ?></td>
                                            <td><?php echo $ticket['email']; ?></td>
                                            <td><?php echo $ticket['cpf']; ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    <?php } ?>


                    <hr class="my-3" />
                    <h4><i class="bi bi-money"></i> Informações de Pagamento</h4>
                    <div class="row">
                        <div class="col-md-3">
                            <?php
                            echo $this->Form->input(
                                'Order.payment_type',
                                array(
                                    'label' => 'Forma de pagamento',
                                    'options' => Configure::read('Order.payment_type'),
                                    'class' => 'form-control',
                                    'empty' => false,
                                    'disabled' => true
                                )
                            );
                            ?>
                        </div>
                        <div class="col-md-3">
                            <?php
                            $price = $this->data['Order']['value'];
                            $installments = ($this->data['Order']['installments'] > 0) ? $this->data['Order']['installments'] : 1;
                            $value = ($price / $installments);
                            $value = number_format($value, 2, '.', '');
                            $installmentsValue = '(' . $installments . 'x) R$' . $this->Alv->tratarValor($value, 'pt');
                            echo $this->Form->input(
                                'Order.installments',
                                array(
                                    'type' => 'text',
                                    'label' => 'Parcelas',
                                    'value' => $installmentsValue,
                                    'class' => 'form-control',
                                    'div' => 'form-group',
                                    'disabled' => true,
                                )
                            );
                            ?>
                        </div>
                    </div>
                    <?php
                    //Se a forma de pagamento é pix_old
                    if ($this->data['Order']['payment_type'] == 'pix_old') {
                        $paymentsType = unserialize($this->data['Lot']['payments_type']);
                        App::import('Vendor', 'QrcodeGen', array('file' => 'QrcodeGen/QrcodeGen.php'));
                        $qrcode = new QrcodeGen();
                        $description = AuthComponent::user('name') . ' - ' . $this->data['Event']['title'];
                        $value = ($this->data['Order']['value'] / $this->data['Order']['installments']);
                        //chama as classes necessárias
                        $image = $qrcode->chavePix(
                            trim($paymentsType['pix_old']['pix']),
                            trim($description),
                            number_format($value, 2, '.', '')
                        );

                        echo $this->Form->input(
                            'Order.pix',
                            array(
                                'type' => 'text',
                                'label' => 'Chave PIX',
                                'value' => $paymentsType['pix_old']['pix'],
                                'class' => 'form-control',
                                'div' => 'form-group',
                                'disabled' => true,
                            )
                        );
                    ?>
                        <div class="row pix-old-info">
                            <div class="col-md-12" id="qrcodepix">
                                <img src="data:image/png;base64, <?php echo base64_encode($image) ?>" class="img-thumbnail img-fluid">
                            </div>
                        </div>
                    <?php } ?>
                    <hr class="my-3" />
                    <div class="row">
                        <div class="col-md-12">
                            <?php
                            //Se já foi pago
                            if (!empty($this->data['Order']['status'] == 'approved')) {

                                echo $this->Html->link(
                                    '<i class="bi bi-printer"></i> Imprimir Ingressos',
                                    array(
                                            'controller' => 'Orders',
                                            'action' => 'ticket',
                                            $this->data['Order']['id']
                                    ),
                                    array(
                                        'class' => 'btn btn-secondary mb-2 mr-3',
                                        'target' => '_blank',
                                        'escape' => false
                                    )
                                );
                            }

                            //Se tem link do boleto
                            if (!empty($this->data['Order']['invoice_receipt'])) {
                                echo $this->Html->link(
                                    '<i class="bi bi-receipt"></i> Ver o recibo',
                                    $this->data['Order']['invoice_receipt'],
                                    array(
                                        'class' => 'btn btn-secondary mb-2 mr-3',
                                        'target' => '_blank',
                                        'escape' => false
                                    )
                                );
                            }

                            //Se tem link da fatura
                            if (!empty($this->data['Order']['invoice_url'])) {
                                echo $this->Html->link(
                                    '<i class="bi bi-receipt"></i> Ver a cobrança',
                                    $this->data['Order']['invoice_url'],
                                    array(
                                        'class' => 'btn btn-secondary mb-2 mr-3',
                                        'target' => '_blank',
                                        'escape' => false
                                    )
                                );
                            }

                            //Se ainda não foi pago
                            if (!empty($this->data['Order']['status'] == 'pending')) {
                                //Se tem link do boleto
                                if (!empty($this->data['Order']['invoice_boleto'])) {
                                    echo $this->Html->link(
                                        '<i class="bi bi-barcode"></i> Ver o boleto',
                                        $this->data['Order']['invoice_boleto'],
                                        array(
                                            'class' => 'btn btn-secondary mb-2 mr-3',
                                            'target' => '_blank',
                                            'escape' => false
                                        )
                                    );
                                }
                            }
                            
                            ?>
                        </div>
                    </div>

                    <hr class="my-3" />
                    <h4><i class="bi bi-paperclip"></i> Comprovantes/Anexos</h4>
                    <?php
                    $haveAttchments = false;
                    //Se tem anexos
                    if (isset($this->data['Attachment']) && !empty($this->data['Attachment'])) {
                        $haveAttchments = true;
                    ?>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col">Número</th>
                                        <th scope="col">Data do envio</th>
                                        <th scope="col">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($this->data['Attachment'] as $attachment) { ?>
                                        <tr>
                                            <td scope="row">
                                                <?php
                                                echo $this->Html->link(
                                                    str_pad($attachment['id'], 5, '0', STR_PAD_LEFT),
                                                    $attachment['path'],
                                                    array(
                                                        'target' => '_blank'
                                                    )
                                                );
                                                ?>
                                            </td>
                                            <td><?php echo date('d/m/Y H:i', strtotime($attachment['created'])); ?></td>
                                            <td>
                                                <?php
                                                echo $this->Html->link(
                                                    '<i class="bi bi-eye"></i>',
                                                    $attachment['path'],
                                                    array(
                                                        'title' => 'Ver',
                                                        'class' => 'btn btn-action text-primary',
                                                        'target' => '_blank',
                                                        'escape' => false
                                                    )
                                                );
                                                //Se o pedido ainda está pendente
                                                if ($this->data['Order']['status'] == 'pending') {
                                                    echo $this->Form->postLink(
                                                        '<i class="bi bi-trash"></i>',
                                                        array(
                                                            'controller' => 'Orders',
                                                            'action' => 'delete_attach',
                                                            $attachment['id']
                                                        ),
                                                        array(
                                                            'confirm' => 'Tem certeza que deseja excluir este Anexo?',
                                                            'title' => 'EXCLUIR',
                                                            'class' => 'btn btn-action text-danger',
                                                            'escape' => false
                                                        )
                                                    );
                                                }
                                                ?>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    <?php } else { ?>
                        <div class="alert alert-primary" Order="alert">Nenhum arquivo anexado.</div>
                    <?php } ?>
                    <?php
                    //Se ainda não tem anexo
                    if (!$haveAttchments || $this->data['Order']['payment_type'] == 'pix_old') {
                        //se o pedido ainda está pendente, pode fazer alterações
                        if ($this->data['Order']['status'] == 'pending') {
                    ?>
                            <?php
                            echo $this->Form->create(
                                'Order',
                                array(
                                    'type' => 'file'
                                )
                            );
                            echo $this->Form->hidden('Order.id');
                            echo $this->Form->hidden('Order.event_id');
                            ?>
                            <div class="row">
                                <div class="col-md-12">
                                    <?php
                                    echo $this->Form->input(
                                        'Attachment.new_file',
                                        array(
                                            'label' => 'Escolher novo comprovante/anexo',
                                            'type' => 'file',
                                            'class' => 'form-control',
                                            'required' => true
                                        )
                                    );
                                    ?>
                                </div>
                            </div>
                            <div class="border-top pt-3">
                                <?php
                                //se o pedido ainda está pendente, pode fazer alterações
                                if ($this->data['Order']['status'] == 'pending') {
                                    //Se ainda não tem anexo
                                    if (!$haveAttchments || $this->data['Order']['payment_type'] == 'pix_old') {

                                        echo $this->Form->submit(
                                            'Enviar Comprovante',
                                            array(
                                                'type'    => 'submit',
                                                'class' => 'btn btn-primary',
                                                'div'    => false,
                                                'label' => false
                                            )
                                        );
                                    }
                                }
                                //se o pedido ainda está pendente, pode fazer alterações
                                if ($this->data['Order']['status'] == 'approved') {
                                    echo $this->Html->link(
                                        '<i class="fas fa-print"></i> Imprimir Inscrição',
                                        array(
                                            'controller' => 'Orders',
                                            'action' => 'ticket',
                                            $this->data['Order']['id']
                                        ),
                                        array(
                                            'class' => 'btn btn-dark mx-1',
                                            'target' => '_blank',
                                            'title' => 'Imprimir',
                                            'escape' => false
                                        )
                                    );
                                }
                                ?>
                            </div>
                            <?php echo $this->Form->end(); ?>
                    <?php }
                    } ?>

                </div>
            </div>
        </div>
</section>