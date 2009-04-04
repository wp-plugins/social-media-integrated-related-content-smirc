<?
/**
 * Object for getting, parsing, and prepping external related content
 *
 * @author Husani S. Oakley
 * @version 1.0
 */
class SMIRC{

  var $total_results;
  var $page_title;
  var $default_header_text = "Blog discussions:";
  var $search_urls = array(
			   "google_blogsearch_norss" => "http://blogsearch.google.com/blogsearch?client=news&um=1&hl=en&scoring=d&q=SEARCHTERM&ie=utf-8",
			   "google_blogsearch" => "http://blogsearch.google.com/blogsearch_feeds?client=news&um=1&hl=en&scoring=d&q=SEARCHTERM&ie=utf-8&num=NUMRESULTS&output=rss",
			   "twitter_search" => "http://search.twitter.com/search.atom?q=SEARCHTERM&rpp=NUMRESULTS"
			   );

  /**
   * constructor
   */
  function SMIRC($page_title, $title_separators, $required_keyword, $data_sources, $header_text, $animation=false){
    $this->page_title = $page_title;
    $this->title_separators = $title_separators;
    $this->required_keyword = $required_keyword;
    $this->data_sources = $data_sources;
    if($header_text == ""){
      $this->header_text = $this->default_header_text;
    } else {
      $this->header_text = $header_text;
    }
    $this->animation = $animation;
  }

  /**
   * main work method.  create url, get data, parse, prep and return xhtml
   */
  function getContent(){
    //don't bother doing anything if we don't have any data sources
    if(!is_array($this->data_sources)){ return false; }
    //if we have delimiter characters, use them to split up the title
    $searchterm = $this->_getSearchTerm();
    //start assembling data
    foreach($this->data_sources as $source_array){
      //prep url
      $data_source = str_replace("SEARCHTERM", $searchterm, $this->search_urls[$source_array[0]]);
      $data_source = str_replace("NUMRESULTS", $source_array[1], $data_source);
      //get data, put into array
      $rss_data_array[$source_array[0]]['results'] = $this->_getResults($data_source, split("\n", $source_array[2]));
      $rss_data_array[$source_array[0]]['header'] = $source_array[3];
    }

    //create and return xhtml for all sources
    $xhtml = $this->_createXHTML($rss_data_array, $this->header_text);
    return $xhtml;
  }

  /**
   * create and return all xhtml
   */
  function _createXHTML($rss_array, $header_text){
    foreach($rss_array as $data_source => $results_and_header){
      //run function to get xhtml from rss object -- name of function depends on data source.
      $lists_xhtml .= $this->$data_source($results_and_header['results'], $results_and_header['header']);
    }
    if($lists_xhtml == ""){
      //no results = no xhtml
      return false;
    } else {
      $all_xhtml = '<div class="smirc_wrapper">';
      $all_xhtml .= '<h2 class="collapsed">'.$this->_getHeaderXHTML().'</h2>';  
      $all_xhtml .= '<ul class="smirc_ul">';

      //add results to overall xhtml
      $all_xhtml .= $lists_xhtml;

      $all_xhtml .= "</ul>";  
      $all_xhtml .= '</div>';
      return $all_xhtml;
    }

  }

  /**
   * GOOGLE BLOGSEARCH:  iterate through rss object and create standards-compliant xhtml for the resultset, while ignoring items in exclude list
   */
  function google_blogsearch($rss_items, $result_header){
    $list_xhtml = "";
    if(count($rss_items) >= 1){
      $list_xhtml = "<li class='result_header'>$result_header</li>";
      foreach($rss_items as $item){	
	$fixed_item = $this->_parseItem($item);
	$list_xhtml .= '<li><a class="link" href="'.$fixed_item['link'].'" target="_blank">'.$fixed_item['title'].'</a><div class="author">by '.$fixed_item['dc']['creator'].'</div><div class="summary">'.strip_tags($fixed_item['summary']).'</div></li>';
      }
    }
    return $list_xhtml;
  }

