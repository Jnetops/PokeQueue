<?php

namespace App\Traits;

trait GoogleApi
{
  public function Timezone($lat, $lon)
  {
    $geoTimezone = "https://maps.googleapis.com/maps/api/timezone/json?location=" . $lat . "," . $lon . "&timestamp=" . time();
    $tz_json = file_get_contents($geoTimezone);
    $tz = json_decode($tz_json, true);

    if ($tz['status'] != 'OK')
    {
      if ($tz['status'] == 'OVER_QUERY_LIMIT')
      {
        return array('Success' => 'False', 'Error' => 'Unable to gather timezone');
      }
      elseif ($tz['status'] == 'ZERO_RESULTS')
      {
        for ($i = 0; $i < 5; $i++)
        {
          $tz_json = file_get_contents($geoTimezone);
          $tz = json_decode($tz_json, true);
          if ($tz['status'] == 'OK')
          {
            $timezone = $tz['timeZoneId'];
            return array('Success' => 'True', 'timezone' => $timezone);
          }
        }
        return array('Success' => 'False', 'Error' => 'Unable to gather timezone');
      }
      elseif ($tz['status'] == 'REQUEST_DENIED')
      {
        return array('Success' => 'False', 'Error' => 'Unable to gather timezone');
      }
      elseif ($tz['status'] == 'INVALID_REQUEST')
      {
        return array('Success' => 'False', 'Error' => 'Unable to gather timezone');
      }
      elseif ($tz['status'] == 'UNKNOWN_ERROR')
      {
        for ($i = 0; $i < 5; $i++)
        {
          $tz_json = file_get_contents($geoTimezone);
          $tz = json_decode($tz_json, true);
          if ($tz['status'] == 'OK')
          {
            $timezone = $tz['timeZoneId'];
            return array('Success' => 'True', 'timezone' => $timezone);
          }
        }
        return array('Success' => 'False', 'Error' => 'Unable to gather timezone');
      }
    }
    elseif ($tz['status']=='OK'){
      $timezone = $tz['timeZoneId'];
      return array('Success' => 'True', 'timezone' => $timezone);
    }
  }

  public function GoogleLocation($address, $type)
  {
    if ($type == 'address')
    {
      $geoURL = "https://maps.googleapis.com/maps/api/geocode/json?address=" . urlencode($address);
    }
    else {
      $geoURL = "https://maps.googleapis.com/maps/api/geocode/json?latlng=" . urlencode($address) . '&sensor=false';
    }

    $resp_json = file_get_contents($geoURL);
    $resp = json_decode($resp_json, true);
    if ($resp['status'] != 'OK')
    {
      if ($resp['status'] == 'OVER_QUERY_LIMIT')
      {
        return array('Success' => 'False', 'Error' => 'Unable to gather location');
      }
      elseif ($resp['status'] == 'ZERO_RESULTS')
      {
        for ($i = 0; $i < 5; $i++)
        {
          $resp_json = file_get_contents($geoURL);
          $resp = json_decode($resp_json, true);
          if ($resp['status'] == 'OK')
          {
            $lat = $resp['results'][0]['geometry']['location']['lat'];
            $lon = $resp['results'][0]['geometry']['location']['lng'];
            return array('Success' => 'True', 'lat' => $lat, 'lon' => $lon, 'results' => $resp);
          }
        }
        return array('Success' => 'False', 'Error' => 'Unable to gather location');
      }
      elseif ($resp['status'] == 'REQUEST_DENIED')
      {
        return array('Success' => 'False', 'Error' => 'Unable to gather location');
      }
      elseif ($resp['status'] == 'INVALID_REQUEST')
      {
        return array('Success' => 'False', 'Error' => 'Unable to gather location');
      }
      elseif ($resp['status'] == 'UNKNOWN_ERROR')
      {
        for ($i = 0; $i < 5; $i++)
        {
          $resp_json = file_get_contents($geoURL);
          $resp = json_decode($resp_json, true);
          if ($resp['status'] == 'OK')
          {
            $lat = $resp['results'][0]['geometry']['location']['lat'];
            $lon = $resp['results'][0]['geometry']['location']['lng'];
            return array('Success' => 'True', 'lat' => $lat, 'lon' => $lon, 'results' => $resp);
          }
        }
        return array('Success' => 'False', 'Error' => 'Unable to gather location');
      }
    }
    elseif($resp['status']=='OK'){
      $lat = $resp['results'][0]['geometry']['location']['lat'];
      $lon = $resp['results'][0]['geometry']['location']['lng'];
      return array('Success' => 'True', 'lat' => $lat, 'lon' => $lon, 'results' => $resp['results']);
    }
  }
}
