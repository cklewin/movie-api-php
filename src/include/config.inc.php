<?php

define('MOVIE_CONSTRAINTS', array(
	'required_params' => array(
		'title',
		'format',
		'length',
		'release_year',
		'rating'
	),
	'format_list' => array(
		'DVD',
		'Streaming',
		'VHS'
	),
	'title_length' => array(
		'min' => 1,
		'max' => 50,
	),
	'length' => array(
		'min' => 0,
		'max' => 500,
	),
	'release_year' => array(
		'min' => 1800,
		'max' => 2100,
	),
	'rating' => array(
		'min' => 1,
		'max' => 5,
	),
));

?>
