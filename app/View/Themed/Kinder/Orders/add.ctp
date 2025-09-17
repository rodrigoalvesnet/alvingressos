<section id="buy_ticket" class="section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Comprar Ingresso!</h2>
            <p class="section-subtitle">Preencha os dados abaixo selecionando as melhores opções para vocês!</p>
        </div>
        
        <div class="ticket-buy">
            <?php
            echo $this->Form->create(
                'Order',
                array(
                    'type' => 'file'
                )
            );
            //Campos que não passarão pela validação CRSF
            $this->Form->unlockField('Order.coupon_id');
            $this->Form->unlockField('Order.installments');
            $this->Form->unlockField('Order.products_total');

            echo $this->Form->hidden('Order.id');
            echo $this->Form->hidden('Order.event_id', array(
                'value' => $event['Event']['id']
            ));
            echo $this->Form->hidden('Order.description', array(
                'value' => $event['Event']['title']
            ));
            //Se TEM lote informado
            if (!empty($event['Lot'])) {
                $lotId = $event['Lot'][0]['id'];
                $lotValue = $event['Lot'][0]['value'];
            } else {
                $lotId = null;
                $lotValue = null;
            }
            echo $this->Form->hidden('Order.lot_id', array(
                'value' =>  $lotId
            ));
            echo $this->Form->hidden('Order.value', array(
                'value' => $lotValue
            ));
            ?>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-2 pb-3">
                        <div class="div-thumb-event position-relative">
                            <?php
                            //trata os tags da miniatura
                            $tagImg = '/img/faixa-comprar.png';
                            //Se foi cancelado
                            if ($event['Event']['status'] == 'canceled') {
                                $tagImg = '/img/faixa-cancelado.png';
                            }
                            //Se já foi concluído
                            if ($event['Event']['status'] == 'closed') {
                                $tagImg = '/img/faixa-concluido.png';
                            }
                            //Se está esgotado
                            if ($event['Event']['status'] == 'soldoff') {
                                $tagImg = '/img/faixa-esgotado.png';
                            }
                            $tag = '<img src="' . $tagImg . '" class="tag-thumb-event" />';
                            echo $this->Html->image(
                                '/uploads/event-' . $event['Event']['id'] . '/medium/' . $event['Event']['banner_mobile'],
                                array(
                                    'class' => 'img-thumbnail img-fluid',
                                    'alt' => $event['Event']['title'],
                                    'title' => $event['Event']['title'],
                                )
                            ) . $tag;
                            ?>
                        </div>
                    </div>
                    <div class="col-lg-10">
                        <h2><?php echo $event['Event']['title']; ?></h2>
                        <div class="event-desctiption">
                            De <?php echo $this->Alv->tratarData($event['Event']['start_date'], 'pt'); ?> até <?php echo $this->Alv->tratarData($event['Event']['end_date'], 'pt'); ?>
                        </div>
                        <div class="event-price">
                            <?php
                            if (!empty($event['Lot'])) {
                                echo 'R$ ' . $this->Alv->tratarValor($event['Lot'][0]['value'], 'pt');
                            } else {
                                echo 'Valor não informado.';
                            }

                            ?>
                        </div>
                        <div class="event-pix">
                            <?php
                            //Se o tipo do pagamento é PIX
                            if (!empty($event['Event']['pix'])) {
                                echo 'Chave PIX: ' . $event['Event']['pix'];
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <?php
                //Se está agendado
                if ($event['Event']['status'] == 'scheduled' || $event['Event']['status'] == 'oculto') {
                ?>
                    <hr class="my-3" />
                    <h4><i class="fas fa-shopping-cart"></i> Informações de Compra</h4>
                    <div class="row">
                        <div class="col-lg-12">
                            <?php
                            echo $this->Form->input(
                                'Order.other',
                                array(
                                    'label' => 'Estou comprando para OUTRA pessoa',
                                    'type' => 'checkbox',
                                    'class' => 'form-check-input',
                                    'div' => 'form-check'
                                )
                            );
                            ?>
                        </div>
                    </div>
                    <div class="div-other-person">
                        <hr class="my-3" />
                        <h4><i class="fas fa-user"></i> Informações para o ingresso</h4>
                        <div class="row">
                            <div class="col-lg-6">
                                <?php
                                echo $this->Form->input(
                                    'Order.name',
                                    array(
                                        'label' => 'Nome completo da pessoa',
                                        'class' => 'form-control',
                                        'div' => 'form-group',
                                        'required' => true,
                                    )
                                );
                                ?>
                            </div>
                            <div class="col-lg-3">
                                <?php
                                echo $this->Form->input(
                                    'Order.cpf',
                                    array(
                                        'label' => 'CPF',
                                        'class' => 'form-control cpf',
                                        'div' => 'form-group',
                                        'required' => true,
                                    )
                                );
                                ?>
                            </div>
                            <div class="col-lg-3">
                                <?php
                                echo $this->Form->input(
                                    'Order.birthday',
                                    array(
                                        'type' => 'text',
                                        'label' => 'Data de nascimento',
                                        'class' => 'form-control datepicker',
                                        'div' => 'form-group',
                                        'required' => true,
                                    )
                                );
                                ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4">
                                <?php
                                echo $this->Form->input(
                                    'Order.phone',
                                    array(
                                        'label' => 'Telefone (whatsapp)',
                                        'class' => 'form-control fone',
                                        'div' => 'form-group',
                                        'required' => true,
                                    )
                                );
                                ?>
                            </div>
                            <div class="col-lg-4">
                                <?php
                                echo $this->Form->input(
                                    'Order.email',
                                    array(
                                        'type' => 'email',
                                        'label' => 'E-mail',
                                        'class' => 'form-control',
                                        'div' => 'form-group',
                                        'required' => true,
                                    )
                                );
                                ?>
                            </div>
                        </div>
                    </div>
                    <?php
                    //Se TEM campos adicionados
                    if (!empty($event['Field'])) {
                    ?>
                            <hr class="my-3" />
                            <h4><i class="fas fa-info-circle"></i> Informações Adicionais</h4>
                        <div class="row">
                            <?php
                            //Se tem campos adicionais
                            if (isset($event['Field']) && !empty($event['Field'])) {
                                foreach ($event['Field'] as $k => $field) {
                                    $fieldId = $field['id'];
                                    //Monta as opções básicas
                                    $fieldOptions = array(
                                        'label' => $field['question'],
                                        'class' => 'form-control',
                                        'div' => $field['size']
                                    );
                                    //Se é obrigatório
                                    if ($field['mandatory']) {
                                        $fieldOptions['required'] = true;
                                    }
                                    //Se é uma lista
                                    if ($field['type'] == 'list') {
                                        $fieldOptions['options'] = $listOptions[$fieldId];
                                        $fieldOptions['empty'] = 'Selecione uma opção';
                                        //Se é obrigatório
                                        if ($field['mandatory']) {
                                            $fieldOptions['required'] = true;
                                        }
                                    } else if ($field['type'] == 'phone') {
                                        $fieldOptions['type'] = 'text';
                                        $fieldOptions['class'] = 'form-control fone';
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
                                            'value' => $fieldId
                                        )
                                    );
                                }
                            }
                            ?>
                        </div>
                    <?php }

                    //Se tem produtos para vender
                    if (!empty($event['Product'])) {
                        echo $this->element('orders/products');
                    }


                    //Se tem cupons de desconto
                    if (!empty($event['Coupon'])) { ?>
                        <hr class="my-3" />
                        <h4><i class="fas fa-gift"></i> Cupom de desconto</h4>
                        <div class="row">
                            <div class="col-lg-6">
                                <?php
                                $buttonApply = '<div class="input-group-append">
                        <button class="btn btn-info" type="button" id="btnApplyDiscount">Aplicar Desconto</button>
                    </div>';
                                echo $this->Form->input(
                                    'Order.coupon',
                                    array(
                                        'label' => false,
                                        'class' => 'form-control',
                                        'div' => 'form-group input-group',
                                        'after' => $buttonApply
                                    )
                                );
                                ?>
                            </div>
                            <div id="callbackcoupon"></div>
                        </div>
                    <?php }

                    //Se TEM lote
                    if (!empty($event['Lot'])) {
                        //Pega as parcelas e as suas configurações
                        $paymentsType = unserialize($event['Lot'][0]['payments_type']);
                    ?>
                        <hr class="my-3" />
                        <h4><i class="fas fa-money-bill-alt"></i> Informações de Pagamento</h4>
                        <div class="row">

                            <div class="col-lg-3">
                                <?php
                                $arrayPementsType = array();
                                foreach ($paymentsType as $k => $type) {
                                    if (isset($type['active']) && $type['active']) {
                                        $arrayPementsType[$k] = $type['label'];
                                    }
                                }
                                echo $this->Form->input(
                                    'Order.payment_type',
                                    array(
                                        'label' => 'Forma de pagamento',
                                        'options' => $arrayPementsType,
                                        'class' => 'form-control',
                                        'div' => 'form-group',
                                        'empty' => 'Selecione a forma de pagamento',
                                        'required' => true
                                    )
                                );
                                ?>
                            </div>
                            <div class="col-lg-3">
                                <div id="divInstallments">
                                </div>
                            </div>
                        </div>
                        <div class="credit-div" style="display: none;">
                            <hr class="my-3" />
                            <h4><i class="fas fa-credit-card"></i> Dados do Cartão de Crédito</h4>
                            <div class="row">
                                <div class="col-lg-6">
                                    <?php
                                    echo $this->Form->input(
                                        'CreditCard.holder_name',
                                        array(
                                            'label' => 'Nome escrito no cartão',
                                            'class' => 'form-control credit-field',
                                            'div' => 'form-group',
                                        )
                                    );
                                    ?>
                                </div>
                                <div class="col-lg-6">
                                    <?php
                                    echo $this->Form->input(
                                        'CreditCard.number',
                                        array(
                                            'label' => 'Número do cartão',
                                            'class' => 'form-control credit-field creditcard',
                                            'div' => 'form-group',
                                        )
                                    );
                                    ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4">
                                    <?php
                                    echo $this->Form->input(
                                        'CreditCard.expiry_month',
                                        array(
                                            'label' => 'Mês de expiração',
                                            'options' => Configure::read('Sistema.mesesNumericos'),
                                            'empty' => '',
                                            'class' => 'form-control credit-field',
                                            'div' => 'form-group',
                                        )
                                    );
                                    ?>
                                </div>
                                <div class="col-lg-4">
                                    <?php
                                    $years = array();
                                    $year = date('Y');
                                    $i = 0;
                                    while ($i <= 10) {
                                        $years[$year] = $year;
                                        $year = $year + 1;
                                        $i++;
                                    }
                                    echo $this->Form->input(
                                        'CreditCard.expiry_year',
                                        array(
                                            'label' => 'Ano de expiração',
                                            'options' => $years,
                                            'class' => 'form-control credit-field',
                                            'empty' => '',
                                            'div' => 'form-group',
                                        )
                                    );
                                    ?>
                                </div>
                                <div class="col-lg-4">
                                    <?php
                                    echo $this->Form->input(
                                        'CreditCard.ccv',
                                        array(
                                            'label' => 'CCV (Código de Segurança)',
                                            'class' => 'form-control credit-field',
                                            'div' => 'form-group',
                                        )
                                    );
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="credit-div" style="display: none;">
                            <hr class="my-3" />
                            <h4><i class="fas fa-id-card"></i> Informações do Titular do Cartão</h4>
                            <div class="row">
                                <div class="col-lg-6">
                                    <?php
                                    echo $this->Form->input(
                                        'CreditCardHolderInfo.name',
                                        array(
                                            'label' => 'Nome completo',
                                            'class' => 'form-control credit-field',
                                            'div' => 'form-group',
                                        )
                                    );
                                    ?>
                                </div>
                                <div class="col-lg-6">
                                    <?php
                                    echo $this->Form->input(
                                        'CreditCardHolderInfo.email',
                                        array(
                                            'label' => 'E-mail',
                                            'type' => 'email',
                                            'class' => 'form-control credit-field',
                                            'div' => 'form-group',
                                        )
                                    );
                                    ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-3">
                                    <?php
                                    echo $this->Form->input(
                                        'CreditCardHolderInfo.cpf_cnpj',
                                        array(
                                            'label' => 'CPF ou CNPJ',
                                            'class' => 'form-control credit-field',
                                            'div' => 'form-group',
                                        )
                                    );
                                    ?>
                                </div>
                                <div class="col-lg-3">
                                    <?php
                                    echo $this->Form->input(
                                        'CreditCardHolderInfo.postal_code',
                                        array(
                                            'label' => 'CEP',
                                            'class' => 'form-control cep credit-field',
                                            'div' => 'form-group',
                                        )
                                    );
                                    ?>
                                </div>
                                <div class="col-lg-3">
                                    <?php
                                    echo $this->Form->input(
                                        'CreditCardHolderInfo.address_number',
                                        array(
                                            'label' => 'Número da residência',
                                            'class' => 'form-control credit-field',
                                            'div' => 'form-group',
                                        )
                                    );
                                    ?>
                                </div>
                                <div class="col-lg-3">
                                    <?php
                                    echo $this->Form->input(
                                        'CreditCardHolderInfo.phone',
                                        array(
                                            'label' => 'Telefone',
                                            'class' => 'form-control fone credit-field',
                                            'div' => 'form-group'
                                        )
                                    );
                                    ?>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                <?php
                    //Se não está mais disponível
                } else { ?>
                    <hr class="my-3" />
                    <h4><i class="fas fa-info"></i> Informações do Evento</h4>
                    <div class="row">
                        <div class="col-lg-12">
                            <?php
                            echo $event['Event']['description'];
                            ?>
                        </div>
                    </div>
                <?php } ?>
            </div>
            <?php
            //Se está disponível e TEM lote informado
            if (($event['Event']['status'] == 'scheduled' || $event['Event']['status'] == 'oculto') && !empty($event['Lot'])) {
            ?>
                <div class="card-footer border-top">
                    <?php
                    echo $this->Form->submit(
                        'Finalizar a Compra',
                        array(
                            'type'    => 'submit',
                            'class' => 'btn btn-success text-white',
                            'div'    => false,
                            'label' => false
                        )
                    );
                    ?>
                </div>
            <?php } ?>
            <?php echo $this->Form->end(); ?>
        </div>
    </div>
</section>
<!-- product Modal -->
<div class="modal fade" id="productModal" tabindex="-1" role="dialog" aria-labelledby="productModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div id="productModalContent" class="p-3">

            </div>
        </div>
    </div>
</div>

<?php
$data = $this->Js->get('#OrderAddForm')->serializeForm(array('isForm' => true, 'inline' => true));

$getInstallments = $this->Js->request(
    array(
        'controller' => 'Orders',
        'action' => 'get_installments'
    ),
    array(
        'async' => true,
        'update' => '#divInstallments',
        'data' => $data,
        'dataExpression' => true,
        'method' => 'POST',
        'before' => 'setLoadingRequest("divInstallments", " Aguarde! Verificando as parcelas...")',
    )
);
$this->Js->get('#OrderPaymentType')->event(
    'change',
    $getInstallments
);

$sumProducts = $this->Js->request(
    array(
        'controller' => 'Orders',
        'action' => 'sum_products'
    ),
    array(
        'async' => true,
        'update' => '#divSumProducts',
        'data' => $data,
        'dataExpression' => true,
        'method' => 'POST',
        'before' => 'setLoadingRequest("divSumProducts", " Aguarde! Somando os produtos...")',
        'complete' => $getInstallments
    )
);
$this->Js->get('.input-quantity')->event(
    'change',
    $sumProducts
);

$applyDiscount = $this->Js->request(
    array(
        'controller' => 'Orders',
        'action' => 'apply_discount'
    ),
    array(
        'async' => true,
        'update' => '#callbackcoupon',
        'data' => $data,
        'dataExpression' => true,
        'method' => 'POST',
        'before' => 'setLoadingRequest("callbackcoupon", " Aguarde! Verificando cupom...")',
        'complete' => $getInstallments
    )
);
$this->Js->get('#btnApplyDiscount')->event(
    'click',
    $applyDiscount
);

//Se foi informada parcelas
if (isset($this->data['Order']['installments']) && !empty($this->data['Order']['installments'])) {
    echo $this->Html->scriptBlock(
        $this->Js->domReady($getInstallments),
        array('block' => 'scriptBottom')
    );
}
echo $this->Html->script('orders', array('block' => 'scriptBottom'));
