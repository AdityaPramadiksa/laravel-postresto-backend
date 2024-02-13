<?php

namespace App\Http\Controllers;

use app\models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\hash;

class UserController extends Controller
{
    //index
    public function index(request $request)
    {
        //get all users with pagination
        $users = DB::table('users')
        ->when($request->input('name'), function ($query, $name) {
            $query->where('name', 'like','%'. $name .'%')
            ->orWhere('email','l'. $name .'%');
        })
        ->paginate(10);
        return view('pages.users.index', compact('users'));
    }

    //create
    public function create()
    {
        return view('pages.users.create');
    }

    //store
    public function store(Request $request)
    {
        //validate the request
        $request->validate([
            'name'=> 'required',
            'email'=> 'required|email|unique:users',
            'password'=> 'required|min:8',
            'role'=>'required|in:admin,staff, user',
        ]);

        //store the request...
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = hash::make($request->password);
        $user->role = $request->role;
        $user->save();

        return redirect()->route('users.index')->with('success','User created succesfully');


    }

    //show
    public function show($id)
    {
        return view('pages.users.show');
    }

    //edit
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('pages.users.edit', compact('user'));
    }

    //update
    public function update(Request $request, $id)
    {
        //validate the request
        $request->validate([
            'name'=> 'required',
            'email' => 'required',
            'role'=>'required|in:admin,staff, user',
        ]);

        //update the request...
    $user = User::find($id);
    $user->name = $request->name;
    $user->email = $request->email;
    $user->role = $request->role;
    $user->save();

    //if password is not empty
    if ($request->Password){
        $user->password = hash::make($request->password);
        $user->save();
    }

    return redirect()->route('users.index')->with('success','User updated succesfully');


    }


    //destroy
    public function destroy($id)
    {
        //delete the request...
        $user = User::find($id);
       $user->delete();

       return response()->json(['success' => true]);
    }
}
