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
use frontend\models\leaveForm;
use yii\helpers\ArrayHelper;
/**
 * User controller
 */
class UserController extends Controller
{
    const STATUS_ACTIVE = 1;
    const PROCESS_SEND = 3;

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['personinfo','waitme','acceptexam','myleave','leave'],
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
        // echo "<pre>";
        // print_r($haves);
        return $this->render('waitme',['processs'=>$processs,'haves'=>$haves]);
    }


    /*
    接收当前用户 post审批请假请求 
    */
    public function actionAcceptexam(){
        $request = Yii::$app->request;
        $process_id = $request->post('process_id');
        $status = $request->post('status');
        $desc = $request->post('desc');
        $log_id = $request->post('log_id');
        if($status==self::PROCESS_SEND){
            $addid = $request->post('addid'); //如果是转交则需新添process记录
            $sort = $request->post('sort');
            $addresult=Process::addProcess($addid,$log_id,$sort+1);//转交  新process的sort在上一级的sort上+1
            $upresult=Process::updateStatudesc($process_id,$status,$desc);
            if( $addresult && $upresult){
                return 1;
            }else{
                return 0;
            }
        }else{
            $upProcess=Process::updateStatudesc($process_id,$status,$desc);//同意或驳回 需要更新该leave_log状态
            $uplog=Leavelog::updatestatus($log_id,$status); //因为 状态都是一样含义 1同意 2驳回  所以直接把状态传过去
            if($upProcess && $uplog){
                return 1;
            }else{
                return 0;
            }
        }
    }


    /*
    *获取当前用户请假信息(处于流程中和已处理完成的)
    */
    public  function actionMyleave(){
         $user_id = Yii::$app->user->identity->id;
         $myleaveundos=LeaveLog::findleaveundo($user_id);//未完成的
         $i=0;
         foreach ($myleaveundos as $myleaveundo) {
            $myleaveundos[$i]['initiator_id']=User::findOne(['id'=>$myleaveundo['initiator_id']])->username;
            $myleaveundos[$i]['processs']=Process::findMyleave($myleaveundo['id']);
            $i++;
         }

        $myleavedones=LeaveLog::findleavedone($user_id);//已完成完成的
        $i=0;
        foreach ($myleavedones as $myleavedone) {
            $myleavedones[$i]['initiator_id']=User::findOne(['id'=>$myleavedone['initiator_id']])->username;
            $myleavedones[$i]['processs']=Process::findMyleave($myleavedone['id']);
            $i++;
        }
         //echo "<pre>";
         //print_r($myleaveundos);
         //print_r($myleavedones);
         return $this->render('myleave',['myleaveundos'=>$myleaveundos,'myleavedones'=>$myleavedones]);
    }

    /*
    *获取当前用户请假表单
    */
    public function actionLeave()
    {
        $initiator_id=Yii::$app->user->identity->id;
        $model = new LeaveForm();
        $model ->initiator_id = $initiator_id;
        $allType=ArrayHelper::map(leave::findtype(),'id','type');
        $allProcer =ArrayHelper::map(User::findProcer($initiator_id),'id','username');
        if ($model->load(Yii::$app->request->post())) {
            $leavelog=$model->addleavelog();
            if(Process::addProcess($model->procer_id,$leavelog->id,$sort=1)){
               $this->redirect(['user/myleave']); 
            }
        }

       return $this->render('leave', ['model' => $model,'alltype'=>$allType,'allprocer'=>$allProcer]);
    }
}