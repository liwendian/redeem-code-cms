<?php
namespace app\admin\validate;
use think\Validate;
class Code extends Validate
{
    protected $rule =   [
        'oid'  => 'require|unique:code',
        'code'   => 'require|unique:code',
    ];
    
    protected $message  =   [
        'oid.require' => '订单号不能为空',
        'oid.unique'  => '订单号不能重复',
        'code.require' => '兑换码不能为空',
        'code.unique'  => '兑换码不能重复',
    ];
}
