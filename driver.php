<?php
    try{
        $driver_id = (int)$_GET['driver_id'];
        if(!$driver_id){
            die('param error');
        }
        require('init.php');
        $driver = $redis->hgetall("driver:$driver_id");
        if(!$driver){
            die('司机不存在');
        }
    }catch(Exception $e){
        error_log($e);
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>司机面板</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <style>
        tr th,tr td{text-align:center}
        .img-circle{box-shadow:1px 1px 5px #ccc;}
    </style>
    <script src="js/do.all.src.js"></script>
</head>
<body>
    <dir class="container">
    <h4 class="text-primary text-center">司机信息</h4>
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
                <div class="col-md-6">
                    <img class="img-responsive img-circle" src="<?=$driver['photo']?>" />
                </div>
                <div class="col-md-6" style="margin-top:15px">
                    <div><?=$driver['driver_name']?></div>
                    <div><?=$driver['plate']?></div>
                </div>
        </div>
    </div>
    <h4 class="text-primary">订单信息</h4>
    <table class="table table-bordered">
        <tr>
            <th>#</th>
            <th>订单号</th>
            <th>上车点</th>
            <th>下车点</th>
            <th>乘车人</th>
            <th>操作</th>
        </tr>
    </table> 
	<script>
          Do('common',function(){
                function get_driver_id(){
                    $.get('ajax/order_list.php',function(ret){
                        if(ret.ret_code == 200){
                            if(ret.result){
                                 var user_id = ret.user_id,
                                     order_id = ret.order_id,
                                    _tr = '<tr data-orderid={order_id}>\
                                          <td><span class="text-success">新订单</span><span class="text-info hide">已接单</span></td>\
                                          <td>{order_id}</td>\
                                          <td>{start_position}</td>\
                                          <td>{end_position}</td>\
                                          <td>user:id:{user_id}</td>\
                                          <td><button class="btn btn-success">接单</button><span class="text-infoi hide">#</span></td>\
                                          </tr>';
                                 var tr = replaceCode(ret.result,_tr);
                                 $(tr).insertAfter($('table tr:first-child')); 
                                 };
                            }
                            get_driver_id();
                    },'json');
                }
                
                get_driver_id();
                //接单
                $('table').on('click','tr button',function(){
					var self = $(this);
					var tr = self.parents('tr');
					var driver_id = <?=$driver_id?>;
					var order_id  = tr.data('orderid');
					$.get('ajax/driver_order.php',{order_id:order_id,driver_id:driver_id},function(ret){
						if(ret.ret_code == 200){
							tr.find('td').first().find('.text-success').hide().next('span').removeClass('hide');                    
							self.hide().next('span').removeClass('hide');
						}else{
							alert('error!');
						}	
					},'json')
                })
		})
	</script>
    </div>
</body>
</html>
