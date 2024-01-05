<?php

namespace App\Controllers\Finance;

class Topup extends BaseController
{
    public function index()
    {
        echo('welcome!');
    }

    public function postList()
    {   
        cekValidation('finance/topup/list');
        $request = request();
        $db = db_connect();
        $dataPost = $request->getJSON(true);
        $id_user = $db->table('app_users')->where('token_login', $request->header('Authorization')->getValue())->limit(1)->get()->getRow()->id_user;
        $q = 'SELECT 
        bftu.*,
        bau.username,
        bau.email,
        bbpm.code,
        bbpm.name,
        bbc.currency_symbol,
        bbc.currency_code,
        bbc.currency_name
        from topup_users bftu
        left join app_users bau on bau.id_user = bftu.id_user
        left join base_payment_methods bbpm on bbpm.id = bftu.id_base_payment_method
        left join base_currencies bbc on bbc.id = bftu.id_currency 
        where bftu.id_user = '.$id_user.'
        order by bftu.id desc limit 3000;
        ';
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

    public function postList_all()
    {   
        cekValidation('finance/topup/list_all');
        $request = request();
        $db = db_connect();
        $dataPost = $request->getJSON(true);
        $isset = $db->table('app_users')->where('token_login', $request->header('Authorization')->getValue())->limit(1)->get()->getNumRows();

        if ($isset < 1) {
            echo '{
                "code": 1,
                "error": "Token is not valid!",
                "message": "Token is not valid!",
                "data": null
            }';
            exit();
        }

        $q = 'SELECT 
        bftu.*,
        sum(bftu.amount) as total_deposit,
        bau.username,
        bau.email,
        bbpm.code,
        bbpm.name,
        bbc.currency_symbol,
        bbc.currency_code,
        bbc.currency_name
        from topup_users bftu
        left join app_users bau on bau.id_user = bftu.id_user
        left join base_payment_methods bbpm on bbpm.id = bftu.id_base_payment_method
        left join base_currencies bbc on bbc.id = bftu.id_currency 
        where bftu.status = \'Success\' and bftu.created_datetime > \'2024-01-01 00:00:00\'
        order by bftu.id desc limit 3000;
        ';
        $query = $db->query($q);
        $dataFinal = $query->getResult();
        $db->close();
        $finalData = json_encode($dataFinal);

        $total_deposit = $dataFinal[0]->total_deposit ?? 0;
        if ($total_deposit > 0 && $total_deposit <= 12) {
            $level = '0';
            $discount = '0%';
        } else if ($total_deposit > 12 && $total_deposit <= 60) {
            $level = 'I';
            $discount = '0%';
        } else if ($total_deposit > 60 && $total_deposit <= 120) {
            $level = 'II';
            $discount = '5%';
        } else if ($total_deposit > 120 && $total_deposit <= 240) {
            $level = 'III';
            $discount = '7.5%';
        } else if ($total_deposit > 240 && $total_deposit <= 600) {
            $level = 'IV';
            $discount = '10%';
        } else if ($total_deposit > 600 && $total_deposit <= 1200) {
            $level = 'V';
            $discount = '12.5%';
        } else if ($total_deposit > 1200 && $total_deposit <= 1800) {
            $level = 'VI';
            $discount = '15%';
        } else if ($total_deposit > 1800 && $total_deposit <= 2400) {
            $level = 'VII';
            $discount = '20%';
        } else if ($total_deposit > 2400 && $total_deposit <= 3600) {
            $level = 'VIII';
            $discount = '25%';
        } else if ($total_deposit > 3600 && $total_deposit <= 4800) {
            $level = 'IX';
            $discount = '30%';
        } else if ($total_deposit > 4800) {
            $level = 'X';
            $discount = '40%';
        }

        echo '{
            "code": 0,
            "error": "",
            "message": "",
            "data": '.$finalData.',
            "total_deposit": '.$total_deposit.',
            "level": "'.$level.'",
            "discount": "'.$discount.'"
        }';
    }

    public function postCreate()
    {   
        $request = request();
        $dataPost = $request->getJSON(true);
        $dataRequest = cek_token_login($dataPost);
        $db = db_connect();
        $builder = $db->table('topup_users');
        $query = $builder->ignore()->insert($dataRequest);
        $dataFinal = $builder->getResult();
        $db->close();
        $finalData = json_encode($dataFinal);
        echo '{
            "code": 0,
            "error": "",
            "message": "",
            "data": '.$finalData.'
        }';
    }


    public function generate_random_string()
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < 32; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function postCreate_bonus_topup()
    {
        $request = request();
        $db = db_connect();
        $dataPost = $request->getJSON(true);
        $dataRequest = cek_token_login($dataPost);

        $exp = date("Y-m-d H:i:s");
        $insert['id_currency'] = 2;
        $insert['amount'] = $dataRequest['amount'];
        $insert['id_base_payment_method'] = 5;
        $insert['id_user'] = $dataRequest['id_user'];
        $insert['expired_date'] = $exp;
        $insert['status'] = 'Success';
        $insert['invoice_number '] = 'INV/TOPUP/BONUS/'.$dataRequest['id_user'].'/'.date('Y').'/'.date('m').'/'.date('s');

        $builder = $db->table('topup_users');
        $query = $builder->ignore()->insert($insert);

        $db->close();

        echo '{
            "code": 0,
            "error": "",
            "message": "",
            "data": "'.$dataRequest['amount'].'"
        }';


        // $responseBody = wp_remote_retrieve_body($response);
        // error_log("binance response " . $responseBody);
        // return json_decode($responseBody, true);
    }

    public function postCreate_binance_order()
    {
        $request = request();
        $db = db_connect();
        $dataPost = $request->getJSON(true);
        $dataRequest = cek_token_login($dataPost);
        $id_user = $db->table('app_users')->where('token_login', $request->header('Authorization')->getValue())->limit(1)->get()->getRow()->id_user;
        $postData['invoice_number'] = 'INV/TOPUP/BINANCE/'.$id_user.'/'.date('Y').'/'.date('m').'/'.date('s');
        $order_id = md5($postData['invoice_number']);
        // $order_id = '7e3c5a48b6f04dd5a682b7a9aaff8d8f';
        // $order = new WC_Order($order_id);
        $req = array(
            'env' => array('terminalType' => 'WEB'),
            'merchantTradeNo' => $order_id,
            'orderAmount' => $dataRequest['amount'],
            'currency' => 'BUSD');
        $req['goods'] = array();
        $req['passThroughInfo'] = "wooCommerce-1.0";
        $req['goods']['goodsType'] = "02";
        $req['goods']['goodsCategory'] = "Z000";
        $req['goods']['referenceGoodsId'] = '1';
        $req['goods']['goodsName'] = 'BestPVA';
        // $req['returnUrl'] = $this->get_return_url($order);
        // $req['cancelUrl'] = $order->get_cancel_order_url();
        // $req['webhookUrl'] = esc_url(home_url('/')) . '?wc-api=wc_gateway_binance';
        $nonce = $this->generate_random_string();
        $body = json_encode($req);
        $timestamp = round(microtime(true) * 1000);
        $payload = $timestamp . "\n" . $nonce . "\n" . $body . "\n";
        $secretKey = getenv('BINANCE_SEKRET_KEY');
        $signature = strtoupper(hash_hmac('sha512', $payload, $secretKey));
        $apiKey = getenv('BINANCE_API_KEY');

        $headers = array(
            'Content-Type: application/json',
            "BinancePay-Timestamp: ".$timestamp,
            "BinancePay-Nonce: ".$nonce,
            "BinancePay-Certificate-SN: ".$apiKey,
            "BinancePay-Signature: ".$signature
        );
        $response = json_decode(curl(getenv('BINANCE_PAY'), 1, json_encode($req), $headers));

        $exchange_rate = $db->table('setting_exchange_rate')->where('id_base_currency_from', 1)->where('id_base_currency_to', 2)->limit(1)->get()->getRow()->exchange_rate;
        $amount = $exchange_rate * $dataRequest['amount'];
        // $dt = new DateTime("@$response->data->expireTime");
        $exp = gmdate("Y-m-d H:i:s", ($response->data->expireTime/1000)+(7*3600));
        $insert['id_currency'] = 2;
        $insert['amount'] = $amount;
        $insert['id_base_payment_method'] = 1;
        $insert['id_user'] = $id_user;
        $insert['expired_date'] = $exp;
        $insert['invoice_number '] = $postData['invoice_number'];

        $builder = $db->table('topup_users');
        $query = $builder->ignore()->insert($insert);
        
        $q = 'SELECT 
        bftu.*,
        bau.username,
        bau.email,
        bbpm.code,
        bbpm.name,
        bbc.currency_symbol,
        bbc.currency_code,
        bbc.currency_name
        from topup_users bftu
        left join app_users bau on bau.id_user = bftu.id_user
        left join base_payment_methods bbpm on bbpm.id = bftu.id_base_payment_method
        left join base_currencies bbc on bbc.id = bftu.id_currency 
        where bftu.id_user = '.$id_user.'
        order by bftu.id desc;
        ';
        $query = $db->query($q);
        $dataFinal = $query->getResult();
        $db->close();
        $finalData = json_encode($dataFinal);

        $db->close();

        echo '{
            "code": 0,
            "error": "",
            "message": "",
            "data": '.$finalData.',
            "url": "'.str_ireplace(".com", ".me", $response->data->universalUrl).'"
        }';


        // $responseBody = wp_remote_retrieve_body($response);
        // error_log("binance response " . $responseBody);
        // return json_decode($responseBody, true);
    }

    public function postCheck_binance_order()
    {
        $request = request();
        $db = db_connect();
        $dataPost = $request->getJSON(true);
        $dataRequest = cek_token_login($dataPost);
        $id_user = $db->table('app_users')->where('token_login', $request->header('Authorization')->getValue())->limit(1)->get()->getRow()->id_user;
        $pendingPayments = $db->table('topup_users')->where('status', 'Waiting for Payment')->where('id_base_payment_method ', '1')->get()->getResult();
        // $postData['invoice_number'] = 'INV/TOPUP/'.$id_user.'/'.date('Y').'/'.date('m').'/'.date('s');
        foreach ($pendingPayments as $val) {
            $order_id = md5($val->invoice_number);
            
            $req['merchantTradeNo'] = $order_id;
    
            $nonce = $this->generate_random_string();
            $body = json_encode($req);
            $timestamp = round(microtime(true) * 1000);
            $payload = $timestamp . "\n" . $nonce . "\n" . $body . "\n";
            $secretKey = getenv('BINANCE_SEKRET_KEY');
            $signature = strtoupper(hash_hmac('sha512', $payload, $secretKey));
            $apiKey = getenv('BINANCE_API_KEY');
    
            $headers = array(
                'Content-Type: application/json',
                "BinancePay-Timestamp: ".$timestamp,
                "BinancePay-Nonce: ".$nonce,
                "BinancePay-Certificate-SN: ".$apiKey,
                "BinancePay-Signature: ".$signature
            );
            // $response = json_decode(curl(getenv('BINANCE_PAY'), 1, json_encode($req), $headers));
            $response = json_decode(curl(getenv('BINANCE_PAY').'/query', 1, json_encode($req), $headers));
            print_r($response);

            if ($response->data->status == 'EXPIRED' || $response->data->status == 'PAID' || $response->data->status == 'CANCELED'){
                $status = 'Expired';
                if ($response->data->status == 'PAID') {
                    $status = 'Success';
                } elseif ($response->data->status == 'CANCELED') {
                    $status = 'Canceled';
                }
                $update['status'] = $status;
                $db->table('topup_users')->where('status', 'Waiting for Payment')->where('invoice_number', $val->invoice_number)->ignore()->update($update);
                // echo $val->invoice_number . ' -> ';
                // echo "\n";
            }
            // print_r($response);
            // echo "\n";
            // echo "\n";
        }

        // $exchange_rate = $db->table('setting_exchange_rate')->where('id_base_currency_from', 1)->where('id_base_currency_to', 2)->limit(1)->get()->getRow()->exchange_rate;
        // $amount = $exchange_rate * $dataRequest['amount'];
        // $insert['id_currency'] = 2;
        // $insert['amount'] = $amount;
        // $insert['id_base_payment_method'] = 1;
        // $insert['id_user'] = $id_user;
        // $insert['invoice_number '] = $postData['invoice_number'];

        // $builder = $db->table('topup_users');
        // $query = $builder->ignore()->insert($insert);
        
        // $q = 'SELECT 
        // bftu.*,
        // bau.username,
        // bau.email,
        // bbpm.code,
        // bbpm.name,
        // bbc.currency_symbol,
        // bbc.currency_code,
        // bbc.currency_name
        // from topup_users bftu
        // left join app_users bau on bau.id_user = bftu.id_user
        // left join base_payment_methods bbpm on bbpm.id = bftu.id_base_payment_method
        // left join base_currencies bbc on bbc.id = bftu.id_currency 
        // where bftu.id_user = '.$id_user.'
        // order by bftu.id desc;
        // ';
        // $query = $db->query($q);
        // $dataFinal = $query->getResult();
        // $db->close();
        // $finalData = json_encode($dataFinal);

        // $db->close();

        // echo '{
        //     "code": 0,
        //     "error": "",
        //     "message": "",
        //     "data": '.$finalData.',
        //     "url": "'.$response->data->universalUrl.'"
        // }';


        // $responseBody = wp_remote_retrieve_body($response);
        // error_log("binance response " . $responseBody);
        // return json_decode($responseBody, true);
    }

    public function getTopup_general () {
        $len0 = 49;
        $len1 = 32;
        $findStart = '<input type="hidden" name="sb_token_csrf" value="';
        $findSecond = '">';
        $__source = curl('https://sociabuzz.com/otpus/give');
        $_source = strpos($__source, $findStart) + $len0;
        $sourceX = substr($__source, $_source, $len1);
        $headers = [
            'Origin: https://sociabuzz.com',
            'Referer: https://sociabuzz.com/otpus/give',
            'Accept: application/json, text/javascript, */*; q=0.01',
            'Content-Type: application/x-www-form-urlencoded; charset=UTF-8',
            'X-Requested-With: XMLHttpRequest',
            'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
            'Cookie: _gid=GA1.2.1154867642.1703737032; _fbp=fb.1.1703737032708.369230319; _gac_UA-30424380-1=1.1703739902.CjwKCAiAs6-sBhBmEiwA1Nl8sxBRDJtEzJSWS1AtJP1J9C7X3Dxse6Vyi-kKUcJvXd3GDbfLvMBBAxoCiUMQAvD_BwE; ci_session=nd703thi15eiq00he824a2gmdha4e486; sociabuzz_csrf_cookie_name=56f16a8c8fbf40ee9cb2c05d00f03318; sociabuzz_ci_session=nd703thi15eiq00he824a2gmdha4e486; __stripe_mid=44698436-6d39-4931-8943-0c3598fe7077e215d9; cf_clearance=wBRwj9bQVAvpIhFuzannjKSRncJizxlssO2XaCae1sM-1703741341-0-2-d5a94ed5.4d882e54.82a81135-0.2.1703741341; __stripe_sid=4ee3c016-f66d-4d5c-b4fe-a98abcb32291e9348f; csrf_cookie_name=b66fcfdc5616c86cecec7167f35f133d; sociabuzz_sb_cookie_csrf='.$sourceX.'; x_trans=x-1eea54af-7d36-6ed6-96df-024283672660; SBsession=srg30ngrm70guq5n38o0nbp79smqono9; _ga_QKTHG0R05D=GS1.1.1703744053.3.1.1703745210.38.0.0; _ga=GA1.2.1866284081.1703737032',
        ];
        // $headers['Origin'] = 'https://sociabuzz.com';
        // $headers['Referer'] = 'https://sociabuzz.com/otpus/tribe';
        // $headers['X-Requested-With'] = 'XMLHttpRequest';
        // $headers['User-Agent'] = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36';
        $dataPostForm = 'sb_token_csrf='.$sourceX.'&currency=USD&amount=124&qty=1&support_duration=120&note=Topup1&fullname=Dewa X123&email=tesakun29@gmail.com&is_agree=1&years18=1&is_vote=0&is_voice=0&is_mediashare=0&is_gif=0&vote_id=&ms_maxtime=3600&start_from=&spin_check=0&prev_url=https://sociabuzz.com/otpus/give&hide_email=0';
        sleep(1);
        $X__source = curl('https://sociabuzz.com/otpus/donate/get-form', 1, $dataPostForm, $headers);
        echo $X__source;

        
        // $myfile = fopen("logs/topup-callback-".date('Y-m-d-H-i-s').".txt", "w") or die("Unable to open file!");
        // $txt = $X__source;
        // fwrite($myfile, $txt);
        // fclose($myfile);
    }
    
    public function postTopup_general () {

        $request = request();
        $dataPost = $request->getJSON(true);
        
        $db = db_connect();
        $api_key =  getenv('API_SERVICE_KEY');
        $user = $db->table('app_users')->where('token_login', $request->header('Authorization')->getValue())->limit(1)->get()->getRow();
        $invoice_number = 'INV/TOPUP/'.$user->id_user.'/'.date('YmdHis');
        $db->close();

        $len0 = 49;
        $len1 = 32;
        $findStart = '<input type="hidden" name="sb_token_csrf" value="';
        $findSecond = '">';
        $__source = curl('https://sociabuzz.com/otpus/give');
        $_source = strpos($__source, $findStart) + $len0;
        $sourceX = substr($__source, $_source, $len1);
        $headers = [
            'Origin: https://sociabuzz.com',
            'Referer: https://sociabuzz.com/otpus/give',
            'Accept: application/json, text/javascript, */*; q=0.01',
            'Content-Type: application/x-www-form-urlencoded; charset=UTF-8',
            'X-Requested-With: XMLHttpRequest',
            'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
            'Cookie: _gid=GA1.2.1154867642.1703737032; _fbp=fb.1.1703737032708.369230319; _gac_UA-30424380-1=1.1703739902.CjwKCAiAs6-sBhBmEiwA1Nl8sxBRDJtEzJSWS1AtJP1J9C7X3Dxse6Vyi-kKUcJvXd3GDbfLvMBBAxoCiUMQAvD_BwE; ci_session=nd703thi15eiq00he824a2gmdha4e486; sociabuzz_csrf_cookie_name=56f16a8c8fbf40ee9cb2c05d00f03318; sociabuzz_ci_session=nd703thi15eiq00he824a2gmdha4e486; __stripe_mid=44698436-6d39-4931-8943-0c3598fe7077e215d9; cf_clearance=wBRwj9bQVAvpIhFuzannjKSRncJizxlssO2XaCae1sM-1703741341-0-2-d5a94ed5.4d882e54.82a81135-0.2.1703741341; __stripe_sid=4ee3c016-f66d-4d5c-b4fe-a98abcb32291e9348f; csrf_cookie_name=b66fcfdc5616c86cecec7167f35f133d; sociabuzz_sb_cookie_csrf='.$sourceX.'; x_trans=x-1eea54af-7d36-6ed6-96df-024283672660; SBsession=srg30ngrm70guq5n38o0nbp79smqono9; _ga_QKTHG0R05D=GS1.1.1703744053.3.1.1703745210.38.0.0; _ga=GA1.2.1866284081.1703737032',
        ];
        // $headers['Origin'] = 'https://sociabuzz.com';
        // $headers['Referer'] = 'https://sociabuzz.com/otpus/tribe';
        // $headers['X-Requested-With'] = 'XMLHttpRequest';
        // $headers['User-Agent'] = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36';
        $dataPostForm = 'sb_token_csrf='.$sourceX.'&currency=USD&amount='.((float)$dataPost['amount'] + 0.5).'&qty=1&support_duration=7200&note='.$invoice_number.'&fullname=00'.$user->id_user.'&email='.$user->email.'&is_agree=1&years18=1&is_vote=0&is_voice=0&is_mediashare=0&is_gif=0&vote_id=&ms_maxtime=7200&start_from=&spin_check=0&prev_url=https://sociabuzz.com/otpus/give&hide_email=0';
        sleep(1);
        $X__source = curl('https://sociabuzz.com/otpus/donate/get-form', 1, $dataPostForm, $headers);
        echo $X__source;
    }
}



/*

Callback TopUp

{
  "id": "4084793074",
  "amount": 15500,
  "currency": "IDR",
  "amount_settled": 15500,
  "currency_settled": "IDR",
  "media_type": "",
  "media_url": "",
  "supporter": "Dewa X123",
  "email_supporter": "tesakun29@gmail.com",
  "message": "Topup1",
  "created_at": "2023-12-28T18:41:29+07:00"
}

*/