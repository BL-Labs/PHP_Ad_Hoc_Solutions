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


$period = new DatePeriod(
     new DateTime('2021-03-14'),
     new DateInterval('P1D'),
     new DateTime('2021-04-11')
);

foreach ($period as $key => $value) {
	$result = $phpFlickr->stats_getTotalViews($value->format('Y-m-d'));
	// $result = $phpFlickr->stats_getTotalViews('2021-04-08');

	$NewStat = $value->format('Y-m-d').','.$result['stats']['total']['views'].','.$result['stats']['photos']['views'].','.$result['stats']['photostream']['views'].','.$result['stats']['sets']['views'].','.$result['stats']['collections']['views'];

	echo $NewStat."\n";
};

?>