<?php
	$http = new swoole_http_server('0.0.0.0','8080');

	
	$onrequest = function (swoole_http_request $request,swoole_http_response $response){
		// var_dump($request);
		// var_dump($response);
		// var_dump($_SERVER);
		// debug_print_backtrace();
		$response->end('hello_word');
		// sleep(1);
	};
	$http->on('request',$onrequest);
	$http->set([
		'worker_num'=>8,
		'daemon'=>true,
		'max_request' => 0,
	]);

	echo 'start...'.PHP_EOL;
	$http->start();
