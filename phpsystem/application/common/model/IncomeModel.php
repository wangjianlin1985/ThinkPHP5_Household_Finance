<?php
namespace app\common\model;
use think\Model;

class IncomeModel extends Model {
    /*关联表名*/
    protected $table  = 't_income';
    /*每页显示记录数目*/
    public $rows = 8;
    /*保存查询后总的页数*/
    public $totalPage;
    /*保存查询到的总记录数*/
    public $recordNumber;

    public function setRows($rows) {
        $this->rows = $rows;
    }

    //收入类型复合属性的获取: 多对一关系
    public function incomeTypeObjF(){
        return $this->belongsTo('IncomeTypeModel','incomeTypeObj');
    }

    //支付方式复合属性的获取: 多对一关系
    public function payWayObjF(){
        return $this->belongsTo('PayWayModel','payWayObj');
    }

    //收入用户复合属性的获取: 多对一关系
    public function userObjF(){
        return $this->belongsTo('UserInfoModel','userObj');
    }

    /*添加收入记录*/
    public function addIncome($income) {
        $this->insert($income);
    }

    /*更新收入记录*/
    public function updateIncome($income) {
        $this->update($income);
    }

    /*删除多条收入信息*/
    public function deleteIncomes($incomeIds){
        $incomeIdArray = explode(",",$incomeIds);
        foreach ($incomeIdArray as $incomeId) {
            $this->incomeId = $incomeId;
            $this->delete();
        }
        return count($incomeIdArray);
    }
    /*根据主键获取收入记录*/
    public function getIncome($incomeId) {
        $income = IncomeModel::where("incomeId",$incomeId)->find();
        return $income;
    }

    /*按照查询条件分页查询收入信息*/
    public function queryIncome($incomeTypeObj, $incomeFrom, $payWayObj, $payAccount, $incomeDate, $userObj, $currentPage) {
        $startIndex = ($currentPage-1) * $this->rows;
        $where = null;
        if($incomeTypeObj['typeId'] != 0) $where['incomeTypeObj'] = $incomeTypeObj['typeId'];
        if($incomeFrom != "") $where['incomeFrom'] = array('like','%'.$incomeFrom.'%');
        if($payWayObj['payWayId'] != 0) $where['payWayObj'] = $payWayObj['payWayId'];
        if($payAccount != "") $where['payAccount'] = array('like','%'.$payAccount.'%');
        if($incomeDate != "") $where['incomeDate'] = array('like','%'.$incomeDate.'%');
        if($userObj['user_name'] != 0) $where['userObj'] = $userObj['user_name'];
        $incomeRs = IncomeModel::where($where)->limit($startIndex,$this->rows)->select();
        /*计算总的页数和总的记录数*/
        $this->recordNumber = IncomeModel::where($where)->count();
        $mod = $this->recordNumber % $this->rows;
        $this->totalPage = (int)($this->recordNumber / $this->rows);
        if($mod != 0) $this->totalPage++;
        return $incomeRs;
    }

    /*按照查询条件查询所有收入记录*/
  public function queryOutputIncome( $incomeTypeObj,  $incomeFrom,  $payWayObj,  $payAccount,  $incomeDate,  $userObj) {
        $where = null;
        if($incomeTypeObj['typeId'] != 0) $where['incomeTypeObj'] = $incomeTypeObj['typeId'];
        if($incomeFrom != "") $where['incomeFrom'] = array('like','%'.$incomeFrom.'%');
        if($payWayObj['payWayId'] != 0) $where['payWayObj'] = $payWayObj['payWayId'];
        if($payAccount != "") $where['payAccount'] = array('like','%'.$payAccount.'%');
        if($incomeDate != "") $where['incomeDate'] = array('like','%'.$incomeDate.'%');
        if($userObj['user_name'] != 0) $where['userObj'] = $userObj['user_name'];
        $incomeRs = IncomeModel::where($where)->select();
        return $incomeRs;
    }

    /*查询所有收入记录*/
    public function queryAllIncome(){
        $incomeRs = IncomeModel::where(null)->select();
        return $incomeRs;

    }

}
