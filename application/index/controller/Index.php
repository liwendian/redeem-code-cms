<?php
namespace app\index\controller;

class Index
{
    public function index()
    {
        return view();
    }

    public function ajaxcheckcode()
    {
    	if(request()->isAjax()){
    		$code=db('code');
    		$oid=trim(input('oid'));
    		$uname=trim(input('uname'));
    		$codes=$code->where(array('oid'=>$oid,'uname'=>$uname))->find();
    		if($codes){
    			//2017-12-31 17:33:12  下单时间
    			$buytime=strtotime($codes['otime']);//将时间字符串转换为时间戳
    			$abuytime=ceil((time()-$buytime)/86400);//计算出购买时长并取整
    			if($abuytime>=15){
    				$code->where(array('id'=>$codes['id']))->setInc('checknum');//查询成功次数自增
    				$_code=$code->field('checknum')->find($codes['id']);
    				$checknum=$_code['checknum'];
    				if($checknum < 6){
    					$msg='兑换码为：'.$codes['code'].'<br>适用于类型：'.$codes['class'];
    				}else{
    					$msg='查询次数超限！';
    				}
	    			return ['error'=>0,'msg'=>$msg];
    			}else{
    				$leftday=15-$abuytime;
    				$msg='您'.$leftday.'天以后可以领取该订单兑换码！';
	    			return ['error'=>2,'msg'=>$msg];
    			}
    		}else{
    			return ['error'=>1,'msg'=>'用户名或者订单号错误！'];
    		}
    	}else{
    		echo '非常操作！';
    	}
    }
}
