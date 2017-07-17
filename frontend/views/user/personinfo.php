<?php
use yii\helpers\Html;

$this->title = '个人信息';
$this->params['breadcrumbs'][] = $this->title;
?>



<hr>
<?= Html::encode($user->id) ?>
<br>
<?= Html::encode($user->username) ?> 
|
<?= Html::encode($user->mobile) ?>
<br>
<hr>
<?php foreach ($workinfo as $key): ?>

<?= Html::encode($key['position']) ?> | <?= Html::encode($key['department']) ?>
<br>
<?php endforeach ?>
<hr>
请假次数 <?= Html::encode($leavelog['sum']) ?>
<hr>
请假成功 <?= Html::encode($leavelog['success']) ?>
 | 
请假失败 <?= Html::encode($leavelog['fail']) ?>
|
请假未审批 <?= Html::encode($leavelog['undo']) ?>
<br>
