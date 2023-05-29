<?php
namespace app\back\controller;
use think\Request;
use think\Exception;
use app\common\model\PayWayModel;

class PayWay extends BackBase {
    protected $payWayModel;

    //控制层对象初始化：注入业务逻辑层对象等
    public function _initialize() {
        parent::_initialize();
        $this->request = Request::instance();
        $this->payWayModel = new PayWayModel();
    }

    /*添加支付方式信息*/
    public function add(){
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
            return view('payWay/payWay_add');
        }
    }

    /*跳转到更新界面*/
    public function modifyView() {
        $this->assign("payWayId",input("payWayId"));
        return view("payWay/payWay_modify");
    }

    /*ajax方式按照查询条件分页查询支付方式信息*/
    public function backList() {
        if($this->request->isPost()) {
            if($this->request->param("page") != null)
                $this->currentPage = $this->request->param("page");
            if($this->request->param("rows") != null)
                $this->payWayModel->setRows($this->request->param("rows"));
            $payWayRs = $this->payWayModel->queryPayWay($this->currentPage);
            $expTableData = [];
            foreach($payWayRs as $payWayRow) {
                $expTableData[] = $payWayRow;
            }
            $data["rows"] = $payWayRs;
            /*当前查询条件下总记录数*/
            $data["total"] = $this->payWayModel->recordNumber;
            echo json_encode($data);
        } else {
            return view("payWay/payWay_query");
        }
    }

    /*ajax方式查询支付方式信息*/
    public function listAll() {
        $payWayRs = $this->payWayModel->queryAllPayWay();
        echo json_encode($payWayRs);
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

    /*按照查询条件导出支付方式信息到Excel*/
    public function outToExcel() {
        $payWayRs = $this->payWayModel->queryOutputPayWay();
        $expTableData = [];
        foreach($payWayRs as $payWayRow) {
            $expTableData[] = $payWayRow;
        }
        $xlsName = "PayWay";
        $xlsCell = array(
            array('payWayId','支付方式id','int'),
            array('payWayName','支付方式名称','string'),
        );//查出字段输出对应Excel对应的列名
        //公共方法调用
        $this->export_excel($xlsName,$xlsCell,$expTableData);
    }

}

