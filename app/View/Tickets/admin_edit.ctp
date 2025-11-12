<div class="card ticket-buy">
    <?php
    echo $this->Form->create(
        'Ticket',
        array(
            'type' => 'file'
        )
    );
    echo $this->Form->hidden('Ticket.id');
    ?>
    <div class="card-body">
        <div class="row">
            <div class="col-lg-3">
                Passaporte: <strong><?php echo str_pad($this->data['Ticket']['id'], 5, '0', STR_PAD_LEFT); ?></strong>
            </div>
            <div class="col-lg-3">
                Pedido: <strong><?php echo str_pad($this->data['Order']['id'], 5, '0', STR_PAD_LEFT); ?></strong>
            </div>
            <div class="col-lg-3">
                Data: <strong><?php echo date('d/m/Y H:i', strtotime($this->data['Ticket']['created'])); ?></strong>
            </div>
            <div class="col-lg-3">
                Comprador: <strong><?php echo $this->data['Order']['name']; ?></strong>
            </div>

        </div>
        <div class="row">
            <hr class="my-3" />
            <h4><i class="fas fa-user"></i> Informações para o ingresso</h4>
            <div class="col-lg-5">
                <?php
                echo $this->Form->input(
                    'Ticket.nome',
                    array(
                        'label' => 'Nome completo da pessoa',
                        'class' => 'form-control',
                        'div' => 'form-group',
                        'required' => true,
                    )
                );
                ?>
            </div>
            <div class="col-lg-2">
                <?php
                echo $this->Form->input(
                    'Ticket.cpf',
                    array(
                        'label' => 'CPF',
                        'class' => 'form-control cpf',
                        'div' => 'form-group',
                        'required' => false,
                    )
                );
                ?>
            </div>
            <div class="col-lg-3">
                <?php
                echo $this->Form->input(
                    'Ticket.email',
                    array(
                        'type' => 'email',
                        'label' => 'E-mail',
                        'class' => 'form-control datepicker',
                        'div' => 'form-group',
                        'required' => false,
                    )
                );
                ?>
            </div>
            <div class="col-lg-2">
                <?php
                echo $this->Form->input(
                    'Ticket.phone',
                    array(
                        'label' => 'Telefone (whatsapp)',
                        'class' => 'form-control fone',
                        'div' => 'form-group',
                        'required' => false,
                    )
                );
                ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3">
                <?php
                echo $this->Form->input(
                    'Ticket.modalidade_data',
                    array(
                        'type' => 'text',
                        'label' => 'Data do Passaporte',
                        'class' => 'form-control datepicker',
                        'div' => 'form-group',
                        'required' => true,
                    )
                );
                ?>
            </div>
            <div class="col-lg-4">
                <?php
                echo $this->Form->input(
                    'Ticket.modalidade_nome',
                    array(
                        'label' => 'Modalidade',
                        'class' => 'form-control',
                        'div' => 'form-group',
                        'disabled' => true,
                    )
                );
                ?>
            </div>
            <div class="col-lg-2">
                <?php
                echo $this->Form->input(
                    'Ticket.modalidade_valor',
                    array(
                        'label' => 'Modalidade Valor',
                        'class' => 'form-control',
                        'div' => 'form-group',
                        'disabled' => true,
                    )
                );
                ?>
            </div>

        </div>
    </div>
</div>

<div class="card-footer bTicket-top">
    <div class="row">
        <div class="col-lg-6">
            <?php
            echo $this->Form->submit(
                'Salvar Alterações',
                array(
                    'type'    => 'submit',
                    'class' => 'btn btn-primary text-white',
                    'div'    => false,
                    'label' => false
                )
            );
            ?>
        </div>

    </div>
</div>
<?php echo $this->Form->end(); ?>
</div>