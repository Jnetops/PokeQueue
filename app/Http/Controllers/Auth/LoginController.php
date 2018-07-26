<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Auth;
use App\Http\Models\Profile;
use App\Traits\GoogleApi;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;
    use GoogleApi;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }

    public function username()
    {
        return 'username';
    }

    public function authenticated()
    {
      $position = \Location::get(\Request::ip());
      $city = $position->cityName;
      $state = $position->regionName;
      $lat = $position->latitude;
      $lon = $position->longitude;
      $location = $lat . ',' . $lon;

      if ($city == "")
      {
        $locationCheck = $this->GoogleLocation($location, 'latlng');
        if ($locationCheck['Success'] == 'False')
        {
          return array('Success' => 'False', 'Error' => 'Unable to gather location');
        }
        else {
          foreach ($locationCheck['results'] as $one)
          {
            foreach ($one['address_components'] as $two)
            {
              if (array_key_exists('types', $two))
              {
                if (in_array('locality', $two['types']))
                {
                  $city = $two['long_name'];
                }
                elseif (in_array('administrative_area_level_1', $two['types']))
                {
                  $state = $two['short_name'];
                }
              }
            }
          }
        }
      }

      $user = Auth::user();
      $user->location = $city . ', ' . $state;
      $user->lat = $lat;
      $user->lon = $lon;
      $user->save();
    }


}
