<?php echo $this->element('site/navbar'); ?>
<section id="info" class="section bg-grey">
	<div class="container">
		<div class="section-header">
			<h2 class="section-title"><?php echo $message; ?></h2>
			<span><?php echo $message; ?></span>
		</div>
		<div class="row justify-content-center">
			<div class="col-lg-10 text-center">
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