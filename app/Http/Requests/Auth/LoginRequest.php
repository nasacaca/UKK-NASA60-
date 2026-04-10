<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'login' => ['required', 'string'], // Bisa email atau NIS
            'password' => ['required', 'string'],
        ];
    }

    public function authenticate(): void
    {
        // Mencoba login menggunakan email
        $credentials = ['email' => $this->login, 'password' => $this->password];

        if (! Auth::attempt($credentials, $this->boolean('remember'))) {
            // Jika gagal, coba menggunakan NIS (Nomor Induk Siswa)
            $credentialsNis = ['nis' => $this->login, 'password' => $this->password];

            if (! Auth::attempt($credentialsNis, $this->boolean('remember'))) {
                throw ValidationException::withMessages([
                    'login' => trans('auth.failed'),
                ]);
            }
        }
    }
}
