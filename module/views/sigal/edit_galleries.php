<?php
$count = 1; 
$route = Route::get('sigal-backend');
$button = array('class' => 'button');
?>
<div id="sigal_galleries">
	<div class="sigal_add">
	<?php
	echo html::anchor(
		$route->uri(array(
			'controller' => 'gallery',
			'action' => 'create')),
		__('Create Gallery'),
		$button);
	?>
	</div>
	<ul>
	<?php foreach ($galleries as $gallery): ?>
		<li <?php if ($count % Kohana::config('sigal.columns') == 0) echo 'class="newline"'; ?> >
			<div class="sigal_gallery">
				<?php
				echo HTML::image(
						Sigal::image_path($gallery->thumbnail()),
						array('alt'=>$gallery->name)
					);
				?>
				<div class="sigal_caption">
					<p class="sigal_name"><?php echo $gallery->name; ?></p>
					<p class="sigal_order"><?php echo $gallery->order; ?></p>
				</div>
				<div class="sigal_actions">
				<?php
				echo HTML::anchor(
					$route->uri(array(
						'controller' => 'gallery',
						'action' => 'delete',
						'id' => $gallery->id)),
					__('Delete'),
					$button);
				echo HTML::anchor(
					$route->uri(array(
						'controller' => 'gallery',
						'action' => 'edit',
						'id' => $gallery->id)),
					__('Edit'),
					$button);
				echo HTML::anchor(
					$route->uri(array(
						'controller' => 'gallery',
						'action' => 'images',
						'id' => $gallery->id)),
					__('Edit images'),
					$button);
				?>
				</div>
			</div>
		</li>
	<?php
		$count++;
		endforeach;
		echo View::factory('profiler/stats');
	?>
	</ul>
</div>