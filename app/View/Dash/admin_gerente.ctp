<?php
/**
 * Dashboard Gerencial
 * Acesso: Admin (role_id=1) e Gerente (role_id=2)
 * URL: /admin/dash/gerente
 */
?>

<!-- ============================================================ -->
<!-- FILTRO DE DATA                                                -->
<!-- ============================================================ -->
<div class="card mb-4">
    <?php echo $this->Form->create('Filtro'); ?>
    <div class="card-body">
        <div class="row align-items-end">
            <div class="col-lg-3 col-md-4 mb-2">
                <label>Data inicial</label>
                <?php echo $this->Form->text('data_inicial', [
                    'type'     => 'date',
                    'class'    => 'form-control',
                    'required' => true,
                ]); ?>
            </div>
            <div class="col-lg-3 col-md-4 mb-2">
                <label>Data final</label>
                <?php echo $this->Form->text('data_final', [
                    'type'     => 'date',
                    'class'    => 'form-control',
                    'required' => true,
                ]); ?>
            </div>
            <div class="col-lg-3 col-md-4 mb-2">
                <?php
                echo $this->Form->submit('Filtrar', [
                    'class' => 'btn btn-primary mr-2',
                    'div'   => false,
                    'label' => false,
                ]);
                echo $this->Html->link('Limpar', [
                    'admin'      => true,
                    'controller' => 'dash',
                    'action'     => 'gerente',
                ], ['class' => 'btn btn-outline-secondary']);
                ?>
            </div>
        </div>
    </div>
    <?php echo $this->Form->end(); ?>
</div>

<?php
$labelPeriodo = (($dataInicial === $dataFinal)
    ? date('d/m/Y', strtotime($dataInicial))
    : date('d/m/Y', strtotime($dataInicial)) . ' até ' . date('d/m/Y', strtotime($dataFinal)));
?>

<!-- ============================================================ -->
<!-- CARDS PRINCIPAIS                                              -->
<!-- ============================================================ -->
<div class="row mb-4">

    <!-- Passaportes Vendidos -->
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card border-left-success shadow h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase text-muted mb-1">Passaportes Vendidos</h6>
                        <small class="text-muted"><?php echo $labelPeriodo; ?></small>
                        <h3 class="mb-0 font-weight-bold text-success mt-1">
                            R$ <?php echo $this->Alv->tratarValor($totalOrdersValor, 'pt'); ?>
                        </h3>
                        <small class="text-muted"><?php echo $totalOrdersPedidos; ?> pedido(s)</small>
                    </div>
                    <div class="text-success">
                        <i class="fa fa-ticket-alt fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Passaportes Agendados -->
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card border-left-info shadow h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase text-muted mb-1">Passaportes Agendados</h6>
                        <small class="text-muted"><?php echo $labelPeriodo; ?></small>
                        <h3 class="mb-0 font-weight-bold text-info mt-1">
                            <?php echo $totalTickets; ?>
                        </h3>
                    </div>
                    <div class="text-info">
                        <i class="fa fa-calendar-alt fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Check-ins -->
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card border-left-primary shadow h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase text-muted mb-1">Check-ins Feitos</h6>
                        <small class="text-muted"><?php echo $labelPeriodo; ?></small>
                        <h3 class="mb-0 font-weight-bold text-primary mt-1">
                            <?php echo $totalCheckins; ?>
                        </h3>
                    </div>
                    <div class="text-primary">
                        <i class="fa fa-check-square fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Formas de Pagamento (Orders) -->
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card border-left-warning shadow h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="text-uppercase text-muted mb-0">Formas de Pagamento</h6>
                    <i class="fa fa-money-bill-wave text-warning fa-2x"></i>
                </div>
                <small class="text-muted d-block mb-2"><?php echo $labelPeriodo; ?></small>
                <?php if (empty($ordersFormaGlobal)): ?>
                    <span class="text-muted">Nenhum registro.</span>
                <?php else: ?>
                    <?php foreach ($ordersFormaGlobal as $forma): ?>
                        <?php
                        $tipo   = $forma['Order']['payment_type'];
                        $label  = isset($paymentLabels[$tipo]) ? $paymentLabels[$tipo] : $tipo;
                        $valor  = (float)$forma[0]['total_valor'];
                        ?>
                        <div class="d-flex justify-content-between">
                            <small><?php echo h($label); ?></small>
                            <small class="font-weight-bold">
                                R$ <?php echo $this->Alv->tratarValor($valor, 'pt'); ?>
                            </small>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

