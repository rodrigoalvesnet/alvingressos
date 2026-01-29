<div class="card">
    <?php echo $this->Form->create('Estadia'); ?>
    <div class="card-body">
        <div class="row">
            <div class="col-lg-4">
                <?php
                echo $this->Form->input('atracao_id', [
                    'label' => 'Atração/Brinquedo',
                    'options' => $atracoes,
                    'class' => 'form-control',
                    'div' => 'form-group',
                    'empty' => 'Selecione',
                    'required' => true
                ]);
                ?>
            </div>

            <div class="col-lg-4">
                <?php
                echo $this->Form->input('tarifa_id', [
                    'label' => 'Tarifa',
                    'options' => $tarifas,
                    'class' => 'form-control',
                    'div' => 'form-group',
                    'empty' => 'Selecione',
                    'required' => true
                ]);
                ?>
            </div>

            <div class="col-lg-4">
                <?php
                echo $this->Form->input('pulseira_numero', [
                    'label' => 'Nº Pulseira',
                    'class' => 'form-control',
                    'div' => 'form-group',
                    'required' => true
                ]);
                ?>
            </div>

            <div class="col-lg-3">
                <?php
                echo $this->Form->input('crianca_nome', [
                    'label' => 'Nome da Criança',
                    'class' => 'form-control',
                    'div' => 'form-group',
                    'required' => true
                ]);
                ?>
            </div>
            <div class="col-lg-2">
                <?php
                echo $this->Form->input(
                    'sexo',
                    array(
                        'label' => 'Sexo da Criança',
                        'options' => $sexo,
                        'class' => 'form-control',
                        'empty' => 'Escolha',
                        'required' => true
                    )
                );
                ?>
            </div>

            <div class="col-lg-3">
                <?php
                echo $this->Form->input('responsavel_nome', [
                    'label' => 'Nome do Responsável',
                    'class' => 'form-control',
                    'div' => 'form-group',
                    'required' => true
                ]);
                ?>
            </div>
            <div class="col-lg-2">
                <?php
                echo $this->Form->input('telefone', [
                    'label' => 'Telefone',
                    'class' => 'form-control fone',
                    'div' => 'form-group',
                    'required' => false
                ]);
                ?>
            </div>
            <div class="col-lg-2">
                <?php
                echo $this->Form->input('email', [
                    'label' => 'E-mail',
                    'type' => 'email',
                    'class' => 'form-control',
                    'div' => 'form-group',
                    'required' => false
                ]);
                ?>
            </div>
            <div class="col-lg-12">
                <?php
                echo $this->Form->input('observacoes', [
                    'label' => 'Observações',
                    'type' => 'textarea',
                    'class' => 'form-control',
                    'div' => 'form-group',
                    'required' => false
                ]);
                ?>
            </div>
        </div>
    </div>

    <div class="card-footer border-top">
        <?php
        echo $this->Form->submit('Iniciar Estadia', [
            'class' => 'btn btn-primary',
            'div' => false
        ]);
        ?>
    </div>
    <?php echo $this->Form->end(); ?>
</div>
