<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Person extends JsonResource
{
  /**
   * Transform the resource into an array.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return array
   */
  public function toArray($request)
  {
    // convience variable
    $currentZone = new \DateTimeZone($this->timezone);
    // get current time-stamp
    $rightNow = new \DateTime(date("Y-m-d H:i:s", $request->has('timestamp') ? $request->input('timestamp') : time()), $currentZone);

    $birthday = new \DateTime($this->birthdate, $currentZone);
    // determine if today is birthday
    $isBirthday = ($rightNow->format("m") == $birthday->format("m")) && ($rightNow->format("d") == $birthday->format("d"));
    // birthday of current year
    $currentBirthday = new \DateTime($rightNow->format("Y") . "-" . $birthday->format("m-d") . ' 23:59:59', $currentZone);
    // upcoming birthday (closest but not yet occured)
    $upComingBirthday = ($currentBirthday->getTimestamp() > $rightNow->getTimestamp()) ? $currentBirthday : new \DateTime(($rightNow->format("Y") + 1) . "-" . $birthday->format("m-d"), $currentZone);
    // interval between now & upcoming-birthday or end of current birthday
    $interval = $isBirthday ?
      (new \DateTime($rightNow->format("Y-m-d") . " 23:59:59" , $currentZone))->diff($rightNow) :
      $upComingBirthday->diff($rightNow);
    // extract only necessary field
    $interval = array_intersect_key(json_decode(json_encode($interval), true), [ 'y' => '', 'm' => '', 'd' => '', 'h' => '', 'i' => '', 's' => '']);

    return [
      'id' => $this->id,
      'name' => $this->name,
      'birthdate' => $this->birthdate,
      'timezone' => $this->timezone,
      'isBirthday' => $isBirthday,
      'interval' => array_merge($interval, [ '_comment' => 'possibly other fields...' ]),
      'message' => $this->name . ' is ' . ($upComingBirthday->format("Y") - $birthday->format("Y")) . ' years old ' .
        ($isBirthday ?
        'today (' . $interval['h'] . ' hours remaining in ' . $this->timezone . ')' :
        'in ' . $interval['m'] . ' months, ' . $interval['d'] . ' days in ' . $this->timezone),
    ];
  }
}
