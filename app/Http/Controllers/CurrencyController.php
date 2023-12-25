<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    public function index()
    {
        return view('currency.index');
    }

    public function exchange_currency(Request $request)
    {
        $amount =($request->amount)?($request->amount):(1);
        $api_key = env('EX_RATE_API_KEY') ?? 'd203e86b204ec9207204f2fc';
        $from_currency  = urlencode($request->from_currency);
        $to_currency = urlencode($request->to_currency);

        $response_json = file_get_contents("https://v6.exchangerate-api.com/v6/{$api_key}/latest/{$from_currency}");

        if(false != $response_json)
        {
            try
            {
                $response = json_decode($response_json, true);
                if('success' === $response['result'])
                {
                    $final = $amount*$response['conversion_rates'][$to_currency];
                    $query = " {$from_currency} {$amount} = {$to_currency} ".number_format($final, 2);
                    echo $query;
                }
            }
            catch(Exception $e)
            {
                echo "Error: " . $e->getMessage() . "\n Code" . $e->getCode();
            }
        }
    }
}
