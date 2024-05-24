<?php
namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Admin;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{

    use ResponseTrait;
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth:user', ['except' => ['login', 'register']]);
    }
    public function login(Request $request){
    	$validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);
        if ($validator->fails()) {
            // return response()->json($validator->errors(), 422);
            return $this->SendResponse(response::HTTP_UNPROCESSABLE_ENTITY , $validator->errors());
        }
        if (! $token = auth()->guard('user')->attempt($validator->validated())) {
            // return response()->json(['error' => 'Unauthorized'], 401);
            return $this->SendResponse(response::HTTP_UNAUTHORIZED , 'wrong email or password');
        }
        return $this->createNewToken($token);
    }
    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|min:6',
            'photo' => 'required|image|mimes:jpg,png,jpeg',
            'phone' => 'required|string|max:17',
        ]);
        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }
        $user = User::create(array_merge(
                    $validator->validated(),
                    [
                        'password' => bcrypt($request->password),
                        'photo' => $request->file('photo')->store('users'),
                    ]
                ));
        return response()->json([
            'message' => 'User successfully registered',
            'user' => $user
        ], 201);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout() {
        auth()->guard('user')->logout();
        return response()->json(['message' => 'User successfully signed out']);
    }
    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh() {
        return $this->createNewToken(auth()->refresh());
    }
    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function userProfile() {
        return response()->json(auth()->guard('user')->user());
    }
    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function createNewToken($token){
        // return response()->json([
        //     'token' => $token,
        //     'user' => auth()->user()
        // ]);
        return $this->SendResponse(response::HTTP_OK , 'logged in successfully' ,['token' => $token]);
    }
}

