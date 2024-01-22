<?php

namespace App\Controllers\Admin;

class Journal extends BaseController
{
    public function index()
    {
        echo('welcome!');
    }

    public function postList()
    {   
        cekValidation('admin/journal/list');
        $request = request();
        $db = db_connect();

        $data = $db->table('journal_finance')->select('journal_finance.*, app_users.email, app_users.username')
        ->join('app_users', 'app_users.id_user = journal_finance.id_user')
        ->orderBy('journal_finance.id', 'DESC')->limit(3000)->get()->getResult();
        $db->close();
        $finalData = json_encode($data);

        echo '{
            "code": 0,
            "error": "",
            "message": "",
            "data": '.$finalData.'
        }';
    }

    public function postAdd()
    {   
        cekValidation('admin/journal/add');
        $request = request();
        $json = $request->getJSON(true);
        $db = db_connect();
        
        $baseCURS = $db->table('base_profit')->where('current_date', date('Y-m-d'))->limit(1)->get()->getRow(); 
        $user = $db->table('app_users')->where('token_login', $request->header('Authorization')->getValue())->limit(1)->get()->getRow();
        $dataFinal = $db->table('journal_finance')->select('(COALESCE(SUM(amount_credit)) - COALESCE(SUM(amount_debet))) as balance_idr, (COALESCE(SUM(amount_credit_usd)) - COALESCE(SUM(amount_debet_usd))) as balance_usd')->limit(1)->get()->getRow();

        // print_r($dataFinal);
        if ((float)$dataFinal->balance_idr < (float)$json['amount']) {
            $data0 = $db->table('journal_finance')->select('journal_finance.*, app_users.email, app_users.username')
            ->join('app_users', 'app_users.id_user = journal_finance.id_user')
            ->orderBy('journal_finance.id', 'DESC')->limit(3000)->get()->getResult();
            $db->close();
            $finalData = json_encode($data0);
            echo '{
                "code": 1,
                "error": "Insuficient Balance",
                "message": "Insuficient Balance!",
                "data": '.$finalData.'
            }';            
            die();
        }

        // $insert = $json;
        $insert['id_user'] = $user->id_user;
        $insert['amount_credit'] = 0;
        $insert['amount_debet'] = $json['amount'];
        $insert['amount_credit_usd'] = 0;
        $insert['amount_debet_usd'] = (float)$json['amount'] / (float)$baseCURS->curs_usd_to_idr;
        $insert['accounting_type'] = 4;
        if (!isset($insert['description'])) {
            $insert['description'] = 'Withdraw By Admin';
        }
        $db->table('journal_finance')->insert($insert);

        $data = $db->table('journal_finance')->select('journal_finance.*, app_users.email, app_users.username')
        ->join('app_users', 'app_users.id_user = journal_finance.id_user')
        ->orderBy('journal_finance.id', 'DESC')->limit(3000)->get()->getResult();
        $db->close();
        $finalData = json_encode($data);

        echo '{
            "code": 0,
            "error": "",
            "message": "Withdraw By Admin Successfully.",
            "data": '.$finalData.'
        }';
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