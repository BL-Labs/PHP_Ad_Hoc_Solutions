<?php

date_default_timezone_set('Europe/London');

require_once __DIR__ . '/vendor/autoload.php';

// Make sure we have the required configuration values.
$configFile = __DIR__ . '/config.php';
require_once $configFile;
if (empty($apiKey) || empty($apiSecret) || empty($accessToken) || empty($accessTokenSecret)) {
    echo 'Please set $apiKey, $apiSecret, $accessToken, and $accessTokenSecret in ' . $configFile;
    exit(1);
}

// Add your access token to the storage.
$token = new \OAuth\OAuth1\Token\StdOAuth1Token();
$token->setAccessToken($accessToken);
$token->setAccessTokenSecret($accessTokenSecret);
$storage = new \OAuth\Common\Storage\Memory();
$storage->storeAccessToken('Flickr', $token);

// Create PhpFlickr.
$phpFlickr = new \Samwilson\PhpFlickr\PhpFlickr($apiKey, $apiSecret);

// Give PhpFlickr the storage containing the access token.
$phpFlickr->setOauthStorage($storage);

// Without a date = total views until that moment;
$result = $phpFlickr->stats_getTotalViews();

// With a date = Views from that day
// $result = $phpFlickr->stats_getTotalViews('2021-04-08');

$NewStat = date("Y-m-d H:i:s").','.$result['stats']['total']['views'].','.$result['stats']['photos']['views'].','.$result['stats']['photostream']['views'].','.$result['stats']['sets']['views'].','.$result['stats']['collections']['views'];

echo $NewStat."\n";

// $file = 'FlickrStatsTotal.csv';
// // Open the file to get existing content
// $current = file_get_contents($file);
// $current .= $NewStat."\n";
// // echo $current;
// // Write the contents back to the file
// file_put_contents($file, $current);

?>