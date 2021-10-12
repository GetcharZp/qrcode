<?php

namespace app\controller;

use app\BaseController;
use Zxing\QrReader;

class Decode extends BaseController
{
    public function index()
    {
        return view('index/decode');
    }
}
