<?php
namespace backend\controllers;

use yii\rest\ActiveController;

class BookController extends ActiveController{
	public $modelClass = 'backend\models\Book';
}
