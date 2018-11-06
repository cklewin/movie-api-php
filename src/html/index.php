<?php
require(__DIR__ . '/../vendor/autoload.php');
require(__DIR__ . '/../include/config.inc.php');
require(__DIR__ . '/../include/Database.class.php');
require(__DIR__ . '/../include/movie_functions.inc.php');
require(__DIR__ . '/../include/api_router.inc.php');
require(__DIR__ . '/../include/sanitize.inc.php');
require(__DIR__ . '/../include/jwtauth.inc.php');

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
$movie_id = $res['movie_id'] ?: null;

/*
 * route the request and output the response
 *
 */
$res = routeRequest($username, $movie_id);
http_response_code($res['http_status']);
unset($res['http_status']);
echo json_encode($res, JSON_PRETTY_PRINT);
return true;

?>
