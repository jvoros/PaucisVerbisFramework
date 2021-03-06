<?php
/* INCLUDES */
// markdown and yaml libraries
include_once("markdown.php");
include_once("Spyc.php");

// quick and dirty array echoing, utility for testing
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

// this class turns the markdown files into the necessary pieces and constructs the static HTML
// this is where the action is

class CardCatalog {
	
	// constants for file paths
	private $input_dir = "../md/"; // dependency
	private $output_dir = "../paucisverbis/"; //dependency
	private $temp_dir = "templates/"; //dependency
	
	// arrays that make up site pieces aka the decks
	private $allCards;
	private $tagCloud;
	private $tagMenus;
	
	public function __construct() { 
		$this->buildDecks(); 
		$this->outputCards();
		$this->outputCategories();
		$this->outputIndex();
		echo "Static site built successfully.";
	}
	
	// BUILD THE DECKS //
	
	// opens the file, splits up the header and the markdown and makes a Card
	private function makeCard($filename) {
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
			$card = $this->makeCard($filename);
			// add to appropriate arrays aka decks 
			$this->allCards[] = $card;
			// add tags from each card to tagMenu, adds to existing tag array if exists, otherwise makes a new one
			foreach($card->getTags() as $tag) { $this->tagMenus[$tag][] = $card; }
		}
		
		// build the tag cloud once all the files are processed	
		foreach($this->tagMenus as $k => $v) { $this->tagCloud[] = $k; }
	}
	
	// BUILD THE STATIC HTML
	
	// generic function to save the html pages
	private function htmlOut($slug, $html) {
		$savepath = $this->output_dir . $slug . ".html";
		$handle = fopen($savepath, 'w');
		fwrite($handle, $html);
		fclose($handle);
	}
	
	// requires a template file and an array of the form array('var1' => 'value1', 'var2' => 'value2')
	public function buildTemp($tmp, array $data) {
		ob_start();
		extract($data);
		include($this->temp_dir.$tmp);
		return ob_get_clean();
	}
	
	// function that builds and saves the card html files based on the card template
	public function outputCards() {
		foreach ($this->allCards as $card) {
			$html = $card->getHTML();
			$tmp = $this->buildTemp('tmp.card.php', array('html' => $html));
			$this->htmlOut($card->getSlug(), $tmp);
		}
	}
	
	// function that builds and saves html for each category list
	public function outputCategories() {
		foreach ($this->tagMenus as $tag => $cards) {
			$cardList = $this->buildTemp('tmp.cardlist.php', array('cards' => $cards));
			$cat = $this->buildTemp('tmp.category.php', array('tag' => $tag, 'cardList' => $cardList));
			$this->htmlOut("cat-".$tag, $cat);		
		}
	}
	
	// function that outputs the main index page that calls the other output HTML pages via the jQuery
	public function outputIndex() {
		$tagList = $this->buildTemp('tmp.taglist.php', array('tags' => $this->tagCloud));
		$cardList = $this->buildTemp('tmp.cardlist.php', array('cards' => $this->allCards));
		$index = $this->buildTemp('tmp.index.php', array('cardList' => $cardList, 'tagList' => $tagList));
		$this->htmlOut("index", $index);		
	}
	
	public function build() {

	}
	
	// array getters
	public function getAllCards() { return $this->allCards; }
	public function getTagMenus() { return $this->tagMenus; }
	public function getTagCloud() { return $this->tagCloud; }
	
}

$catalog = new CardCatalog();
