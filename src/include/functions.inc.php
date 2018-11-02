<?php

// all these movie related functions should probably go into a class or at least a separate file

function createMovie($title, $username, $format, $length, $release_year, $rating) {
	$response =array(
		'http_status'	=> null,
		'messages'	=> array(),
		'success'	=> false,
	);

	$res = sanitizeMovieParams(array(
		'format' => $format,
		'length' => $length,
		'release_year' => $release_year,
		'rating' => $rating
	));
	if ($res['success'] != true) {
		$response['http_status'] = 400;
		$response['messages'] = array_merge($response['messages'], $res['messages']);
		return($response);
	}

	$db = new Database('write');
	$movie_id = $db->write('INSERT INTO movies (owner,title,format,length,release_year,rating) VALUES (?,?,?,?,?,?)', 'sssiii', array($username,$title,$format,$length,$release_year,$rating));

	if (!$movie_id) {
		$response['http_status'] = 500;
		$response['messages'][] = 'failed to create movie';
		return($response);
	}

	$response['http_status'] = 201;
	$response['messages'][] = 'movie created successfully';
	$response['movie_id'] = $movie_id;
	$response['success'] = true;

	return $response;
}

function getMovies() {
	$response = array(
		'http_status'	=> null,
		'success'	=> false
	);

	/*
	 * ask for limit + 1
	 * if the number returned is greater than our limit
	 * set the pager to say there are more results
	 * remove the last result
	 *
	 */
	$db = new Database('read');
	$results = $db->read('SELECT id,title,format,length,release_year,rating FROM movies');

	$response['success'] = true;
	$response['http_status'] = 200;
	$response['data'] = $results;

	return $response;
}

function getMovie($movie_id) {
	$response = array(
		'http_status'	=> null,
		'messages'	=> array(),
		'success'	=> false,
	);

	$res = sanitizeMovieParams(array(
		'movie_id' => $movie_id,
	));
	if ($res['success'] != true) {
		$response['http_status'] = 400;
		$response['messages'] = array_merge($response['messages'], $res['messages']);
		return($response);
	}

	$db = new Database('read');
	$results = $db->read('SELECT id,title,format,length,release_year,rating FROM movies where id=?', 'i', array($movie_id));

	if (empty($results)) {
		$response['http_status'] = 404;
		$response['messages'][] = 'Not Found';
		return $response;
	}

	$response['success'] = true;
	$response['http_status'] = 200;
	$response['data'] = $results;

	return $response;
}

function updateMovie($movie_id) {
	echo 'TODO: updateMovie';
	return true;
}

function deleteMovie() {
	return true;
}

function sanitizeMovieParams($args) {
	$response =array(
		'messages'	=> array(),
		'success'	=> false,
	);

	$insane = false;

	if (!empty($args['movie_id']) &&
		$args['movie_id'] != (int)$args['movie_id']
		) {
		$insane = true;
		$response['messages'][] = 'invalid value for movie_id, expected an integer';
	}

	if (!empty($args['username']) &&
		!preg_match('/^[a-zA-Z0-9]+/', $username)
		) {
		$insane = true;
		$response['messages'][] = 'invalid username, expected alphanumeric string';
	}
	if (!empty($args['title']) &&
		(!is_numeric($args['title'])
		|| strlen($args['title']) < MOVIE_CONSTRAINTS['title']['min']
		|| strlen($args['title']) > MOVIE_CONSTRAINTS['title']['max']
		)) {
		$insane = true;
		$response['messages'][] = 'invalid value for title, maximum characters allowed is ' . MOVIE_CONSTRAINTS['title']['max'];
	}
	if (!empty($args['format']) &&
		!in_array($args['format'], MOVIE_CONSTRAINTS['format_list'])
		) {
		$insane = true;
		$response['messages'][] = 'invalid value for format, expected one of (' . join('|', MOVIE_CONSTRAINTS['format_list']) . ')';
	}
	if (!empty($args['length']) &&
		(!is_numeric($args['length'])
		|| $args['length'] < MOVIE_CONSTRAINTS['length']['min']
		|| $args['length'] > MOVIE_CONSTRAINTS['length']['max']
		)) {
		$insane = true;
		$response['messages'][] = 'invalid value for length, expected a number between ' . MOVIE_CONSTRAINTS['length']['min'] . ' and ' . MOVIE_CONSTRAINTS['length']['max'];
	}
	if (!empty($args['release_year']) &&
		(!is_numeric($args['release_year'])
		|| $args['release_year'] < MOVIE_CONSTRAINTS['release_year']['min']
		|| $args['release_year'] > MOVIE_CONSTRAINTS['release_year']['max']
		)) {
		$insane = true;
		$response['messages'][] = 'invalid value for release_year, expected a number between ' . MOVIE_CONSTRAINTS['release_year']['min'] . ' and ' . MOVIE_CONSTRAINTS['release_year']['max'];
	}
	if (!empty($args['rating']) &&
		(!is_numeric($args['rating'])
		|| $args['rating'] < MOVIE_CONSTRAINTS['rating']['min']
		|| $args['rating'] > MOVIE_CONSTRAINTS['rating']['max']
		)) {
		$insane = true;
		$response['messages'][] = 'invalid value for rating, expected a number between ' . MOVIE_CONSTRAINTS['rating']['min'] . ' and ' . MOVIE_CONSTRAINTS['rating']['max'];
	}

	if (!$insane) { $response['success'] = true; }

	return($response);
}

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

?>
