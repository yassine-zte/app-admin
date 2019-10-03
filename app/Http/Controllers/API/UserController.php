<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(){
   
    // $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $this->authorize('isAdmin');
       /* if (\Gate::allows('isAdmin') || \Gate::allows('isAuthor')) {
            return User::latest()->paginate(5);
        }*/
         //return response()->json(['status'=>'succes','user'=>User::all()]);
         //return User::all()->paginate(5);

        //return User::latest()->paginate(5);
       return response()->json(['status'=>'succes','data'=>user::all()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

       /* $this->validate($request,[
            'name' => 'required|string|max:191',
            'email' => 'required|string|email|max:191|unique:users',
            'password' => 'required|string|min:6'
        ]);*/

        return User::create([
            'name' => $request['name'],
            'email' => $request['email'],
           // 'role' => $request['role'],
            //'permission' => $request['permission'],
            //'photo' => $request['photo'],
            'password' => Hash::make($request['password']),
        ]);


     return response()->json(['status'=>'add succes','message'=>'add succes']);
       /* $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->type = $request->type;
        $user->bio = $request->bio;
        // $user->photo = $request->photo;
        $user->password = Hash::make($request->password);         
               if($user->save()){
                  return response()->json(['status'=>'add succes','data'=>$user]);

               }else{
                      
                    return response()->json(['status'=>'errors']);
               }*/

    }


    public function updateProfileImage(Request $request)
    {
        //$user = auth('api')->user();

        $user=User::findOrFail(1);
      /*  $this->validate($request,[
            'name' => 'required|string|max:191',
            'email' => 'required|string|email|max:191|unique:users,email,'.$user->id,
            'password' => 'sometimes|required|min:6'
        ]);*/


        $currentPhoto = $user->photo;


        $name = time().'.' . explode('/', explode(':', substr($request->photo, 0, strpos($request->photo, ';')))[1])[1];
        $fullPath = public_path('img/profile/').$name;
        \Image::make($request->photo)->save($fullPath);
        $user->photo = $fullPath;
        $user->save();
        $userPhoto = public_path('img/profile/').$currentPhoto;
        if(file_exists($userPhoto)){
            @unlink($userPhoto);
        }


        /*if(!empty($request->password)){
            $request->merge(['password' => Hash::make($request['password'])]);
        }

    */
        return response()->json(['message' => "Success"]);
       

    }


    public function profile()
    {
       // return auth('api')->user();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $user = User::findOrFail($id);

        $this->validate($request,[
            'name' => 'required|string|max:191',
            'email' => 'required|string|email|max:191|unique:users,email,'.$user->id,
            'password' => 'sometimes|min:6'
        ]);

        $user->update($request->all());
        //return ['message' => 'Updated the user info'];

         return response()->json(array("status"=>1,
                                        "message"=> "successfully updated"));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

       // $this->authorize('isAdmin');

        $user = User::findOrFail($id);
        // delete the user

        $user->delete();

        //return ['message' => 'User Deleted'];
        return response()->json('successfully deleted');
    }

    
}
