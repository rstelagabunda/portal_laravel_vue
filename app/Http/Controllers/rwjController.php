<?php

namespace App\Http\Controllers;

use App\models\Diagnosa_INACBG;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\models\EMR_RWJ;
use App\models\operations;
use App\models\Procedur_INACBG;
use App\models\visit;
use App\models\visit_detail;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request as req;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class rwjController extends Controller
{
    //
    public static function convert_from_latin1_to_utf8_recursively($dat)
    {
        if (is_string($dat)) {
            return utf8_encode($dat);
        } elseif (is_array($dat)) {
            $ret = [];
            foreach ($dat as $i => $d) $ret[$i] = self::convert_from_latin1_to_utf8_recursively($d);

            return $ret;
        } elseif (is_object($dat)) {
            foreach ($dat as $i => $d) $dat->$i = self::convert_from_latin1_to_utf8_recursively($d);

            return $dat;
        } else {
            return $dat;
        }
    }
    public function get_imagesfromuploads(Request $request)
    {
        $images = [];
        for ($i = 0; $i < count($request["data"]); $i++) {
            $images[$i]["img_data"] = base64_encode(Storage::disk('ftp')->get('/DO NOT TOUCH/EMR_SIMRS/' . $request["data"][$i]));
            $images[$i]["file_name"] = $request["data"][$i];
        }
        //dd(json_decode((string)$images,true));
        return response()->json(
            $this->convert_from_latin1_to_utf8_recursively($images),
            200
        );
    }
    public function delete_imagesfromuploads(Request $request)
    {
        Storage::disk('ftp')->delete('/DO NOT TOUCH/EMR_SIMRS/' . $request["filename"]);
    }
    public function upload_emrdocs(Request $request)
    {
        $image_data = $request["image_data"];
        // dd($request);
        $exploded = explode(',', $image_data);
        $decoded = base64_decode($exploded[1]);
        $exp2 = explode('/', $request["type"]);
        $filetype = $exp2[1];
        $filename = $request["noreg"] . "_" . $request["count"] . "_" . Str::random(5) . '.' . $filetype;
        // $location=Storage::disk('ftp')->getDriver()->getAdapter()->getPathPrefix();
        // dd($location);
        Storage::disk('ftp')->put('/DO NOT TOUCH/EMR_SIMRS/' . $filename, $decoded);
        return response()->json(
            [
                "filename" => $filename
            ],
            200
        );
        //     $path = $_SERVER['DOCUMENT_ROOT'].'/uploads/'.$filename;
        //    // print $path;
        //     file_put_contents($path,$decoded);
        // $request["skhu"]=$filename;

    }
    public function get_allvisit(Request $request)
    {
        $name = $request['search'];
        $extra = "";
        $extra2 = "";
        if (strlen($name) > 2) {
        }
        if ($request['dok'] != null) {
            $extra = " AND ([MasterdataSQL].[dbo].[Doctors].ID = " . $request['dok'] . ") ";
        }
        if ($request->exists('tgl')) {
            $extra = $extra . " AND (CAST([PerawatanSQL].[dbo].[Visit].TglKunjungan as Date) = '" . $request['tgl'] . "') ";
        }

        $result = DB::select(DB::raw("
        SELECT  CAST([PerawatanSQL].[dbo].[Visit].TglKunjungan as Date) TglKunjungan, [PerawatanSQL].[dbo].[Visit].NoRegistrasi,  [PerawatanSQL].[dbo].[Visit Status].[Status Name],[MasterdataSQL].[dbo].[Admision].ID, [MasterdataSQL].[dbo].[Admision].PatientName, [MasterdataSQL].[dbo].[Doctors].First_Name, [MasterdataSQL].[dbo].[Doctors].Last_Name,[MasterdataSQL].[dbo].[Doctors].foto, [MasterdataSQL].[dbo].[Doctors].Spesialis,[MasterdataSQL].[dbo].[MstrUnitPerwatan].NamaUnit,[MasterdataSQL].[dbo].[MstrUnitPerwatan].codeBPJS, [PerawatanSQL].[dbo].[Visit].NoMR, [PerawatanSQL].[dbo].[Visit].Antrian, [PerawatanSQL].[dbo].[Visit].Selesai
        FROM ((( [PerawatanSQL].[dbo].[Visit] LEFT JOIN [MasterdataSQL].[dbo].[Admision] ON [PerawatanSQL].[dbo].[Visit].NoMR = [MasterdataSQL].[dbo].[Admision].NoMR) LEFT JOIN  [PerawatanSQL].[dbo].[Visit Status] ON [PerawatanSQL].[dbo].[Visit].[Status ID] =  [PerawatanSQL].[dbo].[Visit Status].[Status ID]) LEFT JOIN [MasterdataSQL].[dbo].[Doctors] ON [PerawatanSQL].[dbo].[Visit].Doctor_1 = [MasterdataSQL].[dbo].[Doctors].ID) LEFT JOIN [MasterdataSQL].[dbo].[MstrUnitPerwatan] ON [PerawatanSQL].[dbo].[Visit].Unit = [MasterdataSQL].[dbo].[MstrUnitPerwatan].ID
        WHERE ((([MasterdataSQL].[dbo].[Admision].PatientName is not null) and ( [PerawatanSQL].[dbo].[Visit].[Status ID])<4) " . $extra2 . " " . $extra . " AND (( [PerawatanSQL].[dbo].[Visit].Unit) Not In (9,10))) order by CAST([PerawatanSQL].[dbo].[Visit].TglKunjungan as Date) desc, [PerawatanSQL].[dbo].[Visit].Antrian asc,[PerawatanSQL].[dbo].[Visit].[Status ID] desc;
       "));
        return response()->json(
            [
                "list_visit" => $result
            ],
            200
        );
    }
    public function get_pasiendata_noreg(Request $request)
    {
        $name = $request['noreg'];
        $extra = "";
        $result = DB::select(DB::raw("
       
        SELECT [MasterdataSQL].[dbo].[Admision].NoMR,[MasterdataSQL].[dbo].[Admision].NoPesertaBPJS,[MasterdataSQL].[dbo].[Admision].Religion,[MasterdataSQL].[dbo].[Admision].[Country/Region],[PerawatanSQL].[dbo].[Visit].Antrian,[MasterdataSQL].[dbo].[Admision].ID,[MasterdataSQL].[dbo].[Admision].Alergi,[PerawatanSQL].[dbo].[Visit].PatientType, [PerawatanSQL].[dbo].[Visit].Doctor_1, [MasterdataSQL].[dbo].[Admision].PatientName,[MasterdataSQL].[dbo].[MstrUnitPerwatan].codeBPJS, [MasterdataSQL].[dbo].[Admision].Gander, [MasterdataSQL].[dbo].[Admision].Date_of_birth,[MasterdataSQL].[dbo].[doctors].code_mjkn,[MasterdataSQL].[dbo].[doctors].foto,[MasterdataSQL].[dbo].[doctors].First_Name,[MasterdataSQL].[dbo].[doctors].GroupPerawatan, [PerawatanSQL].[dbo].[Visit].TglKunjungan, [PerawatanSQL].[dbo].[Visit].NoRegistrasi,[PerawatanSQL].[dbo].[Visit].ID visit_id
        FROM [PerawatanSQL].[dbo].[Visit]  LEFT JOIN [MasterdataSQL].[dbo].[Admision] ON [PerawatanSQL].[dbo].[Visit].NoMR = [MasterdataSQL].[dbo].[Admision].NoMR LEFT JOIN [MasterdataSQL].[dbo].[doctors] ON [PerawatanSQL].[dbo].[Visit].Doctor_1 = [MasterdataSQL].[dbo].[doctors].ID LEFT JOIN [MasterdataSQL].[dbo].[MstrUnitPerwatan] ON [MasterdataSQL].[dbO].[doctors].GroupPerawatan = [MasterdataSQL].[dbo].[MstrUnitPerwatan].ID
        WHERE ((([PerawatanSQL].[dbo].[Visit].NoRegistrasi)='" . $name . "'));       
       "));
        return response()->json(
            [
                "data_pasien" => $result
            ],
            200
        );
    }
    public function get_all_emr(Request $request)
    {
        $nomr = $request['mr'];
        $reg = $request['no'];
        $emr = EMR_RWJ::where(["NoRegistrasi" => $reg])->first();
        if ($emr == null) {
            $this->insert_emr($request);
        }
        $extra = "";
        // $result=EMR_RWJ::paginate(15);
        $result = DB::select(DB::raw("
       
        SELECT [PerawatanSQL].[dbo].[EMR_RWJ].ID,[PerawatanSQL].[dbo].[Visit].Doctor_1,[PerawatanSQL].[dbo].[EMR_RWJ].json_foto, [PerawatanSQL].[dbo].[EMR_RWJ].Tgl, [PerawatanSQL].[dbo].[EMR_RWJ].NoMR, [PerawatanSQL].[dbo].[EMR_RWJ].NamaPasien, [PerawatanSQL].[dbo].[EMR_RWJ].NoRegistrasi, [PerawatanSQL].[dbo].[EMR_RWJ].TekananDarah, [PerawatanSQL].[dbo].[EMR_RWJ].FrekuensiNadi, [PerawatanSQL].[dbo].[EMR_RWJ].Suhu, [PerawatanSQL].[dbo].[EMR_RWJ].FrekuensiNafas, [PerawatanSQL].[dbo].[EMR_RWJ].SkorNyeri, [PerawatanSQL].[dbo].[EMR_RWJ].ResikoJatuh, [PerawatanSQL].[dbo].[EMR_RWJ].BeratBadan, [PerawatanSQL].[dbo].[EMR_RWJ].TinggiBadan, [PerawatanSQL].[dbo].[EMR_RWJ].LingkarKepala, [PerawatanSQL].[dbo].[EMR_RWJ].AlatBantu, [PerawatanSQL].[dbo].[EMR_RWJ].Prothesa, [PerawatanSQL].[dbo].[EMR_RWJ].CacatTubuh, [PerawatanSQL].[dbo].[EMR_RWJ].ADL_mandiri, [PerawatanSQL].[dbo].[EMR_RWJ].ADL_dibantu, [PerawatanSQL].[dbo].[EMR_RWJ].RiwayatJatuh, [PerawatanSQL].[dbo].[EMR_RWJ].NamaPerawat, [PerawatanSQL].[dbo].[EMR_RWJ].StatusPsikologis, [PerawatanSQL].[dbo].[EMR_RWJ].SP_Depresi, [PerawatanSQL].[dbo].[EMR_RWJ].SP_Takut, [PerawatanSQL].[dbo].[EMR_RWJ].SP_Agresif, [PerawatanSQL].[dbo].[EMR_RWJ].SP_Melukaidiri, [PerawatanSQL].[dbo].[EMR_RWJ].HambatanEdukasi, [PerawatanSQL].[dbo].[EMR_RWJ].HE_bahasa, [PerawatanSQL].[dbo].[EMR_RWJ].HE_cacat, [PerawatanSQL].[dbo].[EMR_RWJ].KeluhanPasien, [PerawatanSQL].[dbo].[EMR_RWJ].S_Anamnesa, [PerawatanSQL].[dbo].[EMR_RWJ].O_PemeriksaanFisik, [PerawatanSQL].[dbo].[EMR_RWJ].A_Diagnosa, [PerawatanSQL].[dbo].[EMR_RWJ].P_RencanaTatalaksana, [PerawatanSQL].[dbo].[EMR_RWJ].S_Anamnesa_j, [PerawatanSQL].[dbo].[EMR_RWJ].O_PemeriksaanFisik_j, [PerawatanSQL].[dbo].[EMR_RWJ].A_Diagnosa_j, [PerawatanSQL].[dbo].[EMR_RWJ].P_RencanaTatalaksana_j, [PerawatanSQL].[dbo].[EMR_RWJ].DiagnosaUtama, [PerawatanSQL].[dbo].[EMR_RWJ].DiagnosaSkunder1, [PerawatanSQL].[dbo].[EMR_RWJ].DiagnosaSkunder2, [PerawatanSQL].[dbo].[EMR_RWJ].DiagnosaSkunder3, [PerawatanSQL].[dbo].[EMR_RWJ].StatusGizi, [PerawatanSQL].[dbo].[EMR_RWJ].AlasanRawatInap, [PerawatanSQL].[dbo].[EMR_RWJ].Konsul, [PerawatanSQL].[dbo].[EMR_RWJ].AlasanDirujuk, [PerawatanSQL].[dbo].[EMR_RWJ].Edukasi, [PerawatanSQL].[dbo].[EMR_RWJ].IDdokter, [PerawatanSQL].[dbo].[EMR_RWJ].Dokter, [PerawatanSQL].[dbo].[EMR_RWJ].foto, [PerawatanSQL].[dbo].[EMR_RWJ].CaraPulang, [MasterdataSQL].[dbo].Doctors.First_Name
        FROM ([PerawatanSQL].[dbo].[EMR_RWJ] INNER JOIN [PerawatanSQL].[dbo].[Visit] ON [PerawatanSQL].[dbo].[EMR_RWJ].NoRegistrasi = [PerawatanSQL].[dbo].[Visit].NoRegistrasi) INNER JOIN [MasterdataSQL].[dbo].Doctors ON [PerawatanSQL].[dbo].[Visit].Doctor_1 = [MasterdataSQL].[dbo].Doctors.ID
        WHERE ((([PerawatanSQL].[dbo].[EMR_RWJ].NoMR)='" . $nomr . "' )) order by CAST([PerawatanSQL].[dbo].[EMR_RWJ].Tgl as Date) desc;       
        "));
        // if(count($result)==0){
        //     $this->insert_emr($request);
        // }
        return response()->json(
            [
                "list_emr" => $result,
                'inserted' => count($result) > 0 ? true : false
            ],
            200
        );
    }
    public function insert_emr(Request $request)
    {
        $nama = $request['nama'];
        $no = $request['no'];
        $mr = $request['mr'];
        $tgl = $request['tgl'];
        $extra = "";
        $result = DB::insert(DB::raw("
            
            INSERT INTO [PerawatanSQL].[dbo].[EMR_RWJ]
           ([Tgl]
           ,[NoMR]
           ,[NamaPasien]
           ,[NoRegistrasi]
           )
     VALUES
           (
		   '" . $tgl . "','" . $mr . "','" . $nama . "','" . $no . "')            
            "));
    }
    public function update_emr(Request $request)
    {
        $id = $request["ID"];
        $emr = EMR_RWJ::findOrFail($id);
        if ($request["type"] == "Batal") {
            $emr->delete();
        } else {
            $input = $request->except(['First_Name', 'foto', 'Doctor_1']);
            $emr->fill($input)->save();
        }
    }
    public function get_diagnosa_given_reg(Request $request)
    {
        $id = $request['no'];
        $list_diag  = Diagnosa_INACBG::where('NoRegistrasi', $id)->get();
        // dd($speakUpdate);
        return response()->json(
            [
                "list_diag" => $list_diag
            ],
            200
        );
        //$result=EMR_RWJ::find($id)->update(Request::all());
    }
    public function get_procedure_given_reg(Request $request)
    {
        $id = $request['no'];
        $list_procedure  = Procedur_INACBG::where('NoRegistrasi', $id)->get();
        // dd($speakUpdate);
        return response()->json(
            [
                "list_procedure" => $list_procedure
            ],
            200
        );
    }
    public function insert_diagnosa_given_reg(Request $request)
    {
        $diag = new Diagnosa_INACBG();
        $input = $request->all();
        $diag->fill($input)->save();
    }
    public function insert_procedure_given_reg(Request $request)
    {
        $proc = new Procedur_INACBG();
        $input = $request->all();
        $proc->fill($input)->save();
    }
    public function delete_diagnosa_given_reg(Request $request)
    {
        $id = $request['id'];
        $diag  = Diagnosa_INACBG::findOrFail($id);
        $diag->delete();
    }
    public function delete_procedure_given_reg(Request $request)
    {
        $id = $request['id'];
        $proc  = Procedur_INACBG::findOrFail($id);
        //dd($proc);
        $proc->delete();
    }
    public function delete_rwj_tindakan(Request $request)
    {
        $id = $request['id'];
        $diag  = visit_detail::findOrFail($id);
        $diag->delete();
    }
    public function get_rwj_tindakan(Request $request)
    {
        $id = $request['no'];
        $list  = visit_detail::where('NoRegistrasi', $id)->get();
        // dd($speakUpdate);
        return response()->json(
            [
                "list_visit_details" => $list
            ],
            200
        );
    }
    public function insert_rwj_tindakan(Request $request)
    {
        $proc = new visit_detail();
        $request["JamPemeriksaan"] = now();
        $input = $request->all();
        $proc->fill($input)->save();
    }
    public function update_visit(Request $request)
    {
        $id = $request["visit_id"];
        $visit = visit::findOrFail($id);
        //dd($visit);

        if ($request["type"] == "Selesai") {
            $visit["Status ID"] = 2;
            $visit["Selesai"] = 1;
            $visit->save();
        }
        // dd($request["type"]);
        if ($request["type"] == "Konsul") {
            // dd('masuk');
            $visit["Unit"] = $request["newunit"];
            $visit["Doctor_1"] = $request["newdocid"];
            //dd($visit);

            $visit->save();
        }
        if ($request["type"] == "Batal") {
            $visit["Status ID"] = 4;
            $visit["Batal"] = 1;
            $visit["Keterangan"] = "batal dari asistan dokter " . $request["name"];

            $visit["tglBatal"] = now();
            $visit->save();
        }
        //dd($visit);

    }
public function get_operation(Request $request)
{
    $reg=$request["reg"];
    if($reg){
        $operations=operations::where('reg',$reg)->first();
    }
    else{
    $operations=operations::all();
    }
    return response()->json(
        [
            "operations" => $operations
        ],
        200
    );
}
public function save_operation(Request $request)
{
    $op=null;
    if($request['id']){
        $op= operations::findOrFail($request['id']);
    }if(!$op){
    $op = new operations();
    }
    $input = $request->all();
    $id=$op->fill($input)->save();
    return response()->json(
        [
            "operation" => $op
        ],
        200
    );

}

    // public function batal_visit(Request $request)
    // {
    //     $id = $request["visit_id"];
    //     $visit = visit::findOrFail($id);
    //     //dd($visit);

    //     if ($request["type"] == "Selesai") {
    //         $visit["Status ID"] = 2;
    //         $visit["Selesai"] = 1;
    //         $visit->save();
    //     }
    //     // dd($request["type"]);
    //     if ($request["type"] == "Konsul") {
    //         // dd('masuk');
    //         $visit["Unit"] = $request["newunit"];
    //         $visit["Doctor_1"] = $request["newdocid"];
    //         //dd($visit);

    //         $visit->save();
    //     }
    //     //dd($visit);

    // }

}
