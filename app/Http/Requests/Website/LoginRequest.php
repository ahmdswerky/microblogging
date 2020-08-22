<?php

namespace App\Http\Requests\Website;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    public $user;

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
            'email' => 'required|email|exists:users',
            'password' => 'required|min:4',
        ];
    }

    public function authenticateUser()
    {
        if ( Auth::attempt($this->only('email', 'password')) ) {
            $this->user = User::whereEmail($this->email)->first();
        } else {
            throw ValidationException::withMessages([
                'email' => [__('auth.failed')],
            ]);
        }

        return $this->user->createToken('login')->accessToken;
    }
}
