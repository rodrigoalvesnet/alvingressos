<style>
    .value-card {
        font-size: 26px;
        font-weight: bold;
    }

    .dash-filtro {
        display: flex;
        align-items: flex-end;
        flex-wrap: wrap;
        gap: 8px;
        /* substitui mr-2 */
    }
</style>

<?php
$roleId = $_SESSION['Auth']['User']['role_id'];
/**
 * 1 = Admin
 * 2 = Gerente
 * 3 = Comprador
 * 4 = Organizador
 */
?>
<div class="row">
    <div class="col-md-3">

        <a href="/admin/Checkins/add/1" class="btn btn-primary d-block mb-1"><i class="mdi mdi-qrcode"></i> FAZER CHECK-IN</a>
        <a href="/admin/Checkins/index" class="btn btn-info d-block mb-1"><i class="mdi mdi-format-align-justify"></i> VER CHECK-INS</a>
        <?php if ($roleId == 1 || $roleId == 2) { ?>
            <a href="/admin/Orders/index" class="btn btn-secondary d-block mb-1"><i class="mdi mdi-cart"></i> VER PEDIDOS</a>
        <?php } ?>
        <a href="/admin/Tickets/index" class="btn btn-info d-block mb-1"><i class="mdi mdi-ticket"></i> VER PASSAPORTES</a>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body text-center">
                <h4><i class="mdi mdi-cart"></i> Vendidos Hoje</h4><br />

                <div class="value-card">
                    <?php echo $ordersCountToday; ?>
                    <?php if ($roleId == 1 || $roleId == 2) { ?>
                        (R$ <?php echo $this->Alv->tratarValor($ordersTotalToday, 'pt') ?>)
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body text-center">
                <h4><i class="fas fa-calendar"></i> Agendados para hoje</h4><br />
                <div class="value-card"><?php echo $ticketsToday; ?></div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body text-center">
                <h4><i class="fas fa-check-square"></i> Checkins hoje</h4><br />
                <div class="value-card"><?php echo $checkinsToday; ?></div>
            </div>
        </div>
    </div>
</div>

<?php
$meses = array(
    1 => 'Janeiro',
    2 => 'Fevereiro',
    3 => 'Março',
    4 => 'Abril',
    5 => 'Maio',
    6 => 'Junho',
    7 => 'Julho',
    8 => 'Agosto',
    9 => 'Setembro',
    10 => 'Outubro',
    11 => 'Novembro',
    12 => 'Dezembro'
);

$anos = array();
$anoAtual = (int)date('Y');
for ($i = $anoAtual - 5; $i <= $anoAtual + 1; $i++) {
    $anos[$i] = $i;
}
?>

<?php if ($roleId == 1 || $roleId == 2) { ?>
    <hr />
    <div class="row mt-3">

        <div class="col-md-12 mb-2">
            <div class="card">
                <div class="card-header">
                    Filtro de Apontamentos
                </div>
                <div class="card-body">

                    <form method="get"
                        action="<?php echo $this->Html->url(array('admin' => true, 'controller' => 'dash', 'action' => 'index')); ?>"
                        class="d-flex align-items-end flex-wrap dash-filtro">

                        <div class="form-group mr-2">
                            <label>Mês</label>
                            <select name="m" class="form-control">
                                <?php foreach ($meses as $num => $nome) { ?>
                                    <option value="<?php echo (int)$num; ?>" <?php echo ((int)$filterMonth === (int)$num) ? 'selected' : ''; ?>>
                                        <?php echo h($nome); ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="form-group mr-2">
                            <label>Ano</label>
                            <select name="y" class="form-control">
                                <?php foreach ($anos as $ano) { ?>
                                    <option value="<?php echo (int)$ano; ?>" <?php echo ((int)$filterYear === (int)$ano) ? 'selected' : ''; ?>>
                                        <?php echo (int)$ano; ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary" style="margin-top: 32px;">
                                OK
                            </button>
                        </div>

                    </form>


                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-body text-center">
                    <h4><i class="mdi mdi-ticket"></i> Passaportes Vendidos</h4>
                    <div class="text-muted">
                        Referência: <?php echo h($meses[(int)$filterMonth]); ?>/<?php echo (int)$filterYear; ?>
                    </div>
                    <br />
                    <div class="value-card">
                        R$ <?php echo h($this->Alv->tratarValor((float)$ordersTotalMonth, 'pt')); ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-body text-center">
                    <h4><i class="fas fa-check-square"></i> Checkins Realizados</h4>
                    <div class="text-muted">
                        Referência: <?php echo h($meses[(int)$filterMonth]); ?>/<?php echo (int)$filterYear; ?>
                    </div>
                    <br />
                    <div class="value-card">
                        <?php echo (int)$checkinsCountMonth; ?>
                    </div>
                </div>
            </div>
        </div>

    </div>
<?php } ?>