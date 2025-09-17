<?php
echo $this->Form->create('Lot');
echo $this->Form->hidden('id');
?>
<div class="card">
    <div class="card-body">
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
                        'min' => 1
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
                        'div' => 'form-group',
                        'required' => true,
                    )
                );
                ?>
            </div>
        </div>
    </div>
</div>
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
    <div class="card">
        <div class="card-body">
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
        </div>
    </div>
<?php } ?>

<div class="card">
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
echo $this->Html->script('lots', array('block' => 'scriptBottom'));
