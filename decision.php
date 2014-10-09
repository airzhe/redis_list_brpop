<?php 
    try{
        require('init.php');
        $user_id = $_GET['user_id'];
        $order_id = $_GET['order_id']; 
        if(!$user_id || !$order_id){
            die('param error!');
        }
        // 订单决策倒计时
        $order_decision_key = "order:$order_id:decision";
        if(!$redis->exists($order_decision_key)){
            $redis->setex($order_decision_key,150,'');
        }
        $order_decision = $redis->ttl($order_decision_key);
    }catch(Exception $e){
        error_log($e);
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>jie dan ceshi</title>
    <script src="http://common.cnblogs.com/script/jquery.js"></script>
</head>
<body>
   决策倒计时：<span id="timer"><?=$order_decision?></span> 
	<script>
		$('document').ready(function(){
                // 接单司机
				var user_id = <?=$user_id?>,
					order_id = <?=$order_id?>;
                function get_driver_id(){
                    $.get('order_drivers.php',{user_id:user_id,order_id:order_id},function(ret){
                        if(ret.ret_code == 200){
                            if(ret.result){
                                 var driver_id = ret.result; 
                                 $.post('get_driver_info.php',{driver_id:driver_id},function(ret){
                                    if(ret.ret_code == 200 ){
                                        
                                    }
                                 },'json');
                            }
                            get_driver_id();
                        }
                    },'json');
                }
                
                get_driver_id();
                // 接单倒计时
                var timer = $('#timer');
                function decision(){
                    timer.html( timer.html()-1);    
                    if(timer.html() > 0){
                        setTimeout( decision, 1000);
                    }
                }
                decision();

		})
	</script>
</body>
</html>
