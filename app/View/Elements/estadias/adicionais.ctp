<div class="modal-header">
    <h5 class="modal-title">Adicionais</h5>
</div>
<div class="col-md-12 mt-2">

    <div class="d-flex gap-2 align-items-end">
        <div class="flex-grow-1">
            <label><strong>Adicional</strong></label>
            <select class="form-control" id="adicional-id">
                <?php foreach ($adicionals as $p):
                    $pid = (int)$p['Adicional']['id'];
                    $pname = $p['Adicional']['nome'];
                    $preco = (float)$p['Adicional']['valor'];
                ?>
                    <option value="<?= $pid ?>" data-preco="<?= h($preco) ?>"><?= h($pname) ?></option>
                <?php endforeach; ?>
            </select>

        </div>

        <div style="width:120px">
            <label><strong>Qtd</strong></label>
            <input type="number" class="form-control" id="adicional-qtd" value="1" min="1">
        </div>

        <div style="width:160px">
            <label><strong>Valor unit.</strong></label>
            <input type="text"
                class="form-control money"
                id="adicional-valor-unit">
        </div>

        <div>
            <button type="button" class="btn btn-primary" id="btnAddAdicional">
                <i class="mdi mdi-plus"></i> Adicionar
            </button>
        </div>
    </div>

    <div class="table-responsive mt-3">
        <table class="table table-sm table-striped table-hover" id="tableAdicionals">
            <thead>
                <tr>
                    <th>Adicional</th>
                    <th class="text-center">Qtd</th>
                    <th class="text-end">Unit.</th>
                    <th class="text-end">Total</th>
                    <th class="text-center">Ação</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

</div>