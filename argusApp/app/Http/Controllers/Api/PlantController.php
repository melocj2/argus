<?php

namespace App\Http\Controllers\Api;

use App\Plant;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Response;
use App\Transformers\PlantTransformer;

class PlantController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param PlantTransformer $userTransformer
     */
    public function store(Request $request, PlantTransformer $plantTransformer)
    {

        $validated = $request->validate([
            'user_id' => 'required',
            'name' => 'required',
            'type' => 'required',
            'image' => 'image',
            'date_planted' => 'required',
            'location' => 'required',
        ]);

        if (!is_array($validated) && $validated->fails()) {
            return redirect()->back()->withErrors($validated)->withInput();
        }

        $plant = Plant::create($request->all());

        $imageName = Storage::putFile('public/thumbnail', $request->image);

        $plant->image = Storage::url($imageName);

        $plant->date_planted = strtotime($plant->date_planted);

        $plant->save();

        $plant->fresh();

        return response()->json($plantTransformer->transform($plant));
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
    public function update(
        Request $request,
        $id,
        PlantTransformer $plantTransformer
    ) {

        $plant = Plant::find($id);

        if ($request->edit_profile_card) {

            $plant->location = $request->location ? $request->location : $request->locationB;

            if ($request->image) {

                $imageName = Storage::putFile('public/thumbnail', $request->image);

                $plant->image = Storage::url($imageName);

            }

            $plant->save();

        }

        $plant->fresh();

        return response()->json($plantTransformer->transform($plant));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $plant = Plant::destroy($id);

        return response()->json('completed delete');
    }

    /**
     * Gets all plants belonging to the given user
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     * @param PlantTransformer $userTransformer
     */
    public function getPlantList($id, PlantTransformer $plantTransformer) {

        $plants = Plant::where('user_id', $id)->get();

        $plantsTransformed = [];

        foreach ($plants as $item) {
            $plantsTransformed[] = $plantTransformer->transform($item);
        }

        return response()->json($plantsTransformed);
    }
}
