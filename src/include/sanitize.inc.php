<?php

function sanitizeMovieParams($args, $required=array()) {
	$response =array(
		'messages'	=> array(),
		'success'	=> false,
	);

	$insane = false;

	$missing_params = array();
	foreach($required as $param) {
		if (empty($args[$param])) {
			$missing_params[] = $param;
		}
	}
	if (!empty($missing_params)) {
		$insane = true;
		$response['messages'][] = 'missing required parameters: ' . join(',', $missing_params);
		return($response);
	}

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
		(strlen($args['title']) < MOVIE_CONSTRAINTS['title_length']['min']
		|| strlen($args['title']) > MOVIE_CONSTRAINTS['title_length']['max']
		)) {
		$insane = true;
		$response['messages'][] = 'invalid value for title, maximum characters allowed is ' . MOVIE_CONSTRAINTS['title_length']['max'];
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

?>
