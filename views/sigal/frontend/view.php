<?php $count = 0; ?>
<div id="gallery">
	<p class="pages">Page:</p>
	<ul>
	<?php foreach ($photos as $photo): ?>
		<li <?php if ($count % Kohana::config('sigal.columns') == 0) echo 'class="newline"'; ?> >
			<div class="photo">
				<?php
				// TODO: Routes
				echo	html::file_anchor('photo/view/'.$album->name.'/'.$photo->filename,
						html::image('photo/thumbnail/'.$album->slug.'/'.$photo->filename, $photo->filename, TRUE),
						array('rel' => $album->name, 'title' => $photo->name));
				?>
				<div class="caption">
				<p><?php echo $photo->name; ?></p>
				<p><?php echo $photo->description; ?></p>
			</div>
		</li>
	<?php endforeach; ?>
	</ul>
</div>