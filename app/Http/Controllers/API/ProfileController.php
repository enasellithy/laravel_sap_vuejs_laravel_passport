<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\User;
use Intervention\Image\ImageManagerStatic as Image;

class ProfileController extends Controller
{
    public function uploadPhoto(Request $request)
    {
        $user = User::find(auth()->user()->id);
        $validator = Validator::make($request->all(), [
            'photo' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['errors'=>$validator->errors()],422);
        } else {
            $imageData = $request->get('photo');
            $fileName = \Carbon\Carbon::now()->timestamp . '_' . uniqid() . '.' . explode('/', explode(':', substr($imageData, 0, strpos($imageData, ';')))[1])[1];
            Image::make($request->get('photo'))->save(public_path('images/users/').$fileName);
//            $destinationPath = public_path('/images/users');
//            $imageData->storeAs($destinationPath, $fileName);
            $user->photo = $fileName;
            $user->save();
            return response()->json(['success' => 'You have successfully uploaded an image'], 200);
//            return response()->json(['error'=>false]);
        }
    }
}
