<?php

function antzz()
{
    exit('antzz is here!!!');
}


Route::get('/zozozozo', function () {

    $article = Content::find(4)->first(); // eager load
    $article->tag('Gardening');
    $article->save();


    $article = Content::find(1)->first(); // eager load
    $article->tag('Cooking');
    $article->tag('Pepsi');
    $article->save();

    $article = Content::with('tagged')->first(); // eager load
  //  $article = Content::withAnyTag(['Gardening','Cooking'])->get(); // different syntax, same result as above


    //   $article = Content::with(['Gardening'])->first(); // eager load
     //$article = Content::withAnyTag(['Gardening'])->get(); // fetch articles with any tag listed


    $article = Content::withAnyTag('Gardening, Cooking')->get(); // fetch articles with any tag listed
    $article = Content::withAnyTag(['Gardening','Cooking'])->get(); // different syntax, same result as above
    $article =  Content::withAnyTag('Gardening','Cooking')->get(); // different syntax, same result as above

    //Content::withAllTags('Gardening, Cooking')->get(); // only fetch articles with all the tags
  //  Content::withAllTags(['Gardening', 'Cooking'])->get();
  //  Content::withAllTags('Gardening', 'Cooking')->get();


    $article = Content::existingTags(); // return collection of all existing tags on any articles

    dd($article);
//    foreach($article->tags as $tag) {
//        echo $tag->name . ' with url slug of ' . $tag->slug;
//    }
//
//    $article->tag('Gardening'); // attach the tag
//
//    $article->untag('Cooking'); // remove Cooking tag
//    $article->untag(); // remove all tags
//
//    $article->retag(array('Fruit', 'Fish')); // delete current tags and save new tags
//
//    $article->tagNames(); // get array of related tag names
//
//    Article::withAnyTag(['Gardening','Cooking'])->get(); // fetch articles with any tag listed
//
//    Article::withAllTags(['Gardening', 'Cooking'])->get(); // only fetch articles with all the tags
//
//    Conner\Tagging\Model\Tag::where('count', '>', 2)->get(); // return all tags used more than twice
//
//    Article::existingTags(); // return collection of all existing tags on any articles



//    $tag = new Tag(array('name' => 'wamp'));
//    $blogpost = Content::find(4)->tags()->save($tag);
//
//
//    $tags = array('wamp');
//    $posts = Content::whereHas('tags', function ($q) use ($tags) {
//        $q->whereIn('name', $tags);
//        //  $q->whereIn('id', $tags);
//    })->get()->toArray();
//
//
//    dd($posts);


    return $blogpost->tags;
});
