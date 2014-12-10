<?php

namespace mvc_example_blade;


class Controller
{

    function __construct()
    {
        $views_dir = __DIR__ . DS . 'views' . DS;
        \View::addNamespace('mvc_example_blade', $views_dir);


    }

    function index($params)
    {
        
		$number_of_posts  = get_option('number_of_posts', $params['id']);
		if($number_of_posts == false){
		$number_of_posts = 5;	
		}
		$number_of_posts = (int) $number_of_posts;
		//d($number_of_posts);
		
 //$posts = \Content::where('id', '!=',0)->take(intval($number_of_posts))->get()->toArray();
		
		 //$posts = \Content::items()->take($number_of_posts)->get()->toArray();
		// d(\DB::getQueryLog());
      $posts = \Content::all()->take($number_of_posts);
	 //  $posts = \Content::take($number_of_posts)->get();
 //d($posts);
          //$view = \View::make('mvc_example_blade::index')->withPosts($posts)->render();
       // dd($posts);
         // $view = \View::make('mvc_example_blade::index')->with('posts', $posts)->render();
          $view = \View::make('mvc_example_blade::index')->with('posts', $posts);

 //     print $view;
 return $view;
//        print $view;
//
//        print 11111111;
    }


}

