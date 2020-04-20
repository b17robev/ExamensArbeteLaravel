<?php

namespace App\Http\Controllers;

use App\Airport;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use League\Flysystem\Config;

class AirportsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Airport[]|\Illuminate\Database\Eloquent\Collection
     */
    public function index()
    {
        if($amount = request('amount')) {
            $data = [];

            $base_mem = memory_get_usage();
            $before = microtime(true);
            $airports = Airport::all()->take($amount);
            $after = microtime(true);
            $total_mem = memory_get_usage();

            $data[] = ($after - $before) * 1000; //Convert to ms
            $data[] = ($total_mem - $base_mem) / 1024; //Convert to kb

            $result = implode(',', $data) . "\n";

            $this->httpPost(config('scraper.url'), $result, "index");
            return $airports;
        }
        return Airport::all();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        $data = [];

        $base_mem = memory_get_usage();
        $before = microtime(true);
        $airport = Airport::create($request->all());
        $after = microtime(true);
        $total_mem = memory_get_usage();

        $data[] = ($after - $before) * 1000; //Convert to ms
        $data[] = ($total_mem - $base_mem) / 1024; //Convert to kb

        $result = implode(',', $data) . "\n";

        $this->httpPost(config('scraper.url'), $result, "store");

        return new Response($airport, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param $id
     * @return Response
     */
    public function show($id)
    {
        $data = [];

        $base_mem = memory_get_usage();
        $before = microtime(true);
        $airport = Airport::find($id);
        $after = microtime(true);
        $total_mem = memory_get_usage();

        $data[] = ($after - $before) * 1000; //Convert to ms
        $data[] = ($total_mem - $base_mem) / 1024; //Convert to kb

        $result = implode(',', $data) . "\n";

        $this->httpPost(config('scraper.url'), $result, "show");

        return $airport;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Airport  $airport
     * @return \Illuminate\Http\Response
     */
    public function edit(Airport $airport)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $airport = Airport::find($id);

        $data = [];

        $base_mem = memory_get_usage();
        $before = microtime(true);
        $airport->update($request->all());
        $after = microtime(true);
        $total_mem = memory_get_usage();

        $data[] = ($after - $before) * 1000; //Convert to ms
        $data[] = ($total_mem - $base_mem) / 1024; //Convert to kb

        $result = implode(',', $data) . "\n";

        $this->httpPost(config('scraper.url'), $result, "update");

        return $airport;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return Response
     */
    public function destroy($id)
    {
        $airport = Airport::find($id);

        $data = [];

        $base_mem = memory_get_usage();
        $before = microtime(true);
        $airport->delete();
        $after = microtime(true);
        $total_mem = memory_get_usage();

        $data[] = ($after - $before) * 1000; //Convert to ms
        $data[] = ($total_mem - $base_mem) / 1024; //Convert to kb

        $result = implode(',', $data) . "\n";

        $this->httpPost(config('scraper.url'), $result, "destroy");

        return new Response('Airport deleted', 200);
    }

    function httpPost($url, $data, $method)
    {
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, "$method=$data");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curl);
        curl_close($curl);

        return $response;
    }
}
