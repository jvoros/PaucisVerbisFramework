<?php
/* INCLUDES */
// markdown and yaml libraries
include_once("markdown.php");
include_once("Spyc.php");

// quick and dirty array echoing
function echo_array($input, $name = null) {
	echo "<p><pre>";
	echo $name . "<br />";
	echo print_r($input);
	echo "</pre></p>";
}

///////////////////////////////////////////////////
/* CLASS IMPLEMENTATION */

// class implementation of this whole business
// uses Spyc and Markdown libraries, tightly coupled
// consider updating to use dependency injection 

class Card {
	
	private $header;
	private $html;
	
	public function __construct(array $header, $html) {
		$this->header = $header;
		$this->html = $html;
	}
	
	public function getHeader() {
		return $this->header;
	}
	
	public function getHTML() {
		return $this->html;
	}

	public function getSlug() {
		return $this->header['slug'];
	}
	
	public function getTags() {
		return $this->header['tags'];
	}
	
	public function getTitle() {
		return $this->header['title'];
	}
	
	public function getDate() {
		return $this->header['date'];
	}

}

class CardCatalog {
	
	// constants for file paths
	private $input_dir = "../md/"; // dependency
	private $output_dir = "../paucisverbis/"; //dependency
	
	// arrays that make up site pieces aka the decks
	private $allCards;
	private $tagCloud;
	private $tagMenus;
	
	public function __construct() { $this->buildDecks(); }
	
	// opens the file, splits up the header and the markdown and makes a Card
	private function splitMD($filename) {
		$openpath = $this->input_dir . $filename;
		$file = explode("+++++", file_get_contents($openpath));
		
		$header = Spyc::YAMLLoad($file[0]); // dependency
		$html = Markdown($file[1]); // dependency
		
		return new Card($header, $html); // dependency
	}
	
	// process the markdown files and build appropriate arrays	
	private function buildDecks() {
		// scan directory, match *.md files only
		$scan_dir = preg_grep("/\w+\.md/i",scandir($this->input_dir));
		
		foreach ($scan_dir as $filename) {
			$card = $this->splitMD($filename);
			// add to appropriate arrays aka decks 
			$this->allCards[] = $card;
			// add tags from each ard to tagMenu, adds to existing tag array if exists, otherwise makes a new one
			foreach($card->getTags() as $tag) { $this->tagMenus[$tag][] = $card; }
		}
		
		// build the tag cloud once all the files are processed	
		foreach($this->tagMenus as $k => $v) { $this->tagCloud[] = $k; }
		
	}
	
	// save the html
	private function htmlOut($slug, $html, $pre = null, $post = null) {
		$savepath = $this->output_dir . $slug . ".html";
		$handle = fopen($savepath, 'w');
		fwrite($handle, $pre . $html . $post);
		fclose($handle);
	}
	
	public function outputCards() {
		foreach ($this->allCards as $card) {
			$this->htmlOut($card->getSlug(), $card->getHTML(), "<div id='md'>", "</div>");
		}
	}
	
	public function outputCategories() {
		foreach ($this->tagMenus as $tag => $cards) {
			$cardList = "";
			foreach ($cards as $card) { $cardList .= "<li><p class='title' data-detail='".$card->getSlug()."'>".$card->getTitle()."</p>
			<span class='date'>".$card->getDate()."</span><span class='tags'>".$tags."</span></li>
			";
			}
			$cat = "<div id='cat-{$tag}'><h1>{$tag}</h1><input class ='search' /><i class='icon-search icon-large'></i><ul class='list navlist'>
				{$cardList}
				</ul></div>";
			$this->htmlOut("cat-".$tag, $cat);		
		}
	}
	
	
	public function outputIndex() {
		$tagList = "";
		$allCardsList = "";
		foreach ($this->tagCloud as $tag) {
			$tagList .= "<li><p class='title' data-detail='cat-".$tag."'>".$tag."</p></li>";
		}
		
		foreach ($this->allCards as $card) {
			$tags = "";
			foreach ($card->getTags() as $tag) { $tags .= $tag . ' / '; }
			
			$allCardsList .= "<li><p class='title' data-detail='".$card->getSlug()."'>".$card->getTitle()."</p>
			<span class='date'>".$card->getDate()."</span><span class='tags'>".$tags."</span></li>
			";
		}
		
		include_once('templates/tmp.index.php'); // dependency
		
		$this->htmlOut("index", $index);
		
		echo "index output";
		
	}
	
	// array getters
	public function getAllCards() { return $this->allCards; }
	public function getTagMenus() { return $this->tagMenus; }
	public function getTagCloud() { return $this->tagCloud; }
	
}





function indexTemplate($all, $tags) {
	//process arrays to build $allCardsList, $dateList, $tagList variables
	
	
	
	
	//process to output $index as index.html


}

function tagTemplate($menus) {}


$catalog = new CardCatalog();
$catalog->outputCards();
$catalog->outputCategories();
$catalog->outputIndex();


// /* CHECK MY WORK */
// 
// foreach (array($catalog->getAllCards(), $catalog->getTagMenus(), $catalog->getTagCloud()) as $a) {
// 	echo_array($a);
// }

// /////////////////////////////////////////////////////////////
// /* PROCEDURAL IMPLEMENTATION */
// 
// // some constants
// define("INPUTDIR", "../md/");
// define("OUTPUTDIR", "../static/");
// 
// /* FUNCTIONS */
// // quick and dirty array echoing
// function echo_array($input, $name = null) {
// 	echo "<p><pre>";
// 	echo $name . "<br />";
// 	echo print_r($input);
// 	echo "</pre></p>";
// }
// 
// // saves the html to OUTPUTDIR with the slug name
// function htmlOut($slug, $html) {
// 	$savepath = OUTPUTDIR . $slug . ".html";
// 	$handle = fopen($savepath, 'w');
//  	$appenddiv = "<div id='md'><h1>build with procedural final 2</h1>{$html}</div>";
//  	fwrite($handle, $appenddiv);
//  	fclose($handle);
// }
//  
// // opens file from INPUTDIR 
// // splits the markdown file, saves the html with slug name
// // returns the header as array
// function processMD($filename) {
//  	$openpath = INPUTDIR . $filename;
//  	$file = explode("+++++", file_get_contents($openpath));
//  	$yaml = Spyc::YAMLLoad($file[0]);
//  	$html = Markdown($file[1]);
//  	
//  	htmlOut($yaml['slug'], $html);
//  	
//  	return $yaml; // the equivalent of the card object
// }
// 
// // takes a directory and builds the necessary arrays
// function buildDecks($dir) {
// 	// open directory
// 	// only use files that match *.md filenames
// 	$scanned_dir = preg_grep("/\w+\.md/i",scandir($dir));
// 	
// 	// process each file and add to appropriate arrays
// 	foreach ($scanned_dir as $filename) {
// 		$card = processMD($filename);
// 		$all[] = $card;
// 		// add tags from each ard to tagMenu, adds to existing tag array if exists, otherwise makes a new one
// 		foreach($card['tags'] as $tag) { $menus[$tag][] = $card; }
// 	}
// 	
// 	// generates the tag cloud
// 	foreach($menus as $k => $v) { $cloud[] = $k; }
// 	
// 	return array('all' => $all, 'menus' => $menus, 'cloud' => $cloud, 'scan' => $scanned_dir);
// }
// 
// /* ACTUALLY DO IT! */
// 
// // build the decks
// $build = buildDecks(INPUTDIR);
// 
// /* CHECK MY WORK */
//  
// foreach ($build as $a) { echo_array($a); }
//

?>
