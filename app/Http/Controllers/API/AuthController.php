<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Hashing\BcryptHasher;
use Illuminate\Http\Request;
use App\User;
use DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Config;

class AuthController extends Controller
{

    public function token (Request $request)
    {
        $user = User::whereUsername(request('username'))->first();
        if (!$user) {
            return response()->json([ 'message' => 'Wrong username or password','status' => 422], 422);
        }
        $credentials = [ 'username' => request()->username,'password' => request()->password];
        if (auth()->attempt($credentials)) {
            $client = DB::table('oauth_clients')->where('password_client', true)->first();
            if (!$client) {
                return response()->json(['message' => 'Laravel Passport is not setup properly.',
                    'status' => 500 ], 500);
            }
            $data = ['grant_type' => 'password','client_id' => $client->id,
                'client_secret' => $client->secret,'password' => request('password'),];
            $request = Request::create('/oauth/token', 'POST', $data);
            $response = app()->handle($request);
            return response()->json([
                'token' => auth()->user()->createToken('TutsForWeb')->accessToken,
                'user' => $user,
                'status' => 200
            ]);
        } else {
            return response()->json([
                'message' => 'Wrong email or password',
                'status' => 422
            ], 422);
        }
    }

    public function register()
    {
        $validator = Validator::make(request()->all(), [
            'username' => 'required|string|max:255|unique:users|min:3',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:4',
        ]);
        $arr = [];
        foreach ($validator->errors()->messages() as $key => $err) {
            array_push($arr, (object) [$key => $err[0]] );
        }
        $new['message']  = $arr;
        if($validator->fails()){
            return response()->json($new, 422);
        }
        else {
            User::create([
                'username' => request('username'),
                'email' => request('email'),
                'password' => bcrypt(request('password'))
            ]);
            return response()->json(['status' => 200]);
        }
    }

    public function logout()
    {
        $accessToken = auth()->user()->token();

        $refreshToken = DB::table('oauth_refresh_tokens')
            ->where('access_token_id', $accessToken->id)
            ->update([
                'revoked' => true
            ]);

        $accessToken->revoke();

        return response()->json(['status' => 200]);
    }
}
