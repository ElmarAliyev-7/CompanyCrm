<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Http\Traits\MediaTrait;
use Illuminate\Http\Request;
use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;

class ClientController extends Controller
{
    use MediaTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Client::paginate(50);

        return response([
            "message" => "Clients data retrieved successfully",
            'data'    => $data
        ], 200);
    }

    public function companies($id)
    {
        $data = Client::where('id',$id)->with('companies')->paginate(50);

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
        $client = new Client();
        $client->name     = $request->name;
        $client->surname  = $request->surname;
        $client->age      = $request->age;
        $client->about    = $request->about;
        $client->avatar   = $this->uploadImage($request->file('avatar'));
        $client->email    = $request->email;
        $client->password = $request->password;
        $client->save();

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

        //Unlink old image
        if ($request->file('avatar'))
            $this->mediaDestroy($client->avatar);

        $data = $client->update([
            'name'    => $request->name,
            'surname' => $request->surname,
            'age'     => $request->age,
            'about'   => $request->about,
            'avatar'  => $this->uploadImage($request->file('avatar'), $client->logo),
            'email'   => $request->email,
            'password'=> $request->password,
        ]);

        return response([
            "message" => "Client Update successfully",
            'data' => null
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

        //Unlink image
        if ($client->avatar)
            $this->mediaDestroy($client->avatar);

        $client->delete();
        return response([
            'message' => 'Client delete successfully','data' => null
        ],200);
    }
}
