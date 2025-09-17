<div class="row">
    <div class="col-md-2">
        <div class="card">
            <div class="card-body">
                <h4><i class="fas fa-calendar"></i> Vendidos Hoje</h4><br />
                <div class="">R$ <?php echo $this->Alv->tratarValor($ordersTotalToday, 'pt') ?> (<?php echo $ordersCountToday; ?>)</div>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card">
            <div class="card-body">
                <h4><i class="fas fa-calendar"></i> Para hoje</h4><br />
                <div class=""><?php echo $ticketsToday; ?></div>
            </div>
        </div>
    </div>
</div>