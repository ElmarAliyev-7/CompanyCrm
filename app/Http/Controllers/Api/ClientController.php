<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;

class ClientController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Client::select('id','name','surname','age','about','email')->fastPaginate(50);

        return response([
            "message" => "Clients data retrieved successfully",
            'data'    => $data
        ], 200);
    }

    public function companies($id)
    {
        $data = Client::select('id','name','surname','age','about','email')
            ->where('id',$id)->with('companies:id,name,about')->fastPaginate(50);

        return response([
            "message" => "Client companies data retrieved successfully",
            'data'    => $data
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreClientRequest $request)
    {
        $client = Client::create($request->all());
        $client->addMediaFromRequest('avatar')->toMediaCollection('images');

        return response([
            "message" => "Client Store successfully",
            'data'    => null
        ],201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Client $client)
    {
        return response([
            "message" => "Client data retrieved successfully",
            "data"    => $client
        ],200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateClientRequest $request, $id)
    {
        $client = Client::find($id);
        if (!$client)
            return response([
                'message' => 'Not Found'
            ],404);

        if ($request->file('avatar'))
            $client->addMediaFromRequest('avatar')->toMediaCollection('images');

        $client->update($request->all());

        return response([
            "message" => "Client Update successfully",
            'data'    => null
        ],200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $client = Client::find($id);
        if (!$client)
            return response([
                'message' => 'Not Found'
            ],404);

        $client->delete();
        return response([
            'message' => 'Client delete successfully','data' => null
        ],200);
    }
}
