<?php 
try{
	require ('init.php');
	$ret = array('ret_code'=>500,'result'=>'');
	list($key,$order) = $redis->brpop('order:list',28);
	$ret['ret_code'] = 200;
	$ret['result'] = $order?$redis->hgetall($order):NULL;
} catch (Exception $e){
	error_log($e);
}
die(json_encode($ret));

