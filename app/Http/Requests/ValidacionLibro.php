<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ValidacionLibro extends FormRequest
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
            'titulo'=>'required|max:100',
            'isbn'=>'required|max:30|unique:libro,isbn' . $this->route(' id'),
            'autor'=>'required|max:100',
            'cantidad'=>'required|numeric',
            'editorial'=>'nullable|max:50',
            'foto_up'=>'nullable|image',
            'drive_id_original'=>'nullable|max:100',
            'drive_id_recortada'=>'nullable|max:100',
            'drive_id_miniatura'=>'nullable|max:100',
        ];
    }
}
