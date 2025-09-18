<section id="login" class="section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Pagamento!</h2>
            <p class="section-subtitle">Informe os seus dados abaixo e a forma de pagamento!</p>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-9 order-2 order-md-1">
                <?php
                //Se tem alguma coisa na sessão
                if (!empty($ingressos)) {
                    //Se não estiver logado
                    if (!$this->Session->check('Auth.User')) {
                        echo $this->requestAction(array(
                            'controller' => 'Users',
                            'action' => 'login'
                        ), array('return'));
                ?>
                        <hr class="my-3" />
                    <?php
                    }

                    echo $this->Form->create(
                        'Checkout',
                        [
                            'class' => 'form-loading'
                        ]
                    );

                    $resumo = [];
                    foreach ($ingressos['eventos'] as $eventId => $evento) {
                        $resumo[$eventId] = [
                            'evento' => '',
                            'ingressos' => 0,
                            'valor' => 0
                        ];
                        $resumo[$eventId]['evento'] = $evento['title'];
                    ?>
                        <h2 class="mb-4"><?php echo $evento['title']; ?></h2>
                        <?php
                        foreach ($evento['ingressos'] as $date => $pessoas) {
                            $resumo[$eventId]['ingressos'] += count($pessoas);
                            foreach ($pessoas as $p => $pessoa) {
                                echo $this->Form->hidden('Checkout.' . $eventId . '.' . $date . '.' . $p . '.modalidade_id', array(
                                    'value' => $pessoa['modalidade_id']
                                ));
                                echo $this->Form->hidden('Checkout.' . $eventId . '.' . $date . '.' . $p . '.modalidade_nome', array(
                                    'value' => $pessoa['modalidade_nome']
                                ));
                                echo $this->Form->hidden('Checkout.' . $eventId . '.' . $date . '.' . $p . '.modalidade_valor', array(
                                    'value' => $pessoa['modalidade_valor']
                                ));
                                // pr($pessoa);
                                // exit();
                                $resumo[$eventId]['valor'] += $pessoa['modalidade_valor'];
                        ?>
                                <h5><i class="bi bi-ticket-detailed text-secondary"></i> <?php echo $date . ' - ' . $pessoa['modalidade_nome']; ?></h5>
                                <hr class="my-3" />
                                <div class="row">
                                    <div class="col-md-6">
                                        <?php

                                        echo $this->Form->input(
                                            'Checkout.' . $eventId . '.' . $date . '.' . $p . '.nome',
                                            array(
                                                'label' => 'Nome Completo',
                                                'value' => $pessoa['nome'],
                                                'class' => 'form-control',
                                                'div' => 'form-group',
                                                'required' => true,
                                            )
                                        );
                                        ?>
                                    </div>
                                    <div class="col-md-6">
                                        <?php
                                        echo $this->Form->input(
                                            'Checkout.' . $eventId . '.' . $date . '.' . $p . '.cpf',
                                            array(
                                                'label' => 'CPF',
                                                'class' => 'form-control cpf',
                                                'div' => 'form-group',
                                            )
                                        );
                                        ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <?php
                                        echo $this->Form->input(
                                            'Checkout.' . $eventId . '.' . $date . '.' . $p . '.email',
                                            array(
                                                'label' => 'E-mail',
                                                'class' => 'form-control',
                                                'div' => 'form-group'
                                            )
                                        );
                                        ?>
                                    </div>
                                    <div class="col-md-6">
                                        <?php
                                        echo $this->Form->input(
                                            'Checkout.' . $eventId . '.' . $date . '.' . $p . '.telefone',
                                            array(
                                                'label' => 'Telefone',
                                                'class' => 'form-control fone',
                                                'div' => 'form-group'
                                            )
                                        );
                                        ?>
                                    </div>
                                </div>
                    <?php }
                        }
                    }
                    ?>
                    <hr class="my-3" />
                    <?php
                    //Se não estiver logado
                    if ($this->Session->check('Auth.User')) { ?>

                        <h4><i class="fas fa-money-bill-alt"></i> Informações de Pagamento</h4>
                        <div class="row">

                            <div class="col-md-6">
                                <?php
                                echo $this->Form->hidden('Order.event_id', array(
                                    'value' => $eventId
                                ));
                                echo $this->Form->input(
                                    'Order.payment_type',
                                    array(
                                        'label' => 'Forma de pagamento',
                                        'options' => $optionsPayment,
                                        'class' => 'form-control',
                                        'div' => 'form-group',
                                        'empty' => 'Selecione a forma de pagamento',
                                        'required' => true
                                    )
                                );
                                ?>
                            </div>
                            <div class="col-lg-6">
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
                        <hr class="my-3" />
                    <?php

                        echo $this->Form->submit(
                            'Confirmar o Pagamento',
                            array(
                                'type'    => 'submit',
                                'class' => 'btn btn-success btn-block btn-loading',
                                'div'    => false,
                                'label' => false
                            )
                        );
                    }
                    echo $this->Form->end();
                } else { ?>
                    <div class="alert alert-primary" User="alert">Não há nenhum item no seu carrinho.</div>
                <?php } ?>
            </div>
            <div class="col-md-3 order-1 order-md-2">
                <div class="card pricing-table">
                    <div class="card-body">
                        <?php if (!empty($ingressos)): ?>
                            <h5 class="text-center">Tempo restante</h5>
                            <h4 class="text-center"><span id="timer"></span></h4>
                            <script>
                                let remaining = <?php echo $remaining; ?>;

                                function updateTimer() {
                                    if (remaining <= 0) {
                                        document.getElementById("timer").innerText = "Carrinho expirado";
                                        return;
                                    }
                                    let min = Math.floor(remaining / 60);
                                    let sec = remaining % 60;
                                    document.getElementById("timer").innerText =
                                        ("0" + min).slice(-2) + ":" + ("0" + sec).slice(-2);
                                    remaining--;
                                    setTimeout(updateTimer, 1000);
                                }
                                updateTimer();
                            </script>

                        <?php elseif (!empty($cartExpired)): ?>
                            <p>Seu carrinho expirou.</p>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="card pricing-table">
                    <div class="card-body">
                        <h5 class="text-center">Resumo:</h5>
                        <?php foreach ($resumo as $res) { ?>
                            <div><strong><?php echo $res['evento']; ?></strong></div>
                            <div>Ingressos: <?php echo $res['ingressos']; ?></div>
                            <div>Total: R$ <?php echo $this->Alv->tratarValor($res['valor'], 'pt'); ?></div>
                            <hr class="my-3" />
                        <?php } ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php
$data = $this->Js->get('#CheckoutPaymentForm')->serializeForm(array('isForm' => true, 'inline' => true));

$getInstallments = $this->Js->request(
    array(
        'controller' => 'Checkout',
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

//Se foi informada parcelas
if (isset($this->data['Order']['installments']) && !empty($this->data['Order']['installments'])) {
    echo $this->Html->scriptBlock(
        $this->Js->domReady($getInstallments),
        array('block' => 'scriptBottom')
    );
}

echo $this->Html->script('payment', array('block' => 'scriptBottom'));
