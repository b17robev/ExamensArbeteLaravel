<?php

namespace App\Http\Controllers;

use App\Airport;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AirportsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Airport[]|\Illuminate\Database\Eloquent\Collection
     */
    public function index()
    {
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
        $airport = Airport::create($request->all());

        return $airport;
    }

    /**
     * Display the specified resource.
     *
     * @param $id
     * @return Response
     */
    public function show($id)
    {
        $before = microtime(true);
        $airport = Airport::find($id);
        $after = microtime(true);

        $url = "http://localhost:8080/scrapper/index.php";
        $result = $after - $before . "\n";

        $this->httpPost($url, $result, "show");

        if(!$airport) {
            return new Response("Couldn't find airport with id of $id", 410);
        }

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

        $airport->update($request->all());

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

        $airport->delete();

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
