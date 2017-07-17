<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
$this->title = '我的请假';
$this->params['breadcrumbs'][] = $this->title;
?>
<hr>
审批中
<hr>

<?php foreach ($myleaveundos as $myleaveundo): ?>
<hr>
<?= Html::encode($myleaveundo['id'])  ?>| 
<?= Html::encode($myleaveundo['initiator_id']) ?>| 
<?= Html::encode($myleaveundo['detail']) ?> |  
<?= Html::encode($myleaveundo['begin_time']) ?> | 
<?= Html::encode($myleaveundo['end_time']) ?> | 
<?= Html::encode(date("Y-m-d H:i:s ",$myleaveundo['create_time'])) ?> <br>
<hr>
<?php foreach ($myleaveundo['processs'] as $process): ?>
        <?= Html::encode($process['id'])  ?>   |
        <?= Html::encode($process['user_id'])  ?> |
        <?= Html::encode($process['status'])  ?> |
       描述 <?= Html::encode($process['desc'])  ?> |
<?= Html::encode(date("Y-m-d H:i:s ",$process['updata_time'])) ?><br>        
<?php endforeach ?>
<br>
<br>
<?php endforeach ?>


<hr>
我的请假记录
<hr>


<?php foreach ($myleavedones as $myleavedone): ?>
<hr>
<?= Html::encode($myleavedone['id'])  ?>| 
<?= Html::encode($myleavedone['initiator_id']) ?>| 
<?= Html::encode($myleavedone['detail']) ?> |  
<?= Html::encode($myleavedone['begin_time']) ?> | 
<?= Html::encode($myleavedone['end_time']) ?> | 
<?= Html::encode(date("Y-m-d H:i:s ",$myleavedone['create_time'])) ?> |
<?= Html::encode($myleavedone['status']) ?> 
<hr>
<?php foreach ($myleavedone['processs'] as $process): ?>
        <?= Html::encode($process['id'])  ?>   |
        <?= Html::encode($process['user_id'])  ?> |
        <?= Html::encode($process['status'])  ?> |
       描述 <?= Html::encode($process['desc'])  ?> |
<?= Html::encode(date("Y-m-d H:i:s ",$process['updata_time'])) ?><br>        
<?php endforeach ?>
<br>
<br>
<?php endforeach ?>
