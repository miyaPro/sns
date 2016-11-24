<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

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
        $this->input = $this->all();
        return [
            'group'         => 'required|max:255',
            'code' => [
                'required',
                Rule::unique('masters')->ignore($this->input['id'], 'id')
                    ->where(function ($query) {
                        $query->where('code', $this->input['code']);
                        $query->where('group', $this->input['group']);
                        $query->whereNull('deleted_at');
                    }),
            ],
            'name_ja'       => 'max:255',
            'name_vn'       => 'max:255',
            'name_en'       => 'max:255',
            'active_flg'    => 'numeric',
            'attr1'         => 'max:255',
        ];
    }
}
