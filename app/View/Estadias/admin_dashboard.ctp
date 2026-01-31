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
              1
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
              Encerradas Hoje
            </h6>
            <h3 class="mb-0 font-weight-bold text-success">
              <?php echo count($results['encerradas']) ?>
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
    <div class="card border-left-warning shadow h-100">
      <div class="card-body">
        <div>
          <h6 class="text-uppercase text-muted mb-1">
            Tempo Médio
          </h6>
          <h3 class="mb-0 font-weight-bold text-warning">
            1h 35min
          </h3>
        </div>
      </div>
    </div>
  </div>

  <div class="col-md-3 col-sm-6 mb-3">
    <div class="card border-left-danger shadow h-100">
      <div class="card-body">
        <div>
          <h6 class="text-uppercase text-muted mb-1">
            Faturamento Hoje
          </h6>
          <h3 class="mb-0 font-weight-bold text-danger">
            R$ <?php echo $this->Alv->tratarValor($results['valor_total'], 'pt'); ?>
          </h3>
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
        <div class="card-footer bg-light text-right">
          <a href="#" class="text-info">
            Ver detalhes <i class="fa fa-arrow-right"></i>
          </a>
        </div>
      </div>
    </div>
  <?php } ?>

</div>