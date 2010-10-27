<?php $count = 1; ?>
<ul id="galleries">
<?php foreach ($galleries as $gallery): ?>
	<li <?php if ($count % Kohana::config('sigal.columns') == 0) echo 'class="newline"'; ?> >
		<div class="gallery">
			<?php
			echo	HTML::anchor(
						Route::get('sigal-frontend')->uri(array('controller'=>'gallery', 'action'=>'view', 'id'=>$gallery->slug)),
						HTML::image(
							/*
							// Routing for the image-controller
							Route::url(
								'sigal-image',
								array('action'=>'thumbnail', 'slug'=>$gallery->slug, 'file'=>'fubar')//$gallery->thumbnail()->filename)
							),
							 */
							Kohana::config('sigal.paths.galleries').$gallery->thumbnail()->filename,
							array('alt'=>$gallery->name),
							TRUE
						)
					);
			?>
			<p><?php echo $gallery->name; ?></p>
		</div>
	</li>
	<?php
		$count++;
		endforeach;
		echo View::factory('profiler/stats');
	?>
</ul>