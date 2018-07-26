<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use View;
use App\Traits\ValidateInput;
use App\Http\Models\Profile;

class ProfileController extends Controller
{
    use ValidateInput;
    public function Update_Profile(Request $request) {
      $profile = new Profile();
      $profileUpdate = $profile->updateProfile($request);
      echo $profileUpdate;
    }

    public function Update_Password(Request $request) {
      $profile = new Profile();
      $profileUpdate = $profile->updatePassword($request);
      return $profileUpdate;
    }

    public function Update_Team(Request $request) {
      $profile = new Profile();
      $profileUpdate = $profile->updateTeam($request);
      echo $profileUpdate;
    }

    public function Update_Level(Request $request) {
      $profile = new Profile();
      $profileUpdate = $profile->updateLevel($request);
      echo $profileUpdate;
    }

    public function Update_Location(Request $request) {
      $profile = new Profile();
      $profileUpdate = $profile->updateLocation($request);
      echo $profileUpdate;
    }

    public function get_Profile(Request $request) {
      $profile = new Profile();
      $getProfile = $profile->getProfile($request);
      return View::make('profile', ['profile' => $getProfile]);
    }

    public function Get_User(Request $request, $userID) {
      $profile = new Profile();
      $getUser = $profile->getUser($request, $userID);
      return View::make('user_profile', ['profile' => $getUser]);
    }

    public function Update_Trainer(Request $request)
    {
      $validateResults = $this->trainerNameValidate($request);

      if($validateResults->passes()) {
        $profile = new Profile();
        $change = $profile->updateTrainerName($request);
        return $change;

      } else {
        return 'Failed';
      }
    }

}
