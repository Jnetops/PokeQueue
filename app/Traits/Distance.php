<?php

namespace App\Traits;

trait Distance
{
  public function gatherHighLow($lat, $lon, $distance)
  {
    switch ($distance)
    {
      case 10:
        $latLow = $lat - 5*0.018;
        $latHigh = $lat + 5*0.018;
        $lonLow = $lon - 5*0.018;
        $lonHigh = $lon + 5*0.018;
        $distanceCheck = array(
          'latLow' => $latLow,
          'latHigh' => $latHigh,
          'lonLow' => $lonLow,
          'lonHigh' => $lonHigh
        );
        return $distanceCheck;
      case 25:
        $latLow = $lat - 12.5*0.018;
        $latHigh = $lat + 12.5*0.018;
        $lonLow = $lon - 12.5*0.018;
        $lonHigh = $lon + 12.5*0.018;
        $distanceCheck = array(
          'latLow' => $latLow,
          'latHigh' => $latHigh,
          'lonLow' => $lonLow,
          'lonHigh' => $lonHigh
        );
        return $distanceCheck;
      case 50:
        $latLow = $lat - 25*0.018;
        $latHigh = $lat + 25*0.018;
        $lonLow = $lon - 25*0.018;
        $lonHigh = $lon + 25*0.018;
        $distanceCheck = array(
          'latLow' => $latLow,
          'latHigh' => $latHigh,
          'lonLow' => $lonLow,
          'lonHigh' => $lonHigh
        );
        return $distanceCheck;
      default:
        return array('error' => 'Invalid Distance');
    }
  }
}
