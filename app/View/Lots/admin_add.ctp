<div class="card">
    <?php
    echo $this->Form->create('Lot');
    echo $this->Form->hidden('id');
    ?>
    <div class="card-body">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="lot-details" data-bs-toggle="tab" data-bs-target="#details" type="button" role="tab" aria-controls="home" aria-selected="true"><i class="fas fa-info-circle"></i> Detalhes</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="lot-payment" data-bs-toggle="tab" data-bs-target="#payment" type="button" role="tab" aria-controls="home" aria-selected="true"><i class="fas fa-cash"></i> Pagamento</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="lot-rules" data-bs-toggle="tab" data-bs-target="#rules" type="button" role="tab" aria-controls="home" aria-selected="true"><i class="fas fa-cog"></i> Regras</button>
            </li>
        </ul>
        <div class="tab-content mt-3" id="myTabContent">
            <div class="tab-pane fade show active" id="details" role="tabpanel" aria-labelledby="lot-details">
                <div class="row">
                    <div class="col-lg-6">
                        <?php
                        echo $this->Form->input(
                            'name',
                            array(
                                'label' => 'Nome da Lote ou Modalidade',
                                'class' => 'form-control',
                                'div' => 'form-group',
                                'required' => true,
                            )
                        );
                        ?>
                    </div>
                    <div class="col-lg-6">
                        <?php
                        echo $this->Form->input(
                            'description',
                            array(
                                'label' => 'Descrição',
                                'class' => 'form-control',
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
                            'value',
                            array(
                                'type' => 'text',
                                'label' => 'Valor do ingresso',
                                'class' => 'form-control money',
                                'div' => 'form-group'
                            )
                        );
                        ?>
                    </div>
                    <div class="col-lg-2">
                        <?php
                        echo $this->Form->input(
                            'quantity',
                            array(
                                'type' => 'number',
                                'label' => 'Quantidade de ingressos',
                                'class' => 'form-control',
                                'div' => 'form-group',
                                'required' => true,
                                'min' => 0
                            )
                        );
                        ?>
                    </div>
                    <div class="col-lg-2">
                        <?php
                        echo $this->Form->input(
                            'start_date',
                            array(
                                'type' => 'text',
                                'label' => 'Data de Início',
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
                            'end_date',
                            array(
                                'type' => 'text',
                                'label' => 'Data de Fim',
                                'class' => 'form-control datepicker',
                                'div' => 'form-group'
                            )
                        );
                        ?>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade show" id="payment" role="tabpanel" aria-labelledby="lot-payment">
                <?php
                $types = Configure::read('Order.payment_type');
                $typesLabel = Configure::read('Order.payment_type_label');
                foreach ($types as $type => $label) {
                    echo $this->Form->hidden(
                        'Lot.payments_type.' . $type . '.label',
                        array(
                            'value' => $label
                        )
                    );
                ?>
                    <hr class="my-3" />
                    <div class="row">
                        <div class="col-lg-12">
                            <?php
                            echo $this->Form->input(
                                'Lot.payments_type.' . $type . '.active',
                                array(
                                    'hiddenField' => false,
                                    'label' => $typesLabel[$type],
                                    'type' => 'checkbox',
                                    'class' => 'form-check-input',
                                    'div' => 'form-check display-inline'
                                )
                            );
                            ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-2">
                            <?php
                            //SE for gratis
                            if ($type == 'free') {
                                echo $this->Form->hidden(
                                    'Lot.payments_type.' . $type . '.installments',
                                    array(
                                        'value' => 1
                                    )
                                );
                            } else {
                                echo $this->Form->input(
                                    'Lot.payments_type.' . $type . '.installments',
                                    array(
                                        'type' => 'number',
                                        'label' => 'Parcelas',
                                        'class' => 'form-control',
                                        'div' => 'form-group',
                                        'default' => 1,
                                        'min' => 1
                                    )
                                );
                            }
                            ?>
                        </div>
                        <?php
                        //Se for ASAAS
                        if ($type == 'pix' || $type == 'credit' || $type == 'ticket') {
                        ?>
                            <div class="col-lg-3">
                                <?php
                                echo $this->Form->input(
                                    'Lot.payments_type.' . $type . '.tax_type',
                                    array(
                                        'label' => 'Opções de acréscimo',
                                        'options' => array(
                                            0 => 'Não, nenhum acréscimo',
                                            1 => 'Sim, cobrar acréscimo',
                                            2 => 'Somente se for parcelado'
                                        ),
                                        'default' => 0,
                                        'class' => 'form-control',
                                        'div' => 'form-group',
                                        'required' => true
                                    )
                                );
                                ?>
                            </div>
                            <div class="col-lg-2">
                                <?php
                                echo $this->Form->input(
                                    'Lot.payments_type.' . $type . '.tax_value',
                                    array(
                                        'type' => 'text',
                                        'label' => 'Valor do acréscimo (R$)',
                                        'class' => 'form-control money',
                                        'div' => 'form-group'
                                    )
                                );
                                ?>
                            </div>
                        <?php } ?>
                        <?php
                        //Se for chave personalizada
                        if ($type == 'pix_old') { ?>
                            <div class="col-lg-6">
                                <?php
                                echo $this->Form->input(
                                    'Lot.payments_type.' . $type . '.pix',
                                    array(
                                        'label' => 'Chave PIX',
                                        'class' => 'form-control',
                                        'div' => 'form-group'
                                    )
                                );
                                ?>
                            </div>
                        <?php } ?>
                    </div>
                <?php } ?>
            </div>
            <div class="tab-pane fade show" id="rules" role="tabpanel" aria-labelledby="lot-rules">
                <div class="row">
                    <div class="col-md-4">
                        <?php
                        $rulesConfigs = Configure::read('Tickets.rules');
                        $rulesNamesOptions = [];
                        foreach ($rulesConfigs['names'] as $value => $name) {
                            $rulesNamesOptions[$value] = $name;
                        }
                        echo $this->Form->input(
                            'Lot.rule_type',
                            array(
                                'label' => 'Tipo de Regra',
                                'options' => $rulesNamesOptions,
                                'class' => 'form-control',
                                'div' => 'form-group',
                                'empty' => true
                            )
                        );
                        ?>
                        <div id="rule-week">
                            <?php
                            $rulesDaysWeek = [];
                            foreach ($rulesConfigs['days']['week'] as $value => $name) { ?>
                                <div class="col-lg-12">
                                    <?php
                                    echo $this->Form->input(
                                        'Lot.rules.'.$value,
                                        array(
                                            'hiddenField' => false,
                                            'label' => $name,
                                            'type' => 'checkbox',
                                            'class' => 'form-check-input',
                                            'div' => 'form-check display-inline'
                                        )
                                    );
                                    ?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
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

    <?php
    echo $this->Form->end();
    echo $this->Html->script('lots', array('block' => 'scriptBottom'));
    ?>
</div>