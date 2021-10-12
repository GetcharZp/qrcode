<?php
namespace app\controller;

use app\BaseController;
require_once \think\facade\App::getRootPath() . 'extend/phpqrcode.php';

class Index extends BaseController
{
    public function index()
    {
        return view('index');
    }
}
