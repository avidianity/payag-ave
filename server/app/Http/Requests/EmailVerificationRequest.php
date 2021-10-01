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
            $changeEmailRequest = ChangeEmailRequest::with('user')->findOrFail($this->input('request_id'));

            if (!hash_equals(
                (string) $this->route('hash'),
                sha1($changeEmailRequest->email)
            )) {
                return false;
            }

            if ($this->user()->getKey() !== $changeEmailRequest->user->getKey()) {
                return false;
            }

            return true;
        }

        return parent::authorize();
    }
}
