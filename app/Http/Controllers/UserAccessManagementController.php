<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Throwable;

class UserAccessManagementController extends Controller
{
    public function index()
    {
        $dataUser = User::where('role', '!=', 'admin')->get();
        return view('admin.user-access.index', [
            'user' => auth()->user(),
            'users' => $dataUser
        ]);
    }

    public function create()
    {
        return view('admin.user-access.create', [
            'user' => auth()->user(),
        ]);
    }

    public function store(Request $request)
    {
        try{
            DB::transaction(function() use($request) {
                User::create([
                    'name' => $request->dataToSend['name'],
                    'email' => $request->dataToSend['email'],
                    'password' => Hash::make($request->dataToSend['password']),
                    'role' => $request->dataToSend['role']
                ]);
            });
        } catch(Throwable $e) {
            return response()->json(['message' => $e->getMessage], 500);
        }

        return response()->json(['message' => 'Successfully created a new user'], 200);
    }

    public function delete($id) 
    {
        $user = User::where('id', $id)->first();
        if(empty($user)) {
            return response()->json(['message' => 'User not found.'], 500);
        }

        try {
            $user->delete();
        } catch(Throwable $e) {
            return response()->json(['message' => $e->getMessage], 500);
        }

        return response()->json(['message' => 'Successfully delete user'], 200);
    }

    public function view($id) 
    {
        $user = User::where('id', $id)->first();
        if(empty($user)) {
            abort(404);
        }

        return view('admin.user-access.view', [
            'user' => $user 
        ]);
    }

    public function update($id, Request $request) 
    {
        $user = User::where('id', $id)->first();
        if(empty($user)) {
            return response()->json(['message' => 'User not found.'], 500);
        }

        try {
            DB::transaction(function() use($request, $user) {
                $user->update([
                    'name' => $request->dataToSend['name'],
                    'email' => $request->dataToSend['email'],
                    'password' => Hash::make($request->dataToSend['password']),
                    'role' => $request->dataToSend['role']
                ]);
            });
        } catch(Throwable $e) {
            return response()->json(['message' => $e->getMessage], 500);
        }

        return response()->json(['message' => 'Successfully update user.'], 200);
    }   
}
