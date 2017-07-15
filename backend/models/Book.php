<?php 
namespace backend\models;

use yii\db\ActiveRecord;

class Book extends ActiveRecord{
    public static function tableName()
    {
        return 'book';
    }	
}