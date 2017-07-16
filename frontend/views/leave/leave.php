<?php
use yii\helpers\Html;
$this->title = 'My Yii Application';
?>



<hr>
id <?= Html::encode($user->id) ?>
<br>
名字 <?= Html::encode($user->username) ?> 
<br>
手机号码 <?= Html::encode($user->mobile) ?>
<br>
<?php foreach ($workinfo as $key): ?>

职务 <?= Html::encode($key['position']) ?>  组织 <?= Html::encode($key['department']) ?>

<?php endforeach ?>

