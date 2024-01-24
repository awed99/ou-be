<?php

namespace App\Controllers\Admin;
use Config\Services;
use CodeIgniter\Files\File;

date_default_timezone_set("Asia/Bangkok");

class Orders extends BaseController
{
    public function index()
    {
        echo('welcome!');
    }

    public function postList() {   
        cekValidation('admin/orders/list');
        // $this->postUpdate_all_status_activation();
        $request = request();
        $dataPost = $request->getJSON(true);
        // $postData = cek_token_login($dataPost);
        $postData = $dataPost;
        $db = db_connect();
        $api_key = getenv('API_SERVICE_KEY');
        $id_user = $db->table('app_users')->where('token_login', $request->header('Authorization')->getValue())->limit(1)->get()->getRow()->id_user;
        $builder = $db->table('order_products');
        $builder->select('app_operators.operator_code, app_operators.operator_name, app_operators.is_file, order_products.id, order_products.id_user, order_products.is_done, order_products.order_id, order_products.total, order_products.invoice_number, order_products.id_country, order_products.operator, order_products.number, order_products.sms_text, order_products.price_user, order_products.id_currency, order_products.exp_date, order_products.status, order_products.created_date, order_products.updated_date, base_countries.country_code, base_countries.country, COALESCE((select count(*) from order_product_files where order_product_files.order_id=order_products.order_id), 0) as total_done');
        $builder->join('app_operators', 'app_operators.operator_code = order_products.operator', 'left');
        $builder->join('base_countries', 'base_countries.id = order_products.id_country', 'left');
        $builder->where('app_operators.is_file', 1);
        $builder->orderBy('order_products.status', 'ASC');
        $query   = $builder->get(3000);
        // echo $db->getLastQuery();
        // die();

        $expFiles = $db->table('order_product_files')->where('created_at >', date('Y-m-d H:i:s', strtotime('3 day')))->get()->getResult();
        foreach ($expFiles as $files) {
            if (file_exists(FCPATH. "files/".$files->filename.'.zip')) {
                unlink (FCPATH. "files/".$files->filename.'.zip');
            }
        }

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
        cekValidation('admin/orders/get_files');
        // $this->postUpdate_all_status_activation();
        $request = request();
        $dataPost = $request->getJSON(true);
        $db = db_connect();
        $dataFinal = $db->table('order_product_files')->where('order_id', $dataPost['order_id'])->orderBy('id', 'DESC')->get()->getResult();
        $db->close();
        $finalData = json_encode($dataFinal);
        echo '{
            "code": 0,
            "error": "",
            "message": "",
            "data": '.$finalData.'
        }';
    }

    public function postUpload() {   
        cekValidation('admin/orders/upload');
        $request = request();
        $db = db_connect();
        $dataPost = $request->getPost();
        
        $baseCURS = $db->table('base_profit')->where('current_date', date('Y-m-d'))->limit(1)->get()->getRow(); 
        $user = $db->table('app_users')->where('token_login', $request->header('Authorization')->getValue())->limit(1)->get()->getRow();
        $activeOrder = $db->table('order_products')->where('is_file', 1)->where('order_id', $dataPost['order_id'])->where('status', 'Active')->limit(1)->get()->getRow();
        $files = $request->getFileMultiple('file');
 
        $_isDone = (int)$activeOrder->is_done;
        $_total = (int)$activeOrder->total;
        
        $loop = 0;
        foreach ($files as $file) {

            if ($file->isValid() && !$file->hasMoved() && $_isDone < $_total) {
                $newName = $file->getName();
                $x = $file->move(ROOTPATH  . 'public/files', $newName);
                
                $insertFile['order_id'] = $dataPost['order_id'];
                $insertFile['filename'] = $newName;
                $insertFile['filesize'] = $file->getSizeByUnit('mb') . ' Mb';
                $insertFile['status'] = 1;
                $insertFile['created_by'] = $user->username;
                $db->table('order_product_files')->insert($insertFile);

                $_isDone = $_isDone + 1;
                $loop = $loop + 1;
            }
             
        }

        $updateOrder['is_done'] = $_isDone;
        $updateOrder['updated_date'] = date('Y-m-d H:i:s');
        $updateOrder['status'] = ($_isDone >= $_total) ? 'Done' : 'Active';
        $db->table('order_products')->where('order_id', $dataPost['order_id'])->update($updateOrder);
        
        $insertJournal['id_user'] = $activeOrder->id_user;
        $insertJournal['amount_credit'] = (float)$activeOrder->price_real * (float)$baseCURS->curs_usd_to_idr * $loop;
        $insertJournal['amount_debet'] = 0;
        $insertJournal['amount_credit_usd'] = (float)$activeOrder->price_real * $loop;
        $insertJournal['amount_debet_usd'] = 0;
        $insertJournal['accounting_type'] = 5;
        if (!isset($insertJournal['description'])) {
            $insertJournal['description'] = 'Selling '.$loop.' File Product ' . $activeOrder->order_id;
        }
        $db->table('journal_finance')->insert($insertJournal);

        $builder = $db->table('order_products');
        $builder->select('app_operators.operator_code, app_operators.operator_name, app_operators.is_file, order_products.id, order_products.is_done, order_products.id_user, order_products.order_id, order_products.total, order_products.invoice_number, order_products.id_country, order_products.operator, order_products.number, order_products.sms_text, order_products.price_user, order_products.id_currency, order_products.exp_date, order_products.status, order_products.created_date, order_products.updated_date, base_countries.country_code, base_countries.country, COALESCE((select count(*) from order_product_files where order_product_files.order_id=order_products.order_id), 0) as total_done');
        $builder->join('app_operators', 'app_operators.operator_code = order_products.operator', 'left');
        $builder->join('base_countries', 'base_countries.id = order_products.id_country', 'left');
        $builder->where('app_operators.is_file', 1);
        $builder->orderBy('order_products.id', 'ASC')->orderBy('order_products.status', 'ASC');
        $query   = $builder->get(3000);
        // echo $db->getLastQuery();
        // die();
        $dataFinal = $query->getResult();

        
        $dataFinal2 = $db->table('order_product_files')->where('order_id', $dataPost['order_id'])->orderBy('id', 'desc')->get()->getResult();

        $db->close();
        $finalData = json_encode($dataFinal);
        $finalData2 = json_encode($dataFinal2);
        
        echo '{
            "code": 0,
            "error": "",
            "message": "",
            "data": '.$finalData.',
            "files": '.$finalData2.'
        }';
    }



    public function postList_otp() {   
        cekValidation('admin/orders/list_otp');
        // $this->postUpdate_all_status_activation();
        $request = request();
        $dataPost = $request->getJSON(true);
        // $postData = cek_token_login($dataPost);
        $postData = $dataPost;
        $db = db_connect();
        $id_user = $db->table('app_users')->where('token_login', $request->header('Authorization')->getValue())->limit(1)->get()->getRow()->id_user;
        $builder = $db->table('order_products');
        $builder->select('app_operators.operator_code, app_operators.operator_name, app_operators.is_file, order_products.id, order_products.is_done, order_products.id_user, order_products.order_id, order_products.total, order_products.invoice_number, order_products.id_country, order_products.operator, order_products.number, order_products.sms_text, order_products.price_user, order_products.id_currency, order_products.exp_date, order_products.status, order_products.created_date, order_products.updated_date, base_countries.country_code, base_countries.country, COALESCE((select count(*) from order_product_files where order_product_files.order_id=order_products.order_id), 0) as total_done');
        $builder->join('app_operators', 'app_operators.operator_code = order_products.operator', 'left');
        $builder->join('base_countries', 'base_countries.id = order_products.id_country', 'left');
        $builder->where('app_operators.is_file', 0);
        $builder->orderBy('order_products.status', 'ASC');
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


    public function postGet_otp() {   
        cekValidation('admin/orders/get_otp');
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

    public function postAdd_phone_number() {  
        cekValidation('admin/orders/add_phone_number');
        $request = request();
        $dataPost = $request->getJSON(true);
        $db = db_connect();
        
        $user = $db->table('app_users')->where('token_login', $request->header('Authorization')->getValue())->limit(1)->get()->getRow();
        
        $insertFile['order_id'] = $dataPost['order_id'];
        $insertFile['phone_number'] = $dataPost['phone_number'];
        $insertFile['created_by'] = $user->username;
        $db->table('order_product_otp')->insert($insertFile);
        
        $update2['updated_date'] = date('Y-m-d H:i:s');
        $db->table('order_products')->where('order_id', $dataPost['order_id'])->update($update2);
        
        $dataFinal = $db->table('order_product_otp')->where('order_id', $dataPost['order_id'])->orderBy('id', 'DESC')->get()->getResult();
        $_dataFinal = [];
        foreach ($dataFinal as $val) {
            $val->phone_number = ((int)$val->is_request >= 1) ? $val->phone_number : maskingString($val->phone_number);
            $val->otp_code = ((int)$val->is_request >= 1) ? $val->otp_code : maskingString($val->otp_code);
            array_push($_dataFinal, $val);
        }
        
        $builder = $db->table('order_products');
        $builder->select('app_operators.operator_code, app_operators.operator_name, app_operators.is_file, order_products.id, order_products.is_done, order_products.id_user, order_products.order_id, order_products.total, order_products.invoice_number, order_products.id_country, order_products.operator, order_products.number, order_products.sms_text, order_products.price_user, order_products.id_currency, order_products.exp_date, order_products.status, order_products.created_date, order_products.updated_date, base_countries.country_code, base_countries.country, COALESCE((select count(*) from order_product_files where order_product_files.order_id=order_products.order_id), 0) as total_done');
        $builder->join('app_operators', 'app_operators.operator_code = order_products.operator', 'left');
        $builder->join('base_countries', 'base_countries.id = order_products.id_country', 'left');
        $builder->where('app_operators.is_file', 0);
        $builder->orderBy('order_products.status', 'ASC');
        $query   = $builder->get(3000);
        $dataFinal2 = $query->getResult();
        
        $db->close();
        $finalData = json_encode($_dataFinal);
        $finalData2 = json_encode($dataFinal2);
        
        echo '{
            "code": 0,
            "error": "",
            "message": "Success Add Phone Number.",
            "otp": '.$finalData.',
            "data": '.$finalData2.'
        }'; 
    }

    public function postUpdate_otp() {  
        cekValidation('admin/orders/update_otp');
        $request = request();
        $dataPost = $request->getJSON(true);
        $db = db_connect();
        
        $user = $db->table('app_users')->where('token_login', $request->header('Authorization')->getValue())->limit(1)->get()->getRow();
        
        $update['otp_code'] = $dataPost['otp_code'];
        $update['created_by'] = $user->username;
        $update['is_request'] = 2;
        $db->table('order_product_otp')->where('id', $dataPost['id'])->where('order_id', $dataPost['order_id'])->update($update);
        
        $totalDone = $db->table('order_product_otp')->where('is_request', 2)->where('order_id', $dataPost['order_id'])->get()->getNumRows();
        $orderProducts = $db->table('order_products')->where('order_id', $dataPost['order_id'])->get()->getRow();
        $update2['is_done'] = $totalDone;
        $update2['updated_date'] = date('Y-m-d H:i:s');
        $update2['status'] = ((int)$totalDone >= $orderProducts->total) ? 'Done' : 'Active';
        $db->table('order_products')->where('order_id', $dataPost['order_id'])->update($update2);

        $baseCURS = $db->table('base_profit')->where('current_date', date('Y-m-d'))->limit(1)->get()->getRow(); 
        $journal_finance = $db->table('journal_finance')->where('description', 'Selling '.$totalDone.' OTP Product ' . $orderProducts->order_id)->get()->getNumRows();
        // print_r($journal_finance);
        // die();
        if ((int)$totalDone >= $orderProducts->total && (int)$journal_finance < 1) {
            $insertJournal['id_user'] = $user->id_user;
            $insertJournal['amount_credit'] = (float)$orderProducts->price_user * (float)$baseCURS->curs_usd_to_idr;
            $insertJournal['amount_debet'] = 0;
            $insertJournal['amount_credit_usd'] = (float)$orderProducts->price_user;
            $insertJournal['amount_debet_usd'] = 0;
            $insertJournal['accounting_type'] = 5;
            $insertJournal['description'] = 'Selling '.$totalDone.' OTP Product ' . $orderProducts->order_id;
            $db->table('journal_finance')->insert($insertJournal);
        }
        
        $dataFinal = $db->table('order_product_otp')->where('order_id', $dataPost['order_id'])->orderBy('id', 'DESC')->get()->getResult();
        $_dataFinal = [];
        foreach ($dataFinal as $val) {
            $val->phone_number = ((int)$val->is_request >= 1) ? $val->phone_number : maskingString($val->phone_number);
            $val->otp_code = ((int)$val->is_request >= 1) ? $val->otp_code : maskingString($val->otp_code);
            array_push($_dataFinal, $val);
        }
        
        $builder = $db->table('order_products');
        $builder->select('app_operators.operator_code, app_operators.operator_name, app_operators.is_file, order_products.id, order_products.is_done, order_products.id_user, order_products.order_id, order_products.total, order_products.invoice_number, order_products.id_country, order_products.operator, order_products.number, order_products.sms_text, order_products.price_user, order_products.id_currency, order_products.exp_date, order_products.status, order_products.created_date, order_products.updated_date, base_countries.country_code, base_countries.country, COALESCE((select count(*) from order_product_files where order_product_files.order_id=order_products.order_id), 0) as total_done');
        $builder->join('app_operators', 'app_operators.operator_code = order_products.operator', 'left');
        $builder->join('base_countries', 'base_countries.id = order_products.id_country', 'left');
        $builder->where('app_operators.is_file', 0);
        $builder->orderBy('order_products.status', 'ASC');
        $query   = $builder->get(3000);
        $dataFinal2 = $query->getResult();
        
        $db->close();
        $finalData = json_encode($_dataFinal);
        $finalData2 = json_encode($dataFinal2);
        
        echo '{
            "code": 0,
            "error": "",
            "message": "Success Add Phone Number.",
            "otp": '.$finalData.',
            "data": '.$finalData2.'
        }'; 
    }

}
