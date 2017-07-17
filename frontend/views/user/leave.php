<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\SignupForm */

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;

$this->title = '请假单';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-leave">
    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(); ?>
    
                <?= $form->field($model, 'leave_id')->dropDownList($alltype,['prompt'=>'请选择请假类型']) ?>

                <?= $form->field($model, 'detail')->label('请假原因') ?>

                <?= $form->field($model, 'begin_time')->label('请假开始时间') ?>

                <?= $form->field($model, 'end_time')->label('请假结束时间') ?>

                <?= $form->field($model,'procer_id')->dropDownList($allprocer,['prompt'=>'请选择审批人']) ?>

                <div class="form-group">

                    <?= Html::submitButton('发送请求', ['class' => 'btn btn-primary', 'name' => 'leave-button']) ?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
