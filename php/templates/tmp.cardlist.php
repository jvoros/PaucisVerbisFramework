<? // requires array('cards' => array(of Cards objects)) ?>
<? foreach ($cards as $card): ?> 
	<li>
		<p class='title' data-detail='<?= $card->getSlug(); ?>'><?= $card->getTitle(); ?></p>
		<span class='date'><?= $card->getDate(); ?></span>
		<span class='tags'>
			<? foreach	($card->getTags() as $tag) { echo $tag . ' / '; } ?>
		</span>
	</li>
<? endforeach; ?>
