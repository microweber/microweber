<?php

require_once(__DIR__ . DS . 'TwitterAPIExchange.php');

function twitter_feed_perform_api_request($url = 'https://api.twitter.com/1.1/search/tweets.json', $getfield = false) {


    $oauth_access_token = get_option('access_token', 'twitter_feed');
    $oauth_access_token_secret = get_option('access_token_secret', 'twitter_feed');
    $consumer_key = get_option('consumer_key', 'twitter_feed');
    $consumer_secret = get_option('consumer_secret', 'twitter_feed');


    if ($oauth_access_token==false){
        $oauth_access_token = "";
    }

    if ($oauth_access_token_secret==false){
        $oauth_access_token_secret = "";
    }

    if ($consumer_key==false){
        $consumer_key = "";
    }


    if ($consumer_secret==false){
        $consumer_secret = "";
    }


    if (!$oauth_access_token || !$oauth_access_token_secret || !$consumer_key || !$consumer_secret){
        return false;
    }


    $cache_expiration_minutes = 1500;
    $cache_id = md5($url . $getfield);
    $cache_group = 'twitter_feed_2';
    $cached_results = cache_get($cache_id, $cache_group,$cache_expiration_minutes);
    if ($cached_results!=false){
        return $cached_results;
    }

    $settings = array(
        'oauth_access_token'        => $oauth_access_token,
        'oauth_access_token_secret' => $oauth_access_token_secret,
        'consumer_key'              => $consumer_key,
        'consumer_secret'           => $consumer_secret
    );

    $requestMethod = 'GET';

    $twitter = new TwitterAPIExchange($settings);
    $response = $twitter->setGetfield($getfield)
        ->buildOauth($url, $requestMethod)
        ->performRequest();


    $return = json_decode($response, true);



    if (!empty($return)){
        cache_save($return, $cache_id, $cache_group, $cache_expiration_minutes);
    }




    return $return;
}

function twitter_feed_get_items($keyword = false, $results_count = 5) {


    $count = intval($results_count);
    $query = urlencode($keyword);
    $url = 'https://api.twitter.com/1.1/search/tweets.json';
    $getfield = '?count=' . $count . '&q=' . $query;
    $items = twitter_feed_perform_api_request($url, $getfield);
    $return = array();
    if (isset($items['statuses'])){
        foreach ($items['statuses'] as $status => $statusData) {
            $tweet = array();
            $tweet['url'] = false;
            $tweet['media'] = false;
            $tweet['name'] = false;
            $tweet['profile_image'] = false;
            $tweet['screen_name'] = false;

            $tweet['id'] = $statusData['id'];
            $tweet['created_at'] = $statusData['created_at'];
            $tweet['ago'] = mw()->format->ago($statusData['created_at']);

            if (isset($statusData['entities']['urls'][0])){

                if (is_array($statusData['entities']['urls'][0])){
                    $tweet['url'] = $statusData['entities']['urls'][0]['url'];
                } else {
                    $tweet['url'] = $statusData['entities']['urls'][0];

                }
            }
            if (isset($statusData['entities']['media'][0])){
                $tweet['media'] = $statusData['entities']['media'][0]['media_url'];
                if ($tweet['url']==false){
                    $tweet['url'] = $statusData['entities']['media'][0]['expanded_url'];
                }
            }
            if (isset($statusData['user'])){
                $tweet['user_data'] = $statusData['user'];
                $tweet['screen_name'] = $statusData['user']['screen_name'];
                $tweet['name'] = $statusData['user']['name'];
                $tweet['profile_image'] = $statusData['user']['profile_image_url_https'];
                if ($tweet['url']==false){
                    $tweet['url'] = 'https://twitter.com/' . $tweet['screen_name'] . '/status/' . $statusData['id_str'];
                }
            }
            if (isset($statusData['text'])){
                $tweet['text'] = $statusData['text'];
            }
            $return[] = $tweet;
        }
    }


    return $return;
}

function twitter_feed_get_user_tweets($twitter_handle = false, $results_count = 5) {

    $count = intval($results_count);
    $url = 'https://api.twitter.com/1.1/statuses/user_timeline.json';


    $getfield = '?include_entities=true&include_rts=false&count=' . $count . '&exxclude_replies=true&nofilter=retweets&screen_name=' . $twitter_handle;
	//dd($url, $getfield);
    $items = twitter_feed_perform_api_request($url, $getfield);

	if(isset($items["errors"])){
		return;
	}
    return $items;
}
