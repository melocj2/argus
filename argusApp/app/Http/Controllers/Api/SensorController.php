<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Sensor;

class SensorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sensorData = Sensor::all();

        return response()->json($sensorData);
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
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $sensorData = Sensor::create($request->all());

        $sensorData->save();

        $sensorData->fresh();

        return response()->json($sensorData);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function compress()
    {
        $sensorData = Sensor::where('type', 1)->get();

        $datesArr = [];

        $compressedArr = [];

        // $moistureArr = [];
        // $lightArr = [];
        // $tempArr = [];
        // $gasArr = [];

        foreach ($sensorData as $item) {
            //checks to see if sensor values are older than 2 days
            //explanation: gets current time, minus the time at which the item was recorded
            //checks if the item is older than two days (2 * seconds in a day)

            $dateform = date('Y-m-d', strtotime($item->recorded_at));

            if(date('Y-m-d', time()) != $dateform) {
                //if the above is true, pushes the record to sensorArrOldData

                //if date is in datesArr,
                //then loop through the compressedDays array and if the recorded_at attribute
                //is equal to date, push the items into the sensor data array;
                if (in_array($dateform, $datesArr)) {

                    foreach ($compressedArr as $day) {

                        if ($day->recorded_at == $dateform) {
                            $day->moistureArr[] = $item->moisture;
                            $day->lightArr[] = $item->light;
                            $day->tempArr[] = $item->temp;
                            $day->gasArr[] = $item->gas;
                        }
                    }

                //if dat is NOT in datesArr,
                //then create an array like you would push to the database,
                //except keep make the sensor data equal to an array containing
                //the sensor data
                //finally, push the date to the datesArr
                } else {

                    $newDay = new \stdClass();
                    $newDay->moistureArr = [$item->moisture];
                    $newDay->lightArr = [$item->light];
                    $newDay->tempArr = [$item->temp];
                    $newDay->gasArr = [$item->gas];
                    $newDay->recorded_at = $dateform;


                    $compressedArr[] = $newDay;
                    $datesArr[] = $dateform;

                }

                if (time() - strtotime($item->recorded_at) > 86400) {
                    $item->delete();
                }
            }
        }

        if (count($datesArr) > 0) {

            foreach ($compressedArr as $day) {

            $moisture = round(array_sum($day->moistureArr)/count($day->moistureArr));
            $light = round(array_sum($day->lightArr)/count($day->lightArr));
            $temp = round(array_sum($day->tempArr)/count($day->tempArr));
            $gas = round(array_sum($day->gasArr)/count($day->gasArr));

            $compression = Sensor::create([
                'moisture' => $moisture,
                'light' => $light,
                'temp' => $temp,
                'gas' => $gas,
                'type' => 2,
                'recorded_at' => $day->recorded_at,
            ]);

            $compression->save();
        }
        } else {
            $compression = false;
        }


        return response()->json($compression);
    }


}
