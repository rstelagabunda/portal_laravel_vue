<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Database\Schema;
class ppiController extends Controller
{
    public function get_reports(){
        $data = DB::select(DB::raw("SELECT * from data order by tgl asc"));
        return response()->json(
            [
                "list_ppi" => $data
            ],200);
    }
    public function get_ido(){
        $data = DB::select(DB::raw("SELECT [RawatInapSQL].[dbo].Inpatient.NoRegRI, [RawatInapSQL].[dbo].Inpatient.StartDate, [RawatInapSQL].[dbo].v_leporan_ri_diag.code_list, [RawatInapSQL].[dbo].v_leporan_ri_diag.desc_list, [MasterdataSQL].[dbo].Admision.PatientName, [MasterdataSQL].[dbo].Admision.Gander
        FROM ([RawatInapSQL].[dbo].Inpatient INNER JOIN [RawatInapSQL].[dbo].v_leporan_ri_diag ON [RawatInapSQL].[dbo].Inpatient.NoRegRI = [RawatInapSQL].[dbo].v_leporan_ri_diag.NoRegistrasi) INNER JOIN [MasterdataSQL].[dbo].Admision ON [RawatInapSQL].[dbo].Inpatient.NoMR = [MasterdataSQL].[dbo].Admision.NoMR
        WHERE ((([RawatInapSQL].[dbo].v_leporan_ri_diag.code_list) Like '%o90%')) order by [RawatInapSQL].[dbo].Inpatient.StartDate desc
        "));
        return response()->json(
            [
                "ido" => $data
            ],200);
    }
}
