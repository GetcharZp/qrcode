<?php

namespace app\controller;

use app\BaseController;
use PhpOffice\PhpSpreadsheet\Reader\Xls;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use think\facade\App;
use think\Request;
use Zxing\QrReader;

require_once \think\facade\App::getRootPath() . 'extend/phpqrcode.php';

class Api extends BaseController
{
    public function index()
    {
        return view('index/api');
    }

    public function createQrcode()
    {
        $text = input("get.text");
        if (empty($text)) {
            exit("请输入待转化的字符串");
        }
        header("Content-type:image/png");
        \QRcode::png($text,false, 'L', 5, 2);
        exit();
    }

    private function createQrcodeBatch($text, $dirname, $index)
    {
        $filename = 'storage/upload/' . $dirname . '/' . $index . '.png';
        $qrcode = new \QRcode();
        $qrcode->png($text, $filename, 'L', 5, 2);
        return $filename;
    }

    public function upload()
    {
        $file = \request()->file('file');
        $savename = \think\facade\Filesystem::disk('public')->putFile( '/upload', $file);
        return json(array('code' => 200, 'savename' => 'storage/' . $savename));
    }

    public function decode()
    {
        $savename = input('get.savename');
        $qrcode = new QrReader($savename);
        $text = $qrcode->text();
        return json(array('code' => 200, 'text' => $text));
    }

    public function batchCreateQrcode()
    {
        $savename = input('get.savename');
        if (empty($savename)) {
            exit("Excel 不能为空");
        }
        $reader = new Xlsx();
        $spreadsheet = $reader->load($savename);
        $datas = $spreadsheet->getActiveSheet()->toArray();
        $dirname = md5(rand(1, 1000) . time());
        while (file_exists('storage/upload/' . $dirname)) {
            $dirname = md5(rand(1, 1000) . rand(1000, 100000) . time());
        }
        mkdir('storage/upload/' . $dirname);
        $zip = new \ZipArchive();
        $zip->open('storage/upload/' . $dirname . '.zip', \ZipArchive::CREATE);
        for ($i = 1; $i <= count($datas); ++ $i) {
            $filename = $this->createQrcodeBatch($datas[$i - 1][0], $dirname, $i);
            $zip->addFile('storage/upload/' . $dirname . '/' . $i . '.png', basename('storage/upload/' . $dirname . '/' . $i . '.png'));
        }
        $zip->close();
        return json(array('code' => 200, 'download' => 'storage/upload/' . $dirname . '.zip'));
    }
}
