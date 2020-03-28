<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\LoginApiRequest;
use App\Http\Requests\Api\Auth\RegisterApiRequest;
use App\Mail\NewUserRegistered;
use App\Models\User;
use App\Notifications\Emails\SignupActivate;
use GuzzleHttp\Exception\BadResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Client;
use Laravel\Passport\Client as OClient;


use App\Traits\ApiResponse;

use Illuminate\Support\Facades\Storage;
use Laravolt\Avatar\Avatar;

use Carbon\Carbon;

class AuthController extends Controller
{

    use ApiResponse;

    private $CLIENT_MODE = 0;

    private $avatar;
    private $client;

    public function __construct()
    {
        $this->client = OClient::find(2);
        $this->avatar = new Avatar();
    }


    private $unwantedPins = [
        111111,222222,333333,
        444444,555555,666666,
        777777,888888,999999
    ];

    private function inUnWanted($pin){
        return in_array( $pin, $this->unwantedPins );
    }

    /**
     * Create user
     *
     * @param  [string] name
     * @param  [string] email
     * @param  [string] password
     * @param  [string] password_confirmation
     * @return [string] message
     */

    public function register(RegisterApiRequest $request){
        $token = bin2hex( openssl_random_pseudo_bytes( 30 ) );

        /*
        * @var $user User
        */

        $user = User::create([
            'email'         => $request->email,
            'password'      => Hash::make($request->password),
            'email_token'   => $token,
            'access'        => User::USER,
            'active'        => 1
        ]);

        //Generate avatar profile with email
        //$avatar = $this->avatar->create($request->email)->getImageObject()->encode('png');

        //Put Avatar user folder
        //Storage::put('avatars/1/avatar.png', (string) $avatar);

        //Notify user registered
        //$user->notify(new SignupActivate($user));

        //Send mail to user to confirm account
        Mail::to( $user )->queue( new NewUserRegistered( $token ) );

        $this->CLIENT_MODE = env('CLIENT_MODE');

//        try {
            if ($this->CLIENT_MODE == 1) {
                return $this->getTokenAndRefreshToken($this->client, $request->email, $request->password);
            } else {

                $tokenResult = $user->createToken('Personal Access Token');
                $token = $tokenResult->token;
                $token->save();

                return $this->specialResponse([
                    'access_token' => $tokenResult->accessToken,
                    'token_type' => 'Bearer',
                    'expires_at' => Carbon::parse($tokenResult->token->expires_at)->toDateTimeString(),
                    'status' => 'Successfully',
                    'code' => 200,
                    'message' => 'Success Register',
                    'data' => [$user]
                ], 200);
            }
//        } catch (BadResponseException $e){
//            if ($e->getCode() === 400) {
//
//            } elseif ($e->getCode() === 401) {
//                return response()->json('Your credentials are incorrect. Please try again', $e->getCode());
//            }
//        }

    }


    /**
     * Login user and create token
     *
     * @param  [string] email
     * @param  [string] password
     * @param  [boolean] remember_me
     * @return [string] access_token
     * @return [string] token_type
     * @return [string] expires_at
     */
    public function login(LoginApiRequest $request){

        $user = User::where('email', $request->email)->first();

        //CREDENTIALS
        $credentials = $request->only(['email', 'password']);

        //Check user exists
        if ($user) {

            $credentials['active'] = 1;
            $credentials['deleted_at'] = null;

            if(!Auth::attempt($credentials))
                return $this->specialResponse([
                    'access_token'  => null,
                    'token_type'    => null,
                    'expires_at'    => null,
                    'status'        => 'forbidden',
                    'code'          => 403,
                    'message'       => 'check your username and password',
                    'data'          => [$credentials]
                ],403);

            $this->CLIENT_MODE = env('CLIENT_MODE');

            if($this->CLIENT_MODE == 1){
                return $this->getTokenAndRefreshToken($this->client, $request->email, $request->password);
            } else {

                //GET USER
                $user = $request->user();

                $tokenResult = $user->createToken('Personal Access Token');
                $token = $tokenResult->token;

                if ($request->remember_me)
                    $token->expires_at = Carbon::now()->addWeeks(2);
                $token->save();

                return $this->specialResponse([
                    'access_token'  => $tokenResult->accessToken,
                    'token_type'    => 'Bearer',
                    'expires_at'    => Carbon::parse($tokenResult->token->expires_at)->toDateTimeString(),
                    'status'        => 'Successfully',
                    'code'          => 200,
                    'message'       => 'Success Login',
                    'data'          => [$credentials]
                ],200);
            }

        } else {
            if(!Auth::attempt($credentials))
                return $this->specialResponse([
                    'access_token'  => null,
                    'token_type'    => null,
                    'expires_at'    => null,
                    'status'        => 'Error',
                    'code'          => 401,
                    'message'       => 'Unauthorized',
                    'data'          => [$credentials]
                ],401);
        }

    }

    public function refresh(Request $request){
        $refresh_token = $request->header('Refresh-Token');
        $oClient = $this->client;
        $http = new Client();
        $url = env('APP_URL');

        try {
            $response = $http->request('POST', "{$url}/oauth/token", [
                'form_params' => [
                    'grant_type' => 'refresh_token',
                    'refresh_token' => $refresh_token,
                    'client_id' => $oClient->id,
                    'client_secret' => $oClient->secret,
                    'scope' => '',
                ],
            ]);

            return json_decode((string) $response->getBody(), true);
        } catch (\Exception $e) {
            return $this->specialResponse([
                'access_token'  => null,
                'token_type'    => null,
                'expires_at'    => null,
                'status'        => 'Unauthorized',
                'code'          => 401,
                'message'       => 'Unauthorized',
                'data'          => []
            ],401);
        }
    }

    /**
     * Logout user (Revoke the token)
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
//        $request->user()->token()->revoke();

        /*INACTIVE TOKEN USER*/
        $accessToken = $request->user()->token();
        DB::table('oauth_refresh_tokens')->where('access_token_id', $accessToken->id)->update(['revoked' => true]);
        $accessToken->revoke();

        return $this->specialResponse([
            'access_token'  => null,
            'token_type'    => null,
            'expires_at'    => null,
            'status'        => 'Successfully',
            'code'          => 200,
            'message'       => 'Logged out successfully',
            'data'          => []
        ],200);
    }

    /**
     * Get the authenticated User
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function user(Request $request)
    {
        return response()->json($request->user());
    }




    public function getTokenAndRefreshToken(OClient $oClient, $email, $password) {

        //        if (App::environment('production')) {
//            $url = env('APP_URL');
//        }
//
//        if (App::environment('local', 'staging')) {
//            $url = env('APP_URL');
//        }

//        $oClient = OClient::where('password_client', 2)->first();
        $oClient = $this->client;
        $http = new Client();
        $url = env('APP_URL');
        $response = $http->request('POST', "{$url}/oauth/token", [
            'form_params' => [
                'grant_type' => 'password',
                'client_id' => $oClient->id,
                'client_secret' => $oClient->secret,
                'username' => $email,
                'password' => $password,
                'scope' => '',
            ],
        ]);

        $result = json_decode((string) $response->getBody(), true);
        return response()->json($result);
    }
}
