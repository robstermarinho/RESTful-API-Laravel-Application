<?php

namespace App\Http\Controllers\User;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\ApiController;



class UserController extends ApiController
{
    public function __construct()
    {
        $this->middleware('client.credentials')->only(['store', 'resend']);
        $this->middleware('auth:api')->except(['store', 'verify', 'resend']);
    }
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
    public function show(User $user)
    {
        // findOrFail going to throw and exception 404 NotFound if it fails
        // $user = User::findOrFail($id);

        // Using implicit model biding with the variable $user
        return $this->showOne($user, 200);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
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
    public function destroy(User $user)
    {
        
        $user->delete();
        //return response()->json(['data' => $user], 200);

        return $this->showOne($user, 200);       
    }

    public function verify($token)
    {
        $user = User::where('verification_token', $token)->firstOrFail();

        $user->verified = User::VERIFIED_USER;
        $user->verification_token = null;

        $user->save();

        return $this->showMessage('The account has been verified succesfully');
    }

    

    public function resend(User $user)
    {
        if ($user->isVerified()) {
            return $this->errorResponse('This user is already verified', 409);
        }

        retry(5, function() use ($user) {
                Mail::to($user)->send(new UserCreated($user));
            }, 100);

        return $this->showMessage('The verification email has been resend');
    }


    
}
