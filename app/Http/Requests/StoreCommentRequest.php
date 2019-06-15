<?php

namespace App\Http\Requests;

use App\Models\Comment;
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
        //dump($this->attributes);
        Validator::extend('poly_exists', function ($attribute, $value, $parameters, $validator) {
            //dump($value);
            //dump($parameters);
            if (! $type = array_get($validator->getData(), $parameters[0], false)) {
                return false;
            }
            if (Relation::getMorphedModel($type)) {
                $type = Relation::getMorphedModel($type);
            }

            if (! class_exists($type)) {
                return false;
            }

            return ! empty(resolve($type)->find($value));
        });


        Validator::extend('reply_poly_exists', function ($attribute, $value, $parameters, $validator) {

            // ici value est reply_to

            if($value === null){ // commentaire parent, no problem
                return true;
            }
            $parent = Comment::select('id', 'reply_to')
                ->where([
                    'id' =>  $value,
                    'commentable_type' =>  $this->commentable_type,
                    'commentable_id' =>  $this->commentable_id
                ])
                ->first();

            // le parent doit bien exister et son reply_to doit être null
            return (!empty($parent) && $parent->reply_to === null);

        });

        return [
            'email' => ['required', 'email'],
            'content' => 'required',
            'username' => ['required', 'max:255'],
            'commentable_type' => 'required',
            'commentable_id' => ['required', 'poly_exists:commentable_type'],
            'reply_to' => ['sometimes', 'reply_poly_exists']
        ];
    }

}
