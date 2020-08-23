<?php

namespace App\Http\Requests\Website;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class RegisterRequest extends FormRequest
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
            'name' => 'required|max:50',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:4',
            'image' => 'sometimes|mimes:jpeg,png',
        ];
    }

    public function registerUser()
    {
        // NOTE: the use of all here is ok while User doesn't have sensitive fields like is_admin, is_blocked in fillables
        $this->user = User::create($this->all());

        $this->uploadImage();

        return $this;
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

        return $this->user->createToken('register')->accessToken;
    }

    protected function uploadImage()
    {
        if ( $this->has('image') ) {
            $this->user->addMedia($this->image, 'photo', [
                'name' => 'image',
            ]);
        }
    }
}
