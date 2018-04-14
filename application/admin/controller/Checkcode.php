<?php
namespace app\admin\controller;
use think\Controller;
class Checkcode extends Controller
{
	//访问验证（只允许特定IP地址访问后台）
    public function _initialize(){
        $ip=get_real_ip();//get_real_ip()在common.php中
        if($ip != '127.0.0.1'){
            die('非法ip');
        }
    }
    
    //兑换码列表
    public function lst()
    {
        if(request()->isPost()){//条件查询
            $value=trim(input('val'));//查询的值
            $filed=trim(input('filed'));//查询的字段类型
            $codeRes=db('code')->where(array($filed=>$value))->paginate(6);
            $condition=$filed.'='.$value;
            $this->assign([
                'condition'=>$condition,
                ]);
        }else{//列表展示
           $codeRes=db('code')->paginate(6); 
           $this->assign([
                'condition'=>'查询全部',
            ]);
        }
        
        $this->assign([
            'codeRes'=>$codeRes,
            ]);
        return view('list');
    }

	//添加兑换码
    public function add()
    {
        if(request()->isAjax()){
            $_data=input('post.');
            $data=array();
            foreach ($_data as $k => $v) {//去除空格
                $data[$k]=trim($v);
            }
            $validate = validate('code');
            if(!$validate->check($data)){
                $msg=$validate->getError();//验证数据
                return json(['error'=>2,'msg'=>$msg]);
            }
            $add=db('code')->insert($data);
            if($add){
                return json(['error'=>0,'msg'=>'添加成功！']);
            }else{
                return json(['error'=>1,'msg'=>'添加失败！']);
            }
        }
        return view();
    }

	//编辑兑换码
    public function edit()
    {
        $code=db('code');
        if(request()->isAjax()){
            $_data=input('post.');
            $data=array();
            foreach ($_data as $k => $v) {
                $data[$k]=trim($v);
            }
            $validate = validate('code');
            if(!$validate->check($data)){
                $msg=$validate->getError();
                return json(['error'=>2,'msg'=>$msg]);
            }
            $save=$code->update($data);
            if($save !== false){
                return json(['error'=>0,'msg'=>'修改兑换码成功！']);
            }else{
                return json(['error'=>1,'msg'=>'修改兑换码失败！']);
            }
        }
        $codes=$code->find(input('id'));
        $this->assign([
            'codes'=>$codes,
            ]);
        return view();
    }

    //兑换码查询
    public function check(){//查询功能在lst控制器里实现
        return view();
    }
    
	//删除兑换码
    public function del(){
        $id=input('id');
        $del=db('code')->delete($id);
    }

}
