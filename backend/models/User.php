<?php

namespace backend\models;
use yii\db\ActiveRecord;

//  返回链接相关
use yii\web\Link;
use yii\web\Linkable;
use yii\helpers\Url;

class User extends ActiveRecord{
	public static function tablename(){
		return 'user';
	}
	// 覆盖 fields方法 控制返回的字段 ，默认返回全部
	public function fields(){
        return [
            // 输出名 => 数据库字段名
        	'id' => 'id',
            'username'       => 'username',
            'mobile' => 'mobile',
            // 'name:num' => function ($model) {
            //     return $model->name . ':' . $model->num;
            // },
        ];   
	}
    // 过滤掉一些字段，适用于你希望继承父类
    //实现同时你想屏蔽掉一些敏感字段
    // public function fields()
    // {
    //     $fields = parent::fields(); //默认返回所有字段
    //     // 删除一些包含敏感信息的字段
    //     unset($fields['num']);
    //     return $fields;
    // }     	
    // 返回对象
    public function extraFields()
    {
        return ['id'];
    }	
    //实现接口Linkables getLinks方法 : 返回链接
    public function getLinks(){
    	return [
    		Link::REL_SELF => Url::to(['books', 'id' => $this->id], true),
    	];
    }
    //集合
}