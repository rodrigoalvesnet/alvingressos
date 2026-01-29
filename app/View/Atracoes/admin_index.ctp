<div class="card">
    <div class="card-body">
        <a href="<?php echo $this->Html->url(['action' => 'add']); ?>"
           class="btn btn-success mb-3">
            Nova Atração
        </a>

        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Ativo</th>
                    <th width="120">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($list as $row): ?>
                <tr>
                    <td><?php echo $row['Atracao']['id']; ?></td>
                    <td><?php echo h($row['Atracao']['nome']); ?></td>
                    <td><?php echo $row['Atracao']['ativo'] ? 'Sim' : 'Não'; ?></td>
                    <td>
                        <a class="btn btn-sm btn-primary"
                           href="<?php echo $this->Html->url(['action' => 'edit', $row['Atracao']['id']]); ?>">
                            Editar
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
