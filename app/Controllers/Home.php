<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        // return view('welcome_message');
        
        $db = db_connect();
        $insert = array();
        for ($i = 0; $i < 15000; $i ++) {
            $insert2['operator_code'] = 'WAB01';
            $insert2['filename'] = 'com.whatsapp.w4b.tar.gz-'.(substr(md5(date('YmdHis').rand(1, 10000000000000)), rand(0, 10), 6));
            $insert2['created_datetime'] = date('Y-m-d H:i:s');
            $insert2['size'] = '1 Mb';
            array_push($insert, $insert2);
        }
        for ($i = 0; $i < 12000; $i ++) {
            $insert2['operator_code'] = 'TELE01';
            $insert2['filename'] = 'com.telegram.id.tar.gz-'.(substr(md5(date('YmdHis').rand(1, 10000000000000)), rand(0, 10), 6));
            $insert2['created_datetime'] = date('Y-m-d H:i:s');
            $insert2['size'] = '2 Mb';
            array_push($insert, $insert2);
        }
        $db->table('app_products')->insertBatch($insert);

        // $insert = [
        //     [
        //         'title' => 'My title',
        //         'name'  => 'My Name',
        //         'date'  => 'My date',
        //     ],
        //     [
        //         'title' => 'Another title',
        //         'name'  => 'Another Name',
        //         'date'  => 'Another date',
        //     ],
        // ];
        print_r(json_encode($insert));
        // print_r(($insert));
        die();
        
    }
}
