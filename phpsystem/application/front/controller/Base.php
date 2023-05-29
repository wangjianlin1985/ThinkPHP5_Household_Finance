<?php
namespace app\front\controller;
use think\Controller;

class Base extends Controller
{
    protected $currentPage = 1;
    protected $request = null;

    //向客户端输出ajax响应结果
    public function writeJsonResponse($success, $message) {
        $response = array(
            "success" => $success,
            "message" => $message,
        );
        echo json_encode($response);
    }

    /**
     * @param $obj:  保存图片路径的对象
     * @param $indexName 索引名称
     * @param $photoFiledName 提交的图片表单名称
     */
    public function uploadPhoto(&$obj,$indexName,$photoFiledName) {
        if($_FILES[$photoFiledName]['tmp_name']){
            $file = $this->request->file($photoFiledName);
            //控制上传的文件类型，大小
            if(!(($_FILES[$photoFiledName]["type"]=="image/jpeg"
                    || $_FILES[$photoFiledName]["type"]=="image/jpg"
                    || $_FILES[$photoFiledName]["type"]=="image/png") && $_FILES[$photoFiledName]["size"] < 1024000)) {
                $message = "图书图片请选择jpg或png格式的图片!";
                $this->writeJsonResponse(false,$message);
                exit;
            }
            $file->setRule("short"); //文件路径采用简短方式
            $info = $file->move(ROOT_PATH . 'public' . DS . 'upload');
            $obj[$indexName]='upload/'.$info->getSaveName();
        }
    }

    /**
     * @param $obj:  保存文件路径的对象
     * @param $indexName 索引名称
     * @param $resourceFiledName 提交的文件表单名称
     */
    public function uploadFile(&$obj,$indexName,$resourceFiledName) {
        if($_FILES[$resourceFiledName]['tmp_name']){
            $file = $this->request->file($resourceFiledName);
            $file->setRule("short"); //文件路径采用简短方式
            $info = $file->move(ROOT_PATH . 'public' . DS . 'upload');
            $obj[$indexName]='upload/'.$info->getSaveName();
        }
    }

    //接收提交的UserInfo信息参数
    public function getUserInfoForm($insertFlag) {
        $userInfo = [
            'user_name'=> input("userInfo_user_name"), //用户名
            'password'=> input("userInfo_password"), //登录密码
            'name'=> input("userInfo_name"), //姓名
            'gender'=> input("userInfo_gender"), //性别
            'birthDate'=> input("userInfo_birthDate"), //出生日期
            'userPhoto' => $insertFlag==true?"upload/NoImage.jpg":input("userInfo_userPhoto"), //用户照片
            'telephone'=> input("userInfo_telephone"), //联系电话
            'email'=> input("userInfo_email"), //邮箱
            'address'=> input("userInfo_address"), //家庭地址
            'regTime'=> input("userInfo_regTime"), //注册时间
        ];
        return $userInfo;
    }

    //接收提交的IncomeType信息参数
    public function getIncomeTypeForm($insertFlag) {
        $incomeType = [
            'typeId'=> input("incomeType_typeId"), //分类id
            'typeName'=> input("incomeType_typeName"), //分类名称
        ];
        return $incomeType;
    }

    //接收提交的Income信息参数
    public function getIncomeForm($insertFlag) {
        $income = [
            'incomeId'=> input("income_incomeId"), //收入id
            'incomeTypeObj'=> input("income_incomeTypeObj_typeId"), //收入类型
            'incomeFrom'=> input("income_incomeFrom"), //收入来源
            'payWayObj'=> input("income_payWayObj_payWayId"), //支付方式
            'payAccount'=> input("income_payAccount"), //支付账号
            'incomeMoney'=> input("income_incomeMoney"), //收入金额
            'incomeDate'=> input("income_incomeDate"), //收入日期
            'userObj'=> input("income_userObj_user_name"), //收入用户
            'incomeMemo'=> input("income_incomeMemo"), //收入备注
        ];
        return $income;
    }

    //接收提交的ExpendType信息参数
    public function getExpendTypeForm($insertFlag) {
        $expendType = [
            'expendTypeId'=> input("expendType_expendTypeId"), //支出类型id
            'expendTypeName'=> input("expendType_expendTypeName"), //支出类型名称
        ];
        return $expendType;
    }

    //接收提交的Expend信息参数
    public function getExpendForm($insertFlag) {
        $expend = [
            'expendId'=> input("expend_expendId"), //支出id
            'exprendTypeObj'=> input("expend_exprendTypeObj_expendTypeId"), //支出类型
            'expendPurpose'=> input("expend_expendPurpose"), //支出用途
            'payWayObj'=> input("expend_payWayObj_payWayId"), //支付方式
            'payAccount'=> input("expend_payAccount"), //支付账号
            'expendMoney'=> input("expend_expendMoney"), //支付金额
            'expendDate'=> input("expend_expendDate"), //支付日期
            'userObj'=> input("expend_userObj_user_name"), //支出用户
            'expendMemo'=> input("expend_expendMemo"), //支出备注
        ];
        return $expend;
    }

    //接收提交的PayWay信息参数
    public function getPayWayForm($insertFlag) {
        $payWay = [
            'payWayId'=> input("payWay_payWayId"), //支付方式id
            'payWayName'=> input("payWay_payWayName"), //支付方式名称
        ];
        return $payWay;
    }

    //接收提交的Notice信息参数
    public function getNoticeForm($insertFlag) {
        $notice = [
            'noticeId'=> input("notice_noticeId"), //公告id
            'title'=> input("notice_title"), //标题
            'content'=> input("notice_content"), //公告内容
            'publishDate'=> input("notice_publishDate"), //发布时间
        ];
        return $notice;
    }

}

