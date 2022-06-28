<?php

namespace App\Http\Requests;

use Anik\Form\FormRequest;

class PersonCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() : bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules() : array
    {
        // validate uniqueness of name/birthdate/timezone combination
        return [
          'name' => 'required|string|unique:people,name,NULL,_id,birthdate,' . request('birthdate') . ',timezone,' . request('timezone'),
          'birthdate' => 'required|date|unique:people,birthdate,NULL,_id,name,' . request('name') . ',timezone,' . request('timezone'),
          'timezone' => 'required|timezone|unique:people,timezone,NULL,_id,name,' . request('name') . ',birthdate,' . request('birthdate'),
        ];
    }

}
