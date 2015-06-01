<?php
/**
 * VDM class 
 * (fetch, format and save VDM stories)
 */

class vdmHelper {
  
  public $url = "http://www.viedemerde.fr/?page=";
  public $path = "//div[@class='post article']";

  /**
   * Retrieves stories from VDM website
   */
  public function fetchStories($total = 20) {
    
    $current_page = 0;
    $nb_stories = 0;
    $stories = [];
  
    while($nb_stories < $total) {
      
      // fetch new page
      $html = file_get_contents($this->url . $current_page);
      $dom = new DOMDocument();
      @$dom->loadHTML('<?xml encoding="UTF-8">' . $html);
      $xpath = new DOMXPath($dom);
      $tags = $xpath->query($this->path);
      
      // fetch stories from page
      foreach($tags as $tag) {
        
        // Format story
        $story = $this->splitStory($tag, $xpath);
        
        if($nb_stories < $total) {
          $stories[] = $story;
          $nb_stories++;
        }
      }
      
      $current_page++;
      
    }
    return $stories;
  }
  
  /**
   * transforms dom fragment into array
   *
   * TODO : check if it's UTF8 safe
   *
   */
  public function splitStory($tag, $xpath) {
    
    $story = [];
    
    // retrieve id
    $story["id"] = $this->queryNode("a[@class='jTip']", $xpath, $tag);
    $story["id"] = substr($story["id"], 1); // remove "#"
    
    // retrieve content
    $story["content"] =$this->queryNode("a[@class='fmllink']", $xpath, $tag);
    
    // retrieve and format date
    $str = $this->queryNode("div[@class='right_part']", $xpath, $tag);
    $date = substr($str, strpos($str, "Le ") + 3, 10);
    $date = explode("/", $date);
    $time = substr($str, strpos($str, "Le ") + 17, 5);
    $story["date"] = $date[2] . "-" . $date[1] . "-" . $date[0] . " " . $time . ":00";
    
    // retrieve author
    $story["author"] = substr($str, strpos($str, " - par ") + 7);
    $story["author"] = explode(" ", $story["author"])[0];
    
    return $story;
    
  }
  
  /**
   * Helper function to xpath-query a DOM element
   */
  public function queryNode($selector, $xpath, $node) {
    $nodes  = $xpath->query("descendant::$selector", $node);
    $content = "";
    foreach($nodes as $node) {
      $content .= $node->nodeValue;
    }
    return $content;
  }
  
  /**
   * Stores stories in database
   */
  public function storeStories($db, $stories) {
    $db->vdm->delete();
    $db->vdm->insert_multi($stories);
  }
  
  
}
