<?php

namespace App\Controllers\Transactions;
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
        $request = request();
        $dataPost = $request->getJSON(true);
        $dataRequest = cek_token_login($dataPost);
        if (isset($dataRequest['date'])) {
            $date = $dataRequest['date'];
        } else {
            $date = date('Y-m-d');
        }
        $db = db_connect();
        $query = $db->query('SELECT 
        bo.*,
        appsx.service_code,
        bc.country,
        appsx.service_name,
        appsx.supplier_price,
        appsx.selling_price,
        appsx.profit,
        appsx.is_active
        FROM orders bo
        LEFT JOIN app_services appsx ON appsx.id = bo.id_app_service
        left join base_countries bc on bc.id = appsx.id_base_country
        WHERE bo.status = \'Success\' AND DATE(bo.created_date) = \''.$date.'\'
        ORDER BY bo.created_date DESC
        LIMIT 10000;');
        $dataFinal = $query->getResult();
        $total_selling_price = $db->query('SELECT ROUND(SUM(price_user), 2) as total_selling_price from orders where status = \'Success\' AND DATE(created_date) = \''.$date.'\';')->getRow()->total_selling_price ?? 0;
        $total_supplier_price = $db->query('SELECT ROUND(SUM(price_real), 2) as total_supplier_price from orders where status = \'Success\' AND DATE(created_date) = \''.$date.'\';')->getRow()->total_supplier_price ?? 0;
        $total_profit = $db->query('SELECT ROUND(SUM(price_profit), 2) as total_profit from orders where status = \'Success\' AND DATE(created_date) = \''.$date.'\';')->getRow()->total_profit ?? 0;
        $total_order = $db->query('SELECT COUNT(*) as total_order from orders where status = \'Success\' AND DATE(created_date) = \''.$date.'\';')->getRow()->total_order ?? 0;
        $totalBP = $db->query('SELECT ROUND(SUM(amount), 2) as totalBP from bestpva_finance.topup_users where status = \'success\' AND id_base_payment_method = \'1\' AND DATE(created_datetime) = \''.$date.'\';')->getRow()->totalBP ?? 0;
        $totalBonus = $db->query('SELECT ROUND(SUM(amount), 2) as totalBP from bestpva_finance.topup_users where status = \'success\' AND id_base_payment_method = \'5\' AND  DATE(created_datetime) = \''.$date.'\';')->getRow()->totalBP ?? 0;
        $db->close();
        $finalData = json_encode($dataFinal);
        echo '{
            "code": 0,
            "error": "",
            "message": "",
            "data": {
                "data": '.$finalData.',
                "totalBP": '.$totalBP.',
                "totalBonus": '.$totalBonus.',
                "total_selling_price": '.$total_selling_price.',
                "total_supplier_price": '.$total_supplier_price.',
                "total_profit": '.$total_profit.',
                "total_order": '.$total_order.'
            }
        }';
    }

    public function postTop10() {
        $request = request();
        $dataPost = $request->getJSON(true);
        $dataRequest = cek_token_login($dataPost);
        $db = db_connect();
        $query = $db->query('SELECT 
        bo.id_app_service, COUNT(*) AS total_order, ROUND(SUM(bo.price_profit), 2) AS total_profit, ROUND(SUM(bo.price_user), 2) AS total_amount,
        bo.price_user,
        bo.price_real,
        bo.price_admin,
        apps.service_code,
        bc.country,
        apps.service_name,
        apps.supplier_price,
        apps.selling_price,
        apps.profit,
        apps.is_active
        FROM orders bo
        LEFT JOIN app_services apps ON apps.id = bo.id_app_service
        left join base_countries bc on bc.id = apps.id_base_country
        where bo.status = \'Success\'
        GROUP BY bo.id_app_service 
        ORDER BY total_order DESC
        limit 10;');
        $dataFinal = $query->getResult();
        $total_amount = $db->query('SELECT ROUND(SUM(price_user), 2) as total_amount from orders where status = \'Success\';')->getRow()->total_amount;
        $total_profit = $db->query('SELECT ROUND(SUM(price_profit), 2) as total_profit from orders where status = \'Success\';')->getRow()->total_profit;
        $total_order = $db->query('SELECT COUNT(*) as total_order from orders where status = \'Success\';')->getRow()->total_order;
        $db->close();
        $finalData = json_encode($dataFinal);
        echo '{
            "code": 0,
            "error": "",
            "message": "",
            "data": {
                "top": '.$finalData.',
                "total_amount": '.$total_amount.',
                "total_profit": '.$total_profit.',
                "total_order": '.$total_order.'
            }
        }';
    }

    public function postCreate($data=false, $data2=false) {
        $request = request();
        $postData = $request->getJSON(true);
        // $postData = cek_token_login($dataPost);
        $db = db_connect();
        $builder = $db->table('orders');
        $postData0 = $data ? json_decode($data, true) : json_decode($postData['data'], true);

        $general_profit  = $db->table('base_profit')->limit(1)->get()->getRow()->general_profit ;

        // print_r($postData0);
        // $postData2['order_id'] = substr(md5(rand(1,10000).date('YmdHis')), 0, 6);
        $postData2 = array();
        $postData2['id_user'] = $data2['id_user'];
        $postData2['id_app_service'] = $postData['service_id'];
        $postData2['id_country'] = $postData['country'];
        $postData2['operator'] = $postData['operator'];
        $postData2['number'] = $postData0['phoneNumber'];
        $postData2['order_id'] = $postData0['activationId'];
        $postData2['price_admin'] = ($postData0['activationCost'] * 1);
        $postData2['price_real'] = ($postData0['activationCost'] * 1);
        // $postData2['price_user'] = $data2['price_user'];
        // $postData2['price_profit'] = round($data2['price_user'] - ($postData0['activationCost'] * 1), 2);
        $postData2['price_user'] = ($postData0['activationCost'] + $general_profit);
        $postData2['price_profit'] = round(($postData0['activationCost'] + $general_profit) - ($postData0['activationCost'] * 1), 2);
        $postData2['invoice_number'] = 'INV/'.date('Y').'/'.date('m').'/'.$postData2['id_user'].'/'.$postData2['order_id'];
        // echo json_encode($postData2);

        $builder->insert($postData2);

        $builder->select('orders.id id_order, app_services.service_code, app_services.service_name, orders.*, base_countries.country_code, base_countries.country');
        $builder->join('app_services', 'app_services.id = orders.id_app_service', 'left');
        $builder->join('base_countries', 'base_countries.id = orders.id_country', 'left');
        $builder->where('orders.status', 'Waiting for SMS')->where('orders.id_user', $data2['id_user'])->orderBy('orders.id', 'DESC');
        $query   = $builder->get(1000);
        $dataFinal = $query->getResult();
        $db->close();
        $finalData = json_encode($dataFinal);
        return $finalData;
    }

    public function postUpdate_all_status_activations() {
        $db = db_connect();
        $api_key = getenv('API_SERVICE_KEY');
        $builder = $db->table('orders');
        $builder->select('orders.id id_order, app_services.service_code, app_services.service_name, orders.id, orders.id_user, orders.order_id, orders.invoice_number, orders.id_app_service, orders.id_country, orders.operator, orders.number, orders.sms_text, orders.price_user, orders.id_currency, orders.exp_date, orders.status, orders.created_date, base_countries.country_code, base_countries.country');
        $builder->join('app_services', 'app_services.id = orders.id_app_service', 'left');
        $builder->join('base_countries', 'base_countries.id = orders.id_country', 'left');
        $builder->where('(orders.is_done = 0 or orders.is_done = \'0\' or orders.is_done = false)')->orderBy('orders.id', 'DESC');
        $query   = $builder->get(1000);
        $dataFinal = $query->getResult();

        foreach($dataFinal as $key) {
            $dataFinalX = explode(":", curl(getenv('API_SERVICE').$api_key.'&action=getStatus&id='.$key->order_id));
            $status = 'Waiting for SMS';
            if ($dataFinalX[0] === 'STATUS_WAIT_CODE') {
                $status = 'Waiting for SMS';
            } elseif ($dataFinalX[0] === 'STATUS_WAIT_RETRY') {
                $status = 'Waiting for Retry SMS';
            } elseif ($dataFinalX[0] === 'STATUS_WAIT_RESEND') {
                $status = 'Waiting for Resend SMS';
            } elseif ($dataFinalX[0] === 'STATUS_CANCEL') {
                $status = 'Cancel';
                $update['is_done'] = '1';
            } elseif ($dataFinalX[0] === 'STATUS_OK') {
                $status = 'Success';
                $update['sms_text'] = $dataFinalX[1];
            }

            if (time() > (strtotime($key->created_date) + 1800)){
                $update['is_done'] = '1';
            }

            $update['status'] = $status;
            $db->table('orders')->where('(orders.is_done = 0 or orders.is_done = \'0\' or orders.is_done = false)')->where('status <> \'Success\'')->where('order_id ', $key->order_id)->update($update);
        }

        $db->close();
    }

    public function postUpdate_all_status_activation() {
        $request = request();
        $dataPost = $request->getJSON(true);
        // $postData = cek_token_login($dataPost);
        $postData = $dataPost;
        $db = db_connect();
        $api_key = getenv('API_SERVICE_KEY');
        $builder = $db->table('orders');
        $builder->select('orders.id id_order, app_services.service_code, app_services.service_name, orders.id, orders.id_user, orders.order_id, orders.invoice_number, orders.id_app_service, orders.id_country, orders.operator, orders.number, orders.sms_text, orders.price_user, orders.id_currency, orders.exp_date, orders.status, orders.created_date, base_countries.country_code, base_countries.country');
        $builder->join('app_services', 'app_services.id = orders.id_app_service', 'left');
        $builder->join('base_countries', 'base_countries.id = orders.id_country', 'left');
        $builder->where('(orders.is_done = 0 or orders.is_done = \'0\' or orders.is_done = false)')->orderBy('orders.id', 'DESC');
        $query   = $builder->get(1000);
        $dataFinal = $query->getResult();

        foreach($dataFinal as $key) {
            $dataFinalX = explode(":", curl(getenv('API_SERVICE').$api_key.'&action=getStatus&id='.$key->order_id));
            $status = 'Waiting for SMS';
            if ($dataFinalX[0] === 'STATUS_WAIT_CODE') {
                $status = 'Waiting for SMS';
            } elseif ($dataFinalX[0] === 'STATUS_WAIT_RETRY') {
                $status = 'Waiting for Retry SMS';
            } elseif ($dataFinalX[0] === 'STATUS_WAIT_RESEND') {
                $status = 'Waiting for Resend SMS';
            } elseif ($dataFinalX[0] === 'STATUS_CANCEL') {
                $status = 'Cancel';
                $update['is_done'] = '1';
            } elseif ($dataFinalX[0] === 'STATUS_OK') {
                $status = 'Success';
                $update['sms_text'] = $dataFinalX[1];
            }

            if (time() > (strtotime($key->created_date) + 1800)){
                $update['is_done'] = '1';
            }

            $update['status'] = $status;
            $db->table('orders')->where('(orders.is_done = 0 or orders.is_done = \'0\' or orders.is_done = false)')->where('status <> \'Success\'')->where('order_id ', $key->order_id)->update($update);
        }

        $db->close();
        // $finalData = json_encode($dataFinal);
        // echo '{
        //     "code": 0,
        //     "error": "",
        //     "message": "",
        //     "data": '.$finalData.'
        // }';
        // echo $finalData;
    }

    public function postUpdate_status_activation() {
        $request = request();
        // $dataPost = $request->getJSON(true);
        // $postData = cek_token_login($dataPost);
        $db = db_connect();
        $api_key =  getenv('API_SERVICE_KEY');
        $id_user = $db->table('app_users')->where('token_login', $request->header('Authorization')->getValue())->limit(1)->get()->getRow()->id_user;
        $builder = $db->table('orders');
        $builder->select('orders.id id_order, app_services.service_code, app_services.service_name, orders.id, orders.id_user, orders.order_id, orders.invoice_number, orders.id_app_service, orders.id_country, orders.operator, orders.number, orders.sms_text, orders.price_user, orders.id_currency, orders.exp_date, orders.status, orders.created_date, base_countries.country_code, base_countries.country');
        $builder->join('app_services', 'app_services.id = orders.id_app_service', 'left');
        $builder->join('base_countries', 'base_countries.id = orders.id_country', 'left');
        $builder->where('(orders.is_done = 0 or orders.is_done = \'0\' or orders.is_done = false)')->where('orders.id_user', $id_user)->orderBy('orders.id', 'DESC');
        $query   = $builder->get(1000);
        $dataFinal = $query->getResult();
        // echo $db->getLastQuery();
        $builder->select('orders.id id_order, app_services.service_code, app_services.service_name, orders.id, orders.id_user, orders.order_id, orders.invoice_number, orders.id_app_service, orders.id_country, orders.operator, orders.number, orders.sms_text, orders.price_user, orders.id_currency, orders.exp_date, orders.status, orders.created_date, base_countries.country_code, base_countries.country');
        $builder->join('app_services', 'app_services.id = orders.id_app_service', 'left');
        $builder->join('base_countries', 'base_countries.id = orders.id_country', 'left');
        $builder->where('(orders.is_done = 1 or orders.is_done = \'1\' or orders.is_done = true)')->where('orders.id_user', $id_user)->orderBy('orders.id', 'DESC');
        $query2   = $builder->groupBy('orders.number')->limit(5)->get();
        $dataFinalX = $query2->getResult();

        foreach($dataFinal as $key) {
            $dataFinalX = explode(":", curl(getenv('API_SERVICE').$api_key.'&action=getStatus&id='.$key->order_id));
            $status = 'Waiting for SMS';
            if ($dataFinalX[0] === 'STATUS_WAIT_CODE') {
                $status = 'Waiting for SMS';
            } elseif ($dataFinalX[0] === 'STATUS_WAIT_RETRY') {
                $status = 'Waiting for Retry SMS';
            } elseif ($dataFinalX[0] === 'STATUS_WAIT_RESEND') {
                $status = 'Waiting for Resend SMS';
            } elseif ($dataFinalX[0] === 'STATUS_CANCEL') {
                $status = 'Cancel';
                $update['is_done'] = '1';
            } elseif ($dataFinalX[0] === 'STATUS_OK') {
                $status = 'Success';
                $update['sms_text'] = $dataFinalX[1];
            }

            if (time() > (strtotime($key->created_date) + 1800)){
                $update['is_done'] = '1';
            }

            $update['status'] = $status;
            $builder2 = $db->table('orders')->where('(orders.is_done = 0 or orders.is_done = \'0\' or orders.is_done = false)')->where('status <> \'Success\'')->where('order_id ', $key->order_id)->update($update);
        }

        $dataFinal2 = $query->getResult();
        $db->close();
        $finalData = json_encode($dataFinal2);
        $finalDataX = json_encode($dataFinalX);
        return '{
            "dataLists": '.$finalData.',
            "dataList5": '.$finalDataX.'
        }';
        // echo '{
        //     "code": 0,
        //     "error": "",
        //     "message": "",
        //     "data": '.$finalData.',
        //     "data5": '.$finalDataX.'
        // }';
        // echo $finalData;
    }

    public function postRe_order() {
        $request = request();
        $dataPost = $request->getJSON(true);
        // $postData = cek_token_login($dataPost);
        $postData = $dataPost;
        $db = db_connect();
        $api_key = getenv('API_SERVICE_KEY');
        $id_user = $db->table('app_users')->where('token_login', $request->header('Authorization')->getValue())->limit(1)->get()->getRow()->id_user;

        $dataFinal = curl(getenv('API_SERVICE').$api_key.'&action=setStatus&status=3&id='.$postData['order_id']);
        $dataFinal = curl(getenv('API_SERVICE').$api_key.'&action=setStatus&status=1&id='.$postData['order_id']);
        
        $update['status'] = 'Success';
        $update['is_done '] = '1';
        $db->table('orders')->where('order_id ', $postData['order_id'])->update($update);
        $data = $db->table('orders')->where('order_id ', $postData['order_id'])->get()->getRow();
        // $this->postUpdate_all_status_activation();

        
        $builder = $db->table('orders');
        $insert['order_id'] = $postData['order_id'];
        $insert['id_user'] = $id_user;
        $insert['id_app_service'] = $data->id_app_service;
        $insert['id_country'] = $data->id_country;
        $insert['operator'] = $data->operator;
        $insert['number'] = $data->number;
        $insert['price_user'] = $data->price_user;
        $insert['price_admin'] = $data->price_admin;
        $insert['price_real'] = $data->price_real;
        $insert['price_profit'] = $data->price_profit;
        $insert['id_currency'] = $data->id_currency;
        $insert['exp_date'] = $data->exp_date;
        $insert['created_date'] = $data->created_date;
        $insert['status'] = 'Waiting for Retry SMS';
        $insert['invoice_number'] = 'INV/'.date('Y').'/'.date('m').'/'.$id_user.'/'.$postData['order_id'];
        // print_r($postData);

        $builder->insert($insert);

        echo '{
            "code": 0,
            "error": "",
            "message": "",
            "data": null
        }';
    }

    public function postCancel_order() {
        cekValidation('transactions/orders/cancel_order');
        $request = request();
        $postData = $request->getJSON(true);
        // $postData = cek_token_login($dataPost);
        $db = db_connect();
        $api_key = getenv('API_SERVICE_KEY');
        
        $dataFinal = curl(getenv('API_SERVICE').$api_key.'&action=setStatus&status=8&id='.$postData['order_id']);

        // print_r($dataFinal);
        // die();
        if ($dataFinal === 'EARLY_CANCEL_DENIED') {
            echo '{
                "code": 1,
                "error": "System Error.",
                "message": "Early cancel! Please wait for two minutes.",
                "data": null
            }';
            die();
        }
        
        if (!$postData['completed']) {
            $update['status'] = 'Cancel';
        }
        $update['is_done '] = '1';
        $db->table('orders')->where('(orders.is_done = 0 or orders.is_done = \'0\' or orders.is_done = false)')->where('order_id ', $postData['order_id'])->update($update);
        // $this->postUpdate_all_status_activation();
        // $update2['is_done '] = '1';
        // $db->table('orders')->where('(orders.is_done = 0 or orders.is_done = \'0\' or orders.is_done = false)')->where('order_id', $postData['order_id'])->update($update2);
        
        echo '{
            "code": 0,
            "error": "",
            "message": "",
            "data": null
        }';
    }

    public function postCreate_order() {
        $request = request();
        $dataPost = $request->getJSON(true);
        // $postData = cek_token_login($dataPost);
        $postData = $dataPost;
        $db = db_connect();
        $builder = $db->table('orders');
        $postData['order_id'] = substr(md5(rand(1,10000).date('YmdHis')), 0, 6);
        $postData['invoice_number'] = 'INV/'.date('Y').'/'.date('m').'/'.$postData['id_user'].'/'.$postData['order_id'];
        // print_r($postData);

        $builder->insert($postData);

        $builder->orderBy('id', 'DESC');
        $query   = $builder->get(1000);
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

    public function postList_orders()
    {   
        cekValidation('transactions/orders/list_orders');
        $this->postUpdate_all_status_activation();
        $request = request();
        $dataPost = $request->getJSON(true);
        // $postData = cek_token_login($dataPost);
        $postData = $dataPost;
        $db = db_connect();
        $api_key = getenv('API_SERVICE_KEY');
        $id_user = $db->table('app_users')->where('token_login', $request->header('Authorization')->getValue())->limit(1)->get()->getRow()->id_user;
        $builder = $db->table('orders');
        $builder->select('orders.id id_order, app_services.service_code, app_services.service_name, orders.id, orders.id_user, orders.order_id, orders.sms_text, orders.invoice_number, orders.id_app_service, orders.id_country, orders.operator, orders.number, orders.sms_text, orders.price_user, orders.id_currency, orders.exp_date, orders.status, orders.created_date, base_countries.country_code, base_countries.country');
        $builder->join('app_services', 'app_services.id = orders.id_app_service', 'left');
        $builder->join('base_countries', 'base_countries.id = orders.id_country', 'left');
        $builder->where('orders.id_user', $id_user)->orderBy('orders.id', 'DESC');
        $query   = $builder->get(3000);
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

    public function postList_orders_all()
    {   
        $this->postUpdate_all_status_activation();
        $request = request();
        $dataPost = $request->getJSON(true);
        // $postData = cek_token_login($dataPost);
        $postData = $dataPost;
        $db = db_connect();
        $api_key = getenv('API_SERVICE_KEY');
        $id_user = $db->table('app_users')->where('token_login', $request->header('Authorization')->getValue())->limit(1)->get()->getRow()->id_user;
        $builder = $db->table('orders');
        $builder->select('orders.id id_order, app_users.username, app_users.email, app_services.service_code, app_services.service_name, orders.id, orders.id_user, orders.order_id, orders.sms_text, orders.invoice_number, orders.id_app_service, orders.id_country, orders.operator, orders.number, orders.sms_text, orders.price_user, orders.id_currency, orders.exp_date, orders.status, orders.created_date, base_countries.country_code, base_countries.country');
        $builder->join('app_services', 'app_services.id = orders.id_app_service', 'left');
        $builder->join('base_countries', 'base_countries.id = orders.id_country', 'left');
        $builder->join('app_users', 'app_users.id_user = orders.id_user', 'left');
        $builder->orderBy('orders.id', 'DESC');
        $query   = $builder->get(1000);
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

    public function postGet_token_api()
    {   
        $request = request();
        $dataPost = $request->getJSON(true);
        $dataRequest = cek_token_login($dataPost);
        $request = request();
        $db = db_connect();
        $json = $request->getJSON(true);
        $email = $json->email;
        $token_login = $json->token_login;
        $type = $json->type;
        $update["token_api"] = hash('sha256', $email.$token_login.date('YmdHis'));
        $builder = $db->table('app_users')->where('email', $email)->where('token_login', $token_login)->where('user_role', $type);
        $builder->update($update);
        $db->close();
        echo '{
            "code": 0,
            "error": "",
            "message": "",
            "data": '.$update["token_api"].'
        }';
    }

    public function postGet_user_data_from_token_api()
    {   
        $request = request();
        $dataPost = $request->getJSON(true);
        $dataRequest = cek_token_login($dataPost);
        $request = request();
        $db = db_connect();
        $json = $request->getJSON(true);
        $token_api = $json->token_api;
        $builder = $db->table('app_users')->where('token_api', $token_api);
        $dataFinal = $builder->get()->getResult();
        $db->close();
        echo '{
            "code": 0,
            "error": "",
            "message": "",
            "data": '.$dataFinal[0].'
        }';
    }

    public function postCreate_captcha()
    {
        $request = request();
        $dataPost = $request->getJSON(true);
        $dataRequest = cek_token_login($dataPost);
        $rand = create_random_captcha();
        $db = db_connect();
        $insert = [];
        $insert['captcha_code'] = $rand;
        $insert['ip_address'] = getUserIP();
        $builder = $db->table('setting_captcha');
        $builder->ignore(true)->insert($insert);
        $db->close();
        echo '{
            "code": 0,
            "error": "",
            "message": "",
            "data": '.$rand.'
        }';
    }

    public function postRegister()
    {   
        $request = request();
        $json = $request->getJSON(true);
        $db = db_connect();

        $builder0 = $db->table('setting_captcha');
        $builder0->where('captcha_code', $json->captcha);
        $builder0->where('is_used', 0);
        $dataCaptcha = $builder0->get()->getResult();
        if (!$dataCaptcha) {
            echo '{
                "code": 1,
                "error": "Captcha is not valid!",
                "message": "Captcha is not valid!",
                "data": null
            }';
            exit();
        }
        $timestampCreatedCaptcha = strtotime(date($dataCaptcha[0]->created_date));
        $timestampNow = strtotime(date('Y-m-d H:i:s'));
        $intervalTimeCapthca = $timestampNow - $timestampCreatedCaptcha;
        if ($intervalTimeCapthca > 3600) {
            echo '{
                "code": 1,
                "error": "Captcha has been expired!",
                "message": "Captcha has been expired!",
                "data": null
            }';
            exit();
        }
        $update0['is_used'] = 1;
        $builder0->where('captcha_code', $json->captcha);
        $builder0->ignore(true)->update($update0);

        $insert = [];
        $insert['username'] = $json->username;
        $insert['email'] = $json->email;
        $insert['password'] = hash('sha256', $json->password);
        $insert['user_role'] = 2;
        $insert['user_status'] = 'ACTIVE';
        $insert['is_active'] = 1;
        $insert['token_login'] = hash('sha256', $json->email.date('YmdHis'));
        $builder = $db->table('app_users');
        $builder->ignore(true)->insert($insert);
        if ($db->affectedRows() == 1) {
            $_SESSION["login"] = (object)$insert;
            $_SESSION["token_login"] = $insert["token_login"];
            echo '{
                "code": 0,
                "error": "",
                "message": "You have been successfuly registered!",
                "data": '.json_encode((object)$insert).'
            }';
        } else {
            echo '{
                "code": 1,
                "error": "User has been registered!",
                "message": "User has been registered!",
                "data": null
            }';
        }
        $db->close();
    }

}
