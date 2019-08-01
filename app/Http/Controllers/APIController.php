<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class APIController extends Controller
{
    public function login(Request $request)
    {
    	// Validation
        $validator = \Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => [
                	'code' => 422,
                	'message' => $validator->errors()->first()
                ],
            ]);
        }

        // Find the user
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
        		'info' => 'Both admin and user should be able to login.',
                'status' => [
                	'code' => 404,
                	'message' => 'User not exist.'
                ],
            ]);
        }

        // Verify password
        if (!password_verify($request->password, $user->password)) {
        	return response()->json([
        		'info' => 'Both admin and user should be able to login.',
                'status' => [
                	'code' => 401,
                	'message' => 'Wrong credentials.'
                ],
            ]);
        }

        // Generate token
    	$token = $user->createToken('AccessToken')->accessToken;

    	return response()->json([
        	'info' => 'Both admin and user should be able to login.',
            'status' => [
            	'code' => 200,
            	'message' => 'Access granted!'
            ],
            'token' => $token,
        ]);
    }

    public function list(Request $request)
    {
    	// Validation
        $validator = \Validator::make($request->all(), [
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => [
                	'code' => 422,
                	'message' => $validator->errors()->first()
                ],
            ]);
        }

        // Get all listings by logged in user
    	$listings = auth()->user()->listings()
    		->select('id','list_name','latitude','longitude')
    		->get()
    		->map(function($listing) {
	    		$listing['distance'] = $listing->distanceFrom(request()->latitude, request()->longitude);
	    		return $listing->only('id','list_name','distance');
	    	});

    	return response()->json([
        	'info' => 'Admin/user only allowed to view own listings only',
            'status' => [
            	'code' => 200,
            	'message' => 'Listings successfully retrieved!'
            ],
            'listings' => $listings,
        ]);
    }

    public function update(Request $request)
    {
    	// Validation
        $validator = \Validator::make($request->all(), [
            'list_name' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => [
                	'code' => 422,
                	'message' => $validator->errors()->first()
                ],
            ]);
        }

        // Find the listing by logged in user
    	$listing = auth()->user()->listings()->find($request->id);

    	if (!$listing) {
            return response()->json([
        		'info' => 'Admin/user only allowed to update own listings only',
                'status' => [
                	'code' => 404,
                	'message' => 'Listing not found or inaccessible.'
                ],
            ]);
        }

        // Update the listing
        $listing->update($request->all());

        return response()->json([
        	'info' => 'Admin/user only allowed to update own listings only',
            'status' => [
            	'code' => 200,
            	'message' => 'Listings successfully updated!'
            ],
        ]);
    }
}
