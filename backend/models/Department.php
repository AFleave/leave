<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "department".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property integer $status
 * @property integer $create_time
 * @property integer $updata_time
 *
 * @property Position[] $positions
 */
class Department extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'department';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'name', 'create_time'], 'required'],
            [['id', 'status', 'create_time', 'updata_time'], 'integer'],
            [['name'], 'string', 'max' => 20],
            [['description'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'description' => 'Description',
            'status' => 'Status',
            'create_time' => 'Create Time',
            'updata_time' => 'Updata Time',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPositions()
    {
        return $this->hasMany(Position::className(), ['department_id' => 'id']);
    }
    public function fields(){
        $fields = parent::fields();
        unset($fields['create_time'],$fields['status']);
        return $fields;
    }
    //insert是什么意思
    // public function beforeSave($update){
    //     if(parent::beforeSave($insert)){

    //     }
    // }
    // public function beforeValidate(){

    // }
    // public function afterValidate(){
        
    // }
}
