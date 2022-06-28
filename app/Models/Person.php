<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class Person extends Model
{
    // Person model

    // protected $table = 'people';
    protected $collection = 'people';

    protected $connection = 'mongodb';

    protected $fillable = [ 'name', 'birthdate', 'timezone' ];

    // private function to get essential attributes required
    public function getTimeSensitiveInformation($timestamp = null)
    {
      // convience variable
      $currentZone = new \DateTimeZone($this->timezone);
      // get current time-stamp
      $rightNow = $timestamp
        ? new \DateTime(date("Y-m-d H:i:s", $timestamp), $currentZone)
        : (new \DateTime(date("Y-m-d H:i:s", time()), new \DateTimeZone('UTC')))->setTimezone($currentZone)
        ;

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
        'birthday' => $birthday,
        'isBirthday' => $isBirthday,
        'interval' => $interval,
        'upComingBirthday' => $upComingBirthday,
      ];
    }

}
