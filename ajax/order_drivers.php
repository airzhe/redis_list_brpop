<?php 
try{
	require ('../init.php');
	$order_id = $_GET['order_id'];
	$ret = array('ret_code'=>500,'result'=>'');
	list($key,$driver_id) = $redis->brpop("order:$order_id:drivers",28);
	$ret['ret_code'] = 200;
	$ret['result'] = $driver_id;
} catch (Exception $e){
	error_log($e);
}
die(json_encode($ret));

