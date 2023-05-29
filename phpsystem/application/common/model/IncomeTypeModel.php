<?php
namespace app\common\model;
use think\Model;

class IncomeTypeModel extends Model {
    /*关联表名*/
    protected $table  = 't_incomeType';
    /*每页显示记录数目*/
    public $rows = 8;
    /*保存查询后总的页数*/
    public $totalPage;
    /*保存查询到的总记录数*/
    public $recordNumber;

    public function setRows($rows) {
        $this->rows = $rows;
    }

    /*添加收入分类记录*/
    public function addIncomeType($incomeType) {
        $this->insert($incomeType);
    }

    /*更新收入分类记录*/
    public function updateIncomeType($incomeType) {
        $this->update($incomeType);
    }

    /*删除多条收入分类信息*/
    public function deleteIncomeTypes($typeIds){
        $typeIdArray = explode(",",$typeIds);
        foreach ($typeIdArray as $typeId) {
            $this->typeId = $typeId;
            $this->delete();
        }
        return count($typeIdArray);
    }
    /*根据主键获取收入分类记录*/
    public function getIncomeType($typeId) {
        $incomeType = IncomeTypeModel::where("typeId",$typeId)->find();
        return $incomeType;
    }

    /*按照查询条件分页查询收入分类信息*/
    public function queryIncomeType($currentPage) {
        $startIndex = ($currentPage-1) * $this->rows;
        $where = null;
        $incomeTypeRs = IncomeTypeModel::where($where)->limit($startIndex,$this->rows)->select();
        /*计算总的页数和总的记录数*/
        $this->recordNumber = IncomeTypeModel::where($where)->count();
        $mod = $this->recordNumber % $this->rows;
        $this->totalPage = (int)($this->recordNumber / $this->rows);
        if($mod != 0) $this->totalPage++;
        return $incomeTypeRs;
    }

    /*按照查询条件查询所有收入分类记录*/
  public function queryOutputIncomeType() {
        $where = null;
        $incomeTypeRs = IncomeTypeModel::where($where)->select();
        return $incomeTypeRs;
    }

    /*查询所有收入分类记录*/
    public function queryAllIncomeType(){
        $incomeTypeRs = IncomeTypeModel::where(null)->select();
        return $incomeTypeRs;

    }

}
