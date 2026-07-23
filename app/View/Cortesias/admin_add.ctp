<div class="card">
    <?php
    echo $this->Form->create('Ticket', array(
        'url' => array(
            'admin' => true,
            'controller' => 'Cortesias',
            'action' => 'add'
        )
    ));
    ?>
    <div class="card-body">
        <div class="row">
            <div class="col-lg-4">
                <?php
                echo $this->Form->input(
                    'Ticket.unidade_id',
                    array(
                        'label'    => 'Unidade *',
                        'options'  => $unidades,
                        'class'    => 'form-control',
                        'empty'    => 'Selecione',
                        'required' => true
                    )
                );
                ?>
            </div>
            <div class="col-lg-4">
                <?php
                echo $this->Form->input(
                    'Ticket.nome',
                    array(
                        'label'    => 'Nome da pessoa (opcional)',
                        'class'    => 'form-control',
                        'required' => false
                    )
                );
                ?>
            </div>
            <div class="col-lg-4">
                <?php
                echo $this->Form->input(
                    'Ticket.dias_validade',
                    array(
                        'type'     => 'number',
                        'label'    => 'Dias de validade *',
                        'class'    => 'form-control',
                        'min'      => 1,
                        'required' => true
                    )
                );
                ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <?php
                echo $this->Form->input(
                    'Ticket.motivo',
                    array(
                        'type'     => 'textarea',
                        'label'    => 'Motivo da cortesia (opcional)',
                        'class'    => 'form-control',
                        'rows'     => 2,
                        'required' => false
                    )
                );
                ?>
            </div>
        </div>
    </div>
    <div class="card-footer border-top">
        <?php
        echo $this->Form->submit(
            'Gerar Cortesia',
            array(
                'type'  => 'submit',
                'class' => 'btn btn-primary text-white',
                'div'   => false,
                'label' => false
            )
        );
        ?>
    </div>
    <?php echo $this->Form->end(); ?>
</div>
