<?php

namespace App\Http\Controllers;

use JWTAuth;
use App\User;
use JWTAuthException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{
    private $user;

    public function __contruct(User $user)
    {
        $this->user = $user;
    }

    public function register(Request $request)
    {
        $user = User::create([
            'name'     => $request->get('name'),
            'email'    => $request->get('email'),
            'password' => Hash::make($request->get('password'))
        ]);

        return response()->json([
            'status' => 200,
            'success' => true,
            'message' => 'User created successfully',
            'data' => $user
        ]);
    }

    /** Creating a Token based on the users credentials */
    public function login(Request $request)
    {
        /**
         * 1.Authentication
         * 
         * Note to Apache users:
         * 
         * Apache seems to discard the Authorization header if it is not a base64 encoded user/pass combo. 
         * So to fix this you can add the following to your apache config:
         *      RewriteEngine On
         *      RewriteCond %{HTTP:Authorization} ^(.*)
         *      RewriteRule .* - [e=HTTP_AUTHORIZATION:%1]
         * 
         * Once a user has logged in with their credentials, then the next step would be to make a subsequent 
         * request, with the token, to retrieve the users' details, so you can show them as being logged in.
         * 
         * To make authenticated requests via http using the built in methods, 
         * you will need to set an authorization header as follows:
         * 
         *      Authorization: Bearer {your_token_here}
         * 
         * 2.Creating Tokens
         * 
         * There are several ways to create a token within the package. 
         * There are simple ways to do it, and more advanced methods if you want greater control.
         * 
         * sub Subject    - This holds the identifier for the token (defaults to user id)
         * iat Issued At  - When the token was issued (unix timestamp)
         * exp Expiry     - The token expiry date (unix timestamp)
         * nbf Not Before - The earliest point in time that the token can be used (unix timestamp)
         * iss Issuer     - The issuer of the token (defaults to the request url)
         * jti JWT Id     - A unique identifier for the token (md5 of the sub and iat claims)
         * aud Audience   - The intended audience for the token (not required by default)
         * 
         * - Creating a Token based on the users credentials
         * - Creating a Token based on a user object
         * - Creating a Token based on anything you like
         */


        // grab credentials from the request
        $credentials = $request->only('email', 'password');
  
        $token = null;

        try {
            // attempt to verify the credentials and create a token for the user
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'email_or_password_invalid'], 422);
            }
        } catch (JWTAuthException $e) {
            // something went wrong whilst attempting to encode the token
            return response()->json(['error' => 'create_token_failed'], 500);
        }
        
        // all good, so return the token
        return response()->json(compact('token'));
    }

    /** Retreiving the Authenticated user from a token */
    public function getUserInfo(Request $request)
    {
        $user = JWTAuth::toUser($request->token);
        // $user = JWTAuth::parseToken()->toUser();
        // $user = JWTAuth::parseToken()->authenticate();
        return response()->json(compact('user'));

        // this will set the token on the object
        // dd(JWTAuth::parseToken());

        // and you can continue to chain methods
        // $user = JWTAuth::parseToken()->authenticate();
        // dd($user);

        // get token
        // $token = JWTAuth::getToken();
        // dd($token);

        // manually set token
        // JWTAuth::setToken('foo.bar.baz');
        // dd(JWTAuth::getToken());
    }

    /** Creating a Token based on a user object */
    public function createTokenBasedUser(Request $request)
    {
        // grab some user
        $user = User::first();

        $token = JWTAuth::fromUser($user);

        // The above two methods also have a second parameter where you can pass an array of custom claims
        // Note: Be wary about adding lots of custom claims as this will increase the size of your token
        // grab credentials from the request
        
        // $credentials = $request->only('email', 'password');

        // $customClaims = ['foo' => 'bar', 'baz' => 'bob'];

        // $token = JWTAuth::attempt($credentials, $customClaims);
        // or
        // $token = JWTAuth::fromUser($user, $customClaims);
    }

    /** Creating a Token based on anything you like */
    public function createTokenBasedAnything(Request $request)
    {
        $customClaims = ['foo' =>'bar', 'baz' => 'bob'];
        $payload      = JWTFactory::make($customClaims);
        $token        = JWTAuth::encode($payload);

        // You can also chain claims directly onto the Tymon\JWTAuth\PayloadFactory instance
        // add a custom claim with a key of `foo` and a value of ['bar' => 'baz']
        // $payload = JWTFactory::sub(123)->aud('foo')->foo(['bar' => 'baz'])->make();
        // $token   = JWTAuth::encode($payload);
    }
}
