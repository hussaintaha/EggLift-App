<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


class UserController extends Controller
{


       public function CheckArrayKeys($array,$chk)
       {
         $return = false;
         foreach ($array as $key => $value) {
           if(strtolower($value->coupon) == $chk && $value->status == 'approved'){
              $return = true;
              break;
           }
         }
         return $return;
       }

       public function Test2(Request $request)
       {
          // echo "Hello";
            $code = strtolower($request->code);
            // echo $code;

            // ref_code
            $checkURL = "https://api.goaffpro.com/affiliates/search?ref_code=".urlencode($code);
            // $checkURL = "https://api.goaffpro.com/affiliates/search?ref_code=".urlencode($code);
            echo $checkURL;
            $curl = curl_init();
            curl_setopt_array($curl, array(
              CURLOPT_URL => $checkURL,
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => "",
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => "GET",
              CURLOPT_HTTPHEADER => array(
                "x-goaffpro-access-token: 811e7bc145be932a413507cf5eb3b88ddbf0f22cbc574724b16d054a0d034de4"
              ),
            ));
            $response = curl_exec($curl);

            if (curl_errno($curl)) {
                $error_msg = curl_error($curl);
            }
            curl_close($curl);
            $Mapping = json_decode($response);

            echo "<pre>";
            print_r($Mapping);
            echo "</pre>";




       }


    public function Test(Request $request)
    {
      // $code = 'camp';
      $code = strtolower($request->code);
      $validate = false;
      $return = array();
      $curl = curl_init();
      curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.goaffpro.com/affiliates",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
          "x-goaffpro-access-token: 811e7bc145be932a413507cf5eb3b88ddbf0f22cbc574724b16d054a0d034de4"
        ),
      ));
      $response = curl_exec($curl);
      if (curl_errno($curl)) {
          $error_msg = curl_error($curl);
      }
      curl_close($curl);

      if (isset($error_msg)) {
          $return = ['code'=>100,'msg'=>curl_error($ch)];
      }else{
        $Mapping = json_decode($response);
        if(count($Mapping) > 0  && $this->CheckArrayKeys($Mapping,$code)){
          $return = ['code'=>200,'msg'=>'Affliate Code Validated'];
        }else{
          $return = ['code'=>100,'msg'=>'Invalid Affliate Code'];
        }
      }
      header('Content-Type: application/json');
      echo json_encode($return);
    }




    public function AllCodes(Request $request)
    {
      // $code = strtolower('ZEROGRAVITYDIVE');
      // dd($request->all());
      $code = strtolower($request->code);
      $validate = false;
      $return = array();
      $curl = curl_init();
      $fields = "id,coupons,coupon,status";
      curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.goaffpro.com/v1/admin/affiliates?fields=".urlencode($fields),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
          "x-goaffpro-access-token: 811e7bc145be932a413507cf5eb3b88ddbf0f22cbc574724b16d054a0d034de4"
        ),
      ));
      $response = curl_exec($curl);
      if (curl_errno($curl)) {
          $error_msg = curl_error($curl);
      }
      curl_close($curl);
      if (isset($error_msg)) {
          $return = ['code'=>100,'msg'=>curl_error($ch)];
      }else{
          $Mapping = json_decode($response);
          $return = false;
          $status="";
          foreach ($Mapping->affiliates as $key => $affliate) {
              foreach ($affliate->coupons as $c) {
                if(strtolower($c->code) == $code){
                  $status = $affliate->status;
                }
              if(strtolower($c->code) == $code && $affliate->status == 'approved'){
                $return = true;
                break;
              }
            }
          }
        if(count($Mapping->affiliates) > 0  && $return){
          $return = ['code'=>200,'msg'=>'Affliate Code Validated'];
        }else{
          $return = ['code'=>100,'msg'=>'Affliate Code '.$status];
        }
      }
      // header('Content-Type: application/json');
      // echo json_encode($return);
      // return response($return)->header('Content-Type', 'application/json');
    }
}
