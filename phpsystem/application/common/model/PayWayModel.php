<?php
namespace app\common\model;
use think\Model;

class PayWayModel extends Model {
    /*关联表名*/
    protected $table  = 't_payWay';
    /*每页显示记录数目*/
    public $rows = 8;
    /*保存查询后总的页数*/
    public $totalPage;
    /*保存查询到的总记录数*/
    public $recordNumber;

    public function setRows($rows) {
        $this->rows = $rows;
    }

    /*添加支付方式记录*/
    public function addPayWay($payWay) {
        $this->insert($payWay);
    }

    /*更新支付方式记录*/
    public function updatePayWay($payWay) {
        $this->update($payWay);
    }

    /*删除多条支付方式信息*/
    public function deletePayWays($payWayIds){
        $payWayIdArray = explode(",",$payWayIds);
        foreach ($payWayIdArray as $payWayId) {
            $this->payWayId = $payWayId;
            $this->delete();
        }
        return count($payWayIdArray);
    }
    /*根据主键获取支付方式记录*/
    public function getPayWay($payWayId) {
        $payWay = PayWayModel::where("payWayId",$payWayId)->find();
        return $payWay;
    }

    /*按照查询条件分页查询支付方式信息*/
    public function queryPayWay($currentPage) {
        $startIndex = ($currentPage-1) * $this->rows;
        $where = null;
        $payWayRs = PayWayModel::where($where)->limit($startIndex,$this->rows)->select();
        /*计算总的页数和总的记录数*/
        $this->recordNumber = PayWayModel::where($where)->count();
        $mod = $this->recordNumber % $this->rows;
        $this->totalPage = (int)($this->recordNumber / $this->rows);
        if($mod != 0) $this->totalPage++;
        return $payWayRs;
    }

    /*按照查询条件查询所有支付方式记录*/
  public function queryOutputPayWay() {
        $where = null;
        $payWayRs = PayWayModel::where($where)->select();
        return $payWayRs;
    }

    /*查询所有支付方式记录*/
    public function queryAllPayWay(){
        $payWayRs = PayWayModel::where(null)->select();
        return $payWayRs;

    }

}
