<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
    	if($request->ajax()) {
    		$users = User::query();

    		return datatables($users)
    			->editColumn('type', function($user) {
    				return $user->type == 'a' ? '<span class="badge badge-primary">Admin</span>' : '<span class="badge badge-secondary">User</span>';
    			})
    			->editColumn('action', function($user) {
    				$buttons = '';

    				$buttons .= '<a href="javascript:;" class="btn btn-secondary btn-sm text-white mr-1" onclick="edit('.$user->id.')"><i class="fa fa-edit"></i></a>';
    				$buttons .= '<a href="javascript:;" class="btn btn-danger btn-sm text-white mr-1" onclick="remove('.$user->id.')"><i class="fa fa-trash"></i></a>';

    				return $buttons;
    			})
    			->make();
    	}

    	return view('users.index');
    }

    public function insert(Request $request)
    {
        // Validation
        $validator = \Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'type' => 'required|string|size:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'title' => 'Error',
                'message' => $validator->errors()->first()
            ]);
        }

        // Create new model
        $user = User::create($request->all());

    	return response()->json([
    		'status' => 'success',
    		'title' => 'Success!',
    		'message' => 'The data has been added successfully.',
    	]);
    }

    public function view(Request $request)
    {
    	$user = User::findOrFail($request->id);
    	return view('users.view', compact('user'));
    }

    public function update(Request $request)
    {
        // Validation
        $validator = \Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$request->id,
            'new_password' => 'nullable|string|min:8|confirmed',
            'type' => 'required|string|size:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'title' => 'Error',
                'message' => $validator->errors()->first()
            ]);
        }

        // Find the user
    	$user = User::findOrFail($request->id);

        // Update password
        if($request->new_password && !empty($request->new_password)) {
            $request->merge([
                'password' => bcrypt($request->new_password),
            ]);
        }

        // Update the user
        $user->update($request->all());

    	return response()->json([
    		'status' => 'success',
    		'title' => 'Success!',
    		'message' => 'The data has been updated successfully.',
    	]);
    }

    public function delete(Request $request)
    {
    	$user = User::findOrFail($request->id);
    	$user->delete();

    	return response()->json([
    		'status' => 'success',
    		'title' => 'Success!',
    		'message' => 'The data has been deleted successfully.',
    	]);
    }
}
