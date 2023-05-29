<?php
namespace app\front\controller;
use think\Request;
use think\Exception;
use app\common\model\IncomeTypeModel;

class IncomeType extends Base {
    protected $incomeTypeModel;

    //控制层对象初始化：注入业务逻辑层对象等
    public function _initialize() {
        parent::_initialize();
        $this->request = Request::instance();
        $this->incomeTypeModel = new IncomeTypeModel();
    }

    /*添加收入分类信息*/
    public function frontAdd(){
        $message = "";
        $success = false;
        if($this->request->isPost()) {
            $incomeType = $this->getIncomeTypeForm(true);
            try {
                $this->incomeTypeModel->addIncomeType($incomeType);
                $message = "收入分类添加成功!";
                $success = true;
                $this->writeJsonResponse($success, $message);
            } catch (Exception $ex) {
                $message = "收入分类添加失败!";
                $this->writeJsonResponse($success,$message);
            }
        } else {
            return $this->fetch('incomeType/incomeType_frontAdd');
        }
    }

    /*前台修改收入分类信息*/
    public function frontModify() {
        $this->assign("typeId",input("typeId"));
        return $this->fetch("incomeType/incomeType_frontModify");
    }

    /*前台按照查询条件分页查询收入分类信息*/
    public function frontlist() {
        if($this->request->param("currentPage") != null)
            $this->currentPage = $this->request->param("currentPage");
        $incomeTypeRs = $this->incomeTypeModel->queryIncomeType($this->currentPage);
        $this->assign("incomeTypeRs",$incomeTypeRs);
        /*获取到总的页码数目*/
        $this->assign("totalPage",$this->incomeTypeModel->totalPage);
        /*当前查询条件下总记录数*/
        $this->assign("recordNumber",$this->incomeTypeModel->recordNumber);
        $this->assign("currentPage",$this->currentPage);
        $this->assign("rows",$this->incomeTypeModel->rows);
        return $this->fetch('incomeType/incomeType_frontlist');
    }

    /*ajax方式查询收入分类信息*/
    public function listAll() {
        $incomeTypeRs = $this->incomeTypeModel->queryAllIncomeType();
        echo json_encode($incomeTypeRs);
    }
    /*前台查询根据主键查询一条收入分类信息*/
    public function frontshow() {
        $typeId = input("typeId");
        $incomeType = $this->incomeTypeModel->getIncomeType($typeId);
       $this->assign("incomeType",$incomeType);
        return $this->fetch("incomeType/incomeType_frontshow");
    }

    /*更新收入分类信息*/
    public function update() {
        $message = "";
        $success = false;
        if($this->request->isPost()) {
            $incomeType = $this->getIncomeTypeForm(false);
            try {
                $this->incomeTypeModel->updateIncomeType($incomeType);
                $message = "收入分类更新成功!";
                $success = true;
                $this->writeJsonResponse($success, $message);
            } catch (Exception $ex) {
                $message = "收入分类更新失败!";
                $this->writeJsonResponse($success,$message);
            }
        } else {
            /*根据主键获取收入分类对象*/
            $typeId = input("typeId");
            $incomeType = $this->incomeTypeModel->getIncomeType($typeId);
            echo json_encode($incomeType);
        }
    }

    /*删除多条收入分类记录*/
    public function deletes() {
        $message = "";
        $success = false;
        $typeIds = input("typeIds");
        try {
            $count = $this->incomeTypeModel->deleteIncomeTypes($typeIds);
            $success = true;
            $message = $count."条记录删除成功";
            $this->writeJsonResponse($success, $message);
        } catch (Exception $ex) {
            $message = "有记录存在外键约束,删除失败";
            $this->writeJsonResponse($success, $message);
        }
    }

}

