<?php
namespace app\common\model;
use think\Model;

class ExpendModel extends Model {
    /*关联表名*/
    protected $table  = 't_expend';
    /*每页显示记录数目*/
    public $rows = 8;
    /*保存查询后总的页数*/
    public $totalPage;
    /*保存查询到的总记录数*/
    public $recordNumber;

    public function setRows($rows) {
        $this->rows = $rows;
    }

    //支出类型复合属性的获取: 多对一关系
    public function exprendTypeObjF(){
        return $this->belongsTo('ExpendTypeModel','exprendTypeObj');
    }

    //支付方式复合属性的获取: 多对一关系
    public function payWayObjF(){
        return $this->belongsTo('PayWayModel','payWayObj');
    }

    //支出用户复合属性的获取: 多对一关系
    public function userObjF(){
        return $this->belongsTo('UserInfoModel','userObj');
    }

    /*添加支出记录*/
    public function addExpend($expend) {
        $this->insert($expend);
    }

    /*更新支出记录*/
    public function updateExpend($expend) {
        $this->update($expend);
    }

    /*删除多条支出信息*/
    public function deleteExpends($expendIds){
        $expendIdArray = explode(",",$expendIds);
        foreach ($expendIdArray as $expendId) {
            $this->expendId = $expendId;
            $this->delete();
        }
        return count($expendIdArray);
    }
    /*根据主键获取支出记录*/
    public function getExpend($expendId) {
        $expend = ExpendModel::where("expendId",$expendId)->find();
        return $expend;
    }

    /*按照查询条件分页查询支出信息*/
    public function queryExpend($exprendTypeObj, $expendPurpose, $payWayObj, $payAccount, $expendDate, $userObj, $currentPage) {
        $startIndex = ($currentPage-1) * $this->rows;
        $where = null;
        if($exprendTypeObj['expendTypeId'] != 0) $where['exprendTypeObj'] = $exprendTypeObj['expendTypeId'];
        if($expendPurpose != "") $where['expendPurpose'] = array('like','%'.$expendPurpose.'%');
        if($payWayObj['payWayId'] != 0) $where['payWayObj'] = $payWayObj['payWayId'];
        if($payAccount != "") $where['payAccount'] = array('like','%'.$payAccount.'%');
        if($expendDate != "") $where['expendDate'] = array('like','%'.$expendDate.'%');
        if($userObj['user_name'] != 0) $where['userObj'] = $userObj['user_name'];
        $expendRs = ExpendModel::where($where)->limit($startIndex,$this->rows)->select();
        /*计算总的页数和总的记录数*/
        $this->recordNumber = ExpendModel::where($where)->count();
        $mod = $this->recordNumber % $this->rows;
        $this->totalPage = (int)($this->recordNumber / $this->rows);
        if($mod != 0) $this->totalPage++;
        return $expendRs;
    }

    /*按照查询条件查询所有支出记录*/
  public function queryOutputExpend( $exprendTypeObj,  $expendPurpose,  $payWayObj,  $payAccount,  $expendDate,  $userObj) {
        $where = null;
        if($exprendTypeObj['expendTypeId'] != 0) $where['exprendTypeObj'] = $exprendTypeObj['expendTypeId'];
        if($expendPurpose != "") $where['expendPurpose'] = array('like','%'.$expendPurpose.'%');
        if($payWayObj['payWayId'] != 0) $where['payWayObj'] = $payWayObj['payWayId'];
        if($payAccount != "") $where['payAccount'] = array('like','%'.$payAccount.'%');
        if($expendDate != "") $where['expendDate'] = array('like','%'.$expendDate.'%');
        if($userObj['user_name'] != 0) $where['userObj'] = $userObj['user_name'];
        $expendRs = ExpendModel::where($where)->select();
        return $expendRs;
    }

    /*查询所有支出记录*/
    public function queryAllExpend(){
        $expendRs = ExpendModel::where(null)->select();
        return $expendRs;

    }

}