</div>

<!-- ============================================================ -->
<!-- DADOS POR UNIDADE — PASSAPORTES E CHECKINS                   -->
<!-- ============================================================ -->
<?php if (!empty($dadosPorUnidade)): ?>
<hr class="my-4">
<h5 class="text-uppercase text-muted mb-3">
    <i class="fa fa-store"></i> Passaportes e Check-ins por Unidade
    <small class="text-muted font-weight-normal">— <?php echo $labelPeriodo; ?></small>
</h5>

<div class="card shadow mb-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-bordered table-hover mb-0">
                <thead class="thead-light">
                    <tr>
                        <th>Unidade</th>
                        <th class="text-center">Passaportes<br><small>Qtd</small></th>
                        <th class="text-right">Passaportes<br><small>R$</small></th>
                        <th class="text-center">Agendados</th>
                        <th class="text-center">Check-ins</th>
                        <th>Formas de Pgto (Passaportes)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $totPassQtd   = 0;
                    $totPassValor = 0.0;
                    $totTickets   = 0;
                    $totCheckins  = 0;
                    $totFormas    = [];
                    foreach ($dadosPorUnidade as $unidade):
                        $totPassQtd   += $unidade['orders_count'];
                        $totPassValor += $unidade['orders_valor'];
                        $totTickets   += $unidade['tickets_agendados'];
                        $totCheckins  += $unidade['checkins'];
                        foreach ($unidade['orders_por_forma'] as $t => $v) {
                            $totFormas[$t] = ($totFormas[$t] ?? 0) + $v;
                        }
                    ?>
                    <tr>
                        <td class="font-weight-bold"><?php echo h($unidade['nome']); ?></td>
                        <td class="text-center"><?php echo $unidade['orders_count']; ?></td>
                        <td class="text-right">R$ <?php echo $this->Alv->tratarValor($unidade['orders_valor'], 'pt'); ?></td>
                        <td class="text-center"><?php echo $unidade['tickets_agendados']; ?></td>
                        <td class="text-center"><?php echo $unidade['checkins']; ?></td>
                        <td>
                            <?php if (empty($unidade['orders_por_forma'])): ?>
                                <span class="text-muted">—</span>
                            <?php else: ?>
                                <?php foreach ($unidade['orders_por_forma'] as $tipo => $valor): ?>
                                    <?php $label = isset($paymentLabels[$tipo]) ? $paymentLabels[$tipo] : $tipo; ?>
                                    <div class="d-flex justify-content-between">
                                        <small><?php echo h($label); ?></small>
                                        <small class="ml-3 font-weight-bold">
                                            R$ <?php echo $this->Alv->tratarValor($valor, 'pt'); ?>
                                        </small>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr class="font-weight-bold table-secondary">
                        <td>TOTAL</td>
                        <td class="text-center"><?php echo $totPassQtd; ?></td>
                        <td class="text-right">R$ <?php echo $this->Alv->tratarValor($totPassValor, 'pt'); ?></td>
                        <td class="text-center"><?php echo $totTickets; ?></td>
                        <td class="text-center"><?php echo $totCheckins; ?></td>
                        <td>
                            <?php foreach ($totFormas as $tipo => $valor): ?>
                                <?php $label = isset($paymentLabels[$tipo]) ? $paymentLabels[$tipo] : $tipo; ?>
                                <div class="d-flex justify-content-between">
                                    <small><?php echo h($label); ?></small>
                                    <small class="ml-3 font-weight-bold">
                                        R$ <?php echo $this->Alv->tratarValor($valor, 'pt'); ?>
                                    </small>
                                </div>
                            <?php endforeach; ?>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- ============================================================ -->
