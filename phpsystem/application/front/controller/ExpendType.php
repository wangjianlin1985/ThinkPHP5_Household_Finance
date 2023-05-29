<?php
namespace app\front\controller;
use think\Request;
use think\Exception;
use app\common\model\ExpendTypeModel;

class ExpendType extends Base {
    protected $expendTypeModel;

    //控制层对象初始化：注入业务逻辑层对象等
    public function _initialize() {
        parent::_initialize();
        $this->request = Request::instance();
        $this->expendTypeModel = new ExpendTypeModel();
    }

    /*添加支出类型信息*/
    public function frontAdd(){
        $message = "";
        $success = false;
        if($this->request->isPost()) {
            $expendType = $this->getExpendTypeForm(true);
            try {
                $this->expendTypeModel->addExpendType($expendType);
                $message = "支出类型添加成功!";
                $success = true;
                $this->writeJsonResponse($success, $message);
            } catch (Exception $ex) {
                $message = "支出类型添加失败!";
                $this->writeJsonResponse($success,$message);
            }
        } else {
            return $this->fetch('expendType/expendType_frontAdd');
        }
    }

    /*前台修改支出类型信息*/
    public function frontModify() {
        $this->assign("expendTypeId",input("expendTypeId"));
        return $this->fetch("expendType/expendType_frontModify");
    }

    /*前台按照查询条件分页查询支出类型信息*/
    public function frontlist() {
        if($this->request->param("currentPage") != null)
            $this->currentPage = $this->request->param("currentPage");
        $expendTypeRs = $this->expendTypeModel->queryExpendType($this->currentPage);
        $this->assign("expendTypeRs",$expendTypeRs);
        /*获取到总的页码数目*/
        $this->assign("totalPage",$this->expendTypeModel->totalPage);
        /*当前查询条件下总记录数*/
        $this->assign("recordNumber",$this->expendTypeModel->recordNumber);
        $this->assign("currentPage",$this->currentPage);
        $this->assign("rows",$this->expendTypeModel->rows);
        return $this->fetch('expendType/expendType_frontlist');
    }

    /*ajax方式查询支出类型信息*/
    public function listAll() {
        $expendTypeRs = $this->expendTypeModel->queryAllExpendType();
        echo json_encode($expendTypeRs);
    }
    /*前台查询根据主键查询一条支出类型信息*/
    public function frontshow() {
        $expendTypeId = input("expendTypeId");
        $expendType = $this->expendTypeModel->getExpendType($expendTypeId);
       $this->assign("expendType",$expendType);
        return $this->fetch("expendType/expendType_frontshow");
    }

    /*更新支出类型信息*/
    public function update() {
        $message = "";
        $success = false;
        if($this->request->isPost()) {
            $expendType = $this->getExpendTypeForm(false);
            try {
                $this->expendTypeModel->updateExpendType($expendType);
                $message = "支出类型更新成功!";
                $success = true;
                $this->writeJsonResponse($success, $message);
            } catch (Exception $ex) {
                $message = "支出类型更新失败!";
                $this->writeJsonResponse($success,$message);
            }
        } else {
            /*根据主键获取支出类型对象*/
            $expendTypeId = input("expendTypeId");
            $expendType = $this->expendTypeModel->getExpendType($expendTypeId);
            echo json_encode($expendType);
        }
    }

    /*删除多条支出类型记录*/
    public function deletes() {
        $message = "";
        $success = false;
        $expendTypeIds = input("expendTypeIds");
        try {
            $count = $this->expendTypeModel->deleteExpendTypes($expendTypeIds);
            $success = true;
            $message = $count."条记录删除成功";
            $this->writeJsonResponse($success, $message);
        } catch (Exception $ex) {
            $message = "有记录存在外键约束,删除失败";
            $this->writeJsonResponse($success, $message);
        }
    }

}

