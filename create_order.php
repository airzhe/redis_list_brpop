<?php if(!$_POST):?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <title>创建订单</title>
            <link rel="stylesheet" href="css/bootstrap.min.css">
            <style>
            </style>
        </head>
        <body>
            <div class="container">
            <h4>创建订单</h4>
            <hr/>
            <form method="post">
                <p class="form-inline"><input type="text" name="start_position" class="form-control" placeholder="上车地点" required/></p>
                <p class="form-inline"><input type="text" name="end_position" class="form-control" placeholder="下车地点" required/></p>
                <p class="text-center"><button class="btn btn-default">创建订单</button></p>
            </form>
            </div>
        </body>
        </html>
<?php else:
    try{
            require('init.php');
            $user_id = $_GET['user_id'];
            if(!$user_id) die('user_id is empty');
            $order_id = time().rand(0,10000);
            $order_info = array('start_position'=>$_POST['start_position'],'end_position'=>$_POST['end_position'],'user_id'=>$user_id,'order_id'=>$order_id);
            $key = "user:$user_id:order:$order_id";
            //订单详情
            $redis->hmset($key,$order_info);
            $redis->expire($key,60*60);
            //将订单写入订单队列
            $redis->lpush('order:list',$key);
            header("Location:decision.php?user_id=$user_id&order_id=$order_id");
    }catch(Exception $e){
            error_log($e);
            echo '订单创建失败';
    }

endif?>
