<?php

namespace App\Controllers\Api;

use App\Controllers\Finance\Saldo;

use App\Controllers\Transactions\Orders;

use App\Controllers\Basedata as BasicData;
use App\Controllers\Service;

class Basedata extends BaseController
{
    public function index()
    {
        echo('Access Denied!');
    }

    public function postGet_balance0() {   
        $source = new Saldo;
        $source->postGet_user_saldo();
    }

    public function postGet_balance() {   
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
        
        $id_user = $db->table('app_users')->where('token_api', $request->header('Authorization')->getValue())->limit(1)->get()->getRow()->id_user;
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
        where (bto.status = \'Success\' or bto.status = \'Waiting for SMS\' or bto.status = \'Waiting for Retry SMS\' or bto.status = \'Waiting for Resend SMS\') and id_user = '.$id_user.') as total_orders,
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
            where (bto.status = \'Success\' or bto.status = \'Waiting for SMS\' or bto.status = \'Waiting for Retry SMS\' or bto.status = \'Waiting for Resend SMS\') and id_user = '.$id_user.')
        ) as saldo;
        ';
        
        if ($id_user > 0) {
            $query = $db->query($q);
            $dataFinal = $query->getRow();
            $db->close();
            $finalData = json_encode($dataFinal);

            echo '{
                "code": 0,
                "error": "",
                "message": "",
                "data": '.$finalData.'
            }';
        } else {
            $res['code'] = 1;
            $res['error'] = 'Unauthorized!';
            $res['message'] = 'Your Authorization Token is invalid!';
            $res['data'] = $data;
            echo json_encode($res);
        }

    }

    public function postGet_countries() {   
        $request = request();
        $db = db_connect();
        $isset = $db->table('app_users')->where('token_api', $request->header('Authorization')->getValue())->limit(1)->get()->getNumRows();
        
        if ($isset > 0) {
            $source = new BasicData;
            $data = $source->postList_countries(true);
            $res['code'] = 0;
            $res['error'] = '';
            $res['message'] = '';
            $res['data'] = $data;
        } else {
            $res['code'] = 1;
            $res['error'] = 'Unauthorized!';
            $res['message'] = 'Your Authorization Token is invalid!';
            $res['data'] = $data;
        }

        echo json_encode($res);
    }

    public function postGet_services_by_country() {   
        $request = request();
        $db = db_connect();
        $isset = $db->table('app_users')->where('token_api', $request->header('Authorization')->getValue())->limit(1)->get()->getNumRows();
        
        if ($isset > 0) {
            $source = new Service;
            $data = $source->postList_services(true);
            $res['code'] = 0;
            $res['error'] = '';
            $res['message'] = '';
            $res['data'] = $data;
        } else {
            $res['code'] = 1;
            $res['error'] = 'Unauthorized!';
            $res['message'] = 'Your Authorization Token is invalid!';
            $res['data'] = $data;
        }

        echo json_encode($res);
    }
}