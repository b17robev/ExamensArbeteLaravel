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
            $airports = Airport::take($amount)->get();
            $after = microtime(true);
            $total_mem = memory_get_usage();

            $data[] = ($after - $before) * 1000; //Convert to ms
            $data[] = ($total_mem - $base_mem) / 1024; //Convert to kb

            $result = implode(',', $data) . "\n";

            $this->writeToFile(env('APP_ROOT'),"index", $result);

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

        $this->writeToFile(env('APP_ROOT'),"store", $result);

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

        $this->writeToFile(env('APP_ROOT'),"show", $result);

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

        $this->writeToFile(env('APP_ROOT'),"update", $result);

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

        $this->writeToFile(env('APP_ROOT'),"destroy", $result);

        return new Response('Airport deleted', 200);
    }

    function writeToFile($root, $file, $content)
    {
        $measurementsPath = $root . DIRECTORY_SEPARATOR. 'measurements' . DIRECTORY_SEPARATOR;
        $actions = ['index', 'update', 'store', 'destroy', 'show'];

        if(!file_exists($root . DIRECTORY_SEPARATOR. 'measurements')) {
            mkdir($root . DIRECTORY_SEPARATOR. 'measurements');
        }

        $files = array_diff(scandir($measurementsPath), array('.', '..'));
        if(!count($files)) {
            foreach($actions as $action)
            {
                if(!is_file($measurementsPath . $action . '.txt'))
                {
                    file_put_contents($measurementsPath . $action . '.txt', '');
                }
            }
        }

        $outputFile = $measurementsPath . $file . '.txt';

        $fp=fopen($outputFile,"a");
        fputs ($fp, $content);
        fclose ($fp);
    }
}
