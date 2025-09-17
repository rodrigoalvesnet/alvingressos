<?php if ($this->Paginator->numbers()) { ?>
	<div class="row" style="margin: 0;">
		<div class="col-md-4">
			<div class="dataTables_info">
				<?php
				echo $this->Paginator->counter(
					'Total de {:count} registros - Página {:page} de {:pages}'
				);
				?>
			</div>
		</div>
		<div class="col-md-8">
			<div>
				<ul class="pagination pull-right">
					<?php echo $this->Paginator->prev(
						'Anterior',
						array(
							'class' => 'paginate_button page-item',
							'tag' => 'li',
							'class ' => 'page-link',
							'escape' => false
						),
						'<a class="page-link">Anterior</a>',
						array(
							'tag' => 'li class="paginate_button page-item"',
						)
					); ?>
					<?php echo $this->Paginator->numbers(array(
						'class' => 'paginate_button page-item',
						'separator' => false,
						'tag' => 'li',
						'class ' => 'page-link',
						'currentTag' => 'a class="page-link"',
						'currentClass' => 'active'
					)); ?>
					<?php
					echo $this->Paginator->next(
						'Próximo',
						array(
							'class' => 'paginate_button page-item',
							'tag' => 'li',
							'class ' => 'page-link',
							'escape' => false
						),
						'<a class="page-link">Próximo</a>',
						array(
							'tag' => 'li class="paginate_button page-item"',
						)
					); ?>
				</ul>
			</div>
		</div>
	</div>
<?php } ?>