<!-- SEÇÃO DE ESTADIAS POR UNIDADE                                 -->
<!-- ============================================================ -->
<?php
$temEstadias = false;
foreach ($dadosPorUnidade as $u) {
    if ($u['estadias_ativas'] > 0 || $u['estadias_encerradas'] > 0 || $u['estadias_canceladas'] > 0) {
        $temEstadias = true;
        break;
    }
}
?>

<hr class="my-4">
<h5 class="text-uppercase text-muted mb-3">
    <i class="fa fa-child"></i> Estadias por Unidade
    <small class="text-muted font-weight-normal">
        — ativas (tempo real) | encerradas/canceladas: <?php echo $labelPeriodo; ?>
    </small>
</h5>

<?php if (!$temEstadias && empty($dadosPorUnidade)): ?>
    <div class="alert alert-light border">
        <i class="fa fa-info-circle text-muted"></i>
        Nenhum dado de estadia no período selecionado.
    </div>
<?php else: ?>
<div class="card shadow mb-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-bordered table-hover mb-0" style="font-size:.92em;">
                <thead class="thead-light">
                    <tr>
                        <th rowspan="2" class="align-middle">Unidade</th>
                        <th class="text-center" colspan="3">Quantidade</th>
                        <th class="text-right" rowspan="2" class="align-middle">Total R$<br><small>(encerradas)</small></th>
                        <th class="text-right" rowspan="2" class="align-middle">Tempo Cobrado<br><small>(valor_base + blocos)</small></th>
                        <th class="text-right" rowspan="2" class="align-middle">Adicionais R$<br><small>(encerradas)</small></th>
                        <th rowspan="2" class="align-middle">Formas de Pgto<br><small>(encerradas)</small></th>
                    </tr>
                    <tr>
                        <th class="text-center">Ativas</th>
                        <th class="text-center">Encerradas</th>
                        <th class="text-center">Canceladas</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $totEstAtivas     = 0;
                    $totEstEncerradas = 0;
                    $totEstCanceladas = 0;
                    $totEstValor      = 0.0;
                    $totEstTempo      = 0.0;
                    $totEstAdic       = 0.0;
                    $totEstFormas     = [];
                    foreach ($dadosPorUnidade as $unidade):
                        $totEstAtivas     += $unidade['estadias_ativas'];
                        $totEstEncerradas += $unidade['estadias_encerradas'];
                        $totEstCanceladas += $unidade['estadias_canceladas'];
                        $totEstValor      += $unidade['estadias_valor_total'];
                        $totEstTempo      += $unidade['estadias_tempo'];
                        $totEstAdic       += $unidade['estadias_adicionais'];
                        foreach ($unidade['estadias_por_forma'] as $fn => $fv) {
                            $totEstFormas[$fn] = ($totEstFormas[$fn] ?? 0) + $fv;
                        }
                    ?>
                    <tr>
                        <td class="font-weight-bold"><?php echo h($unidade['nome']); ?></td>
                        <td class="text-center">
                            <?php if ($unidade['estadias_ativas'] > 0): ?>
                                <span class="badge badge-primary"><?php echo $unidade['estadias_ativas']; ?></span>
                            <?php else: ?>
                                <span class="text-muted">—</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <?php if ($unidade['estadias_encerradas'] > 0): ?>
                                <span class="badge badge-success"><?php echo $unidade['estadias_encerradas']; ?></span>
                            <?php else: ?>
                                <span class="text-muted">—</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <?php if ($unidade['estadias_canceladas'] > 0): ?>
                                <span class="badge badge-danger"><?php echo $unidade['estadias_canceladas']; ?></span>
                            <?php else: ?>
                                <span class="text-muted">—</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-right">
                            <?php if ($unidade['estadias_valor_total'] > 0): ?>
                                R$ <?php echo $this->Alv->tratarValor($unidade['estadias_valor_total'], 'pt'); ?>
                            <?php else: ?>
                                <span class="text-muted">—</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-right">
                            <?php if ($unidade['estadias_tempo'] > 0): ?>
                                R$ <?php echo $this->Alv->tratarValor($unidade['estadias_tempo'], 'pt'); ?>
                            <?php else: ?>
                                <span class="text-muted">—</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-right">
                            <?php if ($unidade['estadias_adicionais'] > 0): ?>
                                R$ <?php echo $this->Alv->tratarValor($unidade['estadias_adicionais'], 'pt'); ?>
                            <?php else: ?>
                                <span class="text-muted">—</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if (empty($unidade['estadias_por_forma'])): ?>
                                <span class="text-muted">—</span>
                            <?php else: ?>
                                <?php foreach ($unidade['estadias_por_forma'] as $fNome => $fValor): ?>
                                    <div class="d-flex justify-content-between">
                                        <small><?php echo h($fNome); ?></small>
                                        <small class="ml-3 font-weight-bold">
                                            R$ <?php echo $this->Alv->tratarValor($fValor, 'pt'); ?>
                                        </small>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr class="font-weight-bold table-secondary">
                        <td>TOTAL</td>
                        <td class="text-center"><?php echo $totEstAtivas; ?></td>
                        <td class="text-center"><?php echo $totEstEncerradas; ?></td>
                        <td class="text-center"><?php echo $totEstCanceladas; ?></td>
                        <td class="text-right">R$ <?php echo $this->Alv->tratarValor($totEstValor, 'pt'); ?></td>
                        <td class="text-right">R$ <?php echo $this->Alv->tratarValor($totEstTempo, 'pt'); ?></td>
                        <td class="text-right">R$ <?php echo $this->Alv->tratarValor($totEstAdic, 'pt'); ?></td>
                        <td>
                            <?php foreach ($totEstFormas as $fNome => $fValor): ?>
                                <div class="d-flex justify-content-between">
                                    <small><?php echo h($fNome); ?></small>
                                    <small class="ml-3">R$ <?php echo $this->Alv->tratarValor($fValor, 'pt'); ?></small>
                                </div>
                            <?php endforeach; ?>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- ============================================================ -->
