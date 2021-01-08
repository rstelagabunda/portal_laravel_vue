<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// use Illuminate\Routing\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => 'auth'], function ($router) {

    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');
    Route::post('payload', 'AuthController@payload');
});

Route::group(['middleware' => 'jwt.auth'], function ($router) {
    // farmasi

    Route::post('list_obat', 'farmasiController@list_obat');
    Route::post('save_obatdetail', 'farmasiController@save_obatdetail');
    Route::post('delete_obatdetail', 'farmasiController@delete_obatdetail');

    Route::post('allresep', 'farmasiController@allresep');
    Route::post('allresep_per_pasien', 'farmasiController@list_resep');

    Route::post('cetak_label', 'farmasiController@cetak_label');
    Route::post('allstock', 'farmasiController@allstock');
    Route::post('resetstock_instant', 'farmasiController@insert_stock_instant');
    Route::post('pasien_details', 'farmasiController@pasien_details');
    Route::post('insert_order', 'farmasiController@insert_order');
    Route::post('update_order', 'farmasiController@update_order');
    Route::post('update_orderdetails', 'farmasiController@update_orderdetails');
    Route::post('delete_order', 'farmasiController@delete_order');
    Route::get('notification_list', 'farmasiController@notification_list');
    Route::post('save_ordertype', 'farmasiController@save_ordertype');
    Route::post('delete_ordertype', 'farmasiController@delete_ordertype');
Route::post('get_total_resep', 'farmasiController@get_total_resep');
        Route::post('jumlah_obat', 'farmasiController@jumlah_obat');
        





    // masterdata
    // Route::get('master_product_type', 'farmasiController@master_product_type');

    Route::get('master_product_type', 'mstrController@master_product_type');
    Route::get('get_mstr_doctors', 'mstrController@master_doctors');
    Route::get('get_mstr', 'mstrController@master');
    Route::get('get_mstr_proc', 'mstrController@master_proc');
    Route::post('update_alergi', 'mstrController@update_alergi');
    Route::post('search_pasien', 'mstrController@search_pasien');
    Route::post('get_roles', 'mstrController@get_roles');
    

    // rwi routes
    Route::post('get_allinap', 'rwiController@get_allinap');


    // rwj routes


    // emr
    Route::post('get_all_emr', 'rwjController@get_all_emr');
    Route::post('insert_emr', 'rwjController@insert_emr');
    Route::post('update_emr', 'rwjController@update_emr');
    Route::post('get_rwj_tindakan', 'rwjController@get_rwj_tindakan');
    Route::post('insert_rwj_tindakan', 'rwjController@insert_rwj_tindakan');
    Route::post('delete_rwj_tindakan', 'rwjController@delete_rwj_tindakan');
    Route::post('upload_emrdocs', 'rwjController@upload_emrdocs');
    Route::post('get_imagesfromuploads', 'rwjController@get_imagesfromuploads');

    Route::post('delete_imagesfromuploads', 'rwjController@delete_imagesfromuploads');

    // rwj api calls

    Route::post('terlayani', 'apicontroller@terlayani');
    Route::get('get_token', 'apicontroller@get_token_from_apirstb');
    Route::post('get_api_doctors', 'apicontroller@get_api_doctors');
    Route::post('insert_api_doctor', 'apicontroller@insert_api_doctor');
    Route::post('update_api_doctor', 'apicontroller@update_api_doctor');
    Route::post('save_operation', 'rwjController@save_operation');
    Route::post('get_operation', 'rwjController@get_operation');


    Route::post('post_api', 'apicontroller@post_api');


    // only visit
    Route::post('visit_selesai', 'rwjController@visit_selesai');
    Route::post('get_pasiendata_noreg', 'rwjController@get_pasiendata_noreg');
    Route::post('update_visit', 'rwjController@update_visit');

    // roster and shift
    Route::post('get_allshift', 'rosterController@get_allshift');
    Route::post('get_roster', 'rosterController@get_roster');
    Route::post('insert_roster', 'rosterController@insert_roster');
    Route::post('update_roster', 'rosterController@update_roster');
    Route::post('get_employees', 'rosterController@get_employees');
    Route::post('insert_shift', 'rosterController@insert_shift');
    Route::post('update_shift', 'rosterController@update_shift');
    Route::post('delete_shift', 'rosterController@delete_shift');
    Route::post('upload_csv', 'rosterController@upload_csv');

    


    // api for diag and resource ( get, insert, delete)
    Route::post('get_diagnosa_given_reg', 'rwjController@get_diagnosa_given_reg');
    Route::post('get_procedure_given_reg', 'rwjController@get_procedure_given_reg');
    Route::post('insert_diagnosa_given_reg', 'rwjController@insert_diagnosa_given_reg');
    Route::post('insert_procedure_given_reg', 'rwjController@insert_procedure_given_reg');
    Route::post('delete_diagnosa_given_reg', 'rwjController@delete_diagnosa_given_reg');
    Route::post('delete_procedure_given_reg', 'rwjController@delete_procedure_given_reg');
});
Route::post('get_allvisit', 'rwjController@get_allvisit');

Route::post('listlib', 'libcontroller@all');
Route::post('get_laporan', 'libcontroller@get_laporan');
Route::post('get_ido', 'ppicontroller@get_ido');

Route::get('get_reports', 'ppiController@get_reports');


    // Route::post('eticket', 'farmasiController@eticket');
    // Route::post('allresep', 'farmasiController@allresep');
