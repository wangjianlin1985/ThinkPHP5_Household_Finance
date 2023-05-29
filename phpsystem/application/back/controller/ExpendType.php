<?php
namespace app\back\controller;
use think\Request;
use think\Exception;
use app\common\model\ExpendTypeModel;

class ExpendType extends BackBase {
    protected $expendTypeModel;

    //控制层对象初始化：注入业务逻辑层对象等
    public function _initialize() {
        parent::_initialize();
        $this->request = Request::instance();
        $this->expendTypeModel = new ExpendTypeModel();
    }

    /*添加支出类型信息*/
    public function add(){
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
            return view('expendType/expendType_add');
        }
    }

    /*跳转到更新界面*/
    public function modifyView() {
        $this->assign("expendTypeId",input("expendTypeId"));
        return view("expendType/expendType_modify");
    }

    /*ajax方式按照查询条件分页查询支出类型信息*/
    public function backList() {
        if($this->request->isPost()) {
            if($this->request->param("page") != null)
                $this->currentPage = $this->request->param("page");
            if($this->request->param("rows") != null)
                $this->expendTypeModel->setRows($this->request->param("rows"));
            $expendTypeRs = $this->expendTypeModel->queryExpendType($this->currentPage);
            $expTableData = [];
            foreach($expendTypeRs as $expendTypeRow) {
                $expTableData[] = $expendTypeRow;
            }
            $data["rows"] = $expendTypeRs;
            /*当前查询条件下总记录数*/
            $data["total"] = $this->expendTypeModel->recordNumber;
            echo json_encode($data);
        } else {
            return view("expendType/expendType_query");
        }
    }

    /*ajax方式查询支出类型信息*/
    public function listAll() {
        $expendTypeRs = $this->expendTypeModel->queryAllExpendType();
        echo json_encode($expendTypeRs);
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

    /*按照查询条件导出支出类型信息到Excel*/
    public function outToExcel() {
        $expendTypeRs = $this->expendTypeModel->queryOutputExpendType();
        $expTableData = [];
        foreach($expendTypeRs as $expendTypeRow) {
            $expTableData[] = $expendTypeRow;
        }
        $xlsName = "ExpendType";
        $xlsCell = array(
            array('expendTypeId','支出类型id','int'),
            array('expendTypeName','支出类型名称','string'),
        );//查出字段输出对应Excel对应的列名
        //公共方法调用
        $this->export_excel($xlsName,$xlsCell,$expTableData);
    }

}

