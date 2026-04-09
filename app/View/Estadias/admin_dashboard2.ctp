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

<div class="card">
  <?php
  echo $this->Form->create('Filtro');
  ?>
  <div class="card-body">
    <div class="row">
      <div class="col-lg-2">
        <label>Data inicial</label>
        <?php
        echo $this->Form->text(
          'data_inicial',
          array(
            'type'     => 'date',
            'class'    => 'form-control',
            'required' => true
          )
        );
        ?>
      </div>
      <div class="col-lg-2">
        <label>Data final</label>
        <?php
        echo $this->Form->text(
          'data_final',
          array(
            'type'     => 'date',
            'class'    => 'form-control',
            'required' => true
          )
        );
        ?>
      </div>
      <div class="col-lg-3">
        <label>Unidade</label>
        <?php
        echo $this->Form->select(
          'unidade_id',
          $unidades,
          array(
            'class'    => 'form-control',
            'empty'    => 'Todas as unidades',
            'default'  => $unidadeId ?: ''
          )
        );
        ?>
      </div>
    </div>
  </div>
  <div class="card-footer border-top">
    <?php
    echo $this->Form->submit(
      'Pesquisar',
      array(
        'type'      => 'submit',
        'class'     => 'btn btn-primary mx-1',
        'div'       => false,
        'label'     => false
      )
    );
    echo $this->Html->link(
      'Limpar',
      array(
        'admin' => true,
        'controller' => 'estadias',
        'action' => 'dashboard2',
        'limpar' => 1
      ),
      array(
        'class' => 'btn btn-outline-secondary mx-1',
        'escape' => false
      )
    );
    ?>
  </div>
  <?php echo $this->Form->end(); ?>
</div>

<div class="row">
  <div class="col-md-3 col-sm-6 mb-3">
    <div class="card border-left-primary shadow h-100">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <h6 class="text-uppercase text-muted mb-1">
              Estadias Ativas <?php echo empty($this->data) ? '(Hoje)' : ''; ?>
            </h6>
            <h3 class="mb-0 font-weight-bold text-primary">
              <?php echo $results['abertas']['quantidade']; ?>
            </h3>
          </div>
          <div class="text-primary">
            <i class="fa fa-clock fa-2x"></i>
          </div>
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
              Encerradas <?php echo empty($this->data) ? '(Hoje)' : ''; ?>
            </h6>
            <h3 class="mb-0 font-weight-bold text-success">
              <?php echo $results['encerradas']['quantidade']; ?>
            </h3>
          </div>
          <div class="text-success">
            <i class="fa fa-check-circle fa-2x"></i>
          </div>
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
              Canceladas <?php echo empty($this->data) ? '(Hoje)' : ''; ?>
            </h6>
            <h3 class="mb-0 font-weight-bold text-danger">
              <?php echo $results['canceladas']['quantidade']; ?>
            </h3>
          </div>
          <div class="text-danger">
            <i class="fa fa-times-circle fa-2x"></i>
          </div>
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
              Faturamento <?php echo empty($this->data) ? '(Hoje)' : ''; ?>
            </h6>
            <h3 class="mb-0 font-weight-bold text-success">
              R$ <?php echo $this->Alv->tratarValor($results['valor_total'], 'pt'); ?>
            </h3>
          </div>
          <div class="text-success">
            <i class="fa fa-money-circle fa-2x"></i>
          </div>
        </div>
      </div>
    </div>
  </div>

</div>

<div class="row">
  <?php foreach ($results['unidades'] as $unidade) { ?>
    <div class="col-md-4 col-sm-6 mb-4">
      <div class="card shadow h-100 border-left-info">
        <div class="card-body">

          <!-- Cabeçalho -->
          <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0 font-weight-bold text-info">
              <?php echo $unidade['nome']; ?><?php echo empty($this->data) ? ' (Hoje)' : ''; ?>
            </h5>
            <i class="fa fa-store fa-lg text-info"></i>
          </div>

          <!-- Conteúdo -->
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

        <!-- Rodapé opcional -->
        <!-- <div class="card-footer bg-light text-right">
          <a href="#" class="text-info">
            Ver detalhes <i class="fa fa-arrow-right"></i>
          </a>
        </div> -->
      </div>
    </div>
  <?php } ?>

</div>

