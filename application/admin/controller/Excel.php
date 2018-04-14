<?php
namespace app\admin\controller;
use think\Controller;
use app\index\model\Checkcode;

class Excel extends Controller
{
	public function excel(){
        return view();
    }
    public function excelImport(){  
        import('phpexcel.PHPExcel', EXTEND_PATH);
        $objPHPExcel = new \PHPExcel();  
  
        //获取表单上传文件  
        $file = request()->file('excel');  
        $info = $file->validate(['size'=>15678,'ext'=>'xlsx,xls,csv'])->move(ROOT_PATH . 'public' . DS . 'excel');  
        if($info){  
            $exclePath = $info->getSaveName();  //获取文件名  
            $file_name = ROOT_PATH . 'public' . DS . 'excel' . DS . $exclePath;   //上传文件的地址  
            $objReader =\PHPExcel_IOFactory::createReader('Excel2007');  
            $obj_PHPExcel =$objReader->load($file_name, $encode = 'utf-8');  //加载文件内容,编码utf-8  
            echo "<pre>";  
            $excel_array=$obj_PHPExcel->getsheet(0)->toArray();   //转换为数组格式  
            array_shift($excel_array);  //删除第一个数组(标题);  
            $data = [];  
            $i=0;  
            foreach($excel_array as $k=>$v) {//对应数据库数组  
                $data[$k]['id'] = $v[0];
                $data[$k]['oid'] = $v[1]; 
                $data[$k]['uname'] = $v[2]; 
                $data[$k]['code'] = $v[3]; 
                $data[$k]['checknum'] = $v[4]; 
                $data[$k]['otime'] = $v[5];   
                $data[$k]['class'] = $v[6]; 
                $i++;  
            }  
 
           $success=db('code')->insertAll($data);
           //$i=  
           $this->success('新增成功', 'Checkcode/lst');
           //$error=$i-$success;  
            //echo "总{$i}条，成功{$success}条，失败{$error}条。";  
        }else{  
            // 上传失败获取错误信息  
            //echo $file->getError();
             $this->error('新增失败');  
        }  
  
    }  
}
