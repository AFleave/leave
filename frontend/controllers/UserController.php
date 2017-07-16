<?php
namespace frontend\controllers;
use Yii;
use frontend\models\User;
use frontend\models\Position;
use yii\web\Controller;
use frontend\models\Department;
use yii\filters\AccessControl;
use frontend\models\LeaveLog;
use frontend\models\Process;
use frontend\models\leave;

/**
 * User controller
 */
class UserController extends Controller
{
    const STATUS_ACTIVE = 1;

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['personinfo','waitme','acceptexam'],
                'rules' => [
                    // 允许认证用户
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    // 默认禁止其他用户
                ],
            ],
        ];
    }

    /*
	获取当前用户信息 by mobile
    */
	public function actionPersoninfo(){
        $mobile=Yii::$app->user->identity->mobile;
		$user=User::findByMobile($mobile);
        $workinfo=User::findWorkinfo($user->id);
        $leavelog=LeaveLog::findLogtime($user->id);
		return $this->render('personinfo', ['user' => $user,'workinfo'=>$workinfo,'leavelog'=>$leavelog]);
	}

    /*
    获取当前用户未审批别人的processs by id  和已审批别人的按时间取五条
    */
    public function actionWaitme(){
        $user_id = Yii::$app->user->identity->id;
        $processs = Process::findWaitme($user_id);
        $i=0;
        foreach ($processs as $process) {
            $processs[$i]['log_id']=LeaveLog::findbyid($process['log_id']);
            $processs[$i]['log_id']['initiator_id']=User::findOne(['id'=>$processs[$i]['log_id']['initiator_id']])->username;
            $processs[$i]['log_id']['leave_id']=leave::findOne(['id'=>$processs[$i]['log_id']['leave_id']])->type;
            $i++;
        }

        $haves = Process::findHavedbyme($user_id);//只返回五条
        $i=0;
        foreach ($haves as $have) {
            $haves[$i]['log_id']=LeaveLog::findbyid($have['log_id']);
            $haves[$i]['log_id']['initiator_id']=User::findOne(['id'=>$haves[$i]['log_id']['initiator_id']])->username;
            $haves[$i]['log_id']['leave_id']=leave::findOne(['id'=>$haves[$i]['log_id']['leave_id']])->type;
            $i++;
        }
        return $this->render('waitme',['processs'=>$processs,'haves'=>$haves]);
    }


    /*
    接收当前用户 post审批请假请求 
    */
    public function actionAcceptexam(){
        $request = Yii::$app->request;
        $process_id = $request->post('process_id');
        //$leavelog_id = $request->post('leavelog_id');
        $status = $request->post('status');
        if(Process::updateStatudesc($process_id,$status)){
            return 1;
        }else{
            return 0;
        }
    }
}