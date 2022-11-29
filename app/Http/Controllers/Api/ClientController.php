<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
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
        $data = User::paginate(50);

        return response([
            "message" => "Clients data retrieved successfully",
            'data'    => $data
        ], 200);
    }

    public function companies($id)
    {
        $data = User::where('id',$id)->with('companies')->paginate(50);

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

        $user = new User();
        $user->name     = $request->name;
        $user->surname  = $request->surname;
        $user->age      = $request->age;
        $user->about    = $request->about;
        $user->avatar   = $this->uploadImage($request->file('avatar'));
        $user->email    = $request->email;
        $user->password = $request->password;
        $user->save();

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
        $user = User::where('id', $id)->with('companies')->first();

        if (!$user)
            return response(["message"=>'Not found' , 'data' => null],404);

        return response([
            "message" => "Client data retrieved successfully",
            "data"    => $user
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
        $user = User::find($id);
        if (!$user)
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
            $this->mediaDestroy($user->avatar);
        }

        $data = $user->update([
            'name'    => $request->name,
            'surname' => $request->surname,
            'age'     => $request->age,
            'about'   => $request->about,
            'avatar'  => $this->uploadImage($request->file('avatar'), $user->logo),
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
        $user = User::find($id);
        if (!$user)
            return response([
                'message' => 'Not Found'
            ],404);

        //Unlink image
        if ($user->avatar)
            $this->mediaDestroy($user->avatar);

        $user->delete();
        return response([
            'message' => 'User delete successfully','data' => null
        ],200);
    }
}
