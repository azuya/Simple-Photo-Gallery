<?php $count = 0; ?>
<div id="sigal_galleries">
	<ul>
	<?php foreach ($galleries as $gallery): ?>
		<li <?php if ($count % Kohana::config('sigal.columns') == 0) echo 'class="newline"'; ?> >
			<div class="sigal_gallery">
				<?php
				echo	HTML::anchor(
							Route::get('sigal-frontend')->uri(array('action'=>'view', 'id'=>$gallery->slug)),
							HTML::image(
								Sigal::image_path($gallery->thumbnail()),
								array('alt'=>$gallery->name)
							)
						);
				?>
				<div class="sigal_caption">
					<p class="sigal_name"><?php echo $gallery->name; ?></p>
				</div>
			</div>
		</li>
	<? $count++; endforeach; ?>
	</ul>
	<hr class="sigal_clear" />
</div>
