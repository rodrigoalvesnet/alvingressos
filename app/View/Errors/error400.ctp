<?php echo $this->element('site/navbar'); ?>
<section id="info" class="section bg-grey">
	<div class="container">
		<div class="section-header">
			<h2 class="section-title">Página não Encontrada :(</h2>
			<span>Página não Encontrada :(</span>
		</div>
		<div class="row justify-content-center">
			<div class="col-lg-10 text-center">
				<strong>Erro: </strong>
				<?php printf(
					__d('cake', 'O endereço requisitado foi %s mas ele não existe :('),
					"<strong>'{$url}'</strong>"
				);
				if (Configure::read('debug') > 0) :
					echo $this->element('exception_stack_trace');
				endif;
				?>
			</div>
		</div>
	</div>
</section>