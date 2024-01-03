<?php

namespace App\Controllers\Finance;

class Refund extends BaseController
{
    public function index()
    {
        echo('welcome!');
    }

    public function postList()
    {   
        $this->update_price();
        $request = request();
        $dataPost = $request->getJSON();
        $dataRequest = cek_token_login($dataPost);
        $q = 'SELECT 
        bfru.*,
        bau.username,
        bau.email,
        bto.invoice_number,
        bto.id_app_service,
        bas.service_code,
        bas.service_name,
        bto.number,
        bto.price_user,
        bbc.currency_symbol,
        bbc.currency_code,
        bbc.currency_name,
        bto.created_date as transaction_date,
        bto.status
        from bestpva_finance.refund_users bfru
        left join bestpva.app_users bau on bau.id_user = bfru.id_user
        left join bestpva_transactions.orders bto on bto.id = bfru.order_id
        left join bestpva.app_services bas on bas.id = bto.id_app_service
        left join bestpva.base_currencies bbc on bbc.id = bfru.id_currency 
        where bfru.id_user = '.$dataRequest['id_user'].'
        order by bfru.id desc;
        ';
        $db = db_connect();
        $query = $db->query($q);
        $dataFinal = $query->getResult();
        $db->close();
        $finalData = json_encode($dataFinal);
        echo '{
            "code": 0,
            "error": "",
            "message": "",
            "data": '.$finalData.'
        }';
    }

    public function postCreate()
    {   
        $request = request();
        $dataPost = $request->getJSON();
        $dataRequest = cek_token_login($dataPost);
        $db = db_connect();
        $builder = $db->table('bestpva_finance.refund_users');
        $query = $builder->ignore->insert($dataRequest);
        $dataFinal = $builder->getResult();
        $db->close();
        $finalData = json_encode($dataFinal);
        echo '{
            "code": 0,
            "error": "",
            "message": "",
            "data": '.$finalData.'
        }';
    }
}