<!-- CONSOLIDADO GERAL DO PERÍODO                                  -->
<!-- ============================================================ -->
<?php
$totalGeralValor = $totalOrdersValor + $totEstValor;
?>
<hr class="my-4">
<h5 class="text-uppercase text-muted mb-3">
    <i class="fa fa-chart-bar"></i> Consolidado Geral do Período
</h5>
<div class="card shadow mb-4">
    <div class="card-body p-0">
        <table class="table table-bordered mb-0">
            <thead class="thead-light">
                <tr>
                    <th>Origem</th>
                    <th class="text-center" style="width:120px">Qtd</th>
                    <th class="text-right" style="width:180px">Total R$</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><i class="fa fa-ticket-alt text-success"></i> Passaportes — Vendas do Site</td>
                    <td class="text-center"><?php echo $totalOrdersPedidos; ?></td>
                    <td class="text-right">R$ <?php echo $this->Alv->tratarValor($totalOrdersValor, 'pt'); ?></td>
                </tr>
                <tr>
                    <td><i class="fa fa-clock text-primary"></i> Estadias — Tempo cobrado (encerradas)</td>
                    <td class="text-center"><?php echo $totEstEncerradas; ?></td>
                    <td class="text-right">R$ <?php echo $this->Alv->tratarValor($totEstTempo, 'pt'); ?></td>
                </tr>
                <tr>
                    <td><i class="fa fa-plus-circle text-secondary"></i> Estadias — Adicionais (encerradas)</td>
                    <td class="text-center">—</td>
                    <td class="text-right">R$ <?php echo $this->Alv->tratarValor($totEstAdic, 'pt'); ?></td>
                </tr>
            </tbody>
            <tfoot>
                <tr class="font-weight-bold" style="background:#d4edda; font-size:1.1em;">
                    <td><i class="fa fa-check-circle text-success"></i> TOTAL GERAL</td>
                    <td class="text-center">—</td>
                    <td class="text-right text-success">
                        R$ <?php echo $this->Alv->tratarValor($totalOrdersValor + $totEstTempo + $totEstAdic, 'pt'); ?>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
