<?php

namespace App\Controllers;

class Auth extends BaseController
{
    public function index()
    {
        return view('welcome_message');
    }

    public function postLogout()
    {   
        $session = session();
        $session->remove('login');
        $session->remove('token_login');
    }

    public function postCheck_token_login()
    {
        $request = request();
        $session = session();
        $postData = $request->getPost();
        $token_login = $postData['token_login'];
        if(!$session->get('token_login')) {
            $db = db_connect();
            $builder = $db->table('app_users')->where('token_login', $token_login);
            $dataUser = $builder->get()->getRow();
            $db->close();
            if ($dataUser) {
                $user = $dataUser;
                $session->set('login', $user);
                $session->set('token_login', $user->token_login);
                $session->set('token_api', $user->token_api);
            }
        }
        $session_token_login = $session->get('token_login');
        if ($token_login == $session_token_login) {
            echo 1;
        } else {
            echo 0;
        }
    }

    public function postCheck_token_api()
    {
        $request = request();
        $session = session();
        $postData = $request->getPost();
        $token_api = $postData['token_api'];
        if(!$session->get('token_api')) {
            $db = db_connect();
            $builder = $db->table('app_users')->where('token_api', $token_api);
            $dataUser = $builder->get()->getRow();
            $db->close();
            if ($dataUser) {
                $user = $dataUser;
                $session->set('login', $user);
                $session->set('token_login', $user->token_login);
                $session->set('token_api', $user->token_api);
            }
        }
        $session_token_api = $session->get('token_api');
        if ($token_api == $session_token_api) {
            echo 1;
        } else {
            echo 0;
        }
    }
}
