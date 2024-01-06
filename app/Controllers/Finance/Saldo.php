<?php

namespace App\Controllers\Finance;

use App\Controllers\Transactions\Orders;

class Saldo extends BaseController
{
    public function index()
    {
        echo('welcome!');
    }

    public function postGet_user_saldo()
    {   
        cekValidation('finance/saldo/get_user_saldo');
        $request = request();
        $db = db_connect();

        $baseCURS = $db->table('base_profit')->where('current_date', date('Y-m-d'))->limit(1)->get()->getRow(); 
        if ($baseCURS) {
            $usdCURS = $baseCURS->curs_usd;
        } else {
            $usd = json_decode(curl('https://www.floatrates.com/daily/usd.json'));
            $rub = json_decode(curl('https://www.floatrates.com/daily/rub.json'));
            $update['current_date'] = date('Y-m-d');
            $update['curs_usd'] = $usd->rub->rate;
            $update['curs_idr'] = $rub->idr->rate;
            $db->table('base_profit')->update($update)->where('id', 1);
            $usdCURS = $usd->rub->rate;
        }

        $id_user = $db->table('app_users')->where('token_login', $request->header('Authorization')->getValue())->limit(1)->get()->getRow()->id_user;
        $q = 'SELECT
        (SELECT 
        COALESCE(ROUND(SUM(bftu.amount), 2), 0)
        from topup_users bftu
        where bftu.status = \'Success\' and id_user = '.$id_user.') as total_topup,
        (SELECT 
        COALESCE(ROUND(SUM(bfru.amount), 2), 0)
        from refund_users bfru
        where bfru.status = \'Success\' and id_user = '.$id_user.') as total_refund,
        (SELECT 
        (COALESCE(ROUND(SUM(bto.price_user), 2), 0) / '.($usdCURS).')
        from orders bto
        where (bto.status = \'Success\' or bto.status = \'Waiting for SMS\' or bto.status = \'Waiting for Retry SMS\') and id_user = '.$id_user.') as total_orders,
        (
            (SELECT 
            COALESCE(ROUND(SUM(bftu.amount), 2), 0)
            from topup_users bftu
            where bftu.status = \'Success\' and id_user = '.$id_user.') -
            (SELECT 
            COALESCE(ROUND(SUM(bfru.amount), 2), 0)
            from refund_users bfru
            where bfru.status = \'Success\' and id_user = '.$id_user.') -
            (SELECT 
            (COALESCE(ROUND(SUM(bto.price_user), 2), 0) / '.($usdCURS).')
            from orders bto
            where (bto.status = \'Success\' or bto.status = \'Waiting for SMS\' or bto.status = \'Waiting for Retry SMS\') and id_user = '.$id_user.')
        ) as saldo;
        ';
        $db = db_connect();
        $query = $db->query($q);
        $dataFinal = $query->getRow();
        $builder4 = $db->table('base_profit');
        $query4   = $builder4->get();
        $dataFinal4 = $query4->getRow();
        $db->close();
        $finalData = json_encode($dataFinal);

        $orders = new Orders;
        $dataZ = $orders->postUpdate_status_activation();
        echo '{
            "code": 0,
            "error": "",
            "message": "",
            "data": '.$finalData.',
            "list_activations": '.$dataZ.',
            "curs": {
                "curs_idr": '.$dataFinal4->curs_idr.',
                "curs_usd": '.$dataFinal4->curs_usd.'
            }
        }';
    }

    public function get_user_saldo($auth)
    {   
        // cekValidation('finance/saldo/get_user_saldo');
        $request = request();
        $db = db_connect();

        $baseCURS = $db->table('base_profit')->where('current_date', date('Y-m-d'))->limit(1)->get()->getRow(); 
        if ($baseCURS) {
            $usdCURS = $baseCURS->curs_usd;
        } else {
            $usd = json_decode(curl('https://www.floatrates.com/daily/usd.json'));
            $rub = json_decode(curl('https://www.floatrates.com/daily/rub.json'));
            $update['current_date'] = date('Y-m-d');
            $update['curs_usd'] = $usd->rub->rate;
            $update['curs_idr'] = $rub->idr->rate;
            $db->table('base_profit')->update($update)->where('id', 1);
            $usdCURS = $usd->rub->rate;
        }

        $id_user = $db->table('app_users')->where('token_login', $auth)->limit(1)->get()->getRow()->id_user;
        $q = 'SELECT
        (SELECT 
        COALESCE(ROUND(SUM(bftu.amount), 2), 0)
        from topup_users bftu
        where bftu.status = \'Success\' and id_user = '.$id_user.') as total_topup,
        (SELECT 
        COALESCE(ROUND(SUM(bfru.amount), 2), 0)
        from refund_users bfru
        where bfru.status = \'Success\' and id_user = '.$id_user.') as total_refund,
        (SELECT 
        (COALESCE(ROUND(SUM(bto.price_user), 2), 0) / '.($usdCURS).')
        from orders bto
        where (bto.status = \'Success\' or bto.status = \'Waiting for SMS\' or bto.status = \'Waiting for Retry SMS\') and id_user = '.$id_user.') as total_orders,
        (
            (SELECT 
            COALESCE(ROUND(SUM(bftu.amount), 2), 0)
            from topup_users bftu
            where bftu.status = \'Success\' and id_user = '.$id_user.') -
            (SELECT 
            COALESCE(ROUND(SUM(bfru.amount), 2), 0)
            from refund_users bfru
            where bfru.status = \'Success\' and id_user = '.$id_user.') -
            (SELECT 
            (COALESCE(ROUND(SUM(bto.price_user), 2), 0) / '.($usdCURS).')
            from orders bto
            where (bto.status = \'Success\' or bto.status = \'Waiting for SMS\' or bto.status = \'Waiting for Retry SMS\') and id_user = '.$id_user.')
        ) as saldo;
        ';
        $db = db_connect();
        $query = $db->query($q);
        $dataFinal = $query->getRow();
        $builder4 = $db->table('base_profit');
        $query4   = $builder4->get();
        $dataFinal4 = $query4->getRow();
        $db->close();
        $finalData = json_encode($dataFinal);

        $orders = new Orders;
        $dataZ = $orders->postUpdate_status_activation();
        $res = '{
            "data": '.$finalData.',
            "list_activations": '.$dataZ.',
            "curs": {
                "curs_idr": '.$dataFinal4->curs_idr.',
                "curs_usd": '.$dataFinal4->curs_usd.'
            }
        }';

        return json_decode($res);
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