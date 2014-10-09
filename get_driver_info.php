<?php
try{
    require ('init.php');
    $ret = array('ret_code'=>500,'result'=>'');
    $driver_id = $_POSET['driver_id'];
    if($driver_info = $redis->hgetall("driver:$driver_id")){
        $ret['code'] = 200;
        $ret['result'] = $driver_info;
    }
}catch(Exception $e){
    error_log($e);
}
die(json_encode($ret));
