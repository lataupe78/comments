<?php

namespace App\Http\Requests;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class StoreCommentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        Validator::extend('poly_exists', function ($attribute, $value, $parameters, $validator) {

            //dump($value);
            //dump($parameters);

            if (! $type = array_get($validator->getData(), $parameters[0], false)) {
                return false;
            }
            if (Relation::getMorphedModel($type)) {
                $type = Relation::getMorphedModel($type);

            }
            //dump('type:' . $type);

            if (! class_exists($type)) {
                return false;
            }

            return ! empty(resolve($type)->find($value));
        });

        return [
            'email' => ['required', 'email'],
            'content' => 'required',
            'username' => ['required', 'max:255'],
            'commentable_type' => 'required',
            'commentable_id' => ['required', 'poly_exists:commentable_type'],
            'reply_to' => ['sometimes', 'poly_exists:commentable_type']
        ];
    }

}
