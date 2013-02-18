<?

$slug = $_GET['slug'];
$title = $_GET['title'];
$date = $_GET['date'];
$tags = $_GET['tags'];
$md = stripslashes($_GET['md']);

$savedata = "title: ". $title . "\nslug: ". $slug . "\ndate: " . $date . "\ntags:\n" . $tags  . "\n+++++\n\n" . $md;

$myFile = "../md/" . $slug . ".md";
$fh = fopen($myFile, 'w');
fwrite($fh, $savedata);
fclose($fh);

echo "success";


?>