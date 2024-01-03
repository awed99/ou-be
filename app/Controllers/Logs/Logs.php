<?php

namespace App\Controllers\Log;
use Config\Services;
use CodeIgniter\Files\File;

class Logs extends BaseController
{
    public function index()
    {
        echo('welcome!');
    }

    public function postCreate_log_login() {
        // check_token_login();
        $request = request();
        $db = db_connect();
        $postData = (array)$request->getPost();
        $builder = $db->table('log_login');
        $builder->ignore(true)->insert($postData);
        $db->close();
        echo '{
            "code": 0,
            "error": "",
            "message": "",
            "data": null
        }';
    }

    public function postCreate_log_hit_api() {
        // check_token_login();
        $request = request();
        $db = db_connect();
        $postData = (array)$request->getPost();
        $postData['ip_address'] = getUserIP();
        $builder = $db->table('log_hit_api');
        $builder->ignore(true)->insert($postData);
        $db->close();
        echo '{
            "code": 0,
            "error": "",
            "message": "",
            "data": null
        }';
    }

    // public function postCreate_order() {
    //     check_token_login();
    //     $request = request();
    //     $postData = (array)$request->getJSON();
    //     $db = db_connect();
    //     $builder = $db->table('orders');
    //     $postData['order_id'] = substr(md5(rand(1,10000).date('YmdHis')), 0, 6);
    //     $postData['invoice_number'] = 'INV/'.date('Y').'/'.date('m').'/'.$postData['id_user'].'/'.$postData['order_id'];

    //     $builder->insert($postData);

    //     $builder->orderBy('id', 'DESC');
    //     $query   = $builder->get(1000);
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

    // public function postList_orderss()
    // {   
    //     cek_session_login();
    //     $request = request();
    //     $db = db_connect();
    //     $json = $request->getJSON();
    //     $email = $json->email;
    //     $user_role = $json->user_role;
    //     $password = hash('sha256', $json->password);
    //     $builder = $db->table('app_users')->where('email', $email)->where('password', $password)->where('user_role', $user_role)->where('user_status', 'ACTIVE')->where('is_active', 1);
    //     $query   = $builder->get();
    //     $dataFinal = $query->getResult();
        
    //     if ($dataFinal) {
    //         $session = session();
    //         $update["token_login"] = hash('sha256', $email.date('YmdHis'));
    //         $session->set('login', $dataFinal[0]);
    //         $session->set('token_login', $update["token_login"]);
    //         $builder->where('email', $email);
    //         $builder->update($update);
    //         $finalData = json_encode($dataFinal[0]);
    //         echo '{
    //             "code": 0,
    //             "error": "",
    //             "message": "Login successful.",
    //             "data": '.$finalData.'
    //         }';
    //     } else {
    //         echo '{
    //             "code": 1,
    //             "error": "Email or Password is incorrect!",
    //             "message": "Email or Password is incorrect!",
    //             "data": null
    //         }';
    //     }
    //     $db->close();
    // }

    // public function postGet_token_api()
    // {   
    //     cek_session_login();
    //     $request = request();
    //     $db = db_connect();
    //     $json = $request->getJSON();
    //     $email = $json->email;
    //     $token_login = $json->token_login;
    //     $type = $json->type;
    //     $update["token_api"] = hash('sha256', $email.$token_login.date('YmdHis'));
    //     $builder = $db->table('app_users')->where('email', $email)->where('token_login', $token_login)->where('user_role', $type);
    //     $builder->update($update);
    //     $db->close();
    //     echo '{
    //         "code": 0,
    //         "error": "",
    //         "message": "",
    //         "data": '.$update["token_api"].'
    //     }';
    // }

    // public function postGet_user_data_from_token_api()
    // {   
    //     cek_session_login();
    //     $request = request();
    //     $db = db_connect();
    //     $json = $request->getJSON();
    //     $token_api = $json->token_api;
    //     $builder = $db->table('app_users')->where('token_api', $token_api);
    //     $dataFinal = $builder->get()->getResult();
    //     $db->close();
    //     echo '{
    //         "code": 0,
    //         "error": "",
    //         "message": "",
    //         "data": '.$dataFinal[0].'
    //     }';
    // }

    // public function postCreate_captcha()
    // {
    //     cek_session_login();
    //     $rand = create_random_captcha();
    //     $db = db_connect();
    //     $insert = [];
    //     $insert['captcha_code'] = $rand;
    //     $insert['ip_address'] = getUserIP();
    //     $builder = $db->table('setting_captcha');
    //     $builder->ignore(true)->insert($insert);
    //     $db->close();
    //     echo '{
    //         "code": 0,
    //         "error": "",
    //         "message": "",
    //         "data": '.$rand.'
    //     }';
    // }

    // public function postRegister()
    // {   
    //     $request = request();
    //     $json = $request->getJSON();
    //     $db = db_connect();

    //     $builder0 = $db->table('setting_captcha');
    //     $builder0->where('captcha_code', $json->captcha);
    //     $builder0->where('is_used', 0);
    //     $dataCaptcha = $builder0->get()->getResult();
    //     if (!$dataCaptcha) {
    //         echo '{
    //             "code": 1,
    //             "error": "Captcha is not valid!",
    //             "message": "Captcha is not valid!",
    //             "data": null
    //         }';
    //         exit();
    //     }
    //     $timestampCreatedCaptcha = strtotime(date($dataCaptcha[0]->created_date));
    //     $timestampNow = strtotime(date('Y-m-d H:i:s'));
    //     $intervalTimeCapthca = $timestampNow - $timestampCreatedCaptcha;
    //     if ($intervalTimeCapthca > 3600) {
    //         echo '{
    //             "code": 1,
    //             "error": "Captcha has been expired!",
    //             "message": "Captcha has been expired!",
    //             "data": null
    //         }';
    //         exit();
    //     }
    //     $update0['is_used'] = 1;
    //     $builder0->where('captcha_code', $json->captcha);
    //     $builder0->ignore(true)->update($update0);

    //     $insert = [];
    //     $insert['username'] = $json->username;
    //     $insert['email'] = $json->email;
    //     $insert['password'] = hash('sha256', $json->password);
    //     $insert['user_role'] = 2;
    //     $insert['user_status'] = 'ACTIVE';
    //     $insert['is_active'] = 1;
    //     $insert['token_login'] = hash('sha256', $json->email.date('YmdHis'));
    //     $builder = $db->table('app_users');
    //     $builder->ignore(true)->insert($insert);
    //     if ($db->affectedRows() == 1) {
    //         $_SESSION["login"] = (object)$insert;
    //         $_SESSION["token_login"] = $insert["token_login"];
    //         echo '{
    //             "code": 0,
    //             "error": "",
    //             "message": "You have been successfuly registered!",
    //             "data": '.json_encode((object)$insert).'
    //         }';
    //     } else {
    //         echo '{
    //             "code": 1,
    //             "error": "User has been registered!",
    //             "message": "User has been registered!",
    //             "data": null
    //         }';
    //     }
    //     $db->close();
    // }

}
