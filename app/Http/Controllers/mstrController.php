<?php

namespace App\Http\Controllers;

use App\models\Admision;
// use App\Http\Middleware\Admin;
use App\models\tarif_rj;
use App\models\token;
use App\models\roles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class mstrController extends Controller
{
    //
    // public function master_product_type()
    // {
    //     return response()->json(
    //         [
    //         ],
    //         200
    //     );
    // }
    // public function master_doctors()
    // {
    //     return response()->json(
    //         [
    //         ],
    //         200
    //     );
    // }
    public function master()
    {
        $result1 = DB::select(DB::raw("Select * from [MasterdataSQL].[dbo].Ref_diagnosis"));
        $result2 = DB::select(DB::raw("SELECT distinct ProductTypeID,[ProductType],UangR 
        FROM [Apotik_V1.1SQL].[dbo].[tblProductType] left join [Apotik_V1.1SQL].[dbo].[TblMasterUangR] on ProductTypeID=IdResep where UangRri is not null"));
        $result3 = DB::select(DB::raw("Select * from [MasterdataSQL].[dbo].Ref_procedures"));
        $result4 = DB::select(DB::raw("Select * from [MasterdataSQL].[dbo].Doctors inner join (select ID p_id,codeBPJS from [MasterdataSQL].[dbo].mstrUnitPerwatan) t2 on [MasterdataSQL].[dbo].Doctors.GroupPerawatan = t2.p_id where First_Name!='' and Active=1 order by Spesialis desc,First_Name"));
        $result5 = tarif_rj::all();
        //$result6=$this->get_token_from_apirstb();
        return response()->json(
            [
                "diag" => $result1,
                "proc" => $result3,
                "product_type" => $result2,
                "tarif_rj" => $result5,
                "doctors" => $result4,
                // "rstb_api_token"=>$result6
            ],
            200
        );
    }
    // public function master_proc()
    // {
    //     return response()->json(
    //         [
    //         ],
    //         200
    //     );
    // }
    // public function master_tarif_rj()
    // {
    //     return response()->json(
    //         [
    //         ],
    //         200
    //     );
    // }
    public function get_roles(Request $request)
    {
        $data=roles::all();
        return response()->json(
            [
                "data" => $data
            ],
            200
        );

    }
    public function update_alergi(Request $request)
    {
        // $admision_id=Admision::where('NoMR',$request["nomor"])->get();
        // $admision = Admision::findOrFail($admision_id["ID"]);

        // //dd($admision);
        // $admision_id["Alergi"]=$request["Alergi"];
        // $input=$admision_id->all();
        // $admision->fill($input)->save();
        $result = DB::update(DB::raw("
        UPDATE [MasterdataSQL].[dbo].[Admision]
   SET [Alergi] = '" . $request['Alergi'] . "'
 WHERE NoMR = '" . $request['nomor'] . "'"));
    }
    public function search_pasien(Request $request)
    {
        $search = $request["search"];
        $result = Admision::where([["NoMR", "like", '%' . $search . '%']])->orWhere([["NoPesertaBPJS", "like", '%' . $search . '%']])->orWhere([["PatientName", "like", '%' . $search . '%']])->take(3)->get(['PatientName', 'NoMR', 'NoPesertaBPJS', 'Date_of_birth']);
        return response()->json(
            [
                "data" => $result
            ],
            200
        );
    }
}
