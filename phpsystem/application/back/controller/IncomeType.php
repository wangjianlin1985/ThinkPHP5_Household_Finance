<?php
namespace app\back\controller;
use think\Request;
use think\Exception;
use app\common\model\IncomeTypeModel;

class IncomeType extends BackBase {
    protected $incomeTypeModel;

    //控制层对象初始化：注入业务逻辑层对象等
    public function _initialize() {
        parent::_initialize();
        $this->request = Request::instance();
        $this->incomeTypeModel = new IncomeTypeModel();
    }

    /*添加收入分类信息*/
    public function add(){
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
            return view('incomeType/incomeType_add');
        }
    }

    /*跳转到更新界面*/
    public function modifyView() {
        $this->assign("typeId",input("typeId"));
        return view("incomeType/incomeType_modify");
    }

    /*ajax方式按照查询条件分页查询收入分类信息*/
    public function backList() {
        if($this->request->isPost()) {
            if($this->request->param("page") != null)
                $this->currentPage = $this->request->param("page");
            if($this->request->param("rows") != null)
                $this->incomeTypeModel->setRows($this->request->param("rows"));
            $incomeTypeRs = $this->incomeTypeModel->queryIncomeType($this->currentPage);
            $expTableData = [];
            foreach($incomeTypeRs as $incomeTypeRow) {
                $expTableData[] = $incomeTypeRow;
            }
            $data["rows"] = $incomeTypeRs;
            /*当前查询条件下总记录数*/
            $data["total"] = $this->incomeTypeModel->recordNumber;
            echo json_encode($data);
        } else {
            return view("incomeType/incomeType_query");
        }
    }

    /*ajax方式查询收入分类信息*/
    public function listAll() {
        $incomeTypeRs = $this->incomeTypeModel->queryAllIncomeType();
        echo json_encode($incomeTypeRs);
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

    /*按照查询条件导出收入分类信息到Excel*/
    public function outToExcel() {
        $incomeTypeRs = $this->incomeTypeModel->queryOutputIncomeType();
        $expTableData = [];
        foreach($incomeTypeRs as $incomeTypeRow) {
            $expTableData[] = $incomeTypeRow;
        }
        $xlsName = "IncomeType";
        $xlsCell = array(
            array('typeId','分类id','int'),
            array('typeName','分类名称','string'),
        );//查出字段输出对应Excel对应的列名
        //公共方法调用
        $this->export_excel($xlsName,$xlsCell,$expTableData);
    }

}

