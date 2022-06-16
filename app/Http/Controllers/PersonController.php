<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Person;
use App\Http\Resources\Person as PersonResource;

class PersonController extends Controller
{
  CONST PER_PAGE = 2;

  // helper function to check if a Person is unique in database
  private function isUnique($input, $exclusionId = null)
  {
    $query = Person::where('name', $input['name'])
      ->where('birthdate', $input['birthdate'])
      ->where('timezone', $input['timezone']);

    if (isset($exclusionId)) {
      $query->where( '_id', '<>', $exclusionId );
    }

    return $query->count() == 0;
  }

  // helper function to mimic validation error when failing uniqueness check
  private function duplicationError()
  {
    $message = "name-birthdate-timezone combination not unique";

    return response()->json([ 'name' => [ $message ], 'birthdate' => [ $message ], 'timezone' => [ $message ] ], 422);
  }

  // controller to retrieve list of Person
  public function list(Request $request)
  {
    $query = Person::query();

    // pagination in case data set is huge
    if ($request->has('page') && $request->has('per_page')) {
      $query->skip($request->input('page') * $request->input('per_page'))->take($request->input('per_page'));
    }

    $people = $query->get();

    return response()->json([ 'data' => PersonResource::collection($people) ]);
  }

  // controller to create new Person
  public function create(Request $request)
	{
    // basic validation
    $this->validate($request, [
      'name' => 'required|string',
      'birthdate' => 'required|date',
      'timezone' => 'required|timezone',
    ]);

    // check for uniqueness
    if (!$this->isUnique($request->input())) {
      return $this->duplicationError();
    }

    $person = Person::create( $request->input() );

    return response()->json([ 'data' => new PersonResource($person) ]);
  }

  // controller to update an existing Person
  public function update(Request $request, $id)
  {
    // basic validation
    $this->validate($request, [
      'name' => 'string',
      'birthdate' => 'date',
      'timezone' => 'timezone',
    ]);

    $person = Person::find($id);

    // check for uniqueness
    if (!$this->isUnique(array_merge([ 'name' => $person->name, 'birthdate' => $person->birthdate, 'timezone' => $person->timezone ], $request->input()), $id)) {
      return $this->duplicationError();
    }

    foreach($request->input() as $key => $value)
    {
      $person->{$key} = $value;
    }
    $person->save();

    return response()->json([ 'data' => new PersonResource($person) ]);
  }

  // controller to delete an existing person
  public function delete(Request $request, $id)
  {
    $person = Person::find($id)->delete();

    return response()->json([ 'data' => null ]);
  }

}