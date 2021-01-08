<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\lib_pdf;
use Illuminate\Support\Facades\DB;


class libcontroller extends Controller
{
    //
     public function all(Request $request)
    {
        $name=$request["name"];
       $list_lib=lib_pdf::where('name', 'like', '%' . $name . '%')->paginate(12);//->paginate(15);
     
       return response()->json(
           [
               "list_lib" => $list_lib
           ],200);
       
    }
    public function get_laporan(Request $request)
    {
        $date1=$request['start_date'];
        $date2=$request['end_date'];

       $result= DB::select( DB::raw("SELECT Count([PerawatanSQL].[dbo].[Visit].ID) AS CountOfID, [MasterdataSQL].[dbo].Doctors.First_Name,[MasterdataSQL].[dbo].Doctors.Spesialis FROM [PerawatanSQL].[dbo].[Visit] INNER JOIN [MasterdataSQL].[dbo].Doctors ON [PerawatanSQL].[dbo].[Visit].Doctor_1 = [MasterdataSQL].[dbo].Doctors.ID
       WHERE (([PerawatanSQL].[dbo].[Visit].Batal!=1) and (( CONVERT(date, [PerawatanSQL].[dbo].[Visit].[TglKunjungan])  BETWEEN '".$date1."' AND '".$date2."')) AND (([PerawatanSQL].[dbo].[Visit].Doctor_1) Is Not Null) and [MasterdataSQL].[dbo].Doctors.First_Name is not null) 
       GROUP BY [PerawatanSQL].[dbo].[Visit].Doctor_1, [MasterdataSQL].[dbo].Doctors.First_Name,[MasterdataSQL].[dbo].Doctors.Spesialis
       ORDER BY Count([PerawatanSQL].[dbo].[Visit].ID) DESC") );

    $result2= DB::select( DB::raw("SELECT Count([PerawatanSQL].[dbo].[Visit].ID) AS CountOfID, [MasterdataSQL].[dbo].Admision.Religion,[MasterdataSQL].[dbo].Admision.Education FROM [PerawatanSQL].[dbo].[Visit] INNER JOIN [MasterdataSQL].[dbo].Admision ON [PerawatanSQL].[dbo].[Visit].NoMR = [MasterdataSQL].[dbo].Admision.NoMR
       WHERE (([PerawatanSQL].[dbo].[Visit].Batal!=1) and ((CONVERT(date, [PerawatanSQL].[dbo].[Visit].[TglKunjungan]) BETWEEN '".$date1."' AND '".$date2."')) )
       GROUP BY [MasterdataSQL].[dbo].[Admision].Religion, [MasterdataSQL].[dbo].Admision.Education
       ORDER BY Count([PerawatanSQL].[dbo].[Visit].ID) DESC") );



$result3= DB::select( DB::raw("SELECT Count([RawatInapSQL].[dbo].[Inpatient].InpatientID) AS CountOfID,  [MasterdataSQL].[dbo].Doctors.First_Name, [MasterdataSQL].[dbo].Doctors.Spesialis FROM [RawatInapSQL].[dbo].[Inpatient] INNER JOIN  [MasterdataSQL].[dbo].Doctors ON [RawatInapSQL].[dbo].[Inpatient].drPenerima =  [MasterdataSQL].[dbo].Doctors.ID
WHERE (([RawatInapSQL].[dbo].[Inpatient].[StartDate] BETWEEN '".$date1."' AND '".$date2."') AND (([RawatInapSQL].[dbo].[Inpatient].drPenerima) Is Not Null))
GROUP BY [RawatInapSQL].[dbo].[Inpatient].drPenerima,  [MasterdataSQL].[dbo].Doctors.First_Name, [MasterdataSQL].[dbo].Doctors.Spesialis
ORDER BY Count([RawatInapSQL].[dbo].[Inpatient].InpatientID) DESC") );

$result4= DB::select( DB::raw("SELECT Count([RawatInapSQL].[dbo].[Inpatient].InpatientID) AS CountOfID, [MasterdataSQL].[dbo].Admision.Religion,[MasterdataSQL].[dbo].Admision.Education FROM [RawatInapSQL].[dbo].[Inpatient] INNER JOIN [MasterdataSQL].[dbo].Admision ON [RawatInapSQL].[dbo].[Inpatient].NoMR = [MasterdataSQL].[dbo].Admision.NoMR
WHERE ((([RawatInapSQL].[dbo].[Inpatient].[StartDate] BETWEEN '".$date1."' AND '".$date2."')) )
GROUP BY [MasterdataSQL].[dbo].[Admision].Religion, [MasterdataSQL].[dbo].Admision.Education
ORDER BY Count([RawatInapSQL].[dbo].[Inpatient].InpatientID) DESC") );


         return response()->json(
            [
                "rj"=> $result,
         "rj_e"=>$result2,
        "ri"=> $result3,
        "ri_e"=>$result4
                
            ],200);

    }
    
}
