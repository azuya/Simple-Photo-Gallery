<?php
$count = 1; 
$route = Route::get('sigal-backend');
$button = array('class' => 'button');
?>
<div id="sigal_gallery">
	<div class="sigal_add">
	<?php
	echo html::anchor(
		$route->uri(array(
			'controller' => 'image',
			'action' => 'add',
			'id' => $gallery->id)),
		__('Add an image'),
		$button);
	?>
	</div>
	<ul>
	<?php foreach ($gallery->read_images() as $image): ?>
		<li <?php if ($count % Kohana::config('sigal.columns') == 0) echo 'class="newline"'; ?> >
			<div class="sigal_image">
				<?php echo HTML::image(Sigal::image_path($image, TRUE),	array('alt'=>$image->filename, 'rel'=>'image')); ?>
				<div class="sigal_caption">
					<p class="sigal_name"><?php echo $image->name; ?></p>
					<p class="sigal_description"><?php echo $image->description; ?></p>
					<p class="sigal_order"><?php echo $image->order; ?></p>
				</div>
				<div class="sigal_actions">
				<?php
				echo HTML::anchor(
					$route->uri(array(
						'controller' => 'image',
						'action' => 'delete',
						'id' => $image->id)),
					__('Delete'),
					$button);
				echo HTML::anchor(
					$route->uri(array(
						'controller' => 'image',
						'action' => 'edit',
						'id' => $image->id)),
					__('Edit'),
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