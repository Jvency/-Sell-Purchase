<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ProfileFormRequest;
use App\Profile;
use Auth;
use Image;


class UserController extends Controller
{
    //
    public function profile(Request $request) {
    	$user = Auth::user();
    	//dd($user);
    	return view('user.profile', compact('user'));
    }

    public function saveProfile(ProfileFormRequest $request) {

    	$user = Auth::user();
		$profile = $user->profile;

		if($profile == null) {
			$profile = new Profile;
			$profile->user_id = $user->id;
		}

		if ($request->hasFile('avatar')) {
			$avatar = $request->file('avatar');
			$filename = time().'.'.$avatar->getClientOriginalExtension();
			Image::make($avatar)->resize(300, 300)->save( public_path('uploads/users/'.$filename));
			$profile->avatar = $filename;
		}

		foreach ($request->only('gender', 'mobile', 'semester', 'stream') as $key => $value) {
			$profile->$key = $value;
		}


		$profile->save();

		session()->flash('status', 'Saved profile details successfully.');

		return redirect()->route('user.profile');
    }
}

