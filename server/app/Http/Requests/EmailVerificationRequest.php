<?php

namespace App\Http\Requests;

use App\Models\ChangeEmailRequest;
use Illuminate\Foundation\Auth\EmailVerificationRequest as FormRequest;

class EmailVerificationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if ($this->has('request_id')) {
            $changeEmailRequest = ChangeEmailRequest::findOrFail($this->input('request_id'));

            if (!hash_equals(
                (string) $this->route('hash'),
                sha1($changeEmailRequest->email)
            ) && $this->user()->getKey() === $changeEmailRequest->user->getKey()) {
                return false;
            } else {
                return true;
            }
        }

        return parent::authorize();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }
}
