<?php

namespace App\Http\Controllers\API;

use App\Votation;
use App\Option;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreVotation;
use App\Http\Resources\Votation as VotationResource;
use Illuminate\Http\Request;

class VotationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return VotationResource::collection( Votation::with('options')->get() );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreVotation $request)
    {
        $votation = Votation::create([
          'name'         => $request->name,
          'type'         => $request->type,
          'total_voters' => $request->total_voters,
          'quorum'       => $request->quorum,
        ]);
        
        foreach( $request->options as $option ){
          Option::create(
            [
              'votation_id' => $votation->id,
              'name'        => $option['name']
            ]
          );
        }
        
        return new VotationResource( $votation );
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Votation  $votation
     * @return \Illuminate\Http\Response
     */
    public function show(Votation $votation)
    {
        return new VotationResource( $votation->load('options') );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Votation  $votation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Votation $votation)
    {
      $votation->update([
        'name'         => ($request->name)         ? $request->name : $votation->name,
        'type'         => ($request->type  )       ? $request->type : $votation->type,
        'total_voters' => ($request->total_voters) ? $request->total_voters : $votation->total_voters,
        'quorum'       => ($request->quorum)       ? $request->quorum : $votation->quorum
      ]);
      
      return new VotationResource( $votation );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Votation  $votation
     * @return \Illuminate\Http\Response
     */
    public function destroy(Votation $votation)
    {
        return response()->json(['data'=>'Votation' . $votation->id . ' deleted successfully']);
    }
}
