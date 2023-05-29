<?php
namespace app\back\controller;
use think\Controller;

class BackBase extends Controller
{
    protected $currentPage = 1;
    protected $request = null;

    public function _initialize(){
        if(!session('username')){
            $this->error('请先登录系统！','Login/index');
        }
    }

    public function writeJsonResponse($success, $message) {
        $response = array(
            "success" => $success,
            "message" => $message,
        );
        echo json_encode($response);
    }

    /**
     * @param $obj:  保存图片路径的对象
     * @param $indexName 索引名称
     * @param $photoFiledName 提交的图片表单名称
     */
    public function uploadPhoto(&$obj,$indexName,$photoFiledName) {
        if($_FILES[$photoFiledName]['tmp_name']){
            $file = $this->request->file($photoFiledName);
            //控制上传的文件类型，大小
            if(!(($_FILES[$photoFiledName]["type"]=="image/jpeg"
                    || $_FILES[$photoFiledName]["type"]=="image/jpg"
                    || $_FILES[$photoFiledName]["type"]=="image/png") && $_FILES[$photoFiledName]["size"] < 1024000)) {
                $message = "图书图片请选择jpg或png格式的图片!";
                $this->writeJsonResponse(false,$message);
                exit;
            }
            $file->setRule("short"); //文件路径采用简短方式
            $info = $file->move(ROOT_PATH . 'public' . DS . 'upload');
            $obj[$indexName]='upload/'.$info->getSaveName();
        }
    }

    /**
     * @param $obj:  保存文件路径的对象
     * @param $indexName 索引名称
     * @param $resourceFiledName 提交的文件表单名称
     */
    public function uploadFile(&$obj,$indexName,$resourceFiledName) {
        if($_FILES[$resourceFiledName]['tmp_name']){
            $file = $this->request->file($resourceFiledName);
            $file->setRule("short"); //文件路径采用简短方式
            $info = $file->move(ROOT_PATH . 'public' . DS . 'upload');
            $obj[$indexName]='upload/'.$info->getSaveName();
        }
    }

    //接收提交的UserInfo信息参数
    public function getUserInfoForm($insertFlag) {
        $userInfo = [
            'user_name'=> input("userInfo_user_name"), //用户名
            'password'=> input("userInfo_password"), //登录密码
            'name'=> input("userInfo_name"), //姓名
            'gender'=> input("userInfo_gender"), //性别
            'birthDate'=> input("userInfo_birthDate"), //出生日期
            'userPhoto' => $insertFlag==true?"upload/NoImage.jpg":input("userInfo_userPhoto"), //用户照片
            'telephone'=> input("userInfo_telephone"), //联系电话
            'email'=> input("userInfo_email"), //邮箱
            'address'=> input("userInfo_address"), //家庭地址
            'regTime'=> input("userInfo_regTime"), //注册时间
        ];
        return $userInfo;
    }

    //接收提交的IncomeType信息参数
    public function getIncomeTypeForm($insertFlag) {
        $incomeType = [
            'typeId'=> input("incomeType_typeId"), //分类id
            'typeName'=> input("incomeType_typeName"), //分类名称
        ];
        return $incomeType;
    }

    //接收提交的Income信息参数
    public function getIncomeForm($insertFlag) {
        $income = [
            'incomeId'=> input("income_incomeId"), //收入id
            'incomeTypeObj'=> input("income_incomeTypeObj_typeId"), //收入类型
            'incomeFrom'=> input("income_incomeFrom"), //收入来源
            'payWayObj'=> input("income_payWayObj_payWayId"), //支付方式
            'payAccount'=> input("income_payAccount"), //支付账号
            'incomeMoney'=> input("income_incomeMoney"), //收入金额
            'incomeDate'=> input("income_incomeDate"), //收入日期
            'userObj'=> input("income_userObj_user_name"), //收入用户
            'incomeMemo'=> input("income_incomeMemo"), //收入备注
        ];
        return $income;
    }

    //接收提交的ExpendType信息参数
    public function getExpendTypeForm($insertFlag) {
        $expendType = [
            'expendTypeId'=> input("expendType_expendTypeId"), //支出类型id
            'expendTypeName'=> input("expendType_expendTypeName"), //支出类型名称
        ];
        return $expendType;
    }

    //接收提交的Expend信息参数
    public function getExpendForm($insertFlag) {
        $expend = [
            'expendId'=> input("expend_expendId"), //支出id
            'exprendTypeObj'=> input("expend_exprendTypeObj_expendTypeId"), //支出类型
            'expendPurpose'=> input("expend_expendPurpose"), //支出用途
            'payWayObj'=> input("expend_payWayObj_payWayId"), //支付方式
            'payAccount'=> input("expend_payAccount"), //支付账号
            'expendMoney'=> input("expend_expendMoney"), //支付金额
            'expendDate'=> input("expend_expendDate"), //支付日期
            'userObj'=> input("expend_userObj_user_name"), //支出用户
            'expendMemo'=> input("expend_expendMemo"), //支出备注
        ];
        return $expend;
    }

