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

$yesterday = date('Y-m-d',strtotime("-1 days"));

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

$result = $phpFlickr->stats_getTotalViews($yesterday);

$NewStat = $yesterday.','.$result['stats']['total']['views'].','.$result['stats']['photos']['views'].','.$result['stats']['photostream']['views'].','.$result['stats']['sets']['views'].','.$result['stats']['collections']['views'];

echo $NewStat."\n";

?>