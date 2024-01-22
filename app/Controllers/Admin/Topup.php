<?php

namespace App\Controllers\Admin;

class Topup extends BaseController
{
    public function index()
    {
        echo('welcome!');
    }

    public function postList_all($bypass=false)
    {   
        if (!$bypass) {
            cekValidation('admin/topup/list_all');
        }
        $request = request();
        $db = db_connect();
        $dataPost = $request->getJSON(true);

        $db->query("update topup_users set status = 'Expired' where NOW() >= expired_date and status = 'Pending'");

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
        where bftu.status = \'Success\' and bftu.created_datetime > \'2024-01-01 00:00:00\'
        order by bftu.id desc limit 3000;
        ';
        $query = $db->query($q);
        $dataFinal = $query->getResult();

        $users = $db->table('app_users')->where('user_role', 2)->get()->getResult();

        $db->close();

        $total_deposit = 0;
        $_dataFinal = [];
        foreach ($dataFinal as $val) {
            $total_deposit = $total_deposit + $val->amount;
            $val->email = ($val->email);
            $val->username = ($val->username);
            unset($val->id_user);
            array_push($_dataFinal, $val);
        }
        
        $finalData = json_encode($_dataFinal);
        $finalDataUsers = json_encode($users);

        if ($total_deposit >= 0 && $total_deposit <= 12) {
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

        $message = $bypass ? '"Topup User Successfully."' : '""';

        echo '{
            "code": 0,
            "error": "",
            "message": '.$message.',
            "data": '.$finalData.',
            "users": '.$finalDataUsers.',
            "total_deposit": '.$total_deposit.',
            "level": "'.$level.'",
            "discount": "'.$discount.'"
        }';
    }
        
    public function postTopup_balance () {

        cekValidation('admin/topup/topup_balance');
        $request = request();
        $dataPost = $request->getJSON(true);
        $db = db_connect();
        
        $invoice_number = 'OTPUS-'.$dataPost['id_user'].'-'.date('ymdHi');
        $baseCURS = $db->table('base_profit')->where('current_date', date('Y-m-d'))->limit(1)->get()->getRow(); 

        $insert['invoice_number'] = $invoice_number;
        $insert['id_user'] = $dataPost['id_user'];
        $insert['id_base_payment_method'] = 2;
        $insert['amount'] = (float)$dataPost['amount'] - 0.5;
        $insert['fee_idr'] = 0;
        $insert['profit_idr'] = (float)$baseCURS->curs_usd_to_idr * 0.5;
        $insert['id_currency'] = 1; 
        $insert['payment_type'] = 20;
        $insert['payment_number'] = '';
        $insert['payment_name'] = 'By Admin';
        $insert['payment_amount'] = (float)$baseCURS->curs_usd_to_idr * $insert['amount'];
        $insert['payment_image'] = '';
        $insert['status'] = 'Success';
        $insert['link_url'] = 'https://otpus.site/wallet';
        $insert['expired_date'] = date('Y-m-d H:i:s');
        $db->table('topup_users')->insert($insert);
        
        $insert2['id_user'] = $dataPost['id_user'];
        $insert2['amount_credit'] = $insert['profit_idr'];
        $insert2['amount_debet'] = 0;
        $insert2['amount_credit_usd'] = (float)$insert['profit_idr'] / (float)$baseCURS->curs_usd_to_idr;
        $insert2['amount_debet_usd'] = 0;
        $insert2['description'] = 'Profit Topup User';
        $db->table('journal_finance')->insert($insert2);

        $db->close();

        $this->postList_all(true);
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