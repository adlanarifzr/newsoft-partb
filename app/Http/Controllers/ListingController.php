<?php

namespace App\Http\Controllers;

use App\Listing;
use Illuminate\Http\Request;

class ListingController extends Controller
{
    public function index(Request $request)
    {
    	if($request->ajax()) {
    		$listings = Listing::with('submitter');

    		return datatables($listings)
    			->editColumn('action', function($listing) {
    				$buttons = '';

    				$buttons .= '<a href="javascript:;" class="btn btn-secondary btn-sm text-white mr-1" onclick="edit('.$listing->id.')"><i class="fa fa-edit"></i></a>';
    				$buttons .= '<a href="javascript:;" class="btn btn-danger btn-sm text-white mr-1" onclick="remove('.$listing->id.')"><i class="fa fa-trash"></i></a>';

    				return $buttons;
    			})
    			->make();
    	}

    	return view('listings.index');
    }

    public function insert(Request $request)
    {
        // Validation
        $validator = \Validator::make($request->all(), [
            'list_name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'title' => 'Error',
                'message' => $validator->errors()->first()
            ]);
        }

        // Create new model
        $listing = auth()->user()->listings()->create($request->all());

    	return response()->json([
    		'status' => 'success',
    		'title' => 'Success!',
    		'message' => 'The data has been added successfully.',
    	]);
    }

    public function view(Request $request)
    {
    	$listing = Listing::findOrFail($request->id);
    	return view('listings.view', compact('listing'));
    }

    public function update(Request $request)
    {
        // Validation
        $validator = \Validator::make($request->all(), [
            'list_name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'title' => 'Error',
                'message' => $validator->errors()->first()
            ]);
        }

        // Find the listing (Assuming admin can change any listing by other user)
    	$listing = Listing::findOrFail($request->id);

        // Update the listing
        $listing->update($request->all());

    	return response()->json([
    		'status' => 'success',
    		'title' => 'Success!',
    		'message' => 'The data has been updated successfully.',
    	]);
    }

    public function delete(Request $request)
    {
    	$listing = Listing::findOrFail($request->id);
    	$listing->delete();

    	return response()->json([
    		'status' => 'success',
    		'title' => 'Success!',
    		'message' => 'The data has been deleted successfully.',
    	]);
    }
}
