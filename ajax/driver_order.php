<?php 
try{
	require ('../init.php');
	$ret = array('ret_code'=>500,'ret_msg'=>'service error!');
	$driver_id = $_GET['driver_id'];
	$order_id = $_GET['order_id'];
	$redis->lpush("order:$order_id:drivers",$driver_id);
	$ret['ret_code'] = 200;
	$ret['ret_msg'] = 'success';
} catch (Exception $e){
	error_log($e);
}
die(json_encode($ret));

