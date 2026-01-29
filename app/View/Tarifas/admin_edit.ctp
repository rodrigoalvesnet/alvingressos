<div class="card">
    <?php
    echo $this->Form->create('Tarifa', ['url' => ['admin' => true, 'controller' => 'tarifas', 'action' => 'edit', $this->request->data['Tarifa']['id']]]);
    echo $this->Form->hidden('id');
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
                    'empty' => false
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
                    'empty' => false
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
                    'required' => false
                ]);
                ?>
            </div>

            <div class="col-lg-3">
                <?php
                echo $this->Form->input('adicional_valor_bloco', [
                    'label' => 'Valor por bloco',
                    'class' => 'form-control',
                    'div' => 'form-group',
                    'required' => false
                ]);
                ?>
            </div>

            <div class="col-lg-3">
                <?php
                echo $this->Form->input('adicional_tolerancia_segundos', [
                    'label' => 'Tolerância (minutos)',
                    'class' => 'form-control',
                    'div' => 'form-group',
                    'type' => 'number',
                    'min' => 0,
                    'required' => false
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

<br>

<div class="card">
    <div class="card-body">
        <strong>Faixas desta tarifa</strong>
        <p class="text-muted" style="margin-bottom:10px;">Informe min/max em minutos.</p>

        <?php
        // Form para adicionar faixa
        echo $this->Form->create('TarifaFaixa', [
            'url' => ['admin' => true, 'controller' => 'tarifas', 'action' => 'add_faixa', $this->request->data['Tarifa']['id']]
        ]);
        ?>
        <div class="row">
            <div class="col-lg-2">
                <?php echo $this->Form->input('ordem', ['label' => 'Ordem', 'class' => 'form-control', 'div' => 'form-group', 'required' => true, 'default' => 1]); ?>
            </div>
            <div class="col-lg-3">
                <?php echo $this->Form->input('min_segundos', [
                    'label' => 'Min (minutos)',
                    'class' => 'form-control',
                    'div' => 'form-group',
                    'required' => true,
                    'type' => 'number',
                    'min' => 1,
                ]); ?>
            </div>
            <div class="col-lg-3">
                <?php echo $this->Form->input('max_segundos', [
                    'label' => 'Max (minutos)',
                    'class' => 'form-control',
                    'div' => 'form-group',
                    'required' => true,
                    'type' => 'number',
                    'min' => 1,
                ]); ?>
            </div>
            <div class="col-lg-2">
                <?php echo $this->Form->input('valor', ['label' => 'Valor', 'class' => 'form-control', 'div' => 'form-group', 'required' => true, 'placeholder' => 'Ex: 30.00']); ?>
            </div>
            <div class="col-lg-2">
                <?php echo $this->Form->input('ativo', ['label' => 'Ativo', 'options' => ['1' => 'Sim', '0' => 'Não'], 'class' => 'form-control', 'div' => 'form-group', 'required' => true, 'empty' => false, 'default' => '1']); ?>
            </div>
        </div>
        <?php
        echo $this->Form->submit('Adicionar faixa', ['class' => 'btn btn-primary', 'div' => false, 'label' => false]);
        echo $this->Form->end();
        ?>

        <hr>

        <?php
        $faixas = isset($tarifa['TarifaFaixa']) ? $tarifa['TarifaFaixa'] : [];
        ?>

        <?php if (empty($faixas)) : ?>
            <div class="alert alert-info">Nenhuma faixa cadastrada ainda.</div>
        <?php else : ?>
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Ordem</th>
                            <th>Min (minutos)</th>
                            <th>Max (minutos)</th>
                            <th>Valor</th>
                            <th>Ativo</th>
                            <th style="width:130px;">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($faixas as $fx) : ?>
                            <tr>
                                <td><?php echo (int)$fx['ordem']; ?></td>
                                <td><?php echo (int)$fx['min_segundos'] / 60; ?></td>
                                <td><?php echo (int)$fx['max_segundos'] / 60; ?></td>
                                <td>R$ <?php echo number_format((float)$fx['valor'], 2, ',', '.'); ?></td>
                                <td><?php echo !empty($fx['ativo']) ? 'Sim' : 'Não'; ?></td>
                                <td>
                                    <?php
                                    echo $this->Form->postLink(
                                        'Excluir',
                                        ['controller' => 'tarifas', 'action' => 'delete_faixa', $fx['id']],
                                        ['class' => 'btn btn-sm btn-outline-danger'],
                                        'Remover esta faixa?'
                                    );
                                    ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>

    </div>
</div>