<? 

header("Content-Type: text/plain");
$dir = ACTIVE_TEMPLATE_DIR.'temp'.DS.'perfect10'.DS;;

p($dir);


	require_once (LIBSPATH . "simplehtmldom/simple_html_dom.php");

foreach (glob("{$dir}*.aspx") as $filename) {
   
	
	$content = file_get_contents($filename);
	$html = str_get_html ( $content );
	

// Find all article blocks
foreach($html->find('div.detail-wrap') as $article) {
    $item['title']     = $article->find('span.productName', 0)->plaintext;
	    $item['custom_field_price']     = $article->find('span.pricingPrice', 0)->plaintext;
	    $item['custom_field_model']     = $article->find('span.productItemID', 0)->plaintext;

		    $item['custom_field_dimensions']     = $article->find('span[id=ctl00_ctl00_MasterPlaceHolder_MainContent_lblDimensions]', 0)->plaintext;



		    $item['custom_field_specs']     = $article->find('span[id=ctl00_ctl00_MasterPlaceHolder_MainContent_rptProductText_ctl01_lblProductText]', 0)->plaintext;
			
					  $pdf1 =     $article->find('span[id=ctl00_ctl00_MasterPlaceHolder_MainContent_rptProductText_ctl02_lblProductText]', 0)->plaintext;
					  $html2 = str_get_html ( $pdf1 );
					  
					  
  $item['custom_field_pdf']  = $html2->find('a', 0)->href;


		    $item['content_body']     = $article->find('span[id=ctl00_ctl00_MasterPlaceHolder_MainContent_rptProductText_ctl00_lblProductText]', 0)->plaintext;

	
	 $item['image']     = $article->find('img[id=ctl00_ctl00_MasterPlaceHolder_MainContent_rptImages_ctl00_Img1]', 0)->src;
	
	 $item['image']  = 'https://www.perfect-10.tv/WebStore/'. $item['image'];
	
	 $item['image'] = str_ireplace('Thumbnails', '',  $item['image']);
	
	
	
	
	
	
	
	
    $item['intro']    = $article->find('div.intro', 0)->plaintext;
    $item['details'] = $article->find('div.details', 0)->plaintext;
    $articles[] = $item;
}





		/*

		$html = str_get_html ( $content );
		foreach ( $html->find ( 'div[field]' ) as $checkbox ) {
			//var_Dump($checkbox);
			$re1 = $checkbox->rel;
			$style = $checkbox->field;
			
			if ($checkbox->page) {
				$checkbox->page = "{PAGE_ID}";
			}
			
			if ($checkbox->post) {
				$checkbox->post = "{POST_ID}";
			}
			
			$re2 = $checkbox->mw_params_module;
			
			$inner = $checkbox->innertext;
			
			$tag1 = "<editable ";
			$tag1 = $tag1 . "rel=\"{$re1}\" ";
			
			$tag1 = $tag1 . "field=\"{$style}\" ";
			$tag1 .= ">" . $inner . "</editable>";
			//p($tag1);
			$checkbox->outertext = $tag1;
			$html->save ();
		
		}
		
		$content = $html->save ();
	*/
	
	
	
}




p($articles);
	exit();





//
//foreach($rssfeed as $item){
//$post = array();
//$post['content_title'] = $item['title'];
//$post['original_link'] = $item['link'];
//$post['content_body'] = $item['description'];
//$post['taxonomy_categories'] = array( 100002047);
////$post['screenshot_url'] = $item['description'];
////$s = post_save($post);
// 
//p($post );
//	
//}
//
//
//
//
//



?>