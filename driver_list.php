<?php 
    try{
            require('init.php');
            if($_POST){
                    $driver_id = $redis->incr('driver:id');
                    $driver_info['driver_id'] = $driver_id;
                    $driver_info['driver_name'] = $_POST['driver_name'];
                    $driver_info['plate'] = $_POST['plate'];
                    $driver_info['photo'] = $_POST['photo'];
                    $redis->hmset("driver:$driver_id",$driver_info);
                    $redis->lpush('driver:list',$driver_id);
            }
            $driver_id_list = $redis->lrange('driver:list',0,-1);
            if($driver_id_list){
                foreach($driver_id_list as $v){
                    $driver_list[] = $redis->hgetall("driver:$v");
                }
            }
    }catch(Exception $e){
             error_log($e); 
    } 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>司机列表</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <style>
        .table.noborder td{border-width:0;}
        tr th,tr td{text-align:center}
        .img-responsive{max-width:30px;max-height:30px;}
    </style>
    <script src="js/do.all.src.js"></script>
</head>
<body>
    <div class="container">
    <h4 class="text-primary">创建司机</h4>
    <hr/>
    <form method="post">
    <table class="table noborder">
        <tr>
            <td>姓名：</td><td><input type="text" name="driver_name" class="form-control" required/></td>
            <td>车牌号：</td><td><input type="text" name="plate" class="form-control" required/></td>
        </tr>
        <tr>
            <td>照片：</td><td colspan='3'><input type="text" name="photo" class="form-control" required/></td>
        </tr>
    </table>
    <p class="text-center"><button class="btn btn-info">创建</button></p>
    </form>
    <h4 class="text-primary">司机列表</h4>
    <hr/>
    <table class="table table-bordered">
        <tr>
            <th>ID</th>
            <th>姓名</th>
            <th>车牌</th>
            <th>照片</th>
            <th>操作</th>
        </tr>
        <?php if($driver_list):?>
                <?php foreach($driver_list as $v):?>
                <tr>
                    <td><?=$v['driver_id']?></td>
                    <td><?=$v['driver_name']?></td>
                    <td><?=$v['plate']?></td>
                    <td><a href="<?=$v['photo']?>" target="_blank"><img src="<?=$v['photo']?>" class="img-responsive"/></a></td>
                    <td><a href="driver.php?driver_id=<?=$v['driver_id']?>" target="_blank">详情</a></td>
                </tr>
                <?php endforeach?>
        <?php endif?>
    </table>
    </div>
</body>
</html>
