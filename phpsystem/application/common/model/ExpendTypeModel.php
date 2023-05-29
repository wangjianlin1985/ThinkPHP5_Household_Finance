<?php
namespace app\common\model;
use think\Model;

class ExpendTypeModel extends Model {
    /*关联表名*/
    protected $table  = 't_expendType';
    /*每页显示记录数目*/
    public $rows = 8;
    /*保存查询后总的页数*/
    public $totalPage;
    /*保存查询到的总记录数*/
    public $recordNumber;

    public function setRows($rows) {
        $this->rows = $rows;
    }

    /*添加支出类型记录*/
    public function addExpendType($expendType) {
        $this->insert($expendType);
    }

    /*更新支出类型记录*/
    public function updateExpendType($expendType) {
        $this->update($expendType);
    }

    /*删除多条支出类型信息*/
    public function deleteExpendTypes($expendTypeIds){
        $expendTypeIdArray = explode(",",$expendTypeIds);
        foreach ($expendTypeIdArray as $expendTypeId) {
            $this->expendTypeId = $expendTypeId;
            $this->delete();
        }
        return count($expendTypeIdArray);
    }
    /*根据主键获取支出类型记录*/
    public function getExpendType($expendTypeId) {
        $expendType = ExpendTypeModel::where("expendTypeId",$expendTypeId)->find();
        return $expendType;
    }

    /*按照查询条件分页查询支出类型信息*/
    public function queryExpendType($currentPage) {
        $startIndex = ($currentPage-1) * $this->rows;
        $where = null;
        $expendTypeRs = ExpendTypeModel::where($where)->limit($startIndex,$this->rows)->select();
        /*计算总的页数和总的记录数*/
        $this->recordNumber = ExpendTypeModel::where($where)->count();
        $mod = $this->recordNumber % $this->rows;
        $this->totalPage = (int)($this->recordNumber / $this->rows);
        if($mod != 0) $this->totalPage++;
        return $expendTypeRs;
    }

    /*按照查询条件查询所有支出类型记录*/
  public function queryOutputExpendType() {
        $where = null;
        $expendTypeRs = ExpendTypeModel::where($where)->select();
        return $expendTypeRs;
    }

    /*查询所有支出类型记录*/
    public function queryAllExpendType(){
        $expendTypeRs = ExpendTypeModel::where(null)->select();
        return $expendTypeRs;

    }

}
