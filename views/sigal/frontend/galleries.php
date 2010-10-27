<?php $count = 1; ?>
<ul id="galleries">
<?php foreach ($galleries as $gallery): ?>
	<li <?php if ($count % Kohana::config('sigal.columns') == 0) echo 'class="newline"'; ?> >
		<div class="gallery">
			<?php
			// TODO: anchor with routes
			$photo = $gallery->thumbnail();
			echo HTML::anchor(
				'album/view/'.$gallery->slug,
				HTML::image(
					'photo/thumbnail/'.$gallery->slug.'/'.$photo->filename,
					array('alt'=>$gallery->name),
					TRUE
				));
			?>
			<p><?php echo $gallery->name; ?></p>
		</div>
	</li>
	<?php
		$count++;
		endforeach;
	?>
</ul>