<?php

namespace App\Http\Requests;

class MasterRequest extends Request
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
        return [
            'group'         => 'required|max:255',
            'code'          => "required|unique:masters,code,NULL,id,deleted_at,NULL",
            'name_ja'       => 'max:255',
            'name_vn'       => 'max:255',
            'name_en'       => 'max:255',
            'active_flg'    => 'numeric',
            'attr1'         => 'max:255',
        ];
    }
}
