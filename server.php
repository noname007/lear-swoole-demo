<?php
// 	$a = new swoole_server($host, $port);
// swoole_server 类 学习资料



// server 构建流程
// http://wiki.swoole.com/wiki/page/2.html
// 构建Server对象
// 设置运行时参数
// 注册事件回调函数
// 启动服务器
	$server = new swoole_server("127.0.0.1", 8000, SWOOLE_BASE,SWOOLE_TCP);
	$server->set(['worker_num'=>4]);
	
// 事件回调函数
// 连接连过来的时候
// 发送过来数据的时候
// 前端关闭的时候分别触发执行
	
	$server->on("Connect",function (){
		echo 'connected ',PHP_EOL;
		$argv = func_get_args();
		print_r($argv);
// 		notify_connection_message();
		_log();
	});
	
	
	$server->on("Receive", function (){
		echo 'Receive ',PHP_EOL;
		$argu = func_get_args();
// 		print_r($argu);
		_log();
		$server = $argu[0];
		foreach ($server->connections as $fd){
			$server->send($fd,'hello');
		}
	});
	
	$server->on("Close", function (){
		echo 'Close ',PHP_EOL;
		$argv = func_get_args();
		print_r($argv);
		_log();
	});
	
	$server->on("ManagerStart",function (){
		$argv = func_get_args();
		print_r($argv);
		echo 'Manager worker Start',PHP_EOL,__LINE__;
	});
	
	
	$server->on("ManagerStop",function (){
		$argv = func_get_args();
		print_r($argv);
		echo "Manager worker stop",PHP_EOL,__LINE__;
	});
	
	
	
	
	
	function _log(){
		global $server;
		
		echo '进程相关信息 ',PHP_EOL,
		 '[',date('Y-m-d H:i:s'),'] 主进程 id ',$server->master_pid,';',
			' 管理进程pid: ',$server->manager_pid,
			$server->taskworker ? ' task 进程id: ': ' workder 进程 id: ',
			' ',$server->worker_pid,';',
			' 当前Worker进程的编号: ',$server->worker_id
		;
		echo PHP_EOL,'连接 信息:',PHP_EOL;
		// foreach ($server->connections as $fd){
		// 	$info = $server->connection_info($fd);
		// 	print_r($info);
		// }
	}
	
	function  notify_connection_message($msg = ""){
		global  $server;
		foreach ($server->connections as $fd){
			$server->send($fd,"Hello");
		}
		echo "当前共有 :",count($server->connections),' 个连接;',PHP_EOL;
		print_r($server);
// 		print_r($server->connections);
	}

	$server->start();
	