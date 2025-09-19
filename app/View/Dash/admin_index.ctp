<style>
    .value-card {
        font-size: 26px;
        font-weight: bold;
    }
</style>
<div class="row">
    <div class="col-md-2">
        <div class="card">
            <div class="card-body text-center">
                <h4><i class="mdi mdi-cart"></i> Vendidos Hoje</h4><br />
                <div class="value-card">R$ <?php echo $this->Alv->tratarValor($ordersTotalToday, 'pt') ?> (<?php echo $ordersCountToday; ?>)</div>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card">
            <div class="card-body text-center">
                <h4><i class="fas fa-calendar"></i> Agendados para hoje</h4><br />
                <div class="value-card"><?php echo $ticketsToday; ?></div>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card">
            <div class="card-body text-center">
                <h4><i class="fas fa-check-square"></i> Checkins hoje</h4><br />
                <div class="value-card"><?php echo $ticketsToday; ?></div>
            </div>
        </div>
    </div>
</div>