<?php

namespace App\Http\Controllers;

use App\models\inpatient;
use App\models\inv_trans;
use App\models\Order_details;
use App\models\Orders;
use App\models\tblOrderType;
use App\models\visit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class farmasiController extends Controller
{
    //
    public function get_total_resep(Request $request)
    {
        # code...
    }
    public function list_resep(Request $request)
    {
        $noreg = $request["noreg"];
        //echo $request;
        //echo $noreg;
        if (substr($noreg, 2) == 'RJ') {
            $rj = visit::where(['NoRegistrasi', $noreg])->first();
        } else if (substr($noreg, 2) == 'RI') {
            $ri = inpatient::where(['NoRegRI', $noreg])->first();
        }
        // $result = DB::select(DB::raw("SELECT [Apotik_V1.1SQL].[dbo].[Orders].NoResep,[Apotik_V1.1SQL].[dbo].[Orders].[Order ID],[Apotik_V1.1SQL].[dbo].[Orders].NoUrut FROM [Apotik_V1.1SQL].[dbo].[Orders]   WHERE ((([Apotik_V1.1SQL].[dbo].[Orders].NoRegistrasi)='" . $noreg . "'))"));
        $result= Orders::select('NoResep','Order ID','NoUrut','TypeTransaction')->where('NoRegistrasi',$noreg)->get();
        return response()->json(
            [
                "list_resep_per_pasien" => $result
            ],
            200
        );
    }
    public function notification_list(Request $request)
    {
        $result = DB::select(DB::raw("
       
        SELECT top 40 [Apotik_V1.1SQL].[dbo].employees.[First Name],[Apotik_V1.1SQL].[dbo].Orders.NoResep, [Apotik_V1.1SQL].[dbo].Orders.NoUrut, [Apotik_V1.1SQL].[dbo].Orders.NoRegistrasi, [Apotik_V1.1SQL].[dbo].Orders.[Customer ID] as NoMR, [Apotik_V1.1SQL].[dbo].[Order Details].[Status ID], [Apotik_V1.1SQL].[dbo].Orders.tglResep as tglresep
        FROM ([Apotik_V1.1SQL].[dbo].Orders INNER JOIN [Apotik_V1.1SQL].[dbo].[Order Details] ON [Apotik_V1.1SQL].[dbo].Orders.[Order ID] = [Apotik_V1.1SQL].[dbo].[Order Details].[Order ID]) INNER JOIN [Apotik_V1.1SQL].[dbo].employees ON [Apotik_V1.1SQL].[dbo].Orders.[Employee ID] = [Apotik_V1.1SQL].[dbo].employees.ID
        WHERE ((([Apotik_V1.1SQL].[dbo].Orders.NoRegistrasi) Is Not Null) AND (([Apotik_V1.1SQL].[dbo].[Order Details].[Status ID]) In (0,1)) AND (([Apotik_V1.1SQL].[dbo].Orders.tglResep)>'2020-01-01')) group by [Apotik_V1.1SQL].[dbo].Orders.NoRegistrasi,[Apotik_V1.1SQL].[dbo].employees.[First Name], [Apotik_V1.1SQL].[dbo].Orders.NoUrut,[Apotik_V1.1SQL].[dbo].Orders.NoResep,[Apotik_V1.1SQL].[dbo].Orders.[Customer ID], [Apotik_V1.1SQL].[dbo].[Order Details].[Status ID], [Apotik_V1.1SQL].[dbo].Orders.tglResep order by [Apotik_V1.1SQL].[dbo].Orders.tglResep desc;       
       "));
        return response()->json(
            [
                "data" => $result
            ],
            200
        );
    }
    public function pasien_details(Request $request)
    {
        $type = $request["type"];
        $reg = $request["noreg"];
        if ($type == 0) {
            $result = DB::select(DB::raw("
       
        SELECT [MasterdataSQL].[dbo].[Admision].NoMR,[PerawatanSQL].[dbo].[Visit].PatientType,[MasterdataSQL].[dbo].[Admision].ID customer, [PerawatanSQL].[dbo].[Visit].Doctor_1, [MasterdataSQL].[dbo].[Admision].PatientName, [MasterdataSQL].[dbo].[Admision].Gander, [MasterdataSQL].[dbo].[Admision].Date_of_birth,[MasterdataSQL].[dbo].[doctors].foto,[MasterdataSQL].[dbo].[doctors].First_Name,[MasterdataSQL].[dbo].[doctors].ID doc_id, [PerawatanSQL].[dbo].[Visit].NoRegistrasi
         FROM [PerawatanSQL].[dbo].[Visit]  LEFT JOIN [MasterdataSQL].[dbo].[Admision] ON [PerawatanSQL].[dbo].[Visit].NoMR = [MasterdataSQL].[dbo].[Admision].NoMR LEFT JOIN [MasterdataSQL].[dbo].[doctors] ON [PerawatanSQL].[dbo].[Visit].Doctor_1 = [MasterdataSQL].[dbo].[doctors].ID LEFT JOIN [MasterdataSQL].[dbo].[MstrUnitPerwatan] ON [MasterdataSQL].[dbO].[doctors].GroupPerawatan = [MasterdataSQL].[dbo].[MstrUnitPerwatan].ID
        WHERE ((([PerawatanSQL].[dbo].[Visit].NoRegistrasi)='" . $reg . "' ));       
       "));
            return response()->json(
                [
                    "data_pasien" => $result
                ],
                200
            );
        } else if ($type == 1) {
            $result = DB::select(DB::raw("
       
        SELECT [MasterdataSQL].[dbo].[Admision].NoMR,[MasterdataSQL].[dbo].[Admision].ID customer,[RawatInapSQL].[dbo].[Inpatient].TypePatient PatientType, [MasterdataSQL].[dbo].[Admision].PatientName, [MasterdataSQL].[dbo].[Admision].Gander, [MasterdataSQL].[dbo].[Admision].Date_of_birth,[MasterdataSQL].[dbo].[doctors].foto,[MasterdataSQL].[dbo].[doctors].First_Name
         FROM [RawatInapSQL].[dbo].[Inpatient]  LEFT JOIN [MasterdataSQL].[dbo].[Admision] ON [RawatInapSQL].[dbo].[Inpatient].NoMR = [MasterdataSQL].[dbo].[Admision].NoMR LEFT JOIN [MasterdataSQL].[dbo].[doctors] ON [RawatInapSQL].[dbo].[Inpatient].drPenerima = [MasterdataSQL].[dbo].[doctors].ID LEFT JOIN [MasterdataSQL].[dbo].[MstrUnitPerwatan] ON [MasterdataSQL].[dbO].[doctors].GroupPerawatan = [MasterdataSQL].[dbo].[MstrUnitPerwatan].ID
        WHERE ((([RawatInapSQL].[dbo].[Inpatient].NoRegRI)='" . $reg . "'));       
       "));
            return response()->json(
                [
                    "data_pasien" => $result
                ],
                200
            );
        }
    }
    public function list_obat(Request $request)
    {
        //console.log($request);
        $noreg = $request["noreg"];
        $orderid = $request["orderid"];
        $ordertypes = tblOrderType::where('OrderID', $orderid)->get();
        // print_r($ordertypes);
        //echo $request;
        // echo $noreg;
        $result = DB::select(DB::raw("SELECT [Apotik_V1.1SQL].[dbo].[Orders].NoRegistrasi,[Apotik_V1.1SQL].[dbo].[tblOrderType].OrderTypeID, [Apotik_V1.1SQL].[dbo].[tblOrderType].OrderID, [Apotik_V1.1SQL].[dbo].[tblOrderType].ProductTypeID,[Apotik_V1.1SQL].[dbo].[Order Details].[Inventory ID],[Apotik_V1.1SQL].[dbo].[Order Details].[Status ID],[Apotik_V1.1SQL].[dbo].[Order Details].ID as detail_id,[Apotik_V1.1SQL].[dbo].[Order Details].Dosis, [Apotik_V1.1SQL].[dbo].[Order Details].Quantity,[Apotik_V1.1SQL].[dbo].[Products].ID ProductID,[Apotik_V1.1SQL].[dbo].[Products].[Expired],[Apotik_V1.1SQL].[dbo].[Products].[Unit Satuan], [Apotik_V1.1SQL].[dbo].[Products].[Product Name], [Apotik_V1.1SQL].[dbo].[Order Details].Quantity,[Apotik_V1.1SQL].[dbo].[Order Details].[Unit Price] [List Price],  [Apotik_V1.1SQL].[dbo].[Order Details].Signa,(CAST([Apotik_V1.1SQL].[dbo].[View_Inventory Apotik].[Qty Purchased] as INT)-CAST([Apotik_V1.1SQL].[dbo].[View_Inventory Apotik].[QtyReturCstr] as INT)-CAST([Apotik_V1.1SQL].[dbo].[View_Inventory Apotik].[Qty On Hold] as INT)-CAST([Apotik_V1.1SQL].[dbo].[View_Inventory Apotik].[Qty Sold] as INT)-CAST(COALESCE([Apotik_V1.1SQL].[dbo].[View_Inventory Apotik].[QtyMO],0) as INT)) maxi
        FROM (([Apotik_V1.1SQL].[dbo].[Orders] INNER JOIN [Apotik_V1.1SQL].[dbo].[Order Details] ON [Apotik_V1.1SQL].[dbo].[Orders].[Order ID] = [Apotik_V1.1SQL].[dbo].[Order Details].[Order ID]) INNER JOIN [Apotik_V1.1SQL].[dbo].[tblOrderType] ON [Apotik_V1.1SQL].[dbo].[Order Details].OrdertypeID = [Apotik_V1.1SQL].[dbo].[tblOrderType].OrderTypeID) INNER JOIN [Apotik_V1.1SQL].[dbo].[Products] ON [Apotik_V1.1SQL].[dbo].[Order Details].[Product ID] = [Apotik_V1.1SQL].[dbo].[Products].ID INNER JOIN [Apotik_V1.1SQL].[dbo].[View_Inventory Apotik] ON [Apotik_V1.1SQL].[dbo].[View_Inventory Apotik].[Product ID] = [Apotik_V1.1SQL].[dbo].[Products].ID
        WHERE ((([Apotik_V1.1SQL].[dbo].[Orders].NoResep)='" . $noreg . "')) order by detail_id asc"));
        // $result= DB::select( DB::raw("SELECT [Apotik_V1.1SQL].[dbo].[Orders].NoRegistrasi,[Apotik_V1.1SQL].[dbo].[tblOrderType].OrderTypeID, [Apotik_V1.1SQL].[dbo].[tblOrderType].OrderID, [Apotik_V1.1SQL].[dbo].[tblOrderType].ProductTypeID,[Apotik_V1.1SQL].[dbo].[Order Details].ID as detail_id,[Apotik_V1.1SQL].[dbo].[Order Details].Dosis, [Apotik_V1.1SQL].[dbo].[Order Details].Quantity,[Apotik_V1.1SQL].[dbo].[Products].ID ProductID,[Apotik_V1.1SQL].[dbo].[Products].[Expired],[Apotik_V1.1SQL].[dbo].[Products].[Unit Satuan], [Apotik_V1.1SQL].[dbo].[Products].[Product Name], [Apotik_V1.1SQL].[dbo].[Order Details].Quantity, [Apotik_V1.1SQL].[dbo].[Products].[List Price], [Apotik_V1.1SQL].[dbo].[Order Details].Signa
        //     FROM (([Apotik_V1.1SQL].[dbo].[Orders] INNER JOIN [Apotik_V1.1SQL].[dbo].[Order Details] ON [Apotik_V1.1SQL].[dbo].[Orders].[Order ID] = [Apotik_V1.1SQL].[dbo].[Order Details].[Order ID]) INNER JOIN [Apotik_V1.1SQL].[dbo].[tblOrderType] ON [Apotik_V1.1SQL].[dbo].[Order Details].OrdertypeID = [Apotik_V1.1SQL].[dbo].[tblOrderType].OrderTypeID) INNER JOIN [Apotik_V1.1SQL].[dbo].[Products] ON [Apotik_V1.1SQL].[dbo].[Order Details].[Product ID] = [Apotik_V1.1SQL].[dbo].[Products].ID
        //     WHERE ((([Apotik_V1.1SQL].[dbo].[Orders].NoResep)='".$noreg."'))") );
        return response()->json(
            [
                "eticket" => $result,
                "ordertypes" => $ordertypes
            ],
            200,
            ['Content-type' => 'application/json; charset=utf-8'],
            JSON_UNESCAPED_UNICODE
        );
    }
    public function allresep(Request $request)
    {
        $name = $request['nama'];
        $noreg=$request["noreg"];
        $nores=$request["resep"];
        $rj = $request['type'];
        $condition = '';
        $condition2='( ([Apotik_V1.1SQL].[dbo].[Orders].[Status ID])<2) and ';
        $orders=[];
        if (strtoupper($rj) == strtoupper('rj')) {
            $condition = "[Apotik_V1.1SQL].[dbo].[Orders].NoRegistrasi) NOT Like ('ri%')";
            if ($request["noreg"]) {
                $condition2='';
                $condition = "[Apotik_V1.1SQL].[dbo].[Orders].NoRegistrasi)  Like ('" . $request["noreg"] . "')";
                $orders=Orders::where('NoRegistrasi',$request["noreg"])->get();
            }
        } else if (strtoupper($rj) == 'RI'){
            $condition = "[Apotik_V1.1SQL].[dbo].[Orders].NoRegistrasi) Like ('ri%')";
            if ($request["noreg"]) {
                $condition2='';
                $orders=Orders::where('NoRegistrasi',$request["noreg"])->get();

                $condition = "[Apotik_V1.1SQL].[dbo].[Orders].NoRegistrasi) Like ('" . $request["noreg"] . "')";
            }
        }
        else if (strtoupper($rj) == 'B'){
            $condition = "[Apotik_V1.1SQL].[dbo].[Orders].NoResep) Like ('B%')";
            if ($request["resep"]) {
                $condition2='';
                $orders=Orders::where('NoResep',$request["resep"])->get();

                $condition = "[Apotik_V1.1SQL].[dbo].[Orders].NoResep) Like ('" . $request["resep"] . "')";
            }
        }
        // $result=DB::connection('farmasi')->table('Orders')
        // ->select('Order Details.Product ID','Products.Product Name','Order Details.Quantity')
        // ->join('Order Details', 'Orders.Order ID', '=', 'Order Details.Order ID')
        // ->join('Products', 'Order Details.Product ID', '=', 'Products.ID')
        // ->where('Orders.NoRegistrasi',$noreg)->get();
        // dd($orders);
        $result = DB::select(DB::raw("SELECT top 40 [Apotik_V1.1SQL].[dbo].[Orders].[Order ID], [Apotik_V1.1SQL].[dbo].[employees].[First Name],[Apotik_V1.1SQL].[dbo].[Orders].tglresep,[Apotik_V1.1SQL].[dbo].[Orders].TypeTransaction, [Apotik_V1.1SQL].[dbo].[employees].[Last Name],[masterdatasql].[dbo].[Admision].NoMR, [Apotik_V1.1SQL].[dbo].[Orders].NoResep, [Apotik_V1.1SQL].[dbo].[Orders].NoRegistrasi, [Apotik_V1.1SQL].[dbo].[Orders].[Customer ID], [masterdatasql].[dbo].[Admision].PatientName, [Apotik_V1.1SQL].[dbo].[Orders Status].[Status Name]
       FROM (([Apotik_V1.1SQL].[dbo].[Orders] INNER JOIN [Apotik_V1.1SQL].[dbo].[employees] ON [Apotik_V1.1SQL].[dbo].[Orders].[Employee ID] = [Apotik_V1.1SQL].[dbo].[employees].ID) INNER JOIN [masterdatasql].[dbo].[Admision] ON [Apotik_V1.1SQL].[dbo].[Orders].[Customer ID] = [masterdatasql].[dbo].[Admision].ID) INNER JOIN [Apotik_V1.1SQL].[dbo].[Orders Status] ON [Apotik_V1.1SQL].[dbo].[Orders].[Status ID] = [Apotik_V1.1SQL].[dbo].[Orders Status].[Status ID]
       WHERE (((" . $condition . ") AND  ".$condition2." ([masterdatasql].[dbo].[Admision].PatientName like '%" . $name . "%' or [masterdatasql].[dbo].[Admision].NoMR like '%" . $name . "%')) order by [Apotik_V1.1SQL].[dbo].[Orders].[Order ID] desc;"));
        return response()->json(
            [
                "list_resep" => $result,
                "list_orders" => $orders,
            ],
            200
        );
    }
    public function update_order(Request $request)
    {
        $id = $request["orderid"];
        $type= $request["TypeTransaction"];
        $order = Orders::findOrFail($id);
        $order["TypeTransaction"]=$type;
        // dd($order);
    }
    public function jumlah_obat(Request $request)
    {
        $noreg=$request["noreg"];
        $list_obat=DB::connection('farmasi')->table('Orders')
        ->select('Order Details.Product ID','Products.Product Name','Order Details.Quantity','Order Details.Unit Price')
        ->join('Order Details', 'Orders.Order ID', '=', 'Order Details.Order ID')
        ->join('Products', 'Order Details.Product ID', '=', 'Products.ID')
        ->where('Orders.NoRegistrasi',$noreg)->get();
        return response()->json(
            [
                "list_product" => $list_obat
                
            ],
            200
        );
        dd($list_obat);
    }
    public function update_orderdetails(Request $request)
    {
        $id = $request["orderid"];
        $status=$request["status"];
        Order_details::where('Order ID', '=', $id)
        ->update(['Status ID' => $status]);
    }
    public function delete_order(Request $request)
    {
        $id = $request["orderid"];
        $order = Orders::findOrFail($id);
        inv_trans::where('Customer Order ID',$id)->delete();
        Order_details::where('Order ID',$id)->delete();
        tblOrderType::where('OrderID',$id)->delete();
        $order->delete();

    }
    public function allstock(Request $request)
    {
        $name = $request['product'];
        $result = DB::select(DB::raw("SELECT top 5 * FROM [Apotik_V1.1SQL].[dbo].[View_Inventory Apotik] WHERE ([Apotik_V1.1SQL].[dbo].[View_Inventory Apotik].[Product Name] Like '%" . $name . "%')  order by [Apotik_V1.1SQL].[dbo].[View_Inventory Apotik].[Product Name] asc;"));
        return response()->json(
            [
                "list_product" => $result
                
            ],
            200
        );
    }
    public function obat_availability(Request $request)
    {
        $productid = $request["detail"]["Product ID"];
        $detail= Order_details::where('ID',$request["detail"]["ID"])->first();
        $qty=0;
        if($detail && $detail["Product ID"]==$productid){
            $qty=$detail["Quantity"];
        }
        $result1 = DB::select(DB::raw("SELECT top 1 * FROM [Apotik_V1.1SQL].[dbo].[View_Inventory Apotik] WHERE ([Apotik_V1.1SQL].[dbo].[View_Inventory Apotik].[Product ID] = " . $productid . ")  order by [Apotik_V1.1SQL].[dbo].[View_Inventory Apotik].[Product Name] asc;"));
        $result = json_decode(json_encode($result1[0]), true);
        // $result=json_encode($result1[0], true);
        //    dd($result);
        $status = (intval($qty))+(intval($result["Qty Purchased"])) - (intval($result["QtyReturCstr"])) - intval($result["Qty On Hold"]) - intval($result["Qty Sold"]) - intval($result["QtyMO"]);
        // dd('availabity is '.$status." qty is ".$request["detail"]["Quantity"]);
        return $status ;
    }
    public function insert_order(Request $request)
    {
        $order = new Orders();
        $noreg = $request["noreg"];
        if (substr($noreg, 2) == 'RJ') {
            $rj = visit::where(['NoRegistrasi', $noreg])->first();
            $request["NoRegistrasi"] = $noreg;
            $request["Dokter"] = $rj["Doctor_1"];
        } else if (substr($noreg, 2) == 'RI') {
            $ri = inpatient::where(['NoRegRI', $noreg])->first();
            $request["NoRegRI"] = $noreg;
            $request["Dokter"] = $ri["drPenerima"];
        }
        $input = $request->except(['noreg']);
        $order->fill($input)->save();
    }
    
    public function save_ordertype(Request $request)
    {
        if ($request["OrderTypeID"] != null) {
            $type = tblOrderType::findOrFail($request["OrderTypeID"]);
        } else {
            $type = new tblOrderType();
        }
        $input = json_decode(json_encode(collect($request)->except(['OrderTypeID'])), true);
        // dd($input);
        $type->fill($input)->save();
    }
    public function delete_ordertype(Request $request)
    {
        Order_details::where('OrdertypeID',$request["OrderTypeID"])->delete();
        $ordertype=tblOrderType::findOrFail($request["OrderTypeID"]);

        if($ordertype){
            $ordertype->delete();
        }

    }
    public function save_obatdetail(Request $request)
    {
        // print_r($request["inv"]);
        // dd('stop');
        $detail = $request["detail"];
        $inv = $request["inv"];
        $ava = $this->obat_availability($request);
        // dd(intval($ava)<intval($request["Quantity"]));
        if (intval($ava) < intval($request["detail"]["Quantity"]) && $inv["Transaction Type"]==2) {
            return response()->json(
                [
                    "error" => 'Stok hanya tersisa ' . $ava
                ],
                200
            );
        }
        
        $inv_id = $inv["Transaction ID"];
        $detail_id = $detail["ID"];
        $detail_instance = null;
        $inv_instance = null;
        if ($inv_id != null) {
            $id = $inv["Transaction ID"];
            $inv_instance = inv_trans::findOrFail($id);
        } else {
            $inv_instance = new inv_trans();
        }
        $input = json_decode(json_encode(collect($inv)->except(['Transaction ID'])), true);
        // dd($input);
        $inv_instance->fill($input)->save();
        $detail["Inventory ID"] = $inv_instance["Transaction ID"];
        if ($detail_id != null) {
            $id = $detail["ID"];
            $detail_instance = Order_details::findOrFail($id);
        } else {
            $detail_instance = new Order_details();
        }
        $input = json_decode(json_encode(collect($detail)->except(['ID'])), true);
        // dd($input);
        $detail_instance->fill($input)->save();
        // $detail["Inventory ID"]=$
    }
    public function delete_obatdetail(Request $request)
    {
        $inv_id = $request["Inventory ID"];
        $det_id = $request["detail_id"];
        $inv_instance = inv_trans::findOrFail($inv_id);
        $inv_instance != null ? $inv_instance->delete() : '';
        $detail_instance = Order_details::findOrFail($det_id);
        $detail_instance->delete();
        // dd($inv_instance);
    }
    public function cetak_label(Request $request)
    {
        $id = $request["id"];
        $url = "//172.100.15.212/print_req2/apotik/eticket.json";
        $myfile = fopen($url, "w") or die("Unable to open file!");
        fwrite($myfile, $id);
        fclose($myfile);
    }

    public function insert_stock_instant(Request $request)
    {
        // delete stock transactions
        // insert purchase order details
        $id = $request['id'];
        $result1 = DB::delete(DB::raw("DELETE [Apotik_V1.1SQL].[dbo].[Inventory Transactions] where [Apotik_V1.1SQL].[dbo].[Inventory Transactions].[Transaction Type]!=1 and [Apotik_V1.1SQL].[dbo].[Inventory Transactions].[Product ID] =" . $id));
        $result2 = DB::delete(DB::raw("DELETE [Apotik_V1.1SQL].[dbo].[MutasiDetails] where [Apotik_V1.1SQL].[dbo].[MutasiDetails].ProductID = " . $id));
        return $this->insert_purchase_order_details($request);
    }
    function insert_purchase_order_details(Request $request)
    {
        $id = $request['id'];
        $qty_s = $request['qty_s'];
        $qty_m = $request['qty_m'];
        $unit = $request['unit'];
        //     $result2= DB::insert( DB::raw("
        //     INSERT INTO [Apotik_V1.1SQL].[dbo].[Inventory Transactions]
        //        ([Transaction Type]
        //        ,[Transaction Created Date]
        //        ,[Transaction Modified Date]
        //        ,[Product ID]
        //        ,[Quantity]
        //        ,[Purchase Order ID]
        //        ,[Customer Order ID]
        //        ,[Comments]
        //        ,[LocationInv])
        //  VALUES
        //        (1
        //        ,CURRENT_TIMESTAMP
        //        ,CURRENT_TIMESTAMP
        //        ,".$id."
        //        ,".$qty_m."
        //        ,152113
        //        ,null
        //        ,null
        //        ,0)
        //     "));
        //     $id_1 = DB::getPdo()->lastInsertId();;
        //     //dd($id_1);
        //     $result1= DB::insert( DB::raw("
        //     INSERT INTO [Apotik_V1.1SQL].[dbo].[Purchase Order Details]
        //        ([Purchase Order ID]
        //        ,[Product ID]
        //        ,[Quantity]
        //        ,[Unit Cost]
        //        ,[discount]
        //        ,[hpp]
        //        ,[Date Received]
        //        ,[Expired Date]
        //        ,[Posted To Inventory]
        //        ,[Inventory ID]
        //        ,[updateharga])
        //  VALUES
        //        (152113
        //        ,".$id."
        //        ,".$qty_m."
        //        ,".$unit."
        //        ,0
        //        ,null
        //        ,CURRENT_TIMESTAMP
        //        ,null
        //        ,1
        //        ,".$id_1."
        //        ,0)
        //     "));
        $result1 = DB::insert(DB::raw("
        INSERT INTO [Apotik_V1.1SQL].[dbo].[MutasiDetails]
        ([IDMutasi]
        ,[ProductID]
        ,[Quatity]
        ,[UnitPrice]
        ,[status]
        ,[Validation])
        VALUES
        (336755
        ," . $id . "
           ," . $qty_m . "
           ," . $unit . "
        ,null
        ,0)
        "));

        # code...
    }
    public function update_inventory_purchase_stock(Request $request)
    {
        // update both inventory and purchase details
        // insert into mutasi table 
        $result = DB::select(DB::raw("Select * from [Apotik_V1.1SQL].[dbo].tblProductType"));
        return response()->json(
            [
                "master_product_type" => $result
            ],
            200
        );
    }
    // public function stock_obat()
    // {
    //    $result= DB::select( DB::raw("SELECT [Apotik_V1.1SQL].[dbo].[Orders].NoRegistrasi, [Apotik_V1.1SQL].[dbo].[tblOrderType].OrderID, [Apotik_V1.1SQL].[dbo].[tblOrderType].ProductTypeID, [Apotik_V1.1SQL].[dbo].[Order Details].Quantity, [Apotik_V1.1SQL].[dbo].[Products].[Product Name], [Apotik_V1.1SQL].[dbo].[Order Details].Quantity, [Apotik_V1.1SQL].[dbo].[Products].[List Price], [Apotik_V1.1SQL].[dbo].[Order Details].Signa
    //     FROM (([Apotik_V1.1SQL].[dbo].[Orders] INNER JOIN [Apotik_V1.1SQL].[dbo].[Order Details] ON [Apotik_V1.1SQL].[dbo].[Orders].[Order ID] = [Apotik_V1.1SQL].[dbo].[Order Details].[Order ID]) INNER JOIN [Apotik_V1.1SQL].[dbo].[tblOrderType] ON [Apotik_V1.1SQL].[dbo].[Order Details].OrdertypeID = [Apotik_V1.1SQL].[dbo].[tblOrderType].OrderTypeID) INNER JOIN [Apotik_V1.1SQL].[dbo].[Products] ON [Apotik_V1.1SQL].[dbo].[Order Details].[Product ID] = [Apotik_V1.1SQL].[dbo].[Products].ID
    //     WHERE ((([Apotik_V1.1SQL].[dbo].[Orders].NoRegistrasi)='RJBPJS220120-023'))") );
    //      return response()->json(
    //         [
    //             "stock_obat" => $result
    //         ],200);
    // }
}
