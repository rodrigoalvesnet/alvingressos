<div class="card">
    <?php
    echo $this->Form->create('Tarifa', ['url' => ['controller' => 'tarifas', 'action' => 'add']]);
    ?>
    <div class="card-body">
        <div class="row">
            <div class="col-lg-6">
                <?php
                echo $this->Form->input('nome', [
                    'label' => 'Nome',
                    'class' => 'form-control',
                    'div' => 'form-group',
                    'required' => true,
                ]);
                ?>
            </div>

            <div class="col-lg-6">
                <?php
                echo $this->Form->input('ativo', [
                    'label' => 'Ativo',
                    'options' => ['1' => 'Sim', '0' => 'Não'],
                    'class' => 'form-control',
                    'div' => 'form-group',
                    'required' => true,
                    'empty' => false,
                    'default' => '1',
                ]);
                ?>
            </div>

            <div class="col-lg-12">
                <hr>
                <strong>Configuração de adicional (opcional)</strong>
            </div>

            <div class="col-lg-3">
                <?php
                echo $this->Form->input('adicional_ativo', [
                    'label' => 'Cobrar adicional após última faixa?',
                    'options' => ['0' => 'Não', '1' => 'Sim'],
                    'class' => 'form-control',
                    'div' => 'form-group',
                    'required' => true,
                    'empty' => false,
                    'default' => '0',
                ]);
                ?>
            </div>

            <div class="col-lg-3">
                <?php
                echo $this->Form->input('adicional_bloco_segundos', [
                    'label' => 'Bloco (minutos)',
                    'class' => 'form-control',
                    'div' => 'form-group',
                    'type' => 'number',
                    'min' => 1,
                    'required' => false,
                    'placeholder' => 'Ex: 600 (= 10 min)'
                ]);
                ?>
            </div>

            <div class="col-lg-3">
                <?php
                echo $this->Form->input('adicional_valor_bloco', [
                    'label' => 'Valor por bloco',
                    'class' => 'form-control',
                    'div' => 'form-group',
                    'required' => false,
                    'placeholder' => 'Ex: 10.00'
                ]);
                ?>
            </div>

            <div class="col-lg-3">
                <?php
                echo $this->Form->input('adicional_tolerancia_segundos', [
                    'label' => 'Tolerância (minutos)',
                    'class' => 'form-control',
                    'div' => 'form-group',
                    'required' => false,
                    'type' => 'number',
                    'min' => 0
                ]);
                ?>
            </div>

        </div>
    </div>
    <div class="card-footer border-top">
        <?php
        echo $this->Form->submit('Salvar', [
            'type' => 'submit',
            'class' => 'btn btn-primary',
            'div' => false,
            'label' => false
        ]);
        echo ' ';
        echo $this->Html->link('Voltar', ['controller' => 'tarifas', 'action' => 'index'], ['class' => 'btn btn-light']);
        ?>
    </div>
    <?php echo $this->Form->end(); ?>
</div>
