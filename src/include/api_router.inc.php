<?php

function compileRoute() {
	$response = array(
		'http_status'	=> null,
		'messages'	=> array(),
		'success'	=> false
	);

	$path_parts = explode('/', $_SERVER['REDIRECT_URL']);

	/*
	 * verify proper API base path
	 *
	 */
	if (
		($path_parts[1] != 'api') ||
		($path_parts[2] != 'v1') ||
		($path_parts[3] != 'movies')
	) {
		$response['http_status'] = 404;
		$response['messages'][] = 'Not Found';
		return $response;
	}

	/*
	 * verify the movie id if it was passed
	 * there should be no sanitizing here, do that before usage
	 *
	 */
	if (!empty($path_parts[4])) {
		$response['movie_id'] = $path_parts[4];
	}

	$response['success'] = true;

	return $response;
}

function routeRequest($username, $movie_id) {
	switch($_SERVER['REQUEST_METHOD']) {
		case 'POST':
			return createMovie($username, $_POST);

		case 'GET':
			if (empty($movie_id)) {
				return getMovies();
			} else {
				return getMovie($movie_id);
			}

		case 'PUT':
			parse_str(file_get_contents('php://input'), $_PUT);
			if (empty($_PUT)) {
				$response = array(
					'http_status'	=> 400,
					'messages'	=> array(),
					'success'	=> false
				);
				$response['messages'][] = 'missing at least one parameter';
				return($response);
			}
			return updateMovie($username, $movie_id, $_PUT);

		case 'DELETE':
			return deleteMovie($movie_id);

		default:
			return array(
				'http_status'	=> 401,
				'messages'	=> $missing_params,
				'success'	=> false
			);
	}
}

?>
