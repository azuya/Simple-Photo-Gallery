<ul id="galleries">
<?php foreach ($galleries as $gallery): ?>
	<li <?php if ($count % Kohana::config('sigal.columns') == 0) echo 'class="newline"'; ?> >
		<div class="gallery">
			<?php
			// TODO: anchor with routes
			$photo = $album->thumbnail();
			echo HTML::anchor(
				'album/view/'.$album->slug,
				HTML::image('photo/thumbnail/'.$album->slug.'/'.$photo->filename, $album->name, TRUE));
			?>
			<p><?php echo $album->name; ?></p>
		</div>
	</li>
	<?php endforeach; ?>
</ul>