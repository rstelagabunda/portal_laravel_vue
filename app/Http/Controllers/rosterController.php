<?php

namespace App\Http\Controllers;

use App\models\roster;
use App\models\shift;
use App\User;
use DateTime;
use Illuminate\Http\Request;

class rosterController extends Controller
{
    public function get_employees(Request $request)
    {
        $role = $request["role"];

        $users = User::where([['roster_dep', $role]])->get();
        return response()->json(
            [
                "data" => $users
            ],
            200
        );
    }
    public function upload_csv(Request $request)
    {
        $upload = $request->file('csv');
        $filePath = $upload->getRealPath();
        //open and read
        $file = fopen($filePath, 'r');
        $header = fgetcsv($file, 0, ';');
        $escapedHeader = [];
        foreach ($header as $key => $value) {
            $lheader = strtolower($value);
            $escapedItem = preg_replace('/[^a-z]/', '', $lheader);
            array_push($escapedHeader, $escapedItem);
        }

        //looping through othe columns
        while ($columns = fgetcsv($file, 0, ';')) {
            if ($columns[0] == "") {
                continue;
            }
            //trim data
            // foreach ($columns as $key => &$value) {
            //     $value=preg_replace('/\D/','',$value);
            // }

            $data = array_combine($escapedHeader, $columns);
            //    dd($data);
            $splited_date = explode(' ', $data['datetime']);
            $date_arr = explode('/', $splited_date[0]);
            $time = $splited_date[1];
            $bulan = $date_arr[1];
            $hari = $date_arr[0];
            $tahun = $date_arr[2];
            $emp_id = $data['no'];
            // print_r($time);
            // print_r('bulan'.$bulan);
            // print_r('hari'.$hari);
            // print_r('tahun'.$tahun);
            $tanggal = '' . $tahun . '-' . $bulan . '-' . $hari;
            $timest = $tanggal . ' ' . $splited_date[1];
            // print($timest);
            // $tanggal= date('Y-m-d', "2020/08/10" );
            // print_r($tanggal);
            $tanggal_pre = date('Y-m-d', strtotime($tanggal . ' - 1 days'));
            // dd($tanggal_pre);
            $roster_present = roster::where([['tanggal', $tanggal], ['emp_id', $emp_id]])->first();
            $roster_pre = roster::where([['tanggal', $tanggal_pre], ['emp_id', $emp_id]])->first();
            $roster = null;
            // if($roster_pre==null){
            //     $roster=$roster_present;
            // }
            // dd($roster_pre);
            $milli_datetime = strtotime($timest);
            $milli_datetime = $milli_datetime * 1000;
            $datetimes = date('Y-m-d H:i:s', $milli_datetime);


            if ($roster_present != null) {
                $j_datetime = json_decode($roster_present->json_datetime, true);
                // print(count($j_datetime));
                $is_functional_0 = false;
                $suitable_obj_in = null;
                $suitable_obj_out = null;
                // dd($roster_present);
                if ($j_datetime) {
                    if ($roster_present["type_shift"] != '*') {
                        foreach ($j_datetime as $key => $rec) {
                            // print_r($roster_present);
                            // print_r($rec);
                            // if (!array_key_exists('functional', $rec)) {
                            //     // dd($rec);
                            // }
                            // if($rec["shift_code"]=='M'){
                            //     print($milli_datetime);
                            //     print_r($data);
                            //     dd($rec);
                            // }
                            if (array_key_exists('functional', $rec) && $rec["functional"] == "1") {
                                $roster = $roster_present;
                                if ($data["status"] == "C/In") {


                                    // dd($rec);
                                    if ($rec["keluar_timestamp"] > $milli_datetime) {
                                        if ($suitable_obj_in) {
                                            echo 'passed';
                                            if ($rec["keluar_timestamp"] < $suitable_obj_in["keluar_timestamp"]) {
                                                $suitable_obj_in = $rec;
                                            }
                                        } else {
                                            $suitable_obj_in = $rec;
                                        }
                                    }
                                    // $roster->c_in = $datetimes;
                                    // if (($milli_datetime)) {
                                    // } else {
                                    //     $roster->c_out = $datetimes;
                                    // }
                                    // echo 'suitable object is \n';
                                    // echo json_encode($suitable_obj_in);
                                    // echo 'current record is \n';
                                    // echo json_encode($rec);

                                } else if ($data["status"] == "C/Out") {
                                    // print_r($data);
                                    //    echo $rec["masuk_timestamp"].'is '.$milli_datetime; 

                                    if ($rec["masuk_timestamp"] < $milli_datetime) {
                                        if ($suitable_obj_out) {

                                            if ($rec["masuk_timestamp"] < $suitable_obj_out["masuk_timestamp"]) {
                                                $suitable_obj_out = $rec;
                                            }
                                        } else {
                                            $suitable_obj_out = $rec;
                                        }
                                    } else {
                                        // print_r($roster_pre);
                                        if (isset($roster_pre->mix)) {

                                            if ($roster_pre["mix"] == 1) {
                                                $is_functional_0 = true;  
                                            }
                                            //              if(false && $roster_pre->mix=='1' ){
                                            // print($milli_datetime);
                                            // print_r($data);
                                            // dd($roster_pre);
                                            //              }
                                        }
                                    }
                                    // echo 'suitable out';
                                    // dd($suitable_obj_out);
                                }

                                // print_r($suitable_obj_out);
                                // print_r($suitable_obj_in);
                                // dd($j_datetime);
                            } else {
                                $is_functional_0 = true;
                            }
                        }


                        foreach ($j_datetime as $key => $rec) {
                            if ($data["status"] == "C/Out") {
                                if ($rec == $suitable_obj_out) {
                                    if ($j_datetime[$key]["c_out"]) {
                                        if ($j_datetime[$key]["c_out"] < $milli_datetime) {
                                            $j_datetime[$key]["c_out"] = $milli_datetime;
                                            $roster["json_datetime"] = json_encode($j_datetime);
                                        }
                                    } else {
                                        $j_datetime[$key]["c_out"] = $milli_datetime;
                                        $roster["json_datetime"] = json_encode($j_datetime);
                                    }
                                }
                            } else if ($data["status"] == "C/In") {
                                if ($rec == $suitable_obj_in) {
                                    if ($j_datetime[$key]["c_in"]) {
                                        if ($j_datetime[$key]["c_in"] > $milli_datetime) {
                                            $j_datetime[$key]["c_in"] = $milli_datetime;
                                            $roster["json_datetime"] = json_encode($j_datetime);
                                        }
                                    } else {
                                        $j_datetime[$key]["c_in"] = $milli_datetime;
                                        $roster["json_datetime"] = json_encode($j_datetime);
                                    }
                                }
                            }
                            //    dd($j_datetime); 
                        }
                        // dd($roster["json_datetime"]);

                        
                    }
                    else{
                        if (isset($roster_pre->mix)) {

                            if ($roster_pre["mix"] == 1) {
                                $is_functional_0 = true;  
                            }
                            //              if(false && $roster_pre->mix=='1' ){
                            // print($milli_datetime);
                            // print_r($data);
                            // dd($roster_pre);
                            //              }
                        }
                    }
                    if (($is_functional_0 && count($j_datetime) <= 1)  && $data["status"] == "C/Out") {
                        $roster = $roster_pre;
                        // $roster->c_out = $datetimes;
                        $j_datetime_p = json_decode($roster_pre->json_datetime, true);
                        if (count($j_datetime_p) > 0) {
                            $j_datetime_p[count($j_datetime_p) - 1]["c_out"] = $milli_datetime;

                            $roster["json_datetime"] = json_encode($j_datetime_p);
                            if ($roster) {
                                $roster->save();
                            }
                        }
                    }
                    // if ($roster["emp_id"] == "76") {
                    //     echo (json_encode($roster));
                    // }

                    if ($roster) {
                        $roster->save();
                    }
                }
            }
            // if ($data["status"] == "C/In") {
            // } else if ($data["status"] == "C/Out") {
            //     if ($roster_pre->mix == 0) {
            //         $roster = $roster_present;
            //     } else if ($roster_pre->mix == 1) {
            //         $roster = $roster_pre;
            //     }
            // }
        }
        // dd($header);
        $escapedHeader = [];
    }
    public function get_allshift(Request $request)
    {
        $shifts = shift::where('type', $request['type'])->orWhere('type', '*')->orderBy('type', 'desc')->get();
        return response()->json(
            [
                "data" => $shifts
            ],
            200
        );
    }
    public function insert_shift(Request $request)
    {
        $shift = new shift();
        $shift['type'] = $request["type"];
        $shift["nama"] = '-';
        $shift["code"] = '-';
        $shift["jam_masuk"] = "00:00:00";
        $shift["jam_keluar"] = "00:00:00";
        $shift["user"] = $request["user_id"];
        $shift->save();
    }
    public function delete_shift(Request $request)
    {
        $id = $request["id"];
        $shift = shift::findOrFail($id);
        $shift->delete();
    }
    public function update_shift(Request $request)
    {
        $id = $request["id"];
        $shift = shift::findOrFail($id);
        $input = $request->except(['updated_at', 'created_at', 'timestamp_in', 'timestamp_out']);
        $shift->fill($input)->save();
    }
    public function get_roster(Request $request)
    {
        $bulan = $request["bulan"];
        $tahun = $request["tahun"];
        $dep = $request["dep"];

        $today = $tahun . '-' . $bulan . '-' . '01';
        $tanggal_pre = date('Y-m-d', strtotime($today . ' - 1 days'));
        // dd($tanggal_pre);
        // dd($bulan);
        $roster_previousday = roster::where('tanggal', $tanggal_pre)->get();
        // if($dep=='ka_unit'){
        //     $roster = roster::where([['bulan', $bulan], ['tahun', $tahun],['roster_dep', $dep]])->get();

        //     }
        // else{
        $roster = roster::where([['bulan', $bulan], ['tahun', $tahun], ['roster_dep', $dep]])->get();
        // }
        return response()->json(
            [
                "data" => $roster,
                "preday_data" => $roster_previousday
            ],
            200
        );
    }
    public function insert_roster(Request $request)
    {
        // dd($request);
        $emp_id = $request["emp_id"];
        $bulan = $request["bulan"];
        $tahun = $request["tahun"];
        $number = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);
        for ($i = 1; $i <= $number; $i++) {
            $request["hari"] = $i;
            $request["tanggal"] = '' . $tahun . '-' . sprintf("%02d", $bulan) . '-' . sprintf("%02d", $i);

            $roster = new roster();
            $roster->fill($request->all());
            // dd($request);
            // dd($roster);
            $roster->save();
        }
        sleep(1);
    }
    public function update_roster(Request $request)
    {
        $bulan = $request["bulan"];
        $tahun = $request["tahun"];
        $hari = $request["hari"];
        $emp_id = $request["emp_id"];
        // dd($request);
        $roster = roster::where([['bulan', $bulan], ['hari', $hari], ['tahun', $tahun], ['emp_id', $emp_id]])->first();
        // print_r($roster);
        // dd($roster);
        $roster_date = $roster->tanggal;
        if ($request["jam_masuk1"]) {
            $roster->jam_masuk = date('Y-m-d H:i:s', $request["jam_masuk1"] / 1000);
        }
        // if(strtotime($request["jam_keluar"])<=strtotime($request["jam_masuk"])){

        //     $roster_date=date('Y-m-d', strtotime($roster_date. ' + 1 days'));
        // }
        if ($request["jam_keluar1"]) {
            $roster->jam_keluar = date('Y-m-d  H:i:s', $request["jam_keluar1"] / 1000);
        }
        $roster->type_shift = $request["type_shift"];
        $roster->jam_masuk1 = $request["jam_masuk1"];
        $roster->jam_keluar1 = $request["jam_keluar1"];
        $roster->shift = $request["shift"];
        $roster->mix = $request["mix"];
        $roster->json_datetime = $request["json_datetime"];
        // dd($roster);
        $roster->save();
    }
}
