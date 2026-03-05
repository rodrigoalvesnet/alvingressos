<div class="modal fade" id="modalEncerrar" tabindex="-1" role="dialog" aria-labelledby="modalEncerrarLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="modalEncerrarLabel">Confirmar Encerramento</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>


            <div class="modal-body">

                <div id="encerrar-loading" class="text-center py-4">
                    <div class="spinner-border" role="status" aria-hidden="true"></div>
                    <div class="mt-2">Calculando...</div>
                </div>

                <div id="encerrar-error" class="alert alert-danger" style="display:none;"></div>

                <div id="encerrar-content" style="display:none;">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><strong>Pulseira</strong></label>
                                <input type="text" class="form-control" id="prev-pulseira" disabled>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><strong>Tempo utilizado</strong></label>
                                <input type="text" class="form-control" id="prev-tempo" disabled>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label><strong>Tempo pausado</strong></label>
                                <input type="text" class="form-control" id="prev-pausado" disabled>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label><strong>Situação</strong></label>
                                <input type="text" class="form-control" id="prev-status" disabled>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label><strong>Nome da Criança</strong></label>
                                <input type="text" class="form-control" id="prev-crianca" disabled>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label><strong>Nome do Responsável</strong></label>
                                <input type="text" class="form-control" id="prev-responsavel" disabled>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label><strong>Entrada</strong></label>
                                <input type="text" class="form-control" id="prev-entrada" disabled>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label><strong>Total Tempo</strong></label>
                                <input type="text" class="form-control" id="prev-total-tempo" disabled>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label><strong>Total Adicionais</strong></label>
                                <input type="text" class="form-control" id="prev-total-adicionals" disabled>
                            </div>
                        </div>

                        <?php echo $this->element('estadias/adicionais'); ?>
                        
                        <div class="col-md-12">
                            <div id="containerTotal" class="alert alert-success mb-0">
                                <div class="d-flex justify-content-between align-items-center">
                                    <strong id="labelTotal">Total a pagar</strong>
                                    <span id="prev-total" style="font-size: 1.25rem; font-weight: 700;"></span>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>

            <div class="modal-footer">
                <?php
                // POST para admin_encerrar (salvar de fato)
                echo $this->Form->create('Estadia', [
                    'url' => ['action' => 'encerrar'],
                    'id' => 'formEncerrar',
                    'admin' => true
                ]);
                echo $this->Form->hidden('id', ['id' => 'encerrar-id']);
                ?>

                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Voltar</button>

                <?php
                echo $this->Form->submit('Confirmar Encerramento', [
                    'class' => 'btn btn-success text-white',
                    'id' => 'btnEncerrar',
                    'div' => false
                ]);
                echo $this->Form->end();
                ?>
            </div>

        </div>
    </div>
</div>