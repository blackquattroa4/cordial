<?php

namespace App\Http\Requests;

use Anik\Form\FormRequest;

class PersonUpdateRequest extends FormRequest
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
        // extract document id
        $path = request()->pathInfo;
        $id = substr($path, strrpos($path, "/")+1);
        // validate uniqueness of name/birthdate/timezone combination
        return [
          'name' => 'string|unique:people,name,' . $id . ',_id,birthdate,' . request('birthdate') . ',timezone,' . request('timezone'),
          'birthdate' => 'date|unique:people,birthdate,' . $id . ',_id,name,' . request('name') . ',timezone,' . request('timezone'),
          'timezone' => 'timezone|unique:people,timezone,' . $id . ',_id,name,' . request('name') . ',birthdate,' . request('birthdate'),
        ];
    }

}
