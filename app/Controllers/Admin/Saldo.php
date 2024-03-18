<?php

namespace App\Controllers\Admin;

use App\Controllers\Transactions\Orders;

class Saldo extends BaseController
{
    public function index()
    {
        echo('welcome!');
    }

    public function postGet_user_saldo()
    {   
        cekValidation('admin/saldo/get_user_saldo');
        $request = request();
        $db = db_connect();

        $update0['is_done'] = '1';
        $update0['status'] = 'Cancel';
        $builder0 = $db->table('orders')
        ->where('(is_done = 0 or is_done = \'0\' or is_done = false)')
        ->where('status <> \'Success\'')
        ->where('(created_date <= (NOW() - interval 20 minute))')
        ->update($update0);

        $update1['is_done'] = '1';
        $builder1 = $db->table('orders')
        ->where('(is_done = 0 or is_done = \'0\' or is_done = false)')
        ->where('status = \'Success\'')
        ->where('(created_date <= (NOW() - interval 20 minute))')
        ->update($update1);

        $baseCURS = $db->table('base_profit')->where('current_date', date('Y-m-d'))->limit(1)->get()->getRow(); 
        if ($baseCURS) {
            $usdCURS = $baseCURS->curs_usd;
        } else {
            $usd = json_decode(curl('https://www.floatrates.com/daily/usd.json'));
            $rub = json_decode(curl('https://www.floatrates.com/daily/rub.json'));
            $update['current_date'] = date('Y-m-d');
            $update['curs_usd'] = $usd->rub->rate;
            $update['curs_idr'] = $rub->idr->rate;
            $update['curs_usd_to_idr'] = $usd->idr->rate;
            $db->table('base_profit')->where('id', 1)->update($update);
            $usdCURS = $usd->rub->rate;
        }

        $user = $db->table('app_users')->where('token_login', $request->header('Authorization')->getValue())->limit(1)->get()->getRow();
        // print_r($user);
        // die();
        if (!$user || $user === null || $user === 0 || $user === '0' || $user === '') {
            echo '{
                "code": 1,
                "error": "Unauthorized! Please login.",
                "message": "Unauthorized! Please login."
            }';
            die();
        }
        // $id_user = $user->id_user;

        $dataFinal = $db->table('journal_finance')->select('(COALESCE(SUM(amount_credit), 0) - COALESCE(SUM(amount_debet), 0)) as balance_idr, (COALESCE(SUM(amount_credit_usd), 0) - COALESCE(SUM(amount_debet_usd), 0)) as balance_usd')->limit(1)->get()->getRow();

        // $dataFinal = $query->getRow();
        $builder4 = $db->table('base_profit');
        $query4   = $builder4->get();
        $dataFinal4 = $query4->getRow();
        $db->close();
        $finalData = json_encode($dataFinal);

        echo '{
            "code": 0,
            "error": "",
            "message": "",
            "balance": '.$finalData.',
            "curs": {
                "curs_idr": '.$dataFinal4->curs_idr.',
                "curs_usd": '.$dataFinal4->curs_usd.', 
                "curs_usd_to_idr": '.$dataFinal4->curs_usd_to_idr.'
            }
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