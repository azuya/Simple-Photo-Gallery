<?php $count = 0; ?>
<div id="gallery">
	<p class="pages">Page:</p>
	<ul>
	<?php foreach ($images as $image): ?>
		<li <?php if ($count % Kohana::config('sigal.columns') == 0) echo 'class="newline"'; ?> >
			<div class="image">
				<?php
				echo	html::file_anchor(
							Kohana::config('sigal.paths.galleries').$image->gallery->slug.'/'.$image->filename,
							HTML::image(
								Kohana::config('sigal.paths.galleries').$image->gallery->slug.'/thumb_'.$image->filename,
								array('alt'=>$image->filename),
								FALSE
							),
							array('rel' => $image->gallery->name, 'title' => $image->name)
						);
				/*
				// This was used for the frontend image-controller
				echo	html::file_anchor(
							Route::get('sigal-image')->uri(array(
								'action'=>'view',
								'slug'=>$image->gallery->slug,
								'file'=>$image->filename)
							),
							HTML::image(
								Route::get('sigal-image')->uri(array(
									'action'=>'thumbnail',
									'slug'=>$image->gallery->slug,
									'file'=>$image->filename)
								),
								array('alt'=>$image->filename),
								TRUE
							),
							array('rel' => $image->gallery->name, 'title' => $image->name)
						);
				 */
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