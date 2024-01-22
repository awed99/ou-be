<?php

namespace App\Controllers\Admin;
use Config\Services;
use CodeIgniter\Files\File;

class Auth extends BaseController {

    public function get_client_ip() {
        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_X_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if(isset($_SERVER['REMOTE_ADDR']))
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }

    public function postLogin() {   
        cekValidation('admin/auth/login');
        $request = request();
        $response = response();
        $db = db_connect();
        $json = $request->getJSON();
        $email = $json->email;
        $user_role = 1;
        $password = hash('sha256', $json->password);
        $builder = $db->table('app_users')->where('email', $email)->where('password', $password)->where('user_role', $user_role)->where('user_status', 'ACTIVE')->where('is_active', 1);
        $query   = $builder->get();
        $dataFinal = $query->getRow();
        
        if ($dataFinal) {
            $session = session();
            
            // $loc = json_decode(curl(getenv('API_LOGS').'logs/create_log_login', 1, 'ip='.$this->get_client_ip()));
            $update["token_login"] = hash('sha256', $email.date('YmdHis'));

            $postData['ip_address'] = $this->get_client_ip();
            $postData['id_user'] = $dataFinal->id_user;
            $postData['user_role'] = $dataFinal->user_role;
            $postData['token_login'] = $update["token_login"];
            $postData['token_api'] = $dataFinal->token_api;
            $builder0 = $db->table('log_login');
            $builder0->insert($postData);

            $update["last_ip_address"] = $this->get_client_ip();
            if (isset($loc['city'])) {
                $update["last_ip_location"] = $loc['city'].', '.$loc['region_name'].', '.$loc['country_name'];
            }
            $session->set('login', $dataFinal);
            $session->set('token_login', $update["token_login"]);
            $builder->where('email', $email);
            $builder->update($update);
            $builder->where('email', $email);
            $query   = $builder->get();
            $dataFinal2 = $query->getRow();

            if ($user_role === 1) {
                $api_key = $db->table('token_api')->orderBy('id', 'DESC')->limit(1)->get()->getRow()->api_key;
                $dataFinal2->api_key = $api_key;
            }
            $db->close();

            $finalData = json_encode($dataFinal2);
            echo '{
                "code": 0,
                "error": "",
                "message": "Login successful.",
                "data": '.$finalData.'
            }';
        } else {
            echo '{
                "code": 1,
                "error": "Email or Password is incorrect!",
                "message": "Email or Password is incorrect!",
                "data": null
            }';
        }
        $db->close();
    }

}