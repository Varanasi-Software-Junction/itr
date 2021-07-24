<?php
use App\EmpInfo;
use App\Book;
use App\User;
use App\PersonalInfo;
use App\AddressInfo;
use Illuminate\Http\Request;
use App\Http\Controllers\RegisterController;
use Illuminate\Foundation\Validation\ValidationException;
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






Route::post('/hello' , function(Request $request) {
	$request= json_decode($request->getContent(), true);
	return "Hello" . $request["id"];
});







Route::post('/addaddressinfo', function (Request $request) {
	
	try
	{
		
	 $pi = AddressInfo::create($request->all());
		
		
		
		$pi->save();
		$pi["status"]="ok";
		
        return response()->json($pi, 200);
	}
	 catch (\Exception $e) {
$error=array("status"=>"failed","error"=>$e->getMessage());
    return response()->json($error, 200);
}
	
	
	
});






//**********************************************

Route::post('/addpersonlinfo', function (Request $request) {
	
	try
	{
		$request["sourceofincome"]=serialize( $request["sourceofincome"]);
	 $pi = PersonalInfo::create($request->all());
		$userid=$request["userid"];
		
		$user=User::find($userid);
		if ($user==null)
		{
			throw new Exception('No Parent Key');
		}
		$pi->save();
		$pi["status"]="ok";
		$pi["sourceofincome"]=unserialize($pi["sourceofincome"]);
        return response()->json($pi, 200);
	}
	 catch (\Exception $e) {
$error=array("status"=>"failed","error"=>$e->getMessage());
    return response()->json($error, 200);
}
	
	
	
});






Route::post('/testjson', function (Request $request) {
	  $users = json_decode($request->getContent(),true);
	return $users["name"];
});


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
Route::post('/addjsonuser', function (Request $request) {
	try
	{
	$request = json_decode($request->getContent(),true);
	$request["password"]=Hash::make($request['password']);
        $user = User::create($request);
     $user->save();
$user["status"] = "ok";
        return response()->json($user, 200);
}
    catch (\Exception $e) {
$error=array("status"=>"failed","error"=>$e->getMessage());
    return response()->json($error, 200);
}
    
});


//********************************************


Route::get('/adduser', function (Request $request) {
	$request["password"]=Hash::make($request['password']);
        $user = User::create($request->all());
		$user->save();

        return response()->json($user, 200);
    
    
});


Route::post('sanctum/json/token', function (Request $request) {
   
 $request= json_decode($request->getContent(), true);

$email=$request["email"];
    $user = User::where('email', $email)->first();

    if (! $user || ! Hash::check($request["password"], $user->password)) {
		$error=array("status"=>"failed",);
        return response()->json($error, 200);;
    }
	$user["status"] = "ok";
	$user["token"] = $user->createToken("android")->plainTextToken;
    return response()->json($user, 200);// $user->createToken("android")->plainTextToken;
	
	//return "Hello";
});




Route::get('sanctum/token', function (Request $request) {
   
  

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
