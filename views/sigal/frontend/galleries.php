<?php $count = 1; ?>
<ul id="galleries">
<?php foreach ($galleries as $gallery): ?>
	<li <?php if ($count % Kohana::config('sigal.columns') == 0) echo 'class="newline"'; ?> >
		<div class="gallery">
			<?php
			echo	HTML::anchor(
						Route::get('sigal-frontend')->uri(array('controller'=>'gallery', 'action'=>'view', 'id'=>$gallery->slug)),
						HTML::image(
							Sigal::full_path($gallery->thumbnail()),
							array('alt'=>$gallery->name)
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