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

?>
