<?php

namespace App\Http\Controllers\Api\V1;

use App\Country;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Vendor;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Vendor as VendorResource;
use App\Http\Resources\CountryResource;
class VendorController extends Controller
{
    public function create(Request $request){
        try {
            $validator = Validator::make($request->all(), [
                'vendorname' => 'required',
                'first_name' => 'required',
                'last_name'=>'required',
                'country'=>'required',
                'address'=>'required',
                'image'=>'required',
                'pdfdocument'=>'required'
            ]);
            $errors = $validator->errors()->all();
            if ($validator->fails()) {
                $response=['msg'=>'created successfully','data'=>$errors,'status'=>201];
                return response()->json($response);
            }
            $image =Storage::disk('public')->put('images',$request->file('image'));
            $pdfdocument =Storage::disk('public')->put('pdf',$request->file('pdfdocument'));
            $query = Vendor::create(['vendor_name'=>$request->vendorname,'first_name'=>$request->first_name,
            'last_name'=>$request->last_name,'country'=>$request->country,'address'=>$request->address,'image'=>$image,'pdfdocument'=>$pdfdocument]);
            $response=['msg'=>'created successfully','status'=>200];
            return response()->json($response);
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    public function vendors(){
        try {
         $query=Vendor::orderBy('id','DESC')->get();
         foreach ($query as $key => $value) {
            $value->image=Storage::disk('public')->url($value->image);
            $value->pdfdocument=Storage::disk('public')->url($value->pdfdocument);
            $value->country=$value->countryr->name;
         }
         $response=['data'=>new VendorResource($query),'status'=>200];
         return response()->json($response);
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    public function search($query){
        try {
         $query = Vendor::where('vendor_name', 'LIKE', '%' . $query . '%')
            ->orWhere('first_name', 'LIKE', '%' . $query . '%')
            ->orWhere('last_name', 'LIKE', '%' . $query . '%')
            ->get();
            $query= new VendorResource($query);
            return response()->json(['data'=>$query,'status'=>200]);
        } catch (\Throwable $th) {

        }
    }

    public function show($id){
        try {
            $value=Vendor::where('id',$id)->get();
            $value[0]->image=Storage::disk('public')->url($value[0]->image);
            $value[0]->pdfdocument=Storage::disk('public')->url($value[0]->pdfdocument);
            $value= new VendorResource($value);
            $response=['data'=>$value,'status'=>200];
            return response()->json($response);
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    public function edit(Request $request,$id){
        try {
            $data=['vendor_name'=>$request->vendorname,'first_name'=>$request->first_name,'last_name'=>$request->last_name,'country'=>$request->country,'address'=>$request->address];
            if ($request->hasFile('image')) {
                $image =Storage::disk('public')->put('images',$request->file('image'));
                $data['image']=$image;
            }
            if ($request->hasFile('pdfdocument')) {
                $pdfdocument =Storage::disk('public')->put('pdf',$request->file('pdfdocument'));
                $data['pdfdocument']=$pdfdocument;
            }

            $value=Vendor::find($id)->update($data);
            if($value){
                return response()->json(['status'=>200,'message'=>'edit successfull']);
            }else{
                return response()->json(['status'=>201,'message'=>'edit successfull']);
            }

        } catch (\Throwable $th) {
            //throw $th;
        }
    }
    public function countries(){
        try {
            $data= Country::all();
            $data=new CountryResource($data);
            $response=['data'=>$data,'status'=>200];
            return response()->json($response);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
