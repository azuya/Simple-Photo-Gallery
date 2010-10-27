<?php $count = 0; ?>
<div id="gallery">
	<p class="pages">Page:</p>
	<ul>
	<?php foreach ($images as $image): ?>
		<li <?php if ($count % Kohana::config('sigal.columns') == 0) echo 'class="newline"'; ?> >
			<div class="image">
				<?php
				echo html::file_anchor(
						Sigal::full_path($image),
						HTML::image(Sigal::full_path($image, TRUE),	array('alt'=>$image->filename, 'rel'=>'image')),
						array('rel' => $image->gallery->name, 'title' => $image->name)
					 );
				?>
				<div class="caption">
					<p><?php echo $image->name; ?></p>
					<p><?php echo $image->description; ?></p>
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