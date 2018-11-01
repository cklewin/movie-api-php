<?php

function verifyJWT($token='') {
	if (!$token && !empty($_SERVER['HTTP_AUTHENTICATION'])) {
		$auth_parts = explode(' ', $_SERVER['HTTP_AUTHENTICATION']);
		if ((count($auth_parts) != 2) || ($auth_parts[0] != 'Bearer')) {
			return false;
		}
		$token = $auth_parts[1];
	}

	$config = array(
		'region' => 'us-east-1',
		'version' => 'latest',
		'user_pool_id' => 'us-east-1_1SHiUXm9X',
	);

	$aws = new \Aws\Sdk($config);
	$cognitoClient = $aws->createCognitoIdentityProvider();
	$client = new \pmill\AwsCognito\CognitoClient($cognitoClient);
	$client->setRegion($config['region']);
	$client->setUserPoolId($config['user_pool_id']);

	$username = '';
	try {
		$username = $client->verifyAccessToken($token);
	} catch(Exception $e) {
		return false;
	}

	return $username;
}

function verifyMovieID($movie_id) {
	echo 'TODO: verifyMovieID';
	return true;
}

function createMovie() {
	echo 'TODO: createMovie';
	return true;
}

function getMovies() {
	echo 'TODO: listMovies';
	/*
	 * ask for limit + 1
	 * if the number returned is greater than our limit
	 * set the pager to say there are more results
	 * remove the last result
	 *
	 */

	return true;
}

function getMovie($movie_id) {
	echo 'TODO: showMovie';
	return true;
}

function updateMovie($movie_id) {
	echo 'TODO: updateMovie';
	return true;
}

function deleteMovie() {
	return true;
}

?>
