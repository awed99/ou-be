<?php

namespace App\Controllers;
use Config\Services;
use CodeIgniter\Files\File;

class Setting extends BaseController
{
    public function index()
    {
        echo('welcome!');
    }

    public function postList_setting_app()
    {   
        $request = request();
        $dataPost = $request->getPost();
        $dataRequest = cek_token_login($dataPost);
        $db = db_connect();
        $builder = $db->table('setting_app');
        $query   = $builder->get();
        $dataFinal = $query->getResult();
        
        $builder2 = $db->table('base_currencies');
        $query2   = $builder2->get();
        $dataFinal2 = $query2->getResult();
        
        $builder3 = $db->table('base_languages');
        $query3   = $builder3->get();
        $dataFinal3 = $query3->getResult();
        
        $dataFinal4 = ['Light', 'Dark'];

        $db->close();
        $finalData = json_encode($dataFinal);
        $finalData2 = json_encode($dataFinal2);
        $finalData3 = json_encode($dataFinal3);
        $finalData4 = json_encode($dataFinal4);
        echo '{
            "code": 0,
            "error": "",
            "message": "",
            "data": {
                "setting_app": '.$finalData.',
                "base_currencies": '.$finalData2.',
                "base_languages": '.$finalData3.',
                "base_themes": '.$finalData4.'
            }
        }';
    }

    public function postList_setting_banner()
    {   
        $request = request();
        $dataPost = $request->getPost();
        $dataRequest = cek_token_login($dataPost);
        $db = db_connect();
        $builder = $db->table('setting_banner')->where('is_active', '1')->orderBy('id', 'desc');
        $query   = $builder->get();
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

    public function postList_setting_language()
    {   
        $request = request();
        $dataPost = $request->getPost();
        $dataRequest = cek_token_login($dataPost);
        $request = request();
        $json = $request->getJSON();
        $db = db_connect();
        $query = $db->query('SELECT 
        bl.language_code, bl.language_name, bl.language_country,
        bls.sentence,
        sl.*
        from setting_language sl
        left JOIN base_language_sentences bls on bls.id = sl.id_base_language_sentence
        left JOIN base_languages bl on bl.id = sl.id_base_language
        where sl.id_base_language = '.$json->id_base_language.';');
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

    public function postList_setting_pg()
    {   
        $request = request();
        $dataPost = $request->getPost();
        $dataRequest = cek_token_login($dataPost);
        $db = db_connect();
        $query = $db->query('select spg.*,bpg.code,bpg.name from setting_pg spg
        left JOIN base_payment_methods bpg on bpg.id = spg.id_base_payment_method 
        WHERE bpg.is_active = true');
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

    public function postGet_exchange_rate()
    {   
        $request = request();
        $dataPost = $request->getPost();
        $dataRequest = cek_token_login($dataPost);
        $db = db_connect();
        $api_key = $db->table('token_api')->orderBy('id', 'DESC')->limit(1)->get()->getRow()->api_key;

        
        $balance = curl(getenv('API_SERVICE').$api_key.'&action=getBalance');
        $balanceCashback = curl(getenv('API_SERVICE').$api_key.'&action=getBalanceAndCashBack');
        // $balance = curl(getenv('API_SERVICE').$api_key.'&action=getBalance');
        // $balanceCashback = curl(getenv('API_SERVICE').$api_key.'&action=getBalanceAndCashBack');

        $builder = $db->table('setting_exchange_rate');
        $builder->where('id_base_currency_from', '1');
        $builder->where('id_base_currency_to', '2');
        $query   = $builder->get();
        $dataFinal = $query->getRow();
        
        
        $builder2 = $db->table('setting_app');
        $query2   = $builder2->get();
        $dataFinal2 = $query2->getResult();

        $builder3 = $db->table('base_currencies');
        $query3   = $builder3->get();
        $dataFinal3 = $query3->getResult();

        $dataFinal->balance = $balance;
        $dataFinal->balance_cashback = $balanceCashback;
        $dataFinal->setting_app = $dataFinal2;
        $dataFinal->base_currencies = $dataFinal3;
        $db->close();
        $finalData = json_encode($dataFinal);
        echo '{
            "code": 0,
            "error": "",
            "message": "",
            "data": '.$finalData.'
        }';
    }

    // Create Settings ------------------------

    public function postCreate_setting_banner()
    {   
        $request = request();
        $dataPost = $request->getPost();
        $dataRequest = cek_token_login($dataPost);
        $filename = upload_file($request);
        $postData = $request->getPost();
        unset($postData['token_login']);
        $postData['url_image'] = $filename['name'];
        $postData['updated_by'] = 'Super Admin';

        $db = db_connect();
        $builder = $db->table('setting_banner');
        $builder->ignore(true)->insert($postData);
        $query   = $builder->where('is_active', '1')->orderBy('id', 'desc')->get();
        $dataFinal = $query->getResult();
        $db->close();
        $finalData = json_encode($dataFinal);
        
        echo '{
            "code": 0,
            "error": "",
            "message": "Sucess update data.",
            "data": '.$finalData.'
        }';
    }

    public function postCreate_setting_language()
    {   
        $request = request();
        $dataPost = $request->getPost();
        $dataRequest = cek_token_login($dataPost);
        $request = request();
        $postData = $request->getJSON();
        $postData->updated_by = 'Super Admin';

        $db = db_connect();
        $builder = $db->table('setting_language');
        $builder->ignore(true)->upsert(get_object_vars($postData));
        $query   = $builder->get();
        $dataFinal = $query->getResult();
        $db->close();
        $finalData = json_encode($dataFinal);
        
        echo '{
            "code": 0,
            "error": "",
            "message": "Sucess update data.",
            "data": '.$finalData.'
        }';
    }

    // Update Settings ------------------------

    public function postUpdate_setting_app()
    {
        $request = request();
        $dataPost = $request->getPost();
        $dataRequest = cek_token_login($dataPost);
        $request = request();
        $newData = json_decode($dataRequest['data'], true);
        // foreach (json_decode($dataRequest['data'], true) as $row) {
        //     $row->updated_by = 'Super Admin';
        //     $row->updated_date = date('Y-m-d H:i:s');
        //     $newData[] = get_object_vars($row);
        // }
        // print_r($newData);
        $db = db_connect();
        $builder = $db->table('setting_app');
        $builder->ignore(true)->upsertBatch($newData);
        
        $db->close();
        echo '{
            "code": 0,
            "error": "",
            "message": "Sucess update data.",
            "data": []
        }';
    }

    public function postUpdate_setting_banner()
    {   
        $request = request();
        $dataPost = $request->getPost();
        $dataRequest = cek_token_login($dataPost);
        $dataRequest['updated_date'] = date('Y-m-d H:i:s');

        $db = db_connect();
        $builder = $db->table('setting_banner');
        $builder->where('id', $dataRequest['id'])->ignore(true)->update($dataRequest);
        $query   = $builder->where('is_active', '1')->orderBy('id', 'desc')->get();
        $dataFinal = $query->getResult();
        $db->close();
        $finalData = json_encode($dataFinal);
        
        echo '{
            "code": 0,
            "error": "",
            "message": "Sucess update data.",
            "data": '.$finalData.'
        }';
    }

    public function postUpdate_setting_language()
    {   
        $request = request();
        $dataPost = $request->getPost();
        $dataRequest = cek_token_login($dataPost);
        $postData = json_decode($dataRequest['data'], true);

        $db = db_connect();
        $builder = $db->table('setting_language');
        $builder->ignore(true)->upsertBatch($postData);
        
        $builder1 = $db->table('base_languages');
        $query1   = $builder1->get();
        $dataFinal1 = $query1->getResult();
        $builder2 = $db->table('base_language_sentences');
        $query2   = $builder2->get();
        $dataFinal2 = $query2->getResult();
        $builder3 = $db->table('setting_language');
        $query3   = $builder3->get();
        $dataFinal3 = $query3->getResult();
        $db->close();
        $finalData1 = json_encode($dataFinal1);
        $finalData2 = json_encode($dataFinal2);
        $finalData3 = json_encode($dataFinal3);
        
        echo '{
            "code": 0,
            "error": "",
            "message": "Sucess update data.",
            "data": {
                "base_languages": '.$finalData1.',
                "base_language_sentences": '.$finalData2.',
                "setting_language": '.$finalData3.'
            }
        }';
    }

    public function postUpdate_setting_pg()
    {   
        $request = request();
        $dataPost = $request->getPost();
        $dataRequest = cek_token_login($dataPost);

        $db = db_connect();
        $builder = $db->table('setting_pg');
        $builder->where('id', $dataRequest['id'])->ignore(true)->update($dataRequest);

        $query = $db->query('select spg.*,bpg.code,bpg.name from setting_pg spg
        left JOIN base_payment_methods bpg on bpg.id = spg.id_base_payment_method 
        WHERE bpg.is_active = true');
        $dataFinal = $query->getResult();
        $db->close();
        $finalData = json_encode($dataFinal);
        
        echo '{
            "code": 0,
            "error": "",
            "message": "Sucess update data.",
            "data": '.$finalData.'
        }';
    }

    public function postCreate_binance_signature() {
        $request = request();
        $jsonData = $request->getJSON();
        $payload = $jsonData->timestamp . " \n" . $jsonData->nonce . " \n" . $jsonData->body . " \n";
        echo strtoupper(hash_hmac('sha512', $payload, $jsonData->secret_key));
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

    public function postCreate_binance_order()
    {
        $request = request();
        $jsonData = $request->getJSON();
        $order_id = '7e3c5a48b6f04dd5a682b7a9aaff8d8f';
        // $order = new WC_Order($order_id);
        $req = array(
            'env' => array('terminalType' => 'WEB'),
            'merchantTradeNo' => $order_id,
            'orderAmount' => 10,
            'currency' => 'BUSD');
        $req['goods'] = array();
        $req['passThroughInfo'] = "wooCommerce-1.0";
        $req['goods']['goodsType'] = "02";
        $req['goods']['goodsCategory'] = "Z000";
        $req['goods']['referenceGoodsId'] = '1';
        $req['goods']['goodsName'] = 'Test1';
        // $req['returnUrl'] = $this->get_return_url($order);
        // $req['cancelUrl'] = $order->get_cancel_order_url();
        // $req['webhookUrl'] = esc_url(home_url('/')) . '?wc-api=wc_gateway_binance';
        $nonce = $this->generate_random_string();
        $body = json_encode($req);
        $timestamp = round(microtime(true) * 1000);
        $payload = $timestamp . "\n" . $nonce . "\n" . $body . "\n";
        $secretKey = 'qrtqpg0oidm36txywwnkg0bggk410cgbgkzcnqrrip3oye1lio0tgp2s1fqxwqxl';
        $signature = strtoupper(hash_hmac('sha512', $payload, $secretKey));
        $apiKey = "bphy9qk7125pvqmjjmrfahjozpf7nspamvwsdih6soifyrby4gsd8xl0ratmj7so";

        $headers = array(
            'Content-Type: application/json',
            "BinancePay-Timestamp: ".$timestamp,
            "BinancePay-Nonce: ".$nonce,
            "BinancePay-Certificate-SN: ".$apiKey,
            "BinancePay-Signature: ".$signature
        );
        $response = curl( "https://bpay.binanceapi.com/binancepay/openapi/v2/order", 1, json_encode($req), $headers );
        echo $response;
        // $responseBody = wp_remote_retrieve_body($response);
        // error_log("binance response " . $responseBody);
        // return json_decode($responseBody, true);
    }
}