  /**
   * TWITTER SEARCH:  iterate through rss object and create standards-compliant xhtml for the resultset
   * search.twitter.com doesn't seem to have results limits, so we'll have to do that manually.
   */
  function twitter_search($rss_items, $result_header){
    $list_xhtml = "";
    if(count($rss_items) >= 1){
      $list_xhtml = "<li class='result_header'>$result_header</li>";
      foreach($rss_items as $item){
	$fixed_item = $this->_parseItem($item);
	$list_xhtml .= '<li>'.$fixed_item['atom_content'].' on <a href="'.$fixed_item['link'].'" target="_blank">'.date(get_option('date_format'), strtotime($fixed_item['published'])).'</a> by <a href="'.$fixed_item['author_uri'].'" target="_blank">'.$fixed_item['author_name'].'</a></li>';
      }
    }
    return $list_xhtml;
  }

  /**
   * use title separators (if any) to prepare search term(s)
   */
  function _getSearchTerm(){
    if(is_array($this->title_separators)){
      //make this easy -- replace all matches to items in separators array with a common character
      $title = $this->page_title;
      foreach($this->title_separators as $delimiter){
	$title = str_replace($delimiter, "###", $title);
      }
      //split by this common character
      $arr = split("###", $title);
      //iterate, trim, add to array
      foreach($arr as $phrase){
	$searchterms[] = trim($phrase);
      }
      $searchterms[] = $this->required_keyword;
    } else {
      //no separators.  search terms are title and required keyword if any
      $searchterms[] = $this->page_title;
      $searchterms[] = $this->required_keyword;
    }
    //iterate through searchterms and add quotation marks / urlencode as needed
    $str = "";
    foreach($searchterms as $term){
      $str .= '+"' . urlencode($term) . '"';
    }
    return $str;
  }

  /**
   * using the exclude list (if set) and MagpieRSS, return an array of data sources and results
   */
  function _getResults($data_source, $exclude_list){
    $rss = fetch_rss($data_source);
    //set total results
    $this->_setTotalResults($rss);
    $results = $rss->items;    
    //is there an exclude list?
    if(is_array($exclude_list)){      
      //yes.  iterate and remove
      foreach($exclude_list as $exclude_me){
	$matches = $this->array_search_recursive($exclude_me, $results);
	unset($results[$matches[0]]);
      }      
    }
    return $results;
  }

  /**
   * unfortunately-ghetto way to remove google's BOLDING of matching wordds
   */
  function _parseItem($arr){
    $newarr;
    if(!is_array($arr)){ return $arr; }
      foreach($arr as $key => $value){
	$newval = str_replace("<b>", "", $value);
	$newval = str_replace("</b>", "", $newval);
	$newarr[$key] = $newval;
      }
      return $newarr;
  }

  /**
   * recursively search a multidimensional array
   */
  function array_search_recursive($needle, $haystack, $path=array()){
    foreach($haystack as $id => $val){
      $path2 = $path;
      $path2[] = $id;       
      if(eregi($needle, $val)){
	return $path2;
      } else if(is_array($val)){
	if($ret = $this->array_search_recursive($needle, $val, $path2)){
	  return $ret;
	}
      }
      return false;
    }
  }

  /**
   * add to total results count
   */
  function _setTotalResults($rss){
    if($rss->channel['opensearch']['totalresults']){
      $this->total_results = $this->total_results + $rss->channel['opensearch']['totalresults'];
    }
  }

  /**
   * create header text / link
   */
  function _getHeaderXHTML(){
    //create link
    $link = str_replace("SEARCHTERM", $this->_getSearchTerm(), $this->search_urls['google_blogsearch_norss']);    
    return "<a href='". $link . "' target='_blank'>" . number_format($this->total_results) . " " . $this->header_text . "</a>";
  }

}


?>