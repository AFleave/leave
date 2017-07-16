<?php
use yii\helpers\Html;

$this->title = '个人信息';
$this->params['breadcrumbs'][] = $this->title;
?>



<hr>
<?= Html::encode($user->id) ?>
<br>
<?= Html::encode($user->username) ?> 
<br>
<?= Html::encode($user->mobile) ?>
<br>
<?php foreach ($workinfo as $key): ?>

<?= Html::encode($key['position']) ?> | <?= Html::encode($key['department']) ?>
<br>
<?php endforeach ?>

请假次数<?= Html::encode($leavelog['sum']) ?>
<br>
请假成功<?= Html::encode($leavelog['success']) ?>
<br>
请假失败<?= Html::encode($leavelog['fail']) ?>
<br>
请假未审批<?= Html::encode($leavelog['undo']) ?>
<br>
