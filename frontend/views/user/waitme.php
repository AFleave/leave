<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
$this->title = '待我审批';
$this->params['breadcrumbs'][] = $this->title;
?>
<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
<script type="text/javascript">
    $(function(){
        $("#refuse1").click(function(){ 
  			$.ajax({
                    type:"post",
                    url:"http://localhost/myproducts/leave/frontend/web/index.php?r=user/acceptexam",
                    data:{
                        log_id : "1",
                    	process_id : "1",
                    	status : "2",
                        desc : "我拒绝你请假",
                },
                    dataType:"text",
                    success: function(data) {
                    	if(data==1){
                        alert("驳回完成！");
                        location.reload('http://localhost/myproducts/leave/frontend/web/index.php?r=user/waitme');
                    }
                    },
                    error:function() {
                        console.log("驳回失败，请重试！");
                    }
              	})
       	});

                $("#agreen1").click(function(){ 
            $.ajax({
                    type:"post",
                    url:"http://localhost/myproducts/leave/frontend/web/index.php?r=user/acceptexam",
                    data:{
                        log_id : "1",
                        process_id : "1",
                        status : "1",
                        desc : "我同意你请假",
                },
                    dataType:"text",
                    success: function(data) {
                        if(data==1){
                        alert("同意完成！");
                        location.reload('http://localhost/myproducts/leave/frontend/web/index.php?r=user/waitme');
                    }
                    },
                    error:function() {
                        console.log("同意失败，请重试！");
                    }
                })
        });

        $("#send1").click(function(){ 
            $.ajax({
                    type:"post",
                    url:"http://localhost/myproducts/leave/frontend/web/index.php?r=user/acceptexam",
                    data:{
                        process_id : "1",
                        status : "3",
                        addid : "6",
                        sort : "2",
                        log_id : "1",
                        desc : "我转交你请假",
                },
                    dataType:"text",
                    success: function(data) {
                        if(data==1){
                        alert("转交完成！");
                        location.reload('http://localhost/myproducts/leave/frontend/web/index.php?r=user/waitme');
                    }else{
                        alert("转交失败！");
                    }
                    },
                    error:function() {
                        console.log("转交失败，请重试！");
                    }
                })
        });
    });
</script>

 process_id | 
 process_sort | 
 leavelog_id | 
 请假人 | 
 类型 |
 细节 |
 起始时间 |
 结束时间 |
 创建时间 |
<br>
<br>
<hr>
待处理的审批
<hr>
<?php foreach ($processs as $process): ?>
<?= Html::encode($process['id'])  ?>|
<?= Html::encode($process['sort']) ?>|
<?= Html::encode($process['log_id']['id']) ?>|
<?= Html::encode($process['log_id']['initiator_id']) ?> | 
<?= Html::encode($process['log_id']['leave_id']) ?> |
<?= Html::encode($process['log_id']['detail']) ?> |
<?= Html::encode($process['log_id']['begin_time']) ?> |
<?= Html::encode($process['log_id']['end_time']) ?> |
<?= Html::encode(date("Y-m-d H:i:s ",$process['log_id']['create_time'])) ?> 
<button type="button" id="agreen<?= Html::encode($process['id'])  ?>">同意</button>
<button type="button" id="refuse<?= Html::encode($process['id'])  ?>">驳回</button>
<button type="button" id="send<?= Html::encode($process['id'])  ?>">转交</button>
<br>
<br>
<?php endforeach ?>
<hr>
已处理的审批
<hr>
<?php foreach ($haves as $have): ?>
<?= Html::encode($have['id'])  ?>|
<?= Html::encode($have['sort']) ?>|
<?= Html::encode($have['log_id']['id']) ?>|
<?= Html::encode($have['log_id']['initiator_id']) ?> | 
<?= Html::encode($have['log_id']['leave_id']) ?> |
<?= Html::encode($have['log_id']['detail']) ?> |
<?= Html::encode($have['log_id']['begin_time']) ?> |
<?= Html::encode($have['log_id']['end_time']) ?> |
<?= Html::encode(date("Y-m-d H:i:s ",$have['log_id']['create_time'])) ?> |
<?= Html::encode($have['sort'])?>|

<?php
if ($have['status']==1){
 echo "已同意";
 }elseif ($have['status']==2){
 echo "已驳回";
}elseif ($have['status']==3){
 echo "已转交";
} 
?>
 | 处理时间：<?= Html::encode(date("Y-m-d H:i:s ",$have['updata_time'])) ?> |
<br>
<br>
<?php endforeach ?>
<?php
