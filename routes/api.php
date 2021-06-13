<?php
use App\Book;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\RegisterController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


//**********************************************
Route::get('/registeruser',function (Request $request)
{
$validatedData = $request->validate([
'name' => 'required|string|max:255',
                   'email' => 'required|string|email|max:255|unique:users',
                   'password' => 'required|string|min:8',
]);

      $user = User::create([
              'name' => $validatedData['name'],
                   'email' => $validatedData['email'],
                   'password' => Hash::make($validatedData['password']),
       ]);
	   return $user;

//$token = $user->createToken('auth_token')->plainTextToken;

//return response()->json([              'access_token' => $token,                   'token_type' => 'Bearer',]);
});

//********************************************


Route::get('/adduser', function (Request $request) {
	$request["password"]=Hash::make($request['password']);
        $user = User::create($request->all());
		$user->save();

        return response()->json($user, 200);
    
    
});



Route::get('sanctum/token', function (Request $request) {
   
  /* $request->validate([
        'email' => 'required|email',
        'password' => 'required',
        'device_name' => 'required',
    ]);
*/
$email=$request["email"];
    $user = User::where('email', $email)->first();

    if (! $user || ! Hash::check($request->password, $user->password)) {
        throw ValidationException::withMessages([
            'email' => ['The provided credentials are incorrect.'],
        ]);
    }
	

    return $user->createToken("android")->plainTextToken;
	
	//return "Hello";
});


Route::post('sanctum/token', function (Request $request) {
   
  /* $request->validate([
        'email' => 'required|email',
        'password' => 'required',
        'device_name' => 'required',
    ]);
*/
$email=$request["email"];
    $user = User::where('email', $email)->first();

    if (! $user || ! Hash::check($request->password, $user->password)) {
        throw ValidationException::withMessages([
            'email' => ['The provided credentials are incorrect.'],
        ]);
    }
	

    return $user->createToken("android")->plainTextToken;
	
	//return "Hello";
});






Route::post('/all', function () {
	;
    return book::all();
});


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('login', 'Auth\LoginController@login');


Route::post('register', 'Auth\RegisterController@register');
