<?php

/**
 * Twitter Feed Parser
 *
 * @version  	1.1.2
 * @author	Dario Zadro
 * @link	http://zadroweb.com/your-twitter-feed-is-broken/
 *
 * Notes:
 * Caching is used - Twitter only allows 180 queries for every 15 minutes
 * See: https://dev.twitter.com/docs/rate-limiting/1.1
 * Super simple debug mode will output returned API variable
 * --
 * Twitter time is displayed (ex. "about 1 hour ago")
 *
 * Credits:
 * Twitter API: https://github.com/J7mbo/twitter-api-php
 * Hashtag/Username Parsing: http://snipplr.com/view/16221/get-twitter-tweets/
 * Time Ago (modified) Function: http://css-tricks.com/snippets/php/time-ago-function/
 */

// Your Twitter App Settings
// https://dev.twitter.com/apps
$access_token			= '2155615657-l9XX2j5FmZm3NwuCYJIehr4G2A1jvMXGkDRddvY';
$access_token_secret		= 'hALQYqrrOr9pVUQTitoRdlxgelBZmQ9tRs6denN1S31T5';
$consumer_key			= 'g3gGRjm1jhxP3NHWFLkZf6c7f';
$consumer_secret		= 'bh0YwwvETuN7cC8VLURUPcbEkjYubRa8JS0awq7WsPObjmFbPR';

// Some variables
// $twitter_username 		= 'Sergei_Malinin';
// $twitter_username 		= 'OttawaFoodBank';
// $twitter_username 		= 'ottawahumane';
// $twitter_username 		= 'kaspersky_ru';
// $number_tweets			= 3; // How many tweets to display? max 20
$ignore_replies 		= true; // Should we ignore replies?
$twitter_caching		= true; // You can change to false for some reason
$twitter_cache_time 		= 60*60; // 1 Hour
$twitter_cache_file 		= 'tweets.txt'; // Check your permissions
$twitter_debug			= true; // Set to "true" to see all returned values

require_once('TwitterAPIExchange.php');

// Settings for TwitterAPIExchange.php
$url = 'https://api.twitter.com/1.1/statuses/user_timeline.json';
// $getfield = '?screen_name='.$twitter_username.'&count='.$number_tweets;
$requestMethod = 'GET';
$settings = array(
	'oauth_access_token' => $access_token,
	'oauth_access_token_secret' => $access_token_secret,
	'consumer_key' => $consumer_key,
	'consumer_secret' => $consumer_secret
);

// Flag for twitter error
$tweet_flag = 1;
// function a21_tw_get_tweets($twitter_username,$settings,$url,$getfield,$requestMethod,$twitter_debug = true){
function a21_tw_get_tweets($twitter_username,$settings,$url,$requestMethod,$twitter_debug = true,$number_tweets = 3){

	$getfield = '?screen_name='.$twitter_username.'&count='.$number_tweets;

	// Let's run the API then JSON decode and store in variable
	$twitter = new TwitterAPIExchange($settings);
	$twitter_stream = json_decode($twitter->setGetfield($getfield)->buildOauth($url, $requestMethod)->performRequest());

	// Debug mode, just output twitter stream variable
	// echo '<pre>';	print_r($twitter_stream);	echo '</pre>';
	if($twitter_debug){
		foreach ($twitter_stream as $k => $v) {
			$output .= ago($v->created_at,1,1)."<br>";
			$output .= $k."- ".$v->text."<br>";
			$output .= $k."- ".$v->created_at."<br>";
			// short link on tweet
			if(!empty($v->entities->urls[0]->url)) $output .= $k."- ".$v->entities->urls[0]->url;
			$output .= "<hr>";
			// echo "<pre>";  print_r($v); echo "</pre>"; 
			// short link on tweet
			// echo "<pre>";  print_r($v->entities->urls[0]->url); echo "</pre>";
		}
		return $output;
	}else{ return $twitter_stream; }
}

// If API didn't work for some reason, output some text
if (!$tweet_flag) {
	echo $tweets = '<ul class="twitter_stream twitter_error"><li>Oops, something went wrong with our twitter feed - <a href="http://twitter.com/'.$twitter_username.'/">Follow us on Twitter!</a></li></ul>';
}

// Simple function to get Twitter style "time ago"
function ago($tweet_time,$tweet_id,$tweet_name) {

    	$m = time()-strtotime($tweet_time); $o='just now';
    	$t = array('year'=>31556926,'month'=>2629744,'week'=>604800,'day'=>86400,'hour'=>3600,'minute'=>60,'second'=>1);
    	foreach($t as $u=>$s){
        	// if($s<=$m){$v=floor($m/$s); $o='about '.$v.' '.$u.($v==1?'':'s').' ago'; break;}
        	if($s<=$m){$v=floor($m/$s); $o=$v.' '.$u.($v==1?'':'s').' ago'; break;}
    	}
	// return '<a href="http://twitter.com/'.$tweet_name.'/statuses/'.$tweet_id.'">('.$o.')</a>';
	return $o;

}