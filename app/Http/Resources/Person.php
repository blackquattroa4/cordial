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
    $information = $this->getTimeSensitiveInformation($request->input('timestamp'));

    return [
      'id' => $this->id,
      'name' => $this->name,
      'birthdate' => $this->birthdate,
      'timezone' => $this->timezone,
      'isBirthday' => $information['isBirthday'],
      'interval' => array_merge($information['interval'], [ '_comment' => 'possibly other fields...' ]),
      'message' => $this->name . ' is ' . ($information['upComingBirthday']->format("Y") - $information['birthday']->format("Y")) . ' years old ' .
        ($information['isBirthday'] ?
        'today (' . $information['interval']['h'] . ' hours remaining in ' . $this->timezone . ')' :
        'in ' . $information['interval']['m'] . ' months, ' . $information['interval']['d'] . ' days in ' . $this->timezone),
    ];
  }
}
