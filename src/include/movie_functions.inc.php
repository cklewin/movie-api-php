<?php

function createMovie($username, $args) {
	$response =array(
		'http_status'	=> null,
		'messages'	=> array(),
		'success'	=> false,
	);

	$res = sanitizeMovieParams(array_merge($args, array('username'=>$username)), MOVIE_CONSTRAINTS['required_params']);
	if ($res['success'] != true) {
		$response['http_status'] = 400;
		$response['messages'] = array_merge($response['messages'], $res['messages']);
		return($response);
	}

	/*
	 * TODO: cleanup, this is messy
	 *
	 */
	$db = new Database('write');
	$movie_id = $db->write('INSERT INTO movies (owner,title,format,length,release_year,rating) VALUES (?,?,?,?,?,?)', 'sssiii', array($username,$args['title'],$args['format'],$args['length'],$args['release_year'],$args['rating']));

	if (!$movie_id) {
		$response['http_status'] = 500;
		$response['messages'][] = 'failed to create movie';
		return($response);
	}

	$response['http_status'] = 201;
	$response['messages'][] = 'movie created';
	$response['movie_id'] = $movie_id;
	$response['success'] = true;

	return $response;
}

function updateMovie($username, $movie_id, $args) {
	$response =array(
		'http_status'	=> null,
		'messages'	=> array(),
		'success'	=> false,
	);

	/*
	 * store movie data before change for comparison
	 *
	 */
	$res_getMovie = getMovie($movie_id);
	if (!$res_getMovie['success']) {
		return $res_getMovie;
	}
	$movie_data_original = $res_getMovie['data'][0];

	/*
	 * merge new changes into the original data
	 *
	 */
	$movie_data_new = array_merge($movie_data_original, $args);

	if (empty(array_diff($movie_data_original, $movie_data_new))) {
		$response['http_status'] = 200;
		$response['messages'][] = 'no changes detected';
		return($response);
	}

	$res = sanitizeMovieParams(array_merge($movie_data_new, array('username'=>$username)), MOVIE_CONSTRAINTS['required_params']);
	if ($res['success'] != true) {
		$response['http_status'] = 400;
		$response['messages'] = array_merge($response['messages'], $res['messages']);
		return($response);
	}

	/*
	 * TODO: cleanup, this is messy
	 *
	 */
	$db = new Database('write');
	$res = $db->write('UPDATE movies SET owner=?, title=?, format=?, length=?, release_year=?, rating=? WHERE id=?', 'sssiiii', array($username, $movie_data_new['title'], $movie_data_new['format'], $movie_data_new['length'], $movie_data_new['release_year'], $movie_data_new['rating'], $movie_id));
	if (!$res) {
		$response['http_status'] = 500;
		$response['messages'][] = 'failed to update movie';
		return($response);
	}

	$response['http_status'] = 202;
	$response['messages'][] = 'movie updated';
	$response['success'] = true;

	return $response;
}

function deleteMovie($movie_id) {
	$response =array(
		'http_status'	=> null,
		'messages'	=> array(),
		'success'	=> false,
	);

	$res_getMovie = getMovie($movie_id);
	if (!$res_getMovie['success']) {
		return $res_getMovie;
	}

	$db = new Database('write');
	$res = $db->write('DELETE FROM movies WHERE id=?', 'i', array($movie_id));
	if (!$res) {
		$response['http_status'] = 500;
		$response['messages'][] = 'failed to delete movie';
		return($response);
	}

	$response['http_status'] = 202;
	$response['messages'][] = 'movie deleted';
	$response['success'] = true;

	return $response;
}

function getMovies($params = array()) {
	$response = array(
		'http_status'	=> null,
		'messages'	=> array(),
		'success'	=> false
	);

	if (!empty($params)) {
		$res = sanitizeMovieParams($params);
		if ($res['success'] != true) {
			$response['http_status'] = 400;
			$response['messages'] = array_merge($response['messages'], $res['messages']);
			return($response);
		}
	}

	/*
	 * TODO: for the pager, ask for limit + 1
	 *	if the number returned is greater than our limit
	 *	set the pager to say there are more results
	 *	remove the last result
	 *
	 */
	$sql = 'SELECT id,title,format,length,release_year,rating FROM movies';

	if (!empty($params['sort_field'])) {
		if (empty($params['sort_dir'])) {
			$params['sort_dir'] = 'asc';
		}

		$sql .= " order by $params[sort_field] $params[sort_dir]";
	}
	
	$db = new Database('read');
	$results = $db->read($sql);

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

	if (empty($movie_id)) {
		$response['http_status'] = 400;
		$response['messages'][] = 'movie_id is required';
		return($response);
	}

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

?>
