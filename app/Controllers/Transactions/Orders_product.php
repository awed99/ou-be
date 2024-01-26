<?php

namespace App\Controllers\Transactions;

use Config\Services;
use CodeIgniter\Files\File;
use App\Controllers\Finance\Saldo;
use ZipArchive;

date_default_timezone_set("Asia/Bangkok");

class Orders_product extends BaseController
{
    public function index()
    {
        echo('welcome!');
    }

    public function postList_orders()
    {   
        cekValidation('transactions/orders_product/list_orders');
        // $this->postUpdate_all_status_activation();
        $request = request();
        $dataPost = $request->getJSON(true);
        // $postData = cek_token_login($dataPost);
        $postData = $dataPost;
        $db = db_connect();
        $api_key = getenv('API_SERVICE_KEY');
        $id_user = $db->table('app_users')->where('token_login', $request->header('Authorization')->getValue())->limit(1)->get()->getRow()->id_user;
        $builder = $db->table('order_products');
        $builder->select('app_operators.operator_code, app_operators.operator_name, app_operators.is_file, order_products.id, order_products.id_user, order_products.order_id, order_products.total, order_products.invoice_number, order_products.id_country, order_products.operator, order_products.number, order_products.sms_text, order_products.price_user, order_products.id_currency, order_products.exp_date, order_products.status, order_products.is_done, order_products.created_date, order_products.updated_date, base_countries.country_code, base_countries.country, COALESCE((select count(*) from order_product_files where order_product_files.order_id=order_products.order_id), 0) as total_done');
        $builder->join('app_operators', 'app_operators.operator_code = order_products.operator', 'left');
        $builder->join('base_countries', 'base_countries.id = order_products.id_country', 'left');
        $builder->where('order_products.id_user', $id_user)->where($dataPost['filter'])->orderBy('order_products.id', 'DESC');
        $query   = $builder->get(3000);
        // echo $db->getLastQuery();
        // die();
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

    public function postGet_files() {   
        cekValidation('transactions/orders_product/get_files');
        // $this->postUpdate_all_status_activation();
        $request = request();
        $dataPost = $request->getJSON(true);
        $db = db_connect();
        $dataFinal = $db->table('order_product_files')->where('order_id', $dataPost['order_id'])->orderBy('filename', 'ASC')->get()->getResult();
        $db->close();
        $finalData = json_encode($dataFinal);
        echo '{
            "code": 0,
            "error": "",
            "message": "",
            "data": '.$finalData.'
        }';
    }

