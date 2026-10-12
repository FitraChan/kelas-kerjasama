<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\KaryawanModel;
use Response;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

use Illuminate\Support\Facades\Http;
use App\Models\TokenModel;



use Auth;

class DemoCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'demo:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */

     public function __construct()
    {
        parent::__construct();
    }


    public function handle(Request $request)
    {



      $dos = DB::table('mbc_token')->orderBy('id','desc')->limit(1)->first();

      $tok = !empty($dos->token) ? $dos->token : '93|BULFzQPwfZ9ikjo6mz2dZUwHFc08WPaIRdC5sh1O';
                   $header = array(
                           'Authorization' => 'Bearer ' . $tok,
                      //    'Authorization' => 'Bearer 111|h1fm9eL79BytQMFy0scrk5EGKrCtxmbnIrydu6j0',
                        //  'Accept' => 'application/json',
                        //   'Authorization' => 'Bearer 93|BULFzQPwfZ9ikjo6mz2dZUwHFc08WPaIRdC5sh1O',
                           'Accept' => 'application/json',
                           //'Content-Type'=> 'application/json'
                      );

    $statusCode = 0;
    $apiURL = 'http://192.168.10.105/stikom_api_absen/public/api/show_kardos';

    try {

      $client = new Client();
        $response = $client->request('POST', $apiURL, ['headers' => $header]);
            $responseBody = $response->getBody();
            $statusCode = $response->getStatusCode();


            echo $responseBody;;

              \Log::info($responseBody);


    } catch (ClientException $e) {

            print_r($e->getMessage());
            $user = User::where('email', 'fitra@mbc.com')->first();
            if (!Auth::attempt(['email' => 'fitra@mbc.com', 'password' => '123456#$'])){

                    return response()
                        ->json(['message' => '404'], 401);
                }
            $token = $user->createToken('my-app-token')->plainTextToken;



            $simpan = TokenModel::create([
                        'token' => $token,
                   ]);


    }




    }
}
