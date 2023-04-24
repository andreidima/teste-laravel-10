<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EmagController extends Controller
{
    public function citire(){
        // Datele de autorizare
        $username = 'emag@kids-outlet.ro';
        $password = 'HGDw6872T$^&Da';
        $hash = base64_encode($username . ':' . $password);
        $headers = array(
            'Authorization: Basic ' . $hash
        );

        // Aflarea numarului de produse
        // Aflarea a cate pagini trebuie citite, si cate produse per pagina
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://marketplace.emag.ro/api-3/product_offer/count');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $result = curl_exec($ch);

        $all=json_decode($result); // decode response

        if (!isset($all->isError) || ($all->isError !== false)){
            foreach ($all->messages as $message){
                echo $message;
            }
            return ;
        }

        $noOfItems = $all->results->noOfItems; // numarul total de produse
        $noOfPages = $all->results->noOfPages; // numarul total de pagini
        $itemsPerPage = $all->results->itemsPerPage; // numarul de produse per pagina

        // echo $noOfItems . '<br>';
        // echo $noOfPages . '<br>';
        // echo $itemsPerPage . '<br>';


        for ($i=1; $i<=$noOfPages; $i++){
            $data =
                array (
                    // 'currentPage' => 1,
                    // 'itemsPerPage' => 1
                    'currentPage' => $i,
                    'itemsPerPage' => 100
                );

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://marketplace.emag.ro/api-3/product_offer/read');
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array('data' => $data)));
            $result = curl_exec($ch);

            $all=json_decode($result); // decode response
// dd($all, $all->results[0]->stock[0]->value);
// dd($all);
            if (!isset($all->isError) || ($all->isError !== false)){
                echo 'Eroare';
                foreach ($all->messages as $message){
                    echo $message;
                }
                return ;
            }

            // print array response
            // echo '<hr>';
            // echo '<pre>';
            // print_r($all->results[0]);
            // echo '</pre>';

            foreach ($all->results as $produs) {
                // if ($produs->stock[0]->value > 0){
                    // echo 'Part number key: ' . ($produs->part_number_key ?? '') . '<br>';
                    // echo 'ID: ' . ($produs->id ?? '') . '<br>';
                    // echo 'Nume produs: ' . ($produs->name ?? '') . '<br>';
                    // echo '<br><br>';
                // }
                if ($produs->id === 378){
                    echo '<pre>';
                    print_r($produs);
                    echo '</pre>';
                }
            }
        }

    }

    public function editareProdus($produsId = null){
        echo 'Running...<br>';
        echo 'Produs ID: ' . $produsId . '<br>';

            $data =
                Array(
                Array(
                    // "id" => "378",
                    "id" => $produsId,
                    "name" => "Geaca de denim borg Topman decupat si coase in negru - NEGRU 11112222",
                    "status" => "1",
                    "sale_price" => "84.00",
                    "recommended_price" => "100.00",
                    "min_sale_price" => "50.00",
                    "max_sale_price" => "200.00",
                    "availability" => Array(
                    Array(
                        "warehouse_id" => "1",
                        "id" => "3"
                    )
                    ),
                    "handling_time" => Array(
                    Array(
                        "warehouse_id" => "1",
                        "value" => "0"
                    )
                    ),
                    "stock" => Array(
                    Array(
                        "warehouse_id" => "1",
                        "value" => "2"
                    )
                    )
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
            // curl_setopt($ch, CURLOPT_URL, 'https://marketplace-api.emag.ro/api-3/offer_stock/378');
            curl_setopt($ch, CURLOPT_URL, 'https://marketplace-api.emag.ro/api-3/product_offer/save');
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array('data' => $data)));
            $result = curl_exec($ch);
            echo $result . "\n";

    }

    public function actualizareStocProdus($produsId = null){
        echo 'Running...<br>';
        echo 'Produs ID: ' . $produsId . '<br>';

            $data =
                array (
                    "stock" => 21
                );
            // $data =
            //     Array(
            //     Array(
            //         "stock" => Array(
            //         Array(
            //             "warehouse_id" => "1",
            //             "value" => "2"
            //         )
            //         )
            //     )
            //     );

            // $stock = 2;

            // echo $data;
            //     echo http_build_query(array('data' => $data));
            // dd($data);

            // dd('stop');
// $data = [
// 			'stock'			=> 123,

// ];
echo json_encode($data);

            $username = 'emag@kids-outlet.ro';
            $password = 'HGDw6872T$^&Da';
            $hash = base64_encode($username . ':' . $password);
            $headers = array(
                'Content-Type: application/json',
                'Authorization: Basic ' . $hash
            );
            $ch = curl_init();
            // curl_setopt($ch, CURLOPT_URL, 'https://marketplace.emag.ro/api-3/category/read');
            // MARKETPLACE_API_URL	https://marketplace-api.emag.ro/api-3
            // curl_setopt($ch, CURLOPT_URL, 'https://marketplace-api.emag.ro/api-3/offer_stock/' . $produsId);
            curl_setopt($ch, CURLOPT_URL, 'https://marketplace-api.emag.ro/api-3/offer_stock/378');
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
            // curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            // curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
            echo http_build_query($data);
            $result = curl_exec($ch);
            echo $result . "\n";

    }
}
