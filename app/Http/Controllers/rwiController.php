<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\models\inpatient;
use App\models\Admision;
class rwiController extends Controller
{
    public function get_allinap(Request $requst)
    {
        $result=
        DB::connection('in')->table('Inpatient')
        ->join('MasterdataSQl.dbo.Admision','inpatient.NoMR', '=', 'Admision.NoMR')
        ->where([['StatusID','!=',4]])
        ->get(['Admision.NoMR','Admision.PatientName','StartDate','NoRegRI']);
        // dd($result);
        return response()->json(
            [
                "list_inap" => $result
            ],
            200
        );
    }
}
