<?php 
try{
	require ('init.php');
	$ret = array('ret_code'=>500,'result'=>'');
	list($key,$driver_id) = $redis->brpop('order_drivers',28);
	$ret['ret_code'] = 200;
	$ret['result'] = $driver_id;
} catch (Exception $e){
	error_log($e);
}
die(json_encode($ret));

