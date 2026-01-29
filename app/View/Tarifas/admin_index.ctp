<div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div><strong>Tarifas</strong></div>
            <div>
                <?php
                echo $this->Html->link(
                    'Nova Tarifa',
                    ['controller' => 'tarifas', 'action' => 'add'],
                    ['class' => 'btn btn-primary']
                );
                ?>
            </div>
        </div>

        <?php if (empty($list)) : ?>
            <div class="alert alert-info">Nenhuma tarifa cadastrada.</div>
        <?php else : ?>
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Ativo</th>
                        <th>Adicional</th>
                        <th style="width: 160px;">Ações</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($list as $row) : ?>
                        <tr>
                            <td><?php echo (int)$row['Tarifa']['id']; ?></td>
                            <td><?php echo h($row['Tarifa']['nome']); ?></td>
                            <td><?php echo !empty($row['Tarifa']['ativo']) ? 'Sim' : 'Não'; ?></td>
                            <td>
                                <?php
                                echo !empty($row['Tarifa']['adicional_ativo'])
                                    ? ('Sim (' . (int)$row['Tarifa']['adicional_bloco_segundos'] . 's / R$ ' . number_format((float)$row['Tarifa']['adicional_valor_bloco'], 2, ',', '.') . ')')
                                    : 'Não';
                                ?>
                            </td>
                            <td>
                                <?php
                                echo $this->Html->link(
                                    'Editar',
                                    ['controller' => 'tarifas', 'action' => 'edit', $row['Tarifa']['id']],
                                    ['class' => 'btn btn-sm btn-outline-primary']
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
