<!DOCTYPE html>
<html lang="pt-br">

<head>
	<?php echo $this->Html->charset(); ?>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />

	<?php
	echo $this->Html->meta(
		'title',
		Configure::read('Sistema.title') . ' | ' . $this->fetch('title')
	);
	?>
	<title><?php echo Configure::read('Sistema.title') . ' | ' . $this->fetch('title'); ?></title>

	<?php
	echo $this->Html->meta('icon');

	echo $this->Html->css(array(
		'admin/style.min',
		'admin/jquery-ui.min',
		'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.standalone.min.css',
		'https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css',
		'tagify/tagify',
		'admin'
	));

	
	echo $this->Html->script(array(
		'tagify/tagify',
		'tagify/tagify.polyfills.min'
	));

	echo $this->fetch('meta');
	echo $this->fetch('css');
	echo $this->fetch('script');
	?>
</head>

<body>
	<div id="main-wrapper" data-layout="vertical" data-navbarbg="skin5" data-sidebartype="full" data-sidebar-position="absolute" data-header-position="absolute" data-boxed-layout="full">
		<?php echo $this->element('header'); ?>
		<?php echo $this->element('sidebar/menus'); ?>
		<div class="page-wrapper">
			<?php echo $this->element('breadcrumb'); ?>
			<div class="container-fluid">
				<?php echo $this->Flash->render(); ?>
				<?php echo $this->fetch('content'); ?>
			</div>
			<?php echo $this->element('footer'); ?>
		</div>
	</div>
	<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
	<?php
	echo $this->Html->script(array(
		'/assets/libs/jquery/dist/jquery.min',
		'admin/jquery-ui.min',
		'admin/moment.min.js',
		'/assets/libs/bootstrap/dist/js/bootstrap.bundle.min',
		'/assets/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min',
		'/assets/extra-libs/sparkline/sparkline',
		'/assets/js/bootstrap-datetimepicker.min.js',
		'admin/waves',
		'admin/sidebarmenu',
		'admin/custom.min',
		'admin/jquery.maskedinput.min',
		'admin/jquery.maskMoney.min',
		'//cdn.ckeditor.com/4.22.1/full/ckeditor.js',
		'admin/locale_moment_pt-br',
		'validate/jquery.validate.min',
		'validate/localization/messages_pt_BR',
		'https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.3/js/jquery.tablesorter.min.js',
		'https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.3/js/jquery.tablesorter.widgets.min.js',
		'https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js',
		'alv'
	));

	echo $this->fetch('scriptBottom');
	echo $this->Js->writeBuffer(); // Write cached scripts
	echo $this->element('sql_dump')
	?>
</body>

</html>