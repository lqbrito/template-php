<?php
	require '../vendor/slim/slim/Slim/Slim.php';
	\Slim\Slim::registerAutoloader();
	$app = new \Slim\Slim();
	$app->response()->header('Content-Type', 'application/json;charset=utf-8');

	$app->get('/', function () {
		echo "Testando API Restful";
	});

	$app->run();