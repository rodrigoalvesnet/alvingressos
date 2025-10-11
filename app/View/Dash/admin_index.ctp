<style>
    .value-card {
        font-size: 26px;
        font-weight: bold;
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