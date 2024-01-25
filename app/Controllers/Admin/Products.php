<?php

namespace App\Controllers\Admin;

class Products extends BaseController
{
    public function index()
    {
        echo('welcome!');
    }

    public function postList()
    {   
        cekValidation('admin/products/list');
        $request = request();
        $db = db_connect();

        $baseCURS = $db->table('base_profit')->where('current_date', date('Y-m-d'))->limit(1)->get()->getRow(); 
        $data = $db->table('app_operators')->select('app_operators.*, base_countries.country_code, base_countries.country')
        ->join('base_countries', 'base_countries.id = app_operators.id_country', 'left')->where('app_operators.op_type', 0)
        ->orderBy('app_operators.operator_name', 'ASC')->limit(3000)->get()->getResult();
        $db->close();
        $finalData = json_encode($data);
        $finalData2 = json_encode($baseCURS);

        echo '{
            "code": 0,
            "error": "",
            "message": "",
            "curs": '.$finalData2.',
            "data": '.$finalData.'
        }';
    }

    public function postAdd()
    {   
        cekValidation('admin/products/add');
        $request = request();
        $json = $request->getJSON(true);
        $db = db_connect();
        
        $baseCURS = $db->table('base_profit')->where('current_date', date('Y-m-d'))->limit(1)->get()->getRow(); 

        $insert = $json;
        $insert['op_type'] = 0;
        $insert['is_file'] = ($insert['is_file'] === true) ? 1 : 0;
        $db->table('app_operators')->insert($insert);

        $data = $db->table('app_operators')->select('app_operators.*, base_countries.country_code, base_countries.country')
        ->join('base_countries', 'base_countries.id = app_operators.id_country')->where('app_operators.op_type', 0)
        ->orderBy('app_operators.operator_name', 'ASC')->limit(3000)->get()->getResult();
        $db->close();
        $finalData = json_encode($data);
        $finalData2 = json_encode($baseCURS);

        echo '{
            "code": 0,
            "error": "",
            "message": "Successfully add new Product.",
            "curs": '.$finalData2.',
            "data": '.$finalData.'
        }';
    }

    public function postUpdate()
    {   
        cekValidation('admin/products/update');
        $request = request();
        $json = $request->getJSON(true);
        $db = db_connect();
        
        $baseCURS = $db->table('base_profit')->where('current_date', date('Y-m-d'))->limit(1)->get()->getRow(); 

        $update = $json;
        $update['op_type'] = 0;
        if (isset($update['is_file'])) {
            $update['is_file'] = ($update['is_file'] === true || $update['is_file'] === 'true') ? 1 : 0;
        }
        // print_r($update);
        // die();
        $db->table('app_operators')->where('id', $update['id'])->update($update);

        // echo $db->getLastQuery();
        // die();

        $data = $db->table('app_operators')->select('app_operators.*, base_countries.country_code, base_countries.country')
        ->join('base_countries', 'base_countries.id = app_operators.id_country')->where('app_operators.op_type', 0)
        ->orderBy('app_operators.operator_name', 'ASC')->limit(3000)->get()->getResult();
        $db->close();
        $finalData = json_encode($data);
        $finalData2 = json_encode($baseCURS);

        echo '{
            "code": 0,
            "error": "",
            "message": "Successfully update Product.",
            "curs": '.$finalData2.',
            "data": '.$finalData.'
        }';
    }
}