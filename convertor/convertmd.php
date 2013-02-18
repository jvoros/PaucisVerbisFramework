<?
include_once "../php/markdown.php";

$md = stripslashes($_GET['md']);

$my_html = Markdown($md);

echo $my_html;
?>