<?php

namespace App\Controllers;

use App\Controllers\Transactions\Orders;
use App\Controllers\Finance\Saldo;

class Service extends BaseController
{
    public function index()
    {
        echo('welcome!');
        // $this->transactions = new Transactions;
    }

    public function postList()
    {   
        $this->update_price();
        $request = request();
        $dataPost = $request->getJSON();
        $dataRequest = cek_token_login($dataPost);
        $q = 'SELECT 
        apps.id, apps.service_code, bc.country, apps.service_name, apps.supplier_price, apps.selling_price, apps.profit, apps.is_active 
        FROM `app_services` apps 
        left join base_countries bc on bc.id = apps.id_base_country 
        order by apps.service_name;
        ';
        $db = db_connect();
        $query = $db->query($q);
        $dataFinal = $query->getResult();
        $db->close();
        $finalData = json_encode($dataFinal);
        echo '{
            "code": 0,
            "error": "",
            "message": "",
            "data": '.$finalData.'
        }';
    }

    public function postListBest()
    {   
        $this->update_price();
        $request = request();
        $dataPost = $request->getJSON();
        $dataRequest = cek_token_login($dataPost);
        $q = 'SELECT 
        apps.id, apps.service_code, bc.country, apps.service_name, apps.supplier_price, apps.selling_price, apps.profit, apps.is_active 
        FROM `app_services` apps 
        left join base_countries bc on bc.id = apps.id_base_country 
        order by apps.service_name;
        ';
        $db = db_connect();
        $query = $db->query($q);
        $dataFinal = $query->getResult();
        $db->close();
        $finalData = json_encode($dataFinal);
        echo '{
            "code": 0,
            "error": "",
            "message": "",
            "data": '.$finalData.'
        }';
    }

    public function postListServices()
    {   
        $this->update_price();
        $request = request();
        $dataPost = $request->getJSON();
        $dataRequest = cek_token_login($dataPost);
        $q = 'SELECT 
        apps.id, apps.service_code, bc.country, apps.service_name, apps.supplier_price, apps.selling_price, apps.profit, apps.is_active 
        FROM `app_services` apps 
        left join base_countries bc on bc.id = apps.id_base_country 
        where apps.is_active = 1
        order by apps.service_name;
        ';
        $db = db_connect();
        $query = $db->query($q);
        $dataFinal = $query->getResult();
        $db->close();
        $finalData = json_encode($dataFinal);
        echo '{
            "code": 0,
            "error": "",
            "message": "",
            "data": '.$finalData.'
        }';
    }

    public function postGet_operators($insider=false)
    {   
        if (!$insider) {
            cekValidation('service/get_operators');
        }
        $request = request();
        $dataRequest = $request->getJSON(true);
        $db = db_connect();
        if (isset($dataRequest['country_id'])) {
            $builder = $db->table('app_operators')->where('id_country', $dataRequest['country_id'])->where('op_type', 1)->get()->getResult();
        } else {
            $builder = $db->table('app_operators')->where('op_type', 1)->get()->getResult();
        }
        // $builder->select('app_operators.*, base_countries.country');
        // $builder->join('base_countries', 'base_countries.id = app_operators.id_country', 'left');
        $dataFinal = json_encode($builder);
        $db->close();
        if ($insider) {
            return $builder;
        }

        echo '{
            "code": 0,
            "error": "",
            "message": "",
            "data": '.$dataFinal.'
        }';
    }

    public function postGet_operators0($insider=false)
    {   
        if (!$insider) {
            cekValidation('service/get_operators0');
        }
        $request = request();
        $dataRequest = $request->getJSON(true);
        $db = db_connect();
        if (isset($dataRequest['country_id'])) {
            $builder = $db->table('app_operators')->where('id_country', $dataRequest['country_id'])->where('op_type', 0)->where('status', 1)->get()->getResult();
        } else {
            $builder = $db->table('app_operators')->where('op_type', 0)->where('status', 1)->get()->getResult();
        }
        // $builder->select('app_operators.*, base_countries.country');
        // $builder->join('base_countries', 'base_countries.id = app_operators.id_country', 'left');
        $dataFinal = json_encode($builder);
        $db->close();
        if ($insider) {
            return $builder;
        }

        echo '{
            "code": 0,
            "error": "",
            "message": "",
            "data": '.$dataFinal.'
        }';
    }

    public function postGet_operators1($insider=false)
    {   
        if (!$insider) {
            cekValidation('service/get_operators1');
        }
        $request = request();
        $dataRequest = $request->getJSON(true);
        $db = db_connect();
        $builder = $db->table('app_operators')->select('distinct(id_country) as id_country')->where('op_type', 0)->get()->getResult();
        // $builder->select('app_operators.*, base_countries.country');
        // $builder->join('base_countries', 'base_countries.id = app_operators.id_country', 'left');
        $dataFinal = json_encode($builder);
        $db->close();
        if ($insider) {
            return $builder;
        }

        echo '{
            "code": 0,
            "error": "",
            "message": "",
            "data": '.$dataFinal.'
        }';
    }

    public function postList_products($insider=false)
    {   
        if (!$insider) {
            cekValidation('service/list_products');
        }
        $request = request();
        $dataRequest = $request->getJSON(true);
        $db = db_connect();
        $dataFinal = $db->table('app_products')->where('status', 0)->where($dataRequest)->get(100)->getResult();
        $total_data = $db->table('app_products')->where('status', 0)->where($dataRequest)->get()->getNumRows();
        $db->close();
        if ($insider) {
            return array("data" => $dataFinal, "total_data" => $total_data);
        }
        $finalData = json_encode($dataFinal);
        echo '{
            "code": 0,
            "error": "",
            "message": "",
            "data": '.$finalData.',
            "total_data": '.$total_data.'
        }';
    }

    public function postList_services($insider=false)
    {   
        if (!$insider) {
            cekValidation('service/list_services');
        }
        $request = request();
        $dataRequest = $request->getJSON(true);
        $this->update_price($dataRequest['country_id']);
        if (isset($dataRequest['service_codes'])) {
            $q = 'SELECT 
            apps.id, apps.service_code, bc.country, apps.service_name, apps.stocks, apps.supplier_price, apps.selling_price, apps.profit, apps.is_active 
            FROM `app_services` apps 
            left join base_countries bc on bc.id = apps.id_base_country 
            where apps.is_active = 1 and apps.service_code in ('.$dataRequest['service_codes'].')
            order by apps.service_name;
            ';
        } else if (isset($dataRequest['country_code'])) {
            $q = 'SELECT 
            apps.id, apps.service_code, bc.country, apps.service_name, apps.stocks, apps.supplier_price, apps.selling_price, apps.profit, apps.is_active 
            FROM `app_services` apps 
            left join base_countries bc on bc.id = apps.id_base_country 
            where apps.is_active = 1 and bc.country_code = \''.$dataRequest['country_code'].'\'
            order by apps.service_name;
            ';
        } else {
            $q = 'SELECT 
            apps.id, apps.service_code, bc.country, apps.service_name, apps.stocks, apps.supplier_price, apps.selling_price, apps.profit, apps.is_active 
            FROM `app_services` apps 
            left join base_countries bc on bc.id = apps.id_base_country 
            where apps.is_active = 1
            order by apps.service_name;
            ';
        }
        $db = db_connect();
        $query = $db->query($q);
        $dataFinal = $query->getResult();
        $db->close();
        if ($insider) {
            return $dataFinal;
        }
        $finalData = json_encode($dataFinal);
        echo '{
            "code": 0,
            "error": "",
            "message": "",
            "data": '.$finalData.'
        }';
    }

    public function update_price($idCountry=6)
    {  
        $request = request();
        $db = db_connect();
        $dataReal = get_services_price($idCountry);
        $loop = 0;
        $where = '(';
        foreach (array_keys($dataReal) as $key0) {
            if ($loop > 0) {
                $where .= ' OR ';
            }
            $where .= " service_code='".$key0."' ";
            $loop++;
        }
        $where .= ')';
        $builder = $db->table('app_services');
        $general_profit = $db->table('base_profit')->get()->getRow()->general_profit;
        $builder->where($where);
        $query   = $builder->get();
        $data = [];
        // print_r($dataReal);
        // die();
        foreach ($query->getResult() as $row) {
            $row->stocks = explode("-", $dataReal[$row->service_code])[1];
            $row->supplier_price = explode("-", $dataReal[$row->service_code])[0];
            $row->selling_price = explode("-", $dataReal[$row->service_code])[0] + $row->profit;
            $row->profit = $row->profit;
            $row->id_base_country = $idCountry;
            $data[] = get_object_vars($row);
        }
        $builder->upsertBatch($data);
        $dataFinal = $query->getResult();
        $db->close();
    }

    public function postUpdate_price()
    {   
        $request = request();
        $dataPost = $request->getJSON();
        $dataRequest = cek_token_login($dataPost);
        $db = db_connect();
        $request = request();
        $builder = $db->table('app_services');
        
        if (!isset($dataRequest['id']) || $dataRequest['id'] == 0) {
            $builder->where('1=1');
        } else {
            $builder->where('id', $dataRequest['id']);
        }
        
        unset($dataRequest['id']);
        $builder->set('profit', $dataRequest['profit']);
        $builder->set('selling_price', 'supplier_price+profit', false);
        if (isset($dataRequest['is_active'])) {
            $builder->set('is_active', $dataRequest['is_active']);
        }
        $builder->update();
        $q = 'SELECT 
        apps.id, apps.service_code, bc.country, apps.service_name, apps.supplier_price, apps.selling_price, apps.profit, apps.is_active 
        FROM `app_services` apps 
        left join base_countries bc on bc.id = apps.id_base_country 
        order by apps.service_name;
        ';
        $dataFinal = $db->query($q)->getResult();
        $db->close();
        $finalData = json_encode($dataFinal);
        echo '{
            "code": 0,
            "error": "",
            "message": "Sucess update data.",
            "data": '.$finalData.'
        }';
    }

    public function postGet_operators2()
    {   
        $request = request();
        $dataPost = $request->getJSON();
        $dataRequest = cek_token_login($dataPost);
        $db = db_connect();
        $builder = $db->table('app_operators');
        $builder->select('app_operators.*, base_countries.country');
        $builder->join('base_countries', 'base_countries.id = app_operators.id_country', 'left');
        $builder->where('app_operators.operator_name <> \'\'');
        $dataFinal = json_encode($builder->get()->getResult());

        $db->close();
        echo '{
            "code": 0,
            "error": "",
            "message": "",
            "data": '.$dataFinal.'
        }';
    }

    public function postSet_operator_name()
    {  
        $request = request();
        $dataPost = $request->getJSON();
        $dataRequest = cek_token_login($dataPost);
        $db = db_connect();
        $builder = $db->table('app_operators');
        $builder->where('id', $dataRequest['id'])->ignore()->update($dataRequest);
        $builder->select('app_operators.*, base_countries.country');
        $builder->join('base_countries', 'base_countries.id = app_operators.id_country', 'left');
        $dataFinal = json_encode($builder->get()->getResult());
        $db->close();

        echo '{
            "code": 0,
            "error": "",
            "message": "",
            "data": '.$dataFinal.'
        }';
    }

    public function postGet_number_status()
    {   
        $request = request();
        $dataPost = $request->getJSON();
        $dataRequest = cek_token_login($dataPost);
        $db = db_connect();
        $api_key = getenv('API_SERVICE_KEY');

        
        $dataFinal = curl(getenv('API_SERVICE').$api_key.'&action=getNumbersStatus&country='.$dataRequest['country'].'&operator='.$dataRequest['operator']);

        $db->close();
        echo '{
            "code": 0,
            "error": "",
            "message": "",
            "data": '.$dataFinal.'
        }';
    }

    public function postOrder_number()
    {   
        cekValidation('service/order_number');
        $request = request();
        $dataRequest = $request->getJSON(true);

        
        $saldo = new Saldo;
        $dataSALDO = $saldo->get_user_saldo($request->header('Authorization')->getValue());

        // print_r($dataSALDO);
        // die();
        
        if ($dataSALDO->data->saldo < 1) {
            echo '{
                "code": 1,
                "error": "Insuficient Balance",
                "message": "Insuficient Balance. Topup your balance first!",
                "data": null
            }';            
            die();
        }

        // $dataRequest = cek_token_login($dataPost);
        $db = db_connect();
        // $api_key = $db->table('token_api')->orderBy('id', 'DESC')->limit(1)->get()->getRow()->api_key;
        $api_key = getenv('API_SERVICE_KEY');
        $price_user = $db->table('app_services')->where('id', $dataRequest['service_id'])->orderBy('id', 'DESC')->limit(1)->get()->getRow()->selling_price;

        
        $dataX = curl(getenv('API_SERVICE').$api_key.'&action=getNumberV2&service='.$dataRequest['service'].'&country='.$dataRequest['country'].'&operator='.$dataRequest['operator']);
        
        $user = $db->table('app_users')->where('token_login', $request->header('Authorization')->getValue())->limit(1)->get()->getRow();
        $dataY['id_user'] = $user->id_user;
        $dataY['price_user'] = ($price_user * 1.6);
        
        // print_r($dataX);
        // die();
        if ($dataX === 'NO_NUMBERS'){
            $db->close();
            echo '{
                "code": 1,
                "error": "System Error.",
                "message": "No numbers available!",
                "data": null
            }';            
            die();
        }

        
        $order_id = json_decode($dataX);
        $dataFinal = curl(getenv('API_SERVICE').$api_key.'&action=setStatus&status=1&id='.$order_id->activationId);

        $orders = new Orders;
        $dataZ = $orders->postCreate($dataX, $dataY);
        // $data = json_decode($dataX);
        // print_r($dataX);
        // $user = $db->table('app_users')->where('token_login', $request->header('Authorization')->getValue())->get()->getRow();
        // echo curl(getenv('API_TRANSACTIONS').'orders/create', 1, 'id_user='.$user->id_user.'&token_login='.$request->header('Authorization')->getValue().'&data='.$dataX.'&service_id='.$dataRequest['service_id'].'&country='.$dataRequest['country'].'&operator='.$dataRequest['operator'].'&price_user='.$price_user);
        // $dataY = curl(getenv('API_TRANSACTIONS').'orders/create', 1, 'id_user='.$user->id_user.'&token_login='.$request->header('Authorization')->getValue().'&data='.$dataX.'&service_id='.$dataRequest['service_id'].'&country='.$dataRequest['country'].'&operator='.$dataRequest['operator'].'&price_user='.$price_user);
        // print_r($dataY);
        $db->close();
        echo '{
            "code": 0,
            "error": "",
            "message": "",
            "data": '.$dataZ.'
        }';
    }

    public function postList_open_order()
    {   
        $request = request();
        $dataPost = $request->getJSON();
        $dataRequest = cek_token_login($dataPost);
        $db = db_connect();
        $api_key = getenv('API_SERVICE_KEY');

        
        $dataFinal = curl(getenv('API_SERVICE').$api_key.'&action=getActiveActivations');

        $db->close();
        echo '{
            "code": 0,
            "error": "",
            "message": "",
            "data": '.$dataFinal.'
        }';
    }

    public function postOrder_list_by_id()
    {   
        $request = request();
        $dataPost = $request->getJSON();
        $dataRequest = cek_token_login($dataPost);
        $db = db_connect();
        $api_key = getenv('API_SERVICE_KEY');

        
        $dataFinal = curl(getenv('API_SERVICE').$api_key.'&action=getStatus&id='.$dataRequest['id']);

        $db->close();
        echo '{
            "code": 0,
            "error": "",
            "message": "",
            "data": '.$dataFinal.'
        }';
    }

    // public function postServices_price()
    // {   
    //     $request = request();
    //     $dataPost = $request->getJSON();
    //     $dataRequest = cek_token_login($dataPost);
    //     $db = db_connect();
    //     $dataReal = get_services_price(6);
    //     $loop = 0;
    //     $where = '';
    //     foreach (array_keys($dataReal) as $key0) {
    //         if ($loop > 0) {
    //             $where .= ' OR ';
    //         }
    //         $where .= " service_code='".$key0."' ";
    //         $loop++;
    //     }
    //     $builder = $db->table('app_services');
    //     // $builder2 = $db->table('app_services');
    //     $general_profit = $db->table('base_profit')->get()->getRow()->general_profit;
    //     $builder->where($where);
    //     $query   = $builder->get();
    //     $data = [];
    //     foreach ($query->getResult() as $row) {
    //         $row->supplier_price = $dataReal[$row->service_code];
    //         $row->selling_price = $dataReal[$row->service_code] + $row->profit;
    //         $row->profit = $row->profit;
    //         $data[] = get_object_vars($row);
    //     }
    //     // $builder->truncate();
    //     $builder->upsertBatch($data);
    //     $dataFinal = $query->getResult();
    //     $db->close();
    //     $finalData = json_encode($dataFinal);
    //     echo '{
    //         "code": 0,
    //         "error": "",
    //         "message": "",
    //         "data": '.$finalData.'
    //     }';
    // }

    // public function postUpdate_services_price()
    // {   
    //     $request = request();
    //     $dataPost = $request->getJSON();
    //     $dataRequest = cek_token_login($dataPost);
    //     $db = db_connect();
    //     $dataReal = get_services_price(6);
    //     $loop = 0;
    //     $where = '';
    //     foreach (array_keys($dataReal) as $key0) {
    //         if ($loop > 0) {
    //             $where .= ' OR ';
    //         }
    //         $where .= " service_code='".$key0."' ";
    //         $loop++;
    //     }
    //     $builder = $db->table('base_services');
    //     $builder2 = $db->table('app_services');
    //     $builder->where($where);
    //     $query   = $builder->get();
    //     $data = [];
    //     foreach ($query->getResult() as $row) {
    //         $row->id_base_country = 6;
    //         $row->supplier_price = $dataReal[$row->service_code];
    //         $row->selling_price = $dataReal[$row->service_code] + 0.03;
    //         $row->profit = 0.03;
    //         $data[] = get_object_vars($row);
    //     }
    //     $builder2->truncate();
    //     $builder2->insertBatch($data);
    //     $dataFinal = $query->getResult();
    //     $db->close();
    //     $finalData = json_encode($dataFinal);
    //     echo '{
    //         "code": 0,
    //         "error": "",
    //         "message": "",
    //         "data": '.$finalData.'
    //     }';
    // }
}
