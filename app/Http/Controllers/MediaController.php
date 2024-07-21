<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaController extends Controller
{
   public function upload(Request  $request){
       $this->validate($request,[
           'file' => 'required|mimes:png,jpg,jpeg,pdf|max:5000'
       ]);
       $file = $request->file('file');
       $name = time() . $file->getClientOriginalName();
       $filePath = 'images/'. $name;
       Storage::disk('s3')->put($filePath, file_get_contents($file),'public');
       $path =  Storage::disk('s3')->url($filePath);
       return response()->json([
           "success" => true,
           "message" => 'Success',
           "url" => $path
       ]);

   }
}
