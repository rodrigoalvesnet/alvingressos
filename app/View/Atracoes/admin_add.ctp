<div class="card">
    <?php
    echo $this->Form->create('Atracao');
    echo $this->Form->hidden('id');
    ?>
    <div class="card-body">
        <div class="row">
            <div class="col-lg-4">
                <?php
                echo $this->Form->input('nome', [
                    'label' => 'Nome da Atração',
                    'class' => 'form-control',
                    'div' => 'form-group',
                    'required' => true,
                ]);
                ?>
            </div>

            <div class="col-lg-4">
                <?php
                echo $this->Form->input('unidade_id', [
                    'label' => 'Unidade',
                    'options' => $unidades,
                    'class' => 'form-control',
                    'div' => 'form-group',
                    'empty' => 'Selecione',
                    'required' => true
                ]);
                ?>
            </div>

            <div class="col-lg-4">
                <?php
                echo $this->Form->input('ativo', [
                    'label' => 'Ativo',
                    'options' => ['1' => 'Sim', '0' => 'Não'],
                    'class' => 'form-control',
                    'div' => 'form-group',
                    'required' => true,
                    'empty' => false
                ]);
                ?>
            </div>
        </div>
    </div>

    <div class="card-footer border-top">
        <?php
        echo $this->Form->submit('Salvar', [
            'class' => 'btn btn-primary',
            'div' => false
        ]);
        ?>
    </div>
    <?php echo $this->Form->end(); ?>
</div>