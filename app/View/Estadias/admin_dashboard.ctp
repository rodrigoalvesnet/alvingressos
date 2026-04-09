<div class="card">
  <?php echo $this->Form->create('Filtro'); ?>
  <div class="card-body">
    <div class="row">
      <div class="col-lg-2">
        <label>Data inicial</label>
        <?php echo $this->Form->text('data_inicial', ['type' => 'date', 'class' => 'form-control', 'required' => true]); ?>
      </div>
      <div class="col-lg-2">
        <label>Data final</label>
        <?php echo $this->Form->text('data_final', ['type' => 'date', 'class' => 'form-control', 'required' => true]); ?>
      </div>
      <div class="col-lg-3">
        <label>Unidade</label>
        <?php echo $this->Form->select('unidade_id', $unidades, ['class' => 'form-control', 'empty' => 'Todas as unidades', 'default' => $unidadeId ?: '']); ?>
      </div>
    </div>
  </div>
  <div class="card-footer border-top">
    <?php
    echo $this->Form->submit('Pesquisar', ['type' => 'submit', 'class' => 'btn btn-primary mx-1', 'div' => false, 'label' => false]);
    echo $this->Html->link('Limpar', ['admin' => true, 'controller' => 'estadias', 'action' => 'dashboard', 'limpar' => 1], ['class' => 'btn btn-outline-secondary mx-1', 'escape' => false]);
    ?>
  </div>
  <?php echo $this->Form->end(); ?>
</div>

<!-- ============================================================ -->
<!-- CARDS DE RESUMO                                               -->
<!-- ============================================================ -->
<div class="row mt-3">

  <div class="col-md-3 col-sm-6 mb-3">
    <div class="card border-left-primary shadow h-100">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <h6 class="text-uppercase text-muted mb-1">Estadias Ativas</h6>
            <h3 class="mb-0 font-weight-bold text-primary"><?php echo $results['abertas']['quantidade']; ?></h3>
          </div>
          <div class="text-primary"><i class="fa fa-clock fa-2x"></i></div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-md-3 col-sm-6 mb-3">
    <div class="card border-left-success shadow h-100">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <h6 class="text-uppercase text-muted mb-1">
              Encerradas
              <small class="d-block text-muted font-weight-normal">
                <?php echo date('d/m/Y', strtotime($dataInicial)); ?>
                <?php if ($dataInicial !== $dataFinal) echo ' até ' . date('d/m/Y', strtotime($dataFinal)); ?>
              </small>
            </h6>
            <h3 class="mb-0 font-weight-bold text-success"><?php echo $results['encerradas']['quantidade']; ?></h3>
          </div>
          <div class="text-success"><i class="fa fa-check-circle fa-2x"></i></div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-md-3 col-sm-6 mb-3">
    <div class="card border-left-danger shadow h-100">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <h6 class="text-uppercase text-muted mb-1">
              Canceladas
              <small class="d-block text-muted font-weight-normal">
                <?php echo date('d/m/Y', strtotime($dataInicial)); ?>
                <?php if ($dataInicial !== $dataFinal) echo ' até ' . date('d/m/Y', strtotime($dataFinal)); ?>
              </small>
            </h6>
            <h3 class="mb-0 font-weight-bold text-danger"><?php echo $results['canceladas']['quantidade']; ?></h3>
          </div>
          <div class="text-danger"><i class="fa fa-times-circle fa-2x"></i></div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-md-3 col-sm-6 mb-3">
    <div class="card border-left-success shadow h-100">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <h6 class="text-uppercase text-muted mb-1">
              Faturamento
              <small class="d-block text-muted font-weight-normal">
                <?php echo date('d/m/Y', strtotime($dataInicial)); ?>
                <?php if ($dataInicial !== $dataFinal) echo ' até ' . date('d/m/Y', strtotime($dataFinal)); ?>
              </small>
            </h6>
            <h3 class="mb-0 font-weight-bold text-success">
              R$ <?php echo $this->Alv->tratarValor($results['valor_total'], 'pt'); ?>
            </h3>
          </div>
          <div class="text-success"><i class="fa fa-dollar-sign fa-2x"></i></div>
        </div>
      </div>
    </div>
  </div>

</div>

<!-- ============================================================ -->
<!-- CARDS POR UNIDADE                                             -->
<!-- ============================================================ -->
<div class="row">
  <?php foreach ($results['unidades'] as $unidade) { ?>
    <div class="col-md-4 col-sm-6 mb-4">
      <div class="card shadow h-100 border-left-info">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0 font-weight-bold text-info"><?php echo h($unidade['nome']); ?></h5>
            <i class="fa fa-store fa-lg text-info"></i>
          </div>
          <div class="row text-center">
            <div class="col-4">
              <h4 class="mb-0"><?php echo $unidade['quantidade']; ?></h4>
              <small class="text-muted">Estadias</small>
            </div>
            <div class="col-4">
              <h4 class="mb-0"><?php echo $unidade['tempo']; ?></h4>
              <small class="text-muted">Tempo</small>
            </div>
            <div class="col-4">
              <h4 class="mb-0">R$ <?php echo $this->Alv->tratarValor($unidade['faturado'], 'pt'); ?></h4>
              <small class="text-muted">Total</small>
            </div>
          </div>
        </div>
      </div>
    </div>
  <?php } ?>