<?php
// ============================================================
// Cálculo dos totais de adicionais (para o consolidado)
// ============================================================
$totalQtdAdic = 0;
$totalValAdic = 0;
if (!empty($vendasPorAdicional)) {
    foreach ($vendasPorAdicional as $row) {
        $totalQtdAdic += (float)$row[0]['qtd'];
        $totalValAdic += (float)$row[0]['total'];
    }
}

$totalQtdLote = 0;
$totalValLote = 0;
if (!empty($vendasPorLote)) {
    foreach ($vendasPorLote as $row) {
        $totalQtdLote += (int)$row[0]['qtd'];
        $totalValLote += (float)$row[0]['total'];
    }
}

$totalGeral = $totalTempoEstadias + $totalValAdic + $totalValorOrders;
?>

<!-- ============================================================ -->
<!-- VENDAS DO SITE (INGRESSOS)                                    -->
<!-- ============================================================ -->
<hr class="my-4">
<h5 class="text-uppercase text-muted mb-3">
    <i class="fa fa-globe"></i> Vendas do Site (Ingressos)
    <small class="text-muted font-weight-normal">
        — <?php echo date('d/m/Y', strtotime($dataInicial)); ?>
        <?php if ($dataInicial !== $dataFinal) { ?>
            até <?php echo date('d/m/Y', strtotime($dataFinal)); ?>
        <?php } ?>
    </small>
</h5>

<div class="row mb-3">
    <div class="col-md-3 col-sm-6 mb-3">
        <div class="card border-left-info shadow h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase text-muted mb-1">Pedidos Aprovados</h6>
                        <h3 class="mb-0 font-weight-bold text-info">
                            <?php echo (int)$totalPedidosOrders; ?>
                        </h3>
                    </div>
                    <div class="text-info">
                        <i class="fa fa-shopping-cart fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 col-sm-6 mb-3">
        <div class="card border-left-success shadow h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase text-muted mb-1">Total Vendido (Site)</h6>
                        <h3 class="mb-0 font-weight-bold text-success">
                            R$ <?php echo $this->Alv->tratarValor($totalValorOrders, 'pt'); ?>
                        </h3>
                    </div>
                    <div class="text-success">
                        <i class="fa fa-dollar-sign fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if (!empty($vendasPorLote)) { ?>
<div class="card shadow mb-4">
    <div class="card-header font-weight-bold">
        <i class="fa fa-list"></i> Resumo por Modalidade — Ingressos do Site
    </div>
    <div class="card-body p-0">
        <table class="table table-striped table-hover mb-0">
            <thead class="thead-light">
                <tr>
                    <th>Modalidade / Lote</th>
                    <th class="text-center" style="width:120px">Quantidade</th>
                    <th class="text-right" style="width:150px">Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($vendasPorLote as $lote) { ?>
                <tr>
                    <td><?php echo h($lote[0]['modalidade']); ?></td>
                    <td class="text-center">
                        <span class="badge badge-info"><?php echo (int)$lote[0]['qtd']; ?></span>
                    </td>
                    <td class="text-right">
                        <?php if ((float)$lote[0]['total'] == 0) { ?>
                            <span class="text-muted">R$ 0,00</span>
                        <?php } else { ?>
                            R$ <?php echo $this->Alv->tratarValor((float)$lote[0]['total'], 'pt'); ?>
                        <?php } ?>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
            <tfoot>
                <tr class="font-weight-bold table-light">
                    <td>TOTAL</td>
                    <td class="text-center"><?php echo $totalQtdLote; ?></td>
                    <td class="text-right">R$ <?php echo $this->Alv->tratarValor($totalValLote, 'pt'); ?></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
<?php } else { ?>
<div class="alert alert-light border mb-4">
    <i class="fa fa-info-circle text-muted"></i>
    Nenhum ingresso vendido pelo site no período selecionado.
</div>
<?php } ?>

<!-- ============================================================ -->
<!-- ADICIONAIS DE ESTADIAS                                        -->
<!-- ============================================================ -->
<hr class="my-4">
<h5 class="text-uppercase text-muted mb-3">
    <i class="fa fa-plus-circle"></i> Adicionais — Estadias Encerradas
</h5>

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
                <tr>
                    <td>
                        <i class="fa fa-globe text-info"></i>
                        Ingressos — Vendas do site
                    </td>
                    <td class="text-center"><?php echo (int)$totalPedidosOrders; ?></td>
                    <td class="text-right">
                        R$ <?php echo $this->Alv->tratarValor($totalValorOrders, 'pt'); ?>
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
