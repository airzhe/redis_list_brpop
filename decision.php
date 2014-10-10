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
    <title>决策</title>
	<link rel="stylesheet" href="css/bootstrap.min.css">
    <script src="js/do.all.src.js"></script>
	<style>
		#timer{padding:27px 15px;border:2px solid red;border-radius:50%;box-shadow:0 0 2px red;display:inline-block;width:100px;}
		img{max-width:100px;max-height:100px;}
		.row{height:120px;line-height:120px;margin-bottom:30px; box-shadow: 0 1px 5px #CCD;}
	</style>
</head>
<body>
	<div class="container">
		   <h1 class="text-center">决策倒计时</h1>
		   <h1 class="text-center"><span id="timer"><?=$order_decision?></span></h1>
		   <hr/>
		   <h4>已接单司机</h4>
		   <div class="row">
		   		<div class="col-sm-8"><img class="img-thumbnail" src="http://www.cn486.com/car/cars/tu/10Porsche/800/0028.jpg"/></div>
				<div class="col-sm-3">
						<p>魏师傅(18638162344)</p>
				</div>
				<div class="col-sm-1"><button class="btn btn-success">选他</button></div>
		   </div>
		   <div class="row">
		   		<div class="col-sm-8"><img class="img-thumbnail" src="http://www.cn486.com/car/cars/tu/10Porsche/800/0028.jpg"/></div>
				<div class="col-sm-3">魏师傅</div>
				<div class="col-sm-1"><button class="btn btn-success">选他</button></div>
		   </div>
	</div>
	<script>
		Do('common',function(){
                // 接单司机
				var user_id = <?=$user_id?>,
					order_id = <?=$order_id?>;
                function get_driver_id(){
                    $.get('ajax/order_drivers.php',{user_id:user_id,order_id:order_id},function(ret){
                        if(ret.ret_code == 200){
                            if(ret.result){
                                 var driver_id = ret.result; 
                                 $.post('ajax/get_driver_info.php',{driver_id:driver_id},function(ret){
                                    if(ret.ret_code == 200 ){
                                 		var _item = '<div class="row">\
												   		<div class="col-sm-8"><img class="img-thumbnail" src="{photo}"></div>\
														<div class="col-sm-3">\
															<p>{driver_name}({plate})</p>\
														</div>\
														<div class="col-sm-1"><button class="btn btn-success">选他</button></div>\
													</div>'; 
										var item = replaceCode(ret.result,_item);
										console.log(item);
										$(item).insertAfter('h4');
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
