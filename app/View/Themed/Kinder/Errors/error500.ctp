<?php echo $this->element('site/navbar'); ?>
<section id="info" class="section bg-grey">
	<div class="container">
		<div class="section-header">
			<h2 class="section-title"><?php echo $message; ?></h2>
			<span><?php echo $message; ?></span>
		</div>
		<div class="row justify-content-center">
			<div class="col-lg-10 text-center">
				<p>A página que você está procurando não existe ou foi movida.</p>
				<p><a href="<?php echo $this->Html->url('/'); ?>" class="btn btn-common btn-effect">Voltar para a página inicial</a></p><br />
				<strong><?php echo __d('cake', 'Error'); ?>: </strong>
				<?php echo __d('cake', 'An Internal Error Has Occurred.'); ?>
				<?php
				if (Configure::read('debug') > 0) :
					echo $this->element('exception_stack_trace');
				endif;
				?>
			</div>
		</div>
	</div>
</section>