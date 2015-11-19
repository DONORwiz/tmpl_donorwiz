<?php
// get theme configuration
include($this['path']->path('layouts:theme.config.php'));

?>
<!DOCTYPE HTML>
<html style="background:#e5e5e5;" lang="<?php echo $this['config']->get('language'); ?>" dir="<?php echo $this['config']->get('direction'); ?>"  data-config='<?php echo $this['config']->get('body_config','{}'); ?>'>

<head>
<?php echo $this['template']->render('head'); ?>
</head>

<body class="<?php echo $this['config']->get('body_classes'); ?> layout-donorwiz">
	<?php include($this['path']->path('theme:styles').'/common/tracking_code.php'); ?>
	<?php include($this['path']->path('theme:styles').'/common/header.php'); ?>

	<?php if ($this['widgets']->count('fullscreen-slideshow')) : ?>
	<div id="fullscreen-slideshow" class="fullscreen-slideshow">
		<?php echo $this['widgets']->render('fullscreen-slideshow'); ?>
	</div>
	<?php endif; ?>
	
	<div class="uk-container uk-container-center uk-margin-large-bottom">
		
		<?php if ($this['widgets']->count('top-a')) : ?>
		<section class="<?php echo $grid_classes['top-a']; echo $display_classes['top-a']; ?>" data-uk-grid-match="{target:'> div > .uk-panel'}" data-uk-grid-margin><?php echo $this['widgets']->render('top-a', array('layout'=>$this['config']->get('grid.top-a.layout'))); ?></section>
		<?php endif; ?>

		<?php if ($this['widgets']->count('top-b')) : ?>
		<section class="<?php echo $grid_classes['top-b']; echo $display_classes['top-b']; ?>" data-uk-grid-match="{target:'> div > .uk-panel'}" data-uk-grid-margin><?php echo $this['widgets']->render('top-b', array('layout'=>$this['config']->get('grid.top-b.layout'))); ?></section>
		<?php endif; ?>

		<?php if ($this['widgets']->count('main-top + main-bottom + sidebar-a + sidebar-b') || $this['config']->get('system_output', true)) : ?>
		<div class="tm-middle uk-grid" data-uk-grid-match data-uk-grid-margin>

			<?php if ($this['widgets']->count('main-top + main-bottom') || $this['config']->get('system_output', true)) : ?>
			<div class="<?php echo $columns['main']['class'] ?>">

				<?php if ($this['widgets']->count('main-top')) : ?>
				<section class="<?php echo $grid_classes['main-top']; echo $display_classes['main-top']; ?>" data-uk-grid-match="{target:'> div > .uk-panel'}" data-uk-grid-margin><?php echo $this['widgets']->render('main-top', array('layout'=>$this['config']->get('grid.main-top.layout'))); ?></section>
				<?php endif; ?>

				<?php if ($this['config']->get('system_output', true)) : ?>
				<main class="tm-content">

					<?php if ($this['widgets']->count('breadcrumbs')) : ?>
					<?php echo $this['widgets']->render('breadcrumbs'); ?>
					<?php endif; ?>

					<?php echo $this['template']->render('content'); ?>

				</main>
				<?php endif; ?>

				<?php if ($this['widgets']->count('main-bottom')) : ?>
				<section class="<?php echo $grid_classes['main-bottom']; echo $display_classes['main-bottom']; ?>" data-uk-grid-match="{target:'> div > .uk-panel'}" data-uk-grid-margin><?php echo $this['widgets']->render('main-bottom', array('layout'=>$this['config']->get('grid.main-bottom.layout'))); ?></section>
				<?php endif; ?>

			</div>
			<?php endif; ?>

            <?php foreach($columns as $name => &$column) : ?>
            <?php if ($name != 'main' && $this['widgets']->count($name)) : ?>
            <aside class="<?php echo $column['class'] ?>"><?php echo $this['widgets']->render($name) ?></aside>
            <?php endif ?>
            <?php endforeach ?>

		</div>
		<?php endif; ?>

	

	</div>
	
	<?php if ($this['widgets']->count('bottom-grid-a + bottom-grid-b + bottom-grid-c + bottom-grid-d')) : ?>
	
	<div id="bottom-grid" class="uk-container uk-container-center uk-margin-large-top uk-margin-large-bottom">
	
		<?php if ($this['widgets']->count('bottom-grid-a')) : ?>
			<section id="bottom-grid-a" class="<?php echo $grid_classes['bottom-grid-a']; echo $display_classes['bottom-grid-a']; ?>" data-uk-grid-match="{target:'> div > .uk-panel'}" data-uk-grid-margin><?php echo $this['widgets']->render('bottom-grid-a', array('layout'=>$this['config']->get('grid.bottom-grid-a.layout'))); ?></section>
		<?php endif; ?>
		
		<?php if ($this['widgets']->count('bottom-grid-b')) : ?>
			<section id="bottom-grid-b" class="<?php echo $grid_classes['bottom-grid-b']; echo $display_classes['bottom-grid-b']; ?>" data-uk-grid-match="{target:'> div > .uk-panel'}" data-uk-grid-margin><?php echo $this['widgets']->render('bottom-grid-b', array('layout'=>$this['config']->get('grid.bottom-grid-b.layout'))); ?></section>
		<?php endif; ?>

		<?php if ($this['widgets']->count('bottom-grid-c')) : ?>
			<section id="bottom-grid-c" class="<?php echo $grid_classes['bottom-grid-c']; echo $display_classes['bottom-grid-c']; ?>" data-uk-grid-match="{target:'> div > .uk-panel'}" data-uk-grid-margin><?php echo $this['widgets']->render('bottom-grid-c', array('layout'=>$this['config']->get('grid.bottom-grid-c.layout'))); ?></section>
		<?php endif; ?>

		<?php if ($this['widgets']->count('bottom-grid-d')) : ?>
			<section id="bottom-grid-d" class="<?php echo $grid_classes['bottom-grid-d']; echo $display_classes['bottom-grid-d']; ?>" data-uk-grid-match="{target:'> div > .uk-panel'}" data-uk-grid-margin><?php echo $this['widgets']->render('bottom-grid-d', array('layout'=>$this['config']->get('grid.bottom-grid-d.layout'))); ?></section>
		<?php endif; ?>
	
	</div>
	
	<?php endif; ?>

	
		<?php if ($this['widgets']->count('bottom-a')) : ?>
		<div class="uk-container uk-container-center">
		<section class="<?php echo $grid_classes['bottom-a']; echo $display_classes['bottom-a']; ?>" data-uk-grid-match="{target:'> div > .uk-panel'}" data-uk-grid-margin><?php echo $this['widgets']->render('bottom-a', array('layout'=>$this['config']->get('grid.bottom-a.layout'))); ?></section>
		</div>
		<?php endif; ?>
		
		

		<?php if ($this['widgets']->count('fullscreen-bottom')) : ?>
		<div id="fullscreen-bottom" class="fullscreen primary">
		
			<div class="uk-container uk-container-center">
			<section class="uk-grid" data-uk-grid-match="{target:'> div > .uk-panel'}" data-uk-grid-margin><?php echo $this['widgets']->render('fullscreen-bottom', array('layout'=>$this['config']->get('grid.bottom-b.layout'))); ?></section>
			</div>
		
		</div>
		<?php endif; ?>
		
		<?php if ($this['widgets']->count('fullscreen-connect')) : ?>
		<div id="fullscreen-connect" class="fullscreen primary">
		
			<div class="uk-container uk-container-center">
			<section class="uk-grid" data-uk-grid-match="{target:'> div > .uk-panel'}" data-uk-grid-margin><?php echo $this['widgets']->render('fullscreen-connect', array('layout'=>$this['config']->get('grid.bottom-b.layout'))); ?></section>
			</div>
		
		</div>
		<?php endif; ?>
		
		

		<?php if ($this['widgets']->count('bottom-b')) : ?>
		<div class="dw-bottom-b">
			<div class="uk-container uk-container-center">
				<section class="uk-margin-large-top <?php echo $grid_classes['bottom-b']; echo $display_classes['bottom-b']; ?>" data-uk-grid-match="{target:'> div > .uk-panel'}" data-uk-grid-margin><?php echo $this['widgets']->render('bottom-b', array('layout'=>$this['config']->get('grid.bottom-b.layout'))); ?></section>
			</div>
		</div>
		<?php endif; ?>
		

	
		<?php include($this['path']->path('theme:styles').'/common/footer.php'); ?>


	</div>


	
	<?php include($this['path']->path('theme:styles').'/common/offcanvas.php'); ?>

	<?php include($this['path']->path('theme:styles').'/common/intercom.php'); ?>

</body>
</html>