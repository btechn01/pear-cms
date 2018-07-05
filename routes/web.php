<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    if ( Helpers::getSiteMaintenanceStatus() ) {
      // if site maintenance is set to 1, show maintenance page.
      return view('misc.maintenance', ['title' => 'Maintenance Mode']);
    } else {
      // if not, continue as normal.
    return view('welcome');
  }
});

// Route group will redirect all routes within to maintenance page if site maintenance is enabled.
Route::group(['middleware' => ['\App\Http\Middleware\SiteMaintenance::class']], function () {

  Route::get('/connection-guide', function () {
      return view('misc.connection-guide', ['title' => 'Connection Guide']);
  });

  Route::get('donate', array('as' => 'addmoney.paywithpaypal','uses' => 'PaypalController@showForm',))->middleware('auth');
  Route::post('donate', array('as' => 'addmoney.paypal','uses' => 'PaypalController@store',));
  Route::get('donate/status', array('as' => 'payment.status','uses' => 'PaypalController@getPaymentStatus',));


});

// Route group will redirect all routes within to maintenance page if auth maintenance is enabled.
Route::group(['middleware' => ['\App\Http\Middleware\AuthMaintenance::class']], function () {

  Route::get('/acp', function () {
      // get IP info for last ip that accessed account.
      $data = json_decode(file_get_contents("http://ip-api.com/json/" . Auth::user()->last_ip));
      return view('acp.home', ['title' => 'User Account Panel', 'data' => $data]);
  })->middleware('auth');

  Route::get('/acp/character/{name}', function ($name) {
      if ( Helpers::CharacterBelongsToId($name, Auth::user()->id))
      {
        // Character belongs to the id accessing it.
        $data = Helpers::getCharacterDataByName($name);
        return view('acp.view-character', ['title' => 'Viewing character: ' . $name , 'character' => $data]);
      } else {
        // Character do not belong to the id attempting to access it. Redirect to acp.
        return redirect('/acp');
      }
  })->middleware('auth');

TrinityCoreAuth::routes();
});
