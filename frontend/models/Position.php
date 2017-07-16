<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "position".
 *
 * @property integer $id
 * @property string $name
 * @property integer $user_id
 * @property integer $department_id
 * @property integer $status
 * @property integer $created_time
 *
 * @property User $user
 * @property Department $department
 */
class Position extends \yii\db\ActiveRecord
{

    const STATUS_ACTIVE = 1;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'position';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'user_id', 'department_id', 'created_time'], 'required'],
            [['user_id', 'department_id', 'status', 'created_time'], 'integer'],
            [['name'], 'string', 'max' => 20],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['department_id'], 'exist', 'skipOnError' => true, 'targetClass' => Department::className(), 'targetAttribute' => ['department_id' => 'id']],
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
            'user_id' => 'User ID',
            'department_id' => 'Department ID',
            'status' => 'Status',
            'created_time' => 'Created Time',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    // public function getUser()
    // {
    //     return $this->hasOne(User::className(), ['id' => 'user_id']);
    // }

    /**
     * @return \yii\db\ActiveQuery
     */
    // public function getDepartment()
    // {
    //     return $this->hasOne(Department::className(), ['id' => 'department_id']);
    // }

     /**
     * by user_id
     */
    public static function findbyUser_id($user_id)
    {
        return static::findOne(['user_id' => $user_id, 'status' => self::STATUS_ACTIVE]);
    }
}
