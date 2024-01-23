<?php

namespace App\Controllers\Admin;

class Users extends BaseController
{
    public function index()
    {
        echo('welcome!');
    }

    public function postList($bypass=false)
    {   
        if (!$bypass) {
            cekValidation('admin/users/list');
        }
        $request = request();
        $db = db_connect();

        $users = $db->table('app_users')->where('user_role', 2)->orderBy('id_user', 'DESC')->get()->getResult();

        $db->close();
        $finalData = json_encode($users);

        echo '{
            "code": 0,
            "error": "",
            "message": "",
            "data": '.$finalData.'
        }';
    }

    public function postChange_password()
    {   
        cekValidation('admin/users/change_password');
        $request = request();
        $dataRequest = $request->getJSON(true);
        $id_user = $dataRequest['id_user'];
        $update["password"] = hash('sha256', $dataRequest['password']);
        $db = db_connect();
        $db->table('app_users')->where('id_user', $dataRequest['id_user'])->update($update);
        $db->close();

        echo '{
            "code": 0,
            "error": "",
            "message": "User password has been changed."
        }';
    }
}