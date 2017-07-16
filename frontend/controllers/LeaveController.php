<?php
namespace frontend\controllers;

use yii\web\Controller;
use frontend\models\Leave;

/**
 * leave controller
 */
class LeaveController extends Controller
{


    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionType()
    {
    	$type=Leave::findleave();
    	print_r($type);
    	//return $this->render('leave', ['type' => $type]);
    }



}