    public function postDownload_files() {  
        cekValidation('transactions/orders_product/download_files');
        $request = request();
        $dataPost = $request->getJSON(true);
        $db = db_connect();
        if ($dataPost['download_type'] == 'all') {
            $where = '1 = 1';
        } else {
            $where = 'is_downloaded = 0';
        }
        $dataFinal = $db->table('order_product_files')->where('order_id', $dataPost['order_id'])->where($where)->orderBy('filename', 'ASC')->get()->getResult();
        

        $zip = new ZipArchive();
        $DelFilePath = $dataPost['order_id'].".zip";
        if(file_exists(FCPATH. "downloads/".$DelFilePath)) {
                unlink (FCPATH. "downloads/".$DelFilePath); 
        }
        if ($zip->open(FCPATH. "downloads/".$DelFilePath, ZIPARCHIVE::CREATE) != TRUE) {
                die ("Could not open archive");
        }

        $fileIDS = array();
        foreach ($dataFinal as $data) {
            array_push($fileIDS, $data->id);
            $zip->addFile(FCPATH . "files/" . $data->filename);
        }

        // close and save archive

        $zip->close(); 

        if ($fileIDS) {
            $db->table('order_product_files')->where('order_id', $dataPost['order_id'])->whereIn('id', $fileIDS)->update(array('is_downloaded' => 1, "downloaded_at" => date('Y-m-d H:i:s')));
        }
        // echo $db->getLastQuery();

        $id_user = $db->table('app_users')->where('token_login', $request->header('Authorization')->getValue())->limit(1)->get()->getRow()->id_user;
        
        $builder = $db->table('order_products');
        $builder->select('app_operators.operator_code, app_operators.operator_name, app_operators.is_file, order_products.id, order_products.id_user, order_products.is_done, order_products.order_id, order_products.total, order_products.invoice_number, order_products.id_country, order_products.operator, order_products.number, order_products.sms_text, order_products.price_user, order_products.id_currency, order_products.exp_date, order_products.status, order_products.created_date, base_countries.country_code, base_countries.country, COALESCE((select count(*) from order_product_files where order_product_files.order_id=order_products.order_id), 0) as total_done');
        $builder->join('app_operators', 'app_operators.operator_code = order_products.operator', 'left');
        $builder->join('base_countries', 'base_countries.id = order_products.id_country', 'left');
        $builder->where('order_products.id_user', $id_user)->where($dataPost['filter'])->orderBy('order_products.id', 'DESC');
        $query   = $builder->get(3000);
        $dataFinal = $query->getResult();
        
        $dataFinal2 = $db->table('order_product_files')->where('order_id', $dataPost['order_id'])->orderBy('filename', 'ASC')->get()->getResult();
        
        $db->close();

        $finalData = json_encode($dataFinal);
        $finalData2 = json_encode($dataFinal2);
        // unlink (FCPATH. "downloads/".$DelFilePath); 
        
        echo '{
            "code": 0,
            "error": "",
            "message": "Download is completed.",
            "files": '.$finalData2.',
            "data": '.$finalData.'
        }'; 
    }

    public function postDelete_zip() {  
        cekValidation('transactions/orders_product/delete_zip');
        $request = request();
        $dataPost = $request->getJSON(true);
        
        if(file_exists(FCPATH. "downloads/".$dataPost['order_id'].'.zip')) {
            unlink (FCPATH. "downloads/".$dataPost['order_id'].'.zip');
        }
        
        echo '{
            "code": 0,
            "error": "",
            "message": "",
            "data": null
        }'; 
    }

    public function postCreate() {
        cekValidation('transactions/orders_product/create');
        $request = request();
        $postData = $request->getJSON(true);
        $db = db_connect();
        
        // echo '{
        //     "code": 1,
        //     "error": "Error Order",
        //     "message": "No Product/Operator Available!",
        //     "data": null
        // }';          
        // $db->close();  
        // die();
        
        if ((float)$postData['total'] < 1) {
            echo '{
                "code": 1,
                "error": "Error Order",
                "message": "Min Order is 1 Pcs!",
                "data": null
            }';          
            $db->close();  
            die();
        }
        
        if ((float)$postData['total'] > 10) {
            echo '{
                "code": 1,
                "error": "Error Order",
                "message": "Max Order is 10 Pcs!",
                "data": null
            }';          
            $db->close();  
            die();
        }

        $user = $db->table('app_users')->where('token_login', $request->header('Authorization')->getValue())->limit(1)->get()->getRow();
        $issetOrder = $db->table('order_products')->where('id_user', $user->id_user)
        ->where('is_file', (int)$postData['is_file'])->where('status', 'Active')->get()->getNumRows();

        $orderType = ($postData['is_file'] === '1') ? 'File' : 'OTP';

        if ($issetOrder > 0) {
            echo '{
                "code": 2,
                "error": "Error Order",
                "message": "You have to finish order '.$orderType.' first !",
                "data": null
            }';          
            $db->close();  
            die();
        }

        
        $saldo = new Saldo;
        $dataSALDO = $saldo->get_user_saldo($request->header('Authorization')->getValue());
        $opPrice = $db->table('app_operators')->where('operator_code', $postData['operator'])->where('op_type', 0)->get()->getRow()->op_price;
        $cost = ((float)$opPrice * (float)$postData['total']);

        // print_r($dataSALDO);
        // die();
        
        if ($dataSALDO->data->saldo < $cost) {
            echo '{
                "code": 1,
                "error": "Insuficient Balance",
                "message": "Insuficient Balance. Topup your balance first!",
                "data": null
            }';          
            $db->close();  
            die();
        }

        $builder = $db->table('order_products');

        $general_profit  = $db->table('base_profit')->limit(1)->get()->getRow()->general_profit;
        $baseCURS = $db->table('base_profit')->where('current_date', date('Y-m-d'))->limit(1)->get()->getRow();
        $usdCURS = $baseCURS->curs_usd_to_idr;

        // print_r($postData);
        // $insert['order_id'] = substr(md5(rand(1,10000).date('YmdHis')), 0, 6);
        $insert = array();
        $insert['id_user'] = $user->id_user;
        $insert['id_country'] = $postData['id_country'];
        $insert['id_country'] = $postData['id_country'];
        $insert['operator'] = $postData['operator'];
        $insert['is_file'] = $postData['is_file'];
        $insert['total'] = $postData['total'];
        $insert['order_id'] = strtoupper(substr(md5(date('YmdHis').rand(1, 10000000000000)), rand(0, 10), 10));
        $insert['price_admin'] = ($opPrice);
        $insert['price_real'] = ($opPrice);
        $insert['price_user'] = ($cost);
        $insert['price_profit'] = ($cost);
        $insert['price_profit_idr'] = $insert['price_profit'] * $usdCURS;
        $insert['invoice_number'] = 'PROD/'.date('Y').'/'.date('m').'/'.$insert['id_user'].'/'.$insert['order_id'];
        // echo json_encode($insert);

        $builder->insert($insert);

        $builder->select('order_products.id id_order, app_operators.operator_code, app_operators.operator_name, app_operators.is_file, order_products.*, base_countries.country_code, base_countries.country');
        $builder->join('app_operators', 'app_operators.operator_code = order_products.operator', 'left');
        $builder->join('base_countries', 'base_countries.id = order_products.id_country', 'left');
        $builder->where('order_products.id_user', $user->id_user)->where('order_products.id_user', $insert['id_user'])->orderBy('order_products.id', 'DESC');
        $query   = $builder->get(1000);
        $dataFinal = $query->getResult();
        $db->close();
        $finalData = json_encode($dataFinal);
        return $finalData;
    }

    public function postGet_otp() {   
        cekValidation('transactions/orders_product/get_otp');
        // $this->postUpdate_all_status_activation();
        $request = request();
        $dataPost = $request->getJSON(true);
        $db = db_connect();
        $dataFinal = $db->table('order_product_otp')->where('order_id', $dataPost['order_id'])->orderBy('id', 'DESC')->get()->getResult();
        $_dataFinal = [];
        foreach ($dataFinal as $val) {
            $val->phone_number = ((int)$val->is_request >= 1) ? $val->phone_number : maskingString($val->phone_number);
            $val->otp_code = ((int)$val->is_request >= 1) ? $val->otp_code : maskingString($val->otp_code);
            array_push($_dataFinal, $val);
        }
        $db->close();
        $finalData = json_encode($_dataFinal);
        echo '{
            "code": 0,
            "error": "",
            "message": "",
            "data": '.$finalData.'
        }';
    }

    public function postShow_phone_number() {  
        cekValidation('transactions/orders_product/show_phone_number');
        $request = request();
        $dataPost = $request->getJSON(true);
        $db = db_connect();
        
        $user = $db->table('app_users')->where('token_login', $request->header('Authorization')->getValue())->limit(1)->get()->getRow();
        
        $update['id'] = $dataPost['id'];
        $update['order_id'] = $dataPost['order_id'];
        $update['created_by'] = $user->username;
        $update['is_request'] = 1;
        $db->table('order_product_otp')->where('id', $dataPost['id'])->where('order_id', $dataPost['order_id'])->update($update);
        
        $dataFinal = $db->table('order_product_otp')->where('order_id', $dataPost['order_id'])->orderBy('id', 'DESC')->get()->getResult();
        $_dataFinal = [];
        foreach ($dataFinal as $val) {
            $val->phone_number = ((int)$val->is_request >= 1) ? $val->phone_number : maskingString($val->phone_number);
            $val->otp_code = ((int)$val->is_request >= 1) ? $val->otp_code : maskingString($val->otp_code);
            array_push($_dataFinal, $val);
        }
        
        $builder = $db->table('order_products');
        $builder->select('app_operators.operator_code, app_operators.operator_name, app_operators.is_file, order_products.id, order_products.is_done, order_products.id_user, order_products.order_id, order_products.total, order_products.invoice_number, order_products.id_country, order_products.operator, order_products.number, order_products.sms_text, order_products.price_user, order_products.id_currency, order_products.exp_date, order_products.status, order_products.created_date, base_countries.country_code, base_countries.country, COALESCE((select count(*) from order_product_files where order_product_files.order_id=order_products.order_id), 0) as total_done');
        $builder->join('app_operators', 'app_operators.operator_code = order_products.operator', 'left');
        $builder->join('base_countries', 'base_countries.id = order_products.id_country', 'left');
        $builder->where('order_products.id_user', $user->id_user)->where($dataPost['filter'])->orderBy('order_products.id', 'DESC');
        $query   = $builder->get(3000);
        $dataFinal2 = $query->getResult();
        
        $db->close();
        $finalData = json_encode($_dataFinal);
        $finalData2 = json_encode($dataFinal2);
        
        echo '{
            "code": 0,
            "error": "",
            "message": "Success Show Phone Number.",
            "otp": '.$finalData.',
            "data": '.$finalData2.'
        }'; 
    }

}
