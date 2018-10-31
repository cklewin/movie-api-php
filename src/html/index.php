<?php
require(__DIR__ . '/../vendor/autoload.php');
include(__DIR__ . '/../include/functions.inc.php');

/*
 * through the use of JWT and token in HTTP Authentication header
 * authorize user with API token
 *
 */
$username = verifyJWT();
if (!$username) {
	echo 'forbidden';
	http_response_code(403);
	return;
}

/*
 * verify proper API base path
 *
 */
$path_parts = explode('/', $_SERVER['REDIRECT_URL']);
if (
	($path_parts[1] != 'api') ||
	($path_parts[2] != 'v1') ||
	($path_parts[3] != 'movies')
) {
	http_response_code(404);
	exit;
}

/*
 * get the movie id if it was passed
 *
 */
$movie_id = 0;
if (!empty($path_parts[4])) {
	$response = verifyMovieID($path_parts[4]);
	if (!$response['movie_id']) {
		// TODO output proper HTTP code and message along with JSON message
		exit;
	}
	$movie_id = $response['movie_id'];
}

switch($_SERVER['REQUEST_METHOD']) {
	case 'POST':
		createMovie();
		break;
	case 'GET':
		if (empty($movie_id)) {
			listMovies();
		} else {
			showMovie($movie_id);
		}
		break;
	case 'PUT':
		updateMovie($movie_id);
		break;
	case 'DELETE':
		deleteMovie($movie_id);
		break;
	default:
		break;
}

?>
