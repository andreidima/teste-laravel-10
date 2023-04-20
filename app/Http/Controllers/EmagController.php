<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EmagController extends Controller
{
    public function citire(){
        echo 'Running...<br>';

            $data =
                array (
                    'currentPage' => 1,
                    'itemsPerPage' => 10
                );
            $username = 'emag@kids-outlet.ro';
            $password = 'HGDw6872T$^&Da';
            $hash = base64_encode($username . ':' . $password);
            $headers = array(
                'Authorization: Basic ' . $hash
            );
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://marketplace.emag.ro/api-3/category/read');
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array('data' => $data)));
            $result = curl_exec($ch);
            echo $result . "\n";
    }

    public function stocUpdate(){
        echo 'Running...<br>';

            $data =
                array (
                        array(
                            // "id" => "6050",
                            "stock" => array(
                                array(
                                    "value" => "304"
                                )
                            ),
                        )
                );
            $username = 'emag@kids-outlet.ro';
            $password = 'HGDw6872T$^&Da';
            $hash = base64_encode($username . ':' . $password);
            $headers = array(
                'Authorization: Basic ' . $hash
            );
            $ch = curl_init();
            // curl_setopt($ch, CURLOPT_URL, 'https://marketplace.emag.ro/api-3/category/read');
            // MARKETPLACE_API_URL	https://marketplace-api.emag.ro/api-3
            curl_setopt($ch, CURLOPT_URL, 'https://marketplace-api.emag.ro/api-3/offer_stock/304');
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array('data' => $data)));
            $result = curl_exec($ch);
            echo $result . "\n";

    }
}
