<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
 
use Validator;

use File;
use Repsonse;
 

 

use Illuminate\Http\Request;

class UploadAssetController extends Controller
{
    

    /**
   *@param \Illuminate\Http\Resquest
   *@return \Illuminate\Http\Response
   */
    public function store(Request $request)
    {
    
        $validatedData = $request->validate([
            'fileName' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',

           ]);

           $name = $request->file('fileName')->getClientOriginalName();
           $employeeCode='A132456';

           $date = Carbon::now()->format('Ymd');
          // return $date;


           $directory=Storage::makeDirectory('public/images/'.$employeeCode.'/'.$date);
           $path = $request->file('fileName')->store('public/images/'.$employeeCode.'/'.$date);
           //$path = $request->file('fileName')->store('public/images/');

         // $path=$request->file('fileName')->store(Storage::makeDirectory(public_path('public/images/'.$employeeCode)));


           


           $save = new Image;
           $save->empcode=$employeeCode;
           $save->title = $name;
           $save->path = $path;

           $save->save();

           //return $request->file('fileName')->store('public/images/');

           return $path;

       
    }


   

      /**
   *
   *@return \Illuminate\Http\Response
   */
  public function downloadAsset()  {

  $name = DB::table('images')->where('empcode', '=','A132456')->orderBy('created_at', 'desc')->take(1)->get();

  $path=$name[0]->path;

  $filepath = storage_path('/app/'.$path);
  return response()->download($filepath); 

    
  }
 
}
