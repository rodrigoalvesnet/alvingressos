<div class="card">
    <div class="card-body">
        <h5 style="margin:0 0 10px;">Confirmar encerramento</h5>

        <div class="row">
            <div class="col-lg-4">
                <div class="form-group">
                    <label>Pulseira</label>
                    <input class="form-control" value="<?php echo h($row['Estadia']['pulseira_numero']); ?>" disabled>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="form-group">
                    <label>Criança</label>
                    <input class="form-control" value="<?php echo h($row['Estadia']['crianca_nome']); ?>" disabled>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="form-group">
                    <label>Status</label>
                    <input class="form-control" value="<?php echo h($row['Estadia']['status']); ?>" disabled>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="form-group">
                    <label>Início</label>
                    <input class="form-control" value="<?php echo h($row['Estadia']['inicio_em']); ?>" disabled>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="form-group">
                    <label>Fim (agora)</label>
                    <input class="form-control" value="<?php echo h($preview['fim_em']); ?>" disabled>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="form-group">
                    <label>Tempo cobrado</label>
                    <input class="form-control" value="<?php echo h($preview['duracao_cobrada_hms']); ?>" disabled>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="form-group">
                    <label>Valor base</label>
                    <input class="form-control" value="R$ <?php echo number_format($preview['valor_base'], 2, ',', '.'); ?>" disabled>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="form-group">
                    <label>Adicional</label>
                    <input class="form-control" value="R$ <?php echo number_format($preview['valor_adicional'], 2, ',', '.'); ?>" disabled>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="form-group">
                    <label><strong>Total</strong></label>
                    <input class="form-control" value="R$ <?php echo number_format($preview['valor_total'], 2, ',', '.'); ?>" disabled>
                </div>
            </div>
        </div>
    </div>

    <div class="card-footer border-top">
        <?php
        echo $this->Form->create('Estadia', ['url' => ['action' => 'encerrar', $row['Estadia']['id']]]);
        echo $this->Form->submit('Confirmar encerramento', ['class' => 'btn btn-success', 'div' => false]);
        echo ' ';
        echo $this->Html->link('Voltar', ['action' => 'index', '?' => ['status' => 'aberta']], ['class' => 'btn btn-light']);
        echo $this->Form->end();
        ?>
    </div>
</div>