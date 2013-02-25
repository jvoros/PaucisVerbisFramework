<? //  requires array('tags' => array(of tags)) ?>
<? foreach ($tags as $tag): ?>
<li><p class='title' data-detail='cat-<?= $tag; ?>'><?= $tag; ?></p></li>
<? endforeach; ?>
