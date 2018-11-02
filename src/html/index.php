<?php
require(__DIR__ . '/../vendor/autoload.php');
require(__DIR__ . '/../include/config.inc.php');
require(__DIR__ . '/../include/Database.class.php');
require(__DIR__ . '/../include/functions.inc.php');

header('Content-Type: application/json; charset=UTF-8');
$response = array(
	'messages' => array(),
	'success' => false
);

/*
 * verify the user passed a valid JWT token in the HTTP Authentication header
 *
 */
$username = verifyJWT();
if (!$username) {
	http_response_code(401);
	$response['messages'][] = 'Unauthorized';
	echo json_encode($response, JSON_PRETTY_PRINT);
	return false;
}

/*
 * compile the route from the URL
 *
 */
$res = compileRoute();
if (!$res['success']) {
	http_response_code($res['http_status']);
	unset($res['http_status']);
	$response['messages'] = array_merge($response['messages'], $res['messages']);
	echo json_encode($response, JSON_PRETTY_PRINT);
	return false;
}
$movie_id = $res['movie_id'] ?: 0;

/*
 * route the request and output the response
 *
 */
$res = routeRequest($username, $movie_id);
http_response_code($res['http_status']);
unset($res['http_status']);
echo json_encode($res, JSON_PRETTY_PRINT);
return true;



/*
 * TODO - move these functions out of here
 *
 */
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
			/*
			 * TODO - change this to pass by array, move this validation into the function
			 *	- add a "required" arg to sanitize function 
			 *
			 */
			$missing_params = array();
			foreach(MOVIE_CONSTRAINTS['required_params'] as $param) {
				if (empty($_POST[$param])) {
					$missing_params = "missing required parameter $param";
				}
			}
			if (!empty($missing_params)) {
				$response = array(
					'http_status'	=> 401,
					'messages'	=> array(),
					'success'	=> false
				);
				$response['messages'][] = 'missing required parameters: ' . join(',', $missing_params);
				return($response);
			}

			return createMovie($_POST['title'], $username, $_POST['format'], $_POST['length'], $_POST['release_year'], $_POST['rating']);

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
