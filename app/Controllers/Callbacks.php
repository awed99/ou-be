<?php

namespace App\Controllers;

class Callbacks extends BaseController
{
    public function index()
    {
        return view('welcome_message');
    }

    public function postTopup_pg()
    {
        $db = db_connect();
        // $dt = json_encode(file_get_contents("php://input"), true);
        $request = request();
        $dt = $request->getJSON(true);
        print_r($dt);

        $data = json_decode(curl('https://www.floatrates.com/daily/usd.json'));
        $curs = $db->table('base_profit')->get()->getRow();

        $insert['invoice_number'] = $dt['message'];
        $insert['id_user'] = $dt['supporter'];
        $insert['id_base_payment_method'] = 1;
        $insert['amount'] = ((int)$dt['amount_settled'] / ($data->idr->rate));
        $insert['id_currency'] = ($dt['currency_settled'] == 'IDR') ? 5 : 1; 
        $insert['status'] = 'Success';
        $insert['created_datetime'] = substr(str_replace('T', ' ', $dt['created_at']), 0, 19);

        $db->table('topup_users')->insert($insert);
        $db->close();
        
        // print_r(14318 / ($data->idr->rate));

        /*
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

  
        $myfile = fopen("logs/topup-callback-".$insert['id_user']."-".((int)$dt['amount_settled'] / ($data->idr->rate))."-".date('Y-m-d-H-i').".txt", "w") or die("Unable to open file!");
        $txt = json_encode($insert['created_datetime']);
        fwrite($myfile, $txt);
        fclose($myfile);
    }

    public function postMidtrans()
    {
        $db = db_connect();
        // $dt = json_encode(file_get_contents("php://input"), true);
        $request = request();
        $dt = $request->getJSON(true);
        // print_r($dt);
        
        $rawRequestInput = file_get_contents("php://input");
        $myfile = fopen("callbacks/".$dt['order_id'].".txt", "w") or die("Unable to open file!");
        $txt = $rawRequestInput;
        fwrite($myfile, $txt);
        fclose($myfile);

        $usd = json_decode(curl('https://www.floatrates.com/daily/usd.json'));
        $curs = $db->table('base_profit')->get()->getRow();

        $inv = $dt['order_id'];
        $status = $dt['transaction_status'];
        $amountIDR = (int)$dt['gross_amount'];

        if ($status === 'capture') {
            $update['updated_datetime'] = date('Y-m-d H:i:s');
            $update['status'] = 'Paid on Process Settlement';
        } else if ($status === 'settlement') {
            $update['updated_datetime'] = date('Y-m-d H:i:s');
            $update['status'] = 'Success';
        } else if ($status === 'expire') {
            $update['updated_datetime'] = date('Y-m-d H:i:s');
            $update['status'] = 'Expired';
        }


        $db->table('topup_users')->where('invoice_number', $inv)->update($update);
        $db->close();
        
        // print_r(14318 / ($data->idr->rate));

        /*
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

  
        // $myfile = fopen("logs/topup-callback-".$update['id_user']."-".((int)$dt['gross_amount'] / ($data->idr->rate))."-".date('Y-m-d-H-i').".txt", "w") or die("Unable to open file!");
        // $txt = json_encode($update['updated_datetime']);
        // fwrite($myfile, $txt);
        // fclose($myfile);
    }

    public function postPaydisni()
    {
        $db = db_connect();
        // $dt = json_encode(file_get_contents("php://input"), true);
        $request = request();
        $dt = $request->getPostGet();
        // print_r($dt);
        
        $rawRequestInput = file_get_contents("php://input");
        
        $myfile = fopen("callbacks/".$dt['unique_code'].".txt", "w") or die("Unable to open file!");
        $txt = json_encode($dt);
        fwrite($myfile, $txt);
        fclose($myfile);

        $usd = json_decode(curl('https://www.floatrates.com/daily/usd.json'));
        $curs = $db->table('base_profit')->get()->getRow();

        // $inv = $dt['order_id'];
        // $status = $dt['transaction_status'];
        // $amountIDR = (int)$dt['gross_amount'];

        $status = $dt['status'];
        $key = $dt['key'];
        $unique_code = $dt['unique_code'];
        if($status == 'Success'){
            //mysqli_query('YOUR QUERY IF PAYMENT SUCCESS');
            $result = array('success' => true);
            $update['updated_datetime'] = date('Y-m-d H:i:s');
            $update['status'] = 'Success';
        } else if($status == 'Canceled'){
            //mysqli_query('YOUR QUERY IF PAYMENT CANCELED');
            $result = array('success' => true);
            $update['updated_datetime'] = date('Y-m-d H:i:s');
            $update['status'] = 'Expired';
        } else {
            $result = array('success' => false);
        }


        $db->table('topup_users')->where('invoice_number', $unique_code)->update($update);

        $baseCURS = $db->table('base_profit')->where('current_date', date('Y-m-d'))->limit(1)->get()->getRow(); 
        $_dt = $db->table('topup_users')->where('invoice_number', $unique_code)->get();

        if ($_dt && $status == 'Success') {

            $dt = $_dt->getRowArray();
            $idUser = explode('-', $unique_code)[1] ?? '0';
            $feeIDR = (isset($dt['fee_idr'])) ? (float)$dt['fee_idr'] : 0;
            $profitIDR = (isset($dt['profit_idr'])) ? (float)$dt['profit_idr'] : 0;
    
            $insert['id_user'] = $idUser;
            $insert['amount_credit'] = 0;
            $insert['amount_debet'] = $feeIDR;
            $insert['amount_credit_usd'] = 0;
            $insert['amount_debet_usd'] = $feeIDR / (float)$baseCURS->curs_usd_to_idr;
            $insert['accounting_type'] = 1;
            $insert['description'] = 'Fee Topup';
            $db->table('journal_finance')->insert($insert);
            
            $insert2['id_user'] = $idUser;
            $insert2['amount_credit'] = $profitIDR;
            $insert2['amount_debet'] = 0;
            $insert2['amount_credit_usd'] = $profitIDR / (float)$baseCURS->curs_usd_to_idr;
            $insert2['amount_debet_usd'] = 0;
            $insert2['description'] = 'Profit Topup User';
            $db->table('journal_finance')->insert($insert2);

        }
        
        // $insert3['id_user'] = $dt['id_user'];
        // $insert3['amount_credit'] = $dt['profit_idr'];
        // $insert3['amount_debet'] = 0;
        // $insert3['amount_credit_usd'] = (float)$dt['profit_idr'] / (float)$baseCURS->curs_usd_to_idr;
        // $insert3['amount_debet_usd'] = 0;
        // $insert3['description'] = 'Topup User';
        // $db->table('journal_finance')->insert($insert3);

        $db->close();

        header('Content-type: application/json');
        echo json_encode($result);
    }

    public function postUnipayment()
    {
        $db = db_connect();
        // $dt = json_encode(file_get_contents("php://input"), true);
        $request = request();
        $dt = $request->getJSON(true) ?? $request->getPostGet();
        // print_r($dt);
        
        $rawRequestInput = file_get_contents("php://input");
        
        $myfile = fopen("callbacks/".$dt['order_id'].".txt", "w") or die("Unable to open file!");
        $txt = json_encode($dt);
        fwrite($myfile, $txt);
        fclose($myfile);

        $usd = json_decode(curl('https://www.floatrates.com/daily/usd.json'));
        $curs = $db->table('base_profit')->get()->getRow();

        // $inv = $dt['order_id'];
        // $status = $dt['transaction_status'];
        // $amountIDR = (int)$dt['gross_amount'];

        $status = $dt['status'];
        $key = $dt['key'];
        $order_id = $dt['order_id'];
        if($status == 'Success'){
            //mysqli_query('YOUR QUERY IF PAYMENT SUCCESS');
            $result = array('success' => true);
            $update['updated_datetime'] = date('Y-m-d H:i:s');
            $update['status'] = 'Success';
        } else if($status == 'Expired'){
            //mysqli_query('YOUR QUERY IF PAYMENT CANCELED');
            $result = array('success' => true);
            $update['updated_datetime'] = date('Y-m-d H:i:s');
            $update['status'] = 'Expired';
        } else {
            $result = array('success' => false);
        }


        $db->table('topup_users')->where('invoice_number', $order_id)->update($update);

        $baseCURS = $db->table('base_profit')->where('current_date', date('Y-m-d'))->limit(1)->get()->getRow(); 
        $_dt = $db->table('topup_users')->where('invoice_number', $order_id)->get();

        if ($_dt && $status == 'Confirmed') {

            $__dt = $_dt->getRowArray();
            $idUser = explode('-', $order_id)[1] ?? '0';
            $feeIDR = (isset($__dt['fee_idr'])) ? (float)$__dt['fee_idr'] : 0;
            $profitIDR = (isset($__dt['profit_idr'])) ? (float)$__dt['profit_idr'] : 0;
    
            $insert['id_user'] = $idUser;
            $insert['amount_credit'] = 0;
            $insert['amount_debet'] = $feeIDR;
            $insert['amount_credit_usd'] = 0;
            $insert['amount_debet_usd'] = $feeIDR / (float)$baseCURS->curs_usd_to_idr;
            $insert['accounting_type'] = 1;
            $insert['description'] = 'Fee Topup';
            $db->table('journal_finance')->insert($insert);
            
            $insert2['id_user'] = $idUser;
            $insert2['amount_credit'] = $profitIDR;
            $insert2['amount_debet'] = 0;
            $insert2['amount_credit_usd'] = $profitIDR / (float)$baseCURS->curs_usd_to_idr;
            $insert2['amount_debet_usd'] = 0;
            $insert2['description'] = 'Profit Topup User';
            $db->table('journal_finance')->insert($insert2);

        }
        
        // $insert3['id_user'] = $dt['id_user'];
        // $insert3['amount_credit'] = $dt['profit_idr'];
        // $insert3['amount_debet'] = 0;
        // $insert3['amount_credit_usd'] = (float)$dt['profit_idr'] / (float)$baseCURS->curs_usd_to_idr;
        // $insert3['amount_debet_usd'] = 0;
        // $insert3['description'] = 'Topup User';
        // $db->table('journal_finance')->insert($insert3);

        $db->close();

        header('Content-type: application/json');
        echo json_encode($result);
    }

    public function postSms_activate()
    {
        $db = db_connect();
        // $dt = json_encode(file_get_contents("php://input"), true);
        $request = request();
        $dt = $request->getJSON(true);
        // print_r($dt);
        
        $rawRequestInput = file_get_contents("php://input");
        $myfile = fopen("callbacks/activation-".$dt['activationId'].".txt", "w") or die("Unable to open file!");
        $txt = $rawRequestInput;
        fwrite($myfile, $txt);
        fclose($myfile);
        
        // $update['activationId'] = date('Y-m-d H:i:s');
        // $update['status'] = 'Expired';
        // $update['status'] = $status;

        // if ($dt['status'] === '1' || $dt['status'] === 1) {
        //     $update['status'] = 'Waiting for SMS';
        //     // $update['sms_text'] = '('.$dt['code'].') '.$dt['text'];
        // } else if ($dt['status'] === '3' || $dt['status'] === 3) {
        //     $update['status'] = 'Waiting for Resend SMS';
        //     // $update['sms_text'] = '('.$dt['code'].') '.$dt['text'];
        // } else if ($dt['status'] === '6' || $dt['status'] === 6) {
        //     $update['status'] = 'Success';
        //     $update['sms_text'] = $dt['code'];
        // } else if ($dt['status'] === '8' || $dt['status'] === 8) {
        //     $update['status'] = 'Cancel';
        //     // $update['sms_text'] = '('.$dt['code'].') '.$dt['text'];
        //     $update['is_done'] = '1';
        // }

        
        $update['status'] = 'Success';
        $update['sms_text'] = $dt['code'];


        $db->table('orders')->where('order_id', $dt['activationId'])->update($update);

        $baseCURS = $db->table('base_profit')->where('current_date', date('Y-m-d'))->limit(1)->get()->getRow(); 
        $_dtx = $db->table('orders')->where('order_id', $dt['activationId'])->get();

        if ($_dtx) {
            $dtx = $_dtx->getRowArray();

            $insert['id_user'] = $dtx['id_user'];
            $insert['amount_credit'] = $dtx['price_profit_idr'];
            $insert['amount_debet'] = 0;
            $insert['amount_credit_usd'] = (float)$dtx['price_profit_idr'] / (float)$baseCURS->curs_usd_to_idr;
            $insert['amount_debet_usd'] = 0;
            $insert['accounting_type'] = 2;
            $insert['description'] = 'Profit OTP SMS + ' . $dtx['order_id '];
            $db->table('journal_finance')->insert($insert);
    
            $insert2['id_user'] = $dtx['id_user'];
            $insert2['amount_credit'] = 0;
            $insert2['amount_debet'] = $dtx['price_real'] * (float)$baseCURS->curs_idr;
            $insert2['amount_credit_usd'] = 0;
            $insert2['amount_debet_usd'] = (float)$dtx['price_real'] / (float)$baseCURS->curs_usd;
            $insert2['accounting_type'] = 3;
            $insert2['description'] = 'Profit OTP SMS + ' . $dtx['order_id '];
            $db->table('journal_finance')->insert($insert2);
        }


        $db->close();
        
        // print_r(14318 / ($data->idr->rate));

        /*
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

  
        // $myfile = fopen("logs/topup-callback-".$update['id_user']."-".((int)$dt['gross_amount'] / ($data->idr->rate))."-".date('Y-m-d-H-i').".txt", "w") or die("Unable to open file!");
        // $txt = json_encode($update['updated_datetime']);
        // fwrite($myfile, $txt);
        // fclose($myfile);
    }
}