</div>

<!-- ============================================================ -->
<!-- ADICIONAIS DE ESTADIAS ENCERRADAS                             -->
<!-- ============================================================ -->
<hr class="my-4">
<h5 class="text-uppercase text-muted mb-3">
  <i class="fa fa-plus-circle"></i> Adicionais — Estadias Encerradas
</h5>

<?php
$totalQtdAdic = 0;
$totalValAdic = 0;
if (!empty($vendasPorAdicional)) {
    foreach ($vendasPorAdicional as $row) {
        $totalQtdAdic += (float)$row[0]['qtd'];
        $totalValAdic += (float)$row[0]['total'];
    }
}
?>

<?php if (!empty($vendasPorAdicional)) { ?>
<div class="card shadow mb-4">
  <div class="card-header font-weight-bold">
    <i class="fa fa-list"></i> Resumo por Adicional
  </div>
  <div class="card-body p-0">
    <table class="table table-striped table-hover mb-0">
      <thead class="thead-light">
        <tr>
          <th>Adicional</th>
          <th class="text-center" style="width:120px">Quantidade</th>
          <th class="text-right" style="width:150px">Total</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($vendasPorAdicional as $adic) { ?>
        <tr>
          <td><?php echo h($adic[0]['modalidade']); ?></td>
          <td class="text-center">
            <span class="badge badge-secondary">
              <?php echo number_format((float)$adic[0]['qtd'], 0, ',', '.'); ?>
            </span>
          </td>
          <td class="text-right">
            R$ <?php echo $this->Alv->tratarValor((float)$adic[0]['total'], 'pt'); ?>
          </td>
        </tr>
        <?php } ?>
      </tbody>
      <tfoot>
        <tr class="font-weight-bold table-light">
          <td>TOTAL</td>
          <td class="text-center"><?php echo number_format($totalQtdAdic, 0, ',', '.'); ?></td>
          <td class="text-right">R$ <?php echo $this->Alv->tratarValor($totalValAdic, 'pt'); ?></td>
        </tr>
      </tfoot>
    </table>
  </div>
</div>
<?php } else { ?>
<div class="alert alert-light border mb-4">
  <i class="fa fa-info-circle text-muted"></i>
  Nenhum adicional registrado em estadias encerradas no período selecionado.
</div>
<?php } ?>

<!-- ============================================================ -->
<!-- CONSOLIDADO TOTAL DO PERÍODO                                  -->
<!-- ============================================================ -->
<hr class="my-4">
<h5 class="text-uppercase text-muted mb-3">
  <i class="fa fa-chart-bar"></i> Consolidado Total do Período
</h5>

<?php $totalGeral = $totalTempoEstadias + $totalValAdic; ?>

<div class="card shadow mb-4">
  <div class="card-body p-0">
    <table class="table table-bordered mb-0">
      <thead class="thead-light">
        <tr>
          <th>Origem</th>
          <th class="text-center" style="width:120px">Qtd</th>
          <th class="text-right" style="width:180px">Total</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>
            <i class="fa fa-clock text-primary"></i>
            Estadias — Tempo cobrado
            <small class="text-muted">(valor_base + blocos extras)</small>
          </td>
          <td class="text-center"><?php echo $results['encerradas']['quantidade']; ?></td>
          <td class="text-right">
            R$ <?php echo $this->Alv->tratarValor($totalTempoEstadias, 'pt'); ?>
          </td>
        </tr>
        <tr>
          <td>
            <i class="fa fa-plus-circle text-secondary"></i>
            Estadias — Adicionais
          </td>
          <td class="text-center"><?php echo number_format($totalQtdAdic, 0, ',', '.'); ?></td>
          <td class="text-right">
            R$ <?php echo $this->Alv->tratarValor($totalValAdic, 'pt'); ?>
          </td>
        </tr>
      </tbody>
      <tfoot>
        <tr class="font-weight-bold" style="background:#d4edda; font-size:1.1em;">
          <td><i class="fa fa-check-circle text-success"></i> TOTAL GERAL</td>
          <td class="text-center">—</td>
          <td class="text-right text-success">
            R$ <?php echo $this->Alv->tratarValor($totalGeral, 'pt'); ?>
          </td>
        </tr>
      </tfoot>
    </table>
  </div>
</div>
