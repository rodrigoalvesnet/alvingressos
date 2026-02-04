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
        <label>Data inicial </label>
        <?php
        echo $this->Form->text(
          'data_inicial',
          array(
            'type' => 'date',
            'label' => 'Data',
            'class' => 'form-control',
            'required' => true
          )
        );
        ?>
      </div>
      <div class="col-lg-2">
        <label>Data inicial </label>
        <?php
        echo $this->Form->text(
          'data_final',
          array(
            'type' => 'date',
            'label' => 'Data',
            'class' => 'form-control',
            'required' => true
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
        'action' => 'dashboard',
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
              Estadias Ativas
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
              Encerradas
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
              Canceladas
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
              Faturamento
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
              <?php echo $unidade['nome']; ?>
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