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
            /*
            dump('value:'.$value);
            dump('attribute:'.$attribute);
            dump($parameters);
            dump($parameters[0]);
            */
            if($value === null){
                return true;
            }
            if (! $type = array_get($validator->getData(), $parameters[0], false)) {
                return false;
            }
            /*
            dump('#1 type passed');
            dump('type:'.$type);
            */
            if (Relation::getMorphedModel($type)) {
                $type = Relation::getMorphedModel($type);

            }
            //dump('#2 relation passed');
            //dump('type:' . $type);

            if (! class_exists($type)) {
                return false;
            }

            //dump('#3 class_exists passed');


            $comment = Comment::where([
                'commentable_type' => $type,
                'id' => $value,
                'reply_to' => null
            ])->select('id')->get();

            return !empty($comment);

            /*
            $resolve = resolve($type)->find($value);

            dump(resolve($type));
            dump($resolve);

            if(! empty($resolve)){
                return true;
            }

            dump('#4 resolve passed');

            return false;
            */
        });

        return [
            'email' => ['required', 'email'],
            'content' => 'required',
            'username' => ['required', 'max:255'],
            'commentable_type' => 'required',
            'commentable_id' => ['required', 'poly_exists:commentable_type'],
            'reply_to' => ['sometimes', 'reply_poly_exists:commentable_type']
        ];
    }

}
