<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
use App\Models\Company;

class CompanyController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Company::paginate(50);

        return response([
            "message" => "Companies data retrieved successfully",
            'data'    => $data
        ], 200);
    }

    public function clients($id)
    {
        $data = Company::where('id',$id)->with('users')->paginate(50);

        return response([
            "message" => "Companies clients data retrieved successfully",
            'data'    => $data
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCompanyRequest $request)
    {
        $company = new Company();
        $company->name  = $request->name;
        $company->about = $request->about;
        $company->addMediaFromRequest('logo')->toMediaCollection('images');
        $company->save();

        return response([
            "message" => "Company Store successfully",
            'data'    => null
        ],201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Company $company)
    {
        return response([
            "message" => "Company data retrieved successfully",
            "data"    => $company
        ],200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCompanyRequest $request, $id)
    {
        $company = Company::find($id);
        if (!$company)
            return response([
                'message' => 'Not Found'
            ],404);

        //Unlink old image
        if ($request->file('logo'))
            $company->addMediaFromRequest('logo')->toMediaCollection('images');

        $data = $company->update([
            'name'  => $request->name,
            'about' => $request->about,
        ]);

        return response([
            "message" => "Company Update successfully",
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
        $company = Company::find($id);
        if (!$company)
            return response([
                'message' => 'Not Found'
            ],404);

        $company->delete();
        return response([
            'message' => 'Company delete successfully','data' => null
        ],200);
    }
}
