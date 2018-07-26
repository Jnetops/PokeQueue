<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Models\Profile;
use App\Traits\GoogleApi;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;
    use GoogleApi;

    /**
     * Where to redirect users after registration.
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
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'username' => 'required|max:15|unique:users',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:8|confirmed',
            'trainer_name' => 'required|min:4',
            'trainer_level' => 'required|min:1|max:40'

        ]);
    }

    protected function create(array $data)
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

        $timeZoneCheck = $this->Timezone($lat, $lon);


        $s = User::create([
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'trainer_name' => $data['trainer_name'],
            'trainer_level' => $data['trainer_level'],
            'trainer_team' => $data['trainer_team'],
            'location' => $city . ', ' . $state,
            'timezone' => $timeZoneCheck['timezone'],
            'lat' => $lat,
            'lon' => $lon
        ]);

        return $s;
    }
}
