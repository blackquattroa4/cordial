<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Person;
use App\Http\Resources\Person as PersonResource;
use App\Http\Requests\PersonCreateRequest;
use App\Http\Requests\PersonUpdateRequest;

class PersonController extends Controller
{
  CONST PER_PAGE = 2;

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
  public function create(PersonCreateRequest $request)
	{
    $validatedData = $request->validated();

    $person = Person::create( $validatedData );

    return response()->json([ 'data' => new PersonResource($person) ]);
  }

  // controller to update an existing Person
  public function update(PersonUpdateRequest $request, Person $person)
  {
    $validatedData = $request->validated();

    $person->fill($validatedData);
    $person->save();

    return response()->json([ 'data' => new PersonResource($person) ]);
  }

  // controller to delete an existing person
  public function delete(Request $request, Person $person)
  {
    $person->delete();

    return response()->json([ 'data' => null ]);
  }

}