    //接收提交的PayWay信息参数
    public function getPayWayForm($insertFlag) {
        $payWay = [
            'payWayId'=> input("payWay_payWayId"), //支付方式id
            'payWayName'=> input("payWay_payWayName"), //支付方式名称
        ];
        return $payWay;
    }

    //接收提交的Notice信息参数
    public function getNoticeForm($insertFlag) {
        $notice = [
            'noticeId'=> input("notice_noticeId"), //公告id
            'title'=> input("notice_title"), //标题
            'content'=> input("notice_content"), //公告内容
            'publishDate'=> input("notice_publishDate"), //发布时间
        ];
        return $notice;
    }

    /** * 公共数据导出实现功能
     * @param $expTitle 导出文件名
     * @param $expCellName 导出文件列名称
     * @param $expTableData 导出数据
     */
    public function export_excel($expTitle,$expCellName,$expTableData) {
        $xlsTitle = iconv('utf-8', 'gb2312', $expTitle);//文件名称
        $fileName = $expTitle . date('_Ymd');//or $xlsTitle 文件名称可根据自己情况设定
        $cellNum = count($expCellName);
        $dataNum = count($expTableData);
        //这些文件需要下载phpexcel，然后放在vendor文件里面。具体参考上一篇数据导出。
        vendor("PHPExcel.PHPExcel");
        //vendor("PHPExcel.PHPExcel.Writer.IWriter");
        //vendor("PHPExcel.PHPExcel.Writer.Abstract");
        //vendor("PHPExcel.PHPExcel.Writer.Excel5");
        //vendor("PHPExcel.PHPExcel.Writer.Excel2007");
        vendor("PHPExcel.PHPExcel.IOFactory");
        $objPHPExcel = new \PHPExcel();//方法一
        $cellName = array('A','B', 'C','D', 'E', 'F','G','H','I', 'J', 'K','L','M', 'N', 'O', 'P', 'Q','R','S', 'T','U','V', 'W', 'X','Y', 'Z', 'AA',    'AB', 'AC','AD','AE', 'AF','AG','AH','AI', 'AJ', 'AK', 'AL','AM','AN','AO','AP','AQ','AR', 'AS', 'AT','AU', 'AV','AW', 'AX', 'AY', 'AZ');
        //设置头部导出时间备注
        $objPHPExcel->getActiveSheet(0)->mergeCells('A1:' . $cellName[$cellNum - 1] . '1');//合并单元格
        $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', $expTitle . ' 导出时间:' . date('Y-m-d H:i:s'));
        //设置列名称
        for ($i = 0; $i < $cellNum; $i++) {
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($cellName[$i] . '2', $expCellName[$i][1]);
            $objPHPExcel->getActiveSheet()->getColumnDimension($cellName[$i])->setWidth(20); //设置每列宽度
            $objPHPExcel->getActiveSheet()->getStyle($cellName[$i].'2')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle($cellName[$i])->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER); //垂直居中对齐
        }
        //赋值
        for ($i = 0; $i < $dataNum; $i++) {
            for ($j = 0; $j < $cellNum; $j++) {
                $objPHPExcel->getActiveSheet()->getStyle($cellName[$j].($i + 3))->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                if($expCellName[$j][2] == 'photo') {
                    try {
                        // 表格高度
                        $objPHPExcel->getActiveSheet()->getRowDimension($i+3)->setRowHeight(80);
                        // 图片生成
                        $objDrawing = new \PHPExcel_Worksheet_Drawing();
                        $objDrawing->setPath(PUBLIC_PATH.$expTableData[$i][$expCellName[$j][0]]);
                        // 设置宽度高度
                        $objDrawing->setHeight(80);//照片高度
                        $objDrawing->setWidth(80); //照片宽度
                        /*设置图片要插入的单元格*/
                        $objDrawing->setCoordinates($cellName[$j].($i + 3));
                        // 图片偏移距离
                        $objDrawing->setOffsetX(5);
                        $objDrawing->setOffsetY(5);
                        $objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
                    } catch (\Exception $ex) {}
                } else {
                    $objPHPExcel->getActiveSheet(0)->setCellValue($cellName[$j].($i + 3), $expTableData[$i][$expCellName[$j][0]]);
                }
            }
        }
        ob_end_clean();//这一步非常关键，用来清除缓冲区防止导出的excel乱码
        header('pragma:public');
        header('Content-type:application/vnd.ms-excel;charset=utf-8;name="' . $xlsTitle . '.xls"');
        header("Content-Disposition:attachment;filename=$fileName.xls");//"xls"参考下一条备注
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5'  );//"Excel2007"生成2007版本的xlsx，"Excel5"生成2003版本的xls
        $objWriter->save('php://output');
    }
}

