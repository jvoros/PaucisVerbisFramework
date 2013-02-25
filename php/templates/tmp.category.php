<? // requires array('tag' => 'tag name', 'cardList' = 'html for list of cards')  ?>
<div id='cat-<?= $tag ?>'>
	<h1><?= $tag ?></h1>
	<input class ='search' />
	<i class='icon-search icon-large'></i>
	<ul class='list navlist'>
		<?= $cardList ?>
	</ul>
</div>