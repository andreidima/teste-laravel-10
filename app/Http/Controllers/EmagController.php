<?php

namespace App\Http\Controllers;

use App\Models\EmagCategorie;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmagController extends Controller
{
    public function autorizare(){
            $username = 'emag@kids-outlet.ro';
            $password = 'HGDw6872T$^&Da';

            $hash = base64_encode($username . ':' . $password);

            $headers = array(
                'Authorization: Basic ' . $hash
            );

            return $headers;
    }


    public function citireCategoriiDinEmag(){
        // Aflarea numarului de categorii
        // Aflarea a cate pagini trebuie citite, si cate categorii per pagina
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://marketplace.emag.ro/api-3/category/count');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->autorizare());
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



        // for ($i=1; $i<=$noOfPages; $i++){
        for ($i=1; $i<=1; $i++){
            $data =
                array (
                    'currentPage' => $i,
                    'itemsPerPage' => 5,
                    'id' => 3784
                );

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://marketplace.emag.ro/api-3/category/read');
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
            curl_setopt($ch, CURLOPT_HTTPHEADER, $this->autorizare());
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array('data' => $data)));
            $result = curl_exec($ch);

            $all=json_decode($result); // decode response

            if (!isset($all->isError) || ($all->isError !== false)){
                echo 'Eroare';
                foreach ($all->messages as $message){
                    echo $message;
                }
                return ;
            }

            foreach ($all->results as $categorie) {
                // if ($categorie->id === 378){
                    // DB::table('emag_categorii')->insert([
                    //     'id' => $categorie->id,
                    //     'parent_id' => $categorie->parent_id,
                    //     'name' => $categorie->name,
                    // ]);
                    // echo '<br>';
                    // echo $categorie->id . ' - ' . $categorie->parent_id . ' - ' . $categorie->name;
                    // echo '<br>';
                // }
                    echo '<pre>';
                    print_r($categorie);
                    echo '</pre>';
            }
        }

    }

    public function vizualizareCategoriiInAplicatie(){
        $emag_categorii = EmagCategorie::get();

        echo '<ol>';

        function to_html($categorie) {
            echo '<li>' . $categorie->name . ' (' . $categorie->id . ')';

            if (!empty($categorie->subcategorii)) {

                echo '<ol>';

                foreach ($categorie->subcategorii as $subcategorie) {
                    to_html($subcategorie);
                }
            echo '</ol>';
            }
            echo "</li>\n";
        }

        foreach ($emag_categorii->where('parent_id', null) as $categorie){
            to_html($categorie);
        }

        echo '</ol>';
    }

    public function indexProduse(){

        // Aflarea numarului de produse
        // Aflarea a cate pagini trebuie citite, si cate produse per pagina
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://marketplace.emag.ro/api-3/product_offer/count');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->autorizare());
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
            curl_setopt($ch, CURLOPT_HTTPHEADER, $this->autorizare());
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
                if ($produs->id === 11011){
                    echo '<pre>';
                    print_r($produs);
                    echo '</pre>';
                }
            }
        }

    }

    public function adauga(){
        echo 'Running...<br>';

        $produse_de_importat = DB::table('emag_import')->where('id_pk', 11)->get();
        // $produse_de_importat = DB::table('emag_import')->where('id_pk', '>', 11)->get();

        $data = [];

        foreach ($produse_de_importat as $key => $produs) {
            echo $produs->id . '<br>';
            echo $produs->name . '<br>';
            echo $produs->material . '<br>';
            echo '<br><br>';

            $produs_array =
                    Array(
                        "id" => $produs->id,
                        "category_id" => $produs->category_id,
                        "part_number" => $produs->part_number,
                        "source_language" => "en_GB",
                        "name" => $produs->name,
                        "description" => $produs->description,
                        "brand" => $produs->brand,
                        "images" => Array(
                            Array(
                            "display_type" => "1",
                            "url" => $produs->image_url
                            )
                        ),
                        // "url" => "http://www.product-url.test",
                        // "status" => "1",
                        // "sale_price" => $produs->sale_price,
                        "sale_price" => $produs->sale_price,
                        // "recommended_price" => "506.4515",
                        // "min_sale_price" => "200.0000",
                        // "max_sale_price" => "700.0000",
                        // "availability" => Array(
                        // Array(
                        //     "warehouse_id" => "1",
                        //     "id" => "3"
                        // )
                        // ),
                        "ean" => Array(
                            // Array(
                            //     "0" => "5996523771242",
                            // )
                            $produs->ean
                        ),
                        // "handling_time" => Array(
                        // Array(
                        //     "warehouse_id" => "1",
                        //     "value" => "2"
                        // )
                        // ),
                        "stock" => Array(
                            Array(
                                "warehouse_id" => "1",
                                "value" => $produs->stock
                                // "value" => 6
                            )
                        ),
                        // "commission" => Array(
                        // "type" => "percentage",
                        // "value" => "8"
                        // ),
                        "vat_id" => "1",
                        "characteristics" => Array(
                            // Array(
                            //     "id" => "6372",
                            //     // "value" => $produs->material
                            //     "value" => "Sintetic"
                            // ),
                            Array(
                                "id" => "5401",
                                // "value" => $produs->culoare
                                // "value" => 'Alb'
                                "value" => "White"
                            ),
                            Array(
                                "id" => "5401",
                                // "value" => $produs->culoare
                                "value" => 'Alb'
                                // "value" => "White"
                            ),
                            // Array(
                            //     "id" => "6506",
                            //     "value" => $produs->marime
                            // )
                        )

                        );

            $data[] = $produs_array;
        }
        // dd($data, 'stop');

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://marketplace-api.emag.ro/api-3/product_offer/save');
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
            curl_setopt($ch, CURLOPT_HTTPHEADER, $this->autorizare());
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array('data' => $data)));
            $result = curl_exec($ch);
            echo $result . "\n";

    }

    public function editare($produsId = null){
        echo 'Running...<br>';
        echo 'Produs ID: ' . $produsId . '<br>';

            $data =
                Array(
                Array(
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

    public function actualizareStocProdus(int $produsId = null, int $stoc = null){
        echo 'Running...<br>';
        echo 'Produs ID: ' . $produsId . '<br>';
        echo 'Stoc: ' . $stoc . '<br>';

            $data =
                array (
                    "stock"=> $stoc
                );

            $username = 'emag@kids-outlet.ro';
            $password = 'HGDw6872T$^&Da';
            $hash = base64_encode($username . ':' . $password);
            $headers = array(
                'Content-Type: application/json',
                'Authorization: Basic ' . $hash
            );
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://marketplace-api.emag.ro/api-3/offer_stock/' . $produsId);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode( $data ));
            $result = curl_exec($ch);
            echo $result . "\n";

    }
}
