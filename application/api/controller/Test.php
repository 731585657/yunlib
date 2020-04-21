<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/2/26 0026
 * Time: 10:06
 */

namespace app\api\controller;


use app\common\controller\Api;

class Test extends Api
{
    protected $noNeedLogin = ['*'];
    protected $noNeedRight = ['*'];

    public function index()
    {
        $i = 1;
        $i=++$i;
        $i=++$i;
        $i=++$i;
        echo  $i;
    }

    public function test()
    {
        $start_time = urlencode(date('Y-m-d 00:00:00'));
        $end_time = urlencode(date('Y-m-d 23:59:59'));

        $url = "http://223.87.178.183:82/opac/jsonAnalytics/getLoanAndReturnCountAndContent/json?orglib=LSSTSG&startTime=$start_time&endTime=$end_time&flag=1";
        echo  $url;die;


        $url= 'http://118.125.223.236:83/opac/api/interface/loanHistory';


        $date= date('Y-m-d 00:00:00');

        $data = array(
            'return_fmt' => 'json',
            'time' => $date,
            'searchType' => 'rdlib',
            'searchValue' => 'LSSTSG',
            'page' => 1,
            'rows' => 50
        );
//var_dump($date);die;
      //  $str ="return_fmt=json&time=$date&searchType=rdlib&searchValue=LSSTSG&page=1&rows=50";


        $ch = curl_init();//初始化curl
//设置会话操作的通用参数
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL,$url);//抓取指定网页
        curl_setopt($ch, CURLOPT_POST, 1);//post提交方式
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $result = curl_exec($ch);//运行curl

        curl_close($ch);

        $re =  json_decode($result,true);

        $array= array();
        foreach ($re['loanHistory'] as  $value)
        {
            if ($value['logType'] == 30002){
                $array['return'][]= array(
                    'rdid' => $value['rdid'],
                    'logType' => $value['logType'],
                    'title' => $value['biblios']['title'],
                );

            }else{
                $array['borrow'][]= array(
                    'rdid' => $value['rdid'],
                    'logType' => $value['logType'],
                    'title' => $value['biblios']['title'],
                );

            }

        }
        //var_dump($array);die;
        $list = json_encode($array);

        echo $list;
    }

    public function createM (int $a, int $b): int
    {

        $site = $_GET['site'] ?? '运算符';

        return $site;

    }


}

