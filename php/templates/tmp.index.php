<?php 

$index = <<<EOF
<!DOCTYPE html>
<html>

<head>

	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
	
	<link rel="stylesheet" href="css/reset.css" type="text/css" />
	<link rel="stylesheet" href="css/font-awesome.min.css" type="text/css" />
	<link rel="stylesheet" href="css/style.css" type="text/css" />
	
	<script src="js/jquery-1.9.1.min.js" type="text/javascript"></script>
	<script src="js/fastclick.js" type="text/javascript"></script>
	<script src="js/list.min.js" type="text/javascript"></script>
	<script src="js/scripts.js" type="text/javascript" ></script>
	
</head>


<html>
<body>

<!-- NAV CARD -->
<nav id="navcard" class="card">

	<h1>Paucis Verbis</h1>

	<ul class="navlist">
		<li data-list="alllist">All Cards</li>
		<li data-list="catlist">Cards by Category</li>
		<li data-list="datelist">Cards by Date</li>
	</ul>
	
</nav>

<!-- INDEX CARD -->
<section id="indexcard" class="card">
<div id="alllist">

	<i class="icon-reorder icon-large navbutton"></i>

	<h1>All Cards</h1>

	<input class ="search" /><i class="icon-search icon-large"></i>
	
	<ul class="list navlist">
		{$allCardsList}
	</ul>
</div>
</section>

<!-- DETAIL CARD -->
<section id="detailcard" class="card slider">
	<i class="icon-arrow-left icon-large navbutton" id="backbutton" ></i>
</section>

<!-- LIST CARD -->
<section id="listcard" class="card slider">
	<i class="icon-arrow-left icon-large navbutton" id="backbutton" ></i>
</section>

<!-- OFF SCREEN MENUS -->
<section id="noshow">
	<div id="loader"><h1></h1><i class="icon-refresh icon-spin icon-4x"></i></div>
	<div id="datelist">
		<i class="icon-reorder icon-large navbutton"></i>
	
		<h1>By Date</h1>
	
		<input class ="search" /><i class="icon-search icon-large"></i>
		
		<ul class="list navlist">
			{$dateList}
	
		</ul>
	</div>
	<div id="catlist">
		<i class="icon-reorder icon-large navbutton"></i>
	
		<h1>Categories</h1>
	
		<input class ="search" /><i class="icon-search icon-large"></i>
		
		<ul class="list navlist">
			{$tagList}	
		</ul>
	</div>
</section>

</body>
</html>

EOF;

?>