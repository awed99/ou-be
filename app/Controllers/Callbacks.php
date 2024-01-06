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

        if ($dt['status'] === '1' || $dt['status'] === 1) {
            $update['status'] = 'Waiting for SMS';
            // $update['sms_text'] = '('.$dt['code'].') '.$dt['text'];
        } else if ($dt['status'] === '3' || $dt['status'] === 3) {
            $update['status'] = 'Waiting for Resend SMS';
            // $update['sms_text'] = '('.$dt['code'].') '.$dt['text'];
        } else if ($dt['status'] === '6' || $dt['status'] === 6) {
            $update['status'] = 'Success';
            $update['sms_text'] = $dt['code'];
        } else if ($dt['status'] === '8' || $dt['status'] === 8) {
            $update['status'] = 'Cancel';
            // $update['sms_text'] = '('.$dt['code'].') '.$dt['text'];
            $update['is_done'] = '1';
        }


        $db->table('orders')->where('order_id', $dt['activationId'])->update($update);
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
