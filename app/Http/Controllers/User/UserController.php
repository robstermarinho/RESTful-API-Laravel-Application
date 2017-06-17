<?php

namespace App\Http\Controllers\User;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;



class UserController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();

        // 200 OK RESPONSE
        //return response()->json(['data' => $users], 200);

        return $this->showAll($users, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validation Rules
        $rules = [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
        ];

        $this->validate($request, $rules);

        $data = $request->all();
        $data['password'] = bcrypt($request->password); // Encrypting the password
        $data['verified'] = User::UNVERIFIED_USER;      // When the user is created he is unverified first
        $data['verification_token'] = User::generateVerificationCode(); // Generate a code for verification
        $data['admin'] = User::REGULAR_USER; // When the user is created he is an regular user

        $user = User::create($data); // Create and store
        
        // 201 CREATED
        //return response()->json(['data' => $user], 201);
        
        return $this->showOne($user, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // findOrFail going to throw and exception 404 NotFound if it fails
        $user = User::findOrFail($id);

        //return response()->json(['data' => $user], 200);
        return $this->showOne($user, 200);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
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
        
        $rules = [
            'email' => 'email|unique:users,email,' . $user->id,      // The own email skip the validation
            'password' => 'min:6|confirmed',                                
            'admin' => 'in:' . User::ADMIN_USER . ',' . User::REGULAR_USER, // A regular or admin user only
        ];

        // If the request has a name
        if ($request->has('name')) {
            $user->name = $request->name;
        }        

        // If the request has an name and the email is different from the user
        if ($request->has('email') && $user->email != $request->email) {
            $user->verified = User::UNVERIFIED_USER;                        // Change to Unverified again
            $user->verification_token = User::generateVerificationCode();   // Generate a new Code
            $user->email = $request->email;                                 // Set the new email
        }

        // Encrypt the password
        if ($request->has('password')) {
            $user->password = bcrypt($request->password);
        }


        if ($request->has('admin')) {
            // If the user is not verified...
            if (!$user->isVerified()) {
                //return response()->json(['error' => 'Only verified users can modify the admin field', 'code' => 409], 409);

                return $this->errorResponse('Only verified users can modify the admin field', 409);
            }

            $user->admin = $request->admin;
        }

        // If you are trying to update the same value or if you pass nothing like parameter
        if (!$user->isDirty()) {
            return $this->errorResponse('You need to specify a different value to update', 422);
        }

        $user->save();    
        //return response()->json(['data' => $user], 200);
        
        return $this->showOne($user, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        //return response()->json(['data' => $user], 200);

        return $this->showOne($user, 200);       
    }
}
