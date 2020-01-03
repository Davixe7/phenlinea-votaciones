<?php

namespace App\Http\Controllers\API;

use App\Station;
use App\Option;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStation;
use App\Http\Resources\Station as StationResource;
use Illuminate\Http\Request;

class StationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return StationResource::collection( Station::all() );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreStation $request, Votation $votation)
    {
        $user = User::create([
          'name'         => $request->name,
          'password'     => bcrypt('st2k19' + $votation->id),
          'role'         => 'station'
        ]);
        
        $station = Station::create([
          'votation_id'  => $votation->id,
          'name'         => $request->name,
          'password'     => bcrypt('st2k19' + $votation->id),
          'user_id'      => $user->id
        ]);
        
        return new StationResource( $station );
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Station  $station
     * @return \Illuminate\Http\Response
     */
    public function show(Station $station)
    {
        return new StationResource( $station );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Station  $station
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Station $station)
    {
      $station->update([
        'name'         => ($request->name)         ? $request->name : $station->name,
        'password'     => ($request->password)     ? bcrypt($request->password) : $station->password,
        'votation_id'  => ($request->votation_id)  ? $request->votation_id : $station->votation_id,
        'status'       => ($request->status)       ? $request->status : $station->status,
      ]);
      
      return new StationResource( $station );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Station  $station
     * @return \Illuminate\Http\Response
     */
    public function destroy(Station $station)
    {
        return response()->json(['data'=>'Station ' . $station->id . ' deleted successfully']);
    }
}
