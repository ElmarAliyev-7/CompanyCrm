<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Http\Traits\MediaTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
    public function store(Request $request)
    {
        $validator = Validator::make($request->all() , [
            'name'     => 'required',
            'surname'  => 'nullable',
            'age'      => 'nullable|integer',
            'about'    => 'nullable',
            'avatar'   => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'email'    => 'email|required',
            'password' => 'required',
        ]);

        if($validator->fails()){
            return response([
                'error' => true,
                'message' => $validator->errors()
            ], 400);
        }

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
    public function show($id)
    {
        $client = Client::where('id', $id)->with('companies')->first();

        if (!$client)
            return response([
                "message"=>'Not found' , 'data' => null]
                ,404);

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
    public function update(Request $request, $id)
    {
        $client = Client::find($id);
        if (!$client)
            return response([
                'message' => 'Not Found'
            ],404);

        //Unlink old image
        if ($request->file('avatar')){
            $validator = Validator::make($request->all() , [
                'avatar'   => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);

            if($validator->fails()){
                return response([
                    'error' => true,
                    'message' => $validator->errors()
                ], 400);
            }
            $this->mediaDestroy($client->avatar);
        }

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
