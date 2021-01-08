<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class apicontroller extends Controller
{
    public function terlayani(Request $request)
    {
        // $token = $request["token"];
        $request["url"] = "http://api.rstelagabunda.com:8081/api/terlayani";
        $request["data"] = $request->except('token');
        $this->post_api($request);
        // $handle = curl_init($url);
        // $encodedData = json_encode($body);
        // curl_setopt($handle, CURLOPT_POST, 1);
        // curl_setopt($handle, CURLOPT_POSTFIELDS, $encodedData);
        // curl_setopt($handle, CURLOPT_HTTPHEADER, array("Content-Type:application/json", "x-token:$token"));
        // $result = curl_exec($handle);
      }
    public function get_token_from_apirstb()
    {
        // dd(storage_path());
        // $contents = Storage::disk('ftp')->get('Kosong.jpg');
        // $ret = Storage::disk('ftp')->put('testingtxt.jpg', $contents);
        // dd($ret);
       
        $request=new Request();
        $request["url"] = "http://api.rstelagabunda.com:8081/api/auth/login";
        $request["data"] = ["username" => "rstb", "password" => "mobile_jkn"];
        $this->post_api($request);
        // $handle = curl_init($url);
        // $encodedData = json_encode($body);
        // curl_setopt($handle, CURLOPT_POST, 1);
        // curl_setopt($handle, CURLOPT_POSTFIELDS, $encodedData);
        // curl_setopt($handle, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        // $result = curl_exec($handle);
    }
    public function get_api_doctors(Request $request)
    {
        $request["data"] = $request["jad"];
        $this->post_api($request);
    }
    public function post_api(Request $request)
    {
        $url = $request["url"];
        // print($url);
        $token = $request["token"];
        $encodedData = json_encode($request["data"]);
        // print_r($encodedData);
        $handle = curl_init($url);
        curl_setopt($handle, CURLOPT_POST, 1);
        curl_setopt($handle, CURLOPT_POSTFIELDS, $encodedData);
        curl_setopt($handle, CURLOPT_HTTPHEADER, array("Content-Type:application/json", "x-token:$token"));

        $result = curl_exec($handle);

    }
}
