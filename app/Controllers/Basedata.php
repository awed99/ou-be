<?php

namespace App\Controllers;

class Basedata extends BaseController
{
    public function index()
    {
        echo('welcome!');
    }

    public function postList_countries($insider=false)
    {   
        if (!$insider) {
            cekValidation('basedata/list_countries');
        }
        $request = request();
        $dataPost = $request->getPost();
        // $dataRequest = cek_token_login($dataPost);
        $dataRequest = ($dataPost);
        $db = db_connect();
        $builder = $db->table('base_countries');
        $builder->where('country_code IS NOT NULL');
        $query   = $builder->get();
        $dataFinal = $query->getResult();
        $db->close();
        if ($insider) {
            return $dataFinal;
        }
        $finalData = json_encode($dataFinal);

        
        $builder1 = $db->table('base_languages');
        $query1   = $builder1->get();
        $dataFinal1 = $query1->getResult();
        $builder2 = $db->table('base_language_sentences');
        $query2   = $builder2->get();
        $dataFinal2 = $query2->getResult();
        $builder3 = $db->table('setting_language');
        $query3   = $builder3->get();
        $dataFinal3 = $query3->getResult();
        $builder4 = $db->table('base_profit');
        $query4   = $builder4->get();
        $dataFinal4 = $query4->getRow();
        $db->close();
        $finalData1 = json_encode($dataFinal1);
        $finalData2 = json_encode($dataFinal2);
        $finalData3 = json_encode($dataFinal3);
        echo '{
            "code": 0,
            "error": "",
            "message": "",
            "data": '.$finalData.',
            "languages": {
                "base_languages": '.$finalData1.',
                "base_language_sentences": '.$finalData2.',
                "setting_language": '.$finalData3.'
            },
            "curs": {
                "curs_idr": '.$dataFinal4->curs_idr.',
                "curs_usd": '.$dataFinal4->curs_usd.'
            }
        }';
    }

    public function postList_currencies()
    {   
        $db = db_connect();
        $builder = $db->table('base_currencies');
        $query   = $builder->get();
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

    public function postList_languages()
    {   
        $db = db_connect();
        $builder = $db->table('base_languages');
        $query   = $builder->get();
        $dataFinal1 = $query->getResult();
        $builder2 = $db->table('base_language_sentences');
        $query2   = $builder2->get();
        $dataFinal2 = $query2->getResult();
        $builder3 = $db->table('setting_language');
        $query3   = $builder3->get();
        $dataFinal3 = $query3->getResult();
        $db->close();
        $finalData1 = json_encode($dataFinal1);
        $finalData2 = json_encode($dataFinal2);
        $finalData3 = json_encode($dataFinal3);
        echo '{
            "code": 0,
            "error": "",
            "message": "",
            "data": {
                "base_languages": '.$finalData1.',
                "base_language_sentences": '.$finalData2.',
                "setting_language": '.$finalData3.'
            }
        }';
    }

    public function postList_language_sentences()
    {   
        $db = db_connect();
        $builder = $db->table('base_language_sentences');
        $query   = $builder->get();
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

    public function postList_payment_methods()
    {   
        $request = request();
        $dataPost = $request->getPost();
        $dataRequest = cek_token_login($dataPost);
        $db = db_connect();
        $builder = $db->table('base_payment_methods');
        $builder->where('is_active', '1');
        $query   = $builder->get();
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

    public function postList_services()
    {   
        $db = db_connect();
        $builder = $db->table('base_services');
        $query   = $builder->get();
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
}
