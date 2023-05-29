<?php
namespace app\front\controller;
use think\Request;
use think\Exception;
use app\common\model\PayWayModel;

class PayWay extends Base {
    protected $payWayModel;

    //控制层对象初始化：注入业务逻辑层对象等
    public function _initialize() {
        parent::_initialize();
        $this->request = Request::instance();
        $this->payWayModel = new PayWayModel();
    }

    /*添加支付方式信息*/
    public function frontAdd(){
        $message = "";
        $success = false;
        if($this->request->isPost()) {
            $payWay = $this->getPayWayForm(true);
            try {
                $this->payWayModel->addPayWay($payWay);
                $message = "支付方式添加成功!";
                $success = true;
                $this->writeJsonResponse($success, $message);
            } catch (Exception $ex) {
                $message = "支付方式添加失败!";
                $this->writeJsonResponse($success,$message);
            }
        } else {
            return $this->fetch('payWay/payWay_frontAdd');
        }
    }

    /*前台修改支付方式信息*/
    public function frontModify() {
        $this->assign("payWayId",input("payWayId"));
        return $this->fetch("payWay/payWay_frontModify");
    }

    /*前台按照查询条件分页查询支付方式信息*/
    public function frontlist() {
        if($this->request->param("currentPage") != null)
            $this->currentPage = $this->request->param("currentPage");
        $payWayRs = $this->payWayModel->queryPayWay($this->currentPage);
        $this->assign("payWayRs",$payWayRs);
        /*获取到总的页码数目*/
        $this->assign("totalPage",$this->payWayModel->totalPage);
        /*当前查询条件下总记录数*/
        $this->assign("recordNumber",$this->payWayModel->recordNumber);
        $this->assign("currentPage",$this->currentPage);
        $this->assign("rows",$this->payWayModel->rows);
        return $this->fetch('payWay/payWay_frontlist');
    }

    /*ajax方式查询支付方式信息*/
    public function listAll() {
        $payWayRs = $this->payWayModel->queryAllPayWay();
        echo json_encode($payWayRs);
    }
    /*前台查询根据主键查询一条支付方式信息*/
    public function frontshow() {
        $payWayId = input("payWayId");
        $payWay = $this->payWayModel->getPayWay($payWayId);
       $this->assign("payWay",$payWay);
        return $this->fetch("payWay/payWay_frontshow");
    }

    /*更新支付方式信息*/
    public function update() {
        $message = "";
        $success = false;
        if($this->request->isPost()) {
            $payWay = $this->getPayWayForm(false);
            try {
                $this->payWayModel->updatePayWay($payWay);
                $message = "支付方式更新成功!";
                $success = true;
                $this->writeJsonResponse($success, $message);
            } catch (Exception $ex) {
                $message = "支付方式更新失败!";
                $this->writeJsonResponse($success,$message);
            }
        } else {
            /*根据主键获取支付方式对象*/
            $payWayId = input("payWayId");
            $payWay = $this->payWayModel->getPayWay($payWayId);
            echo json_encode($payWay);
        }
    }

    /*删除多条支付方式记录*/
    public function deletes() {
        $message = "";
        $success = false;
        $payWayIds = input("payWayIds");
        try {
            $count = $this->payWayModel->deletePayWays($payWayIds);
            $success = true;
            $message = $count."条记录删除成功";
            $this->writeJsonResponse($success, $message);
        } catch (Exception $ex) {
            $message = "有记录存在外键约束,删除失败";
            $this->writeJsonResponse($success, $message);
        }
    }

}

