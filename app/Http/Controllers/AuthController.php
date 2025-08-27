<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Show the login form
     */
    public function showLogin()
    {
        return view('pages.auth.login');
    }

    /**
     * Handle login attempt
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string',
            'password' => 'required',
            'g-recaptcha-response' => 'required',
        ], [
            'g-recaptcha-response.required' => 'Mohon selesaikan verifikasi reCAPTCHA.',
        ]);

        // Verify reCAPTCHA
        $recaptchaResponse = $request->input('g-recaptcha-response');
        if (!$this->verifyRecaptcha($recaptchaResponse)) {
            return back()->withErrors([
                'g-recaptcha-response' => 'Verifikasi reCAPTCHA gagal. Mohon coba lagi.',
            ])->withInput($request->only('email'));
        }

        $loginField = $request->input('email');
        $password = $request->input('password');

        // Try to login with email or username
        $credentials = filter_var($loginField, FILTER_VALIDATE_EMAIL) 
            ? ['email' => $loginField, 'password' => $password]
            : ['username' => $loginField, 'password' => $password];

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            
            $user = Auth::user();
            
            // Redirect based on user role
            return $this->redirectBasedOnRole($user->role);
        }

        return back()->withErrors([
            'email' => 'Email/Username atau password tidak sesuai.',
        ])->withInput($request->only('email'));
    }

    /**
     * Handle logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('login');
    }

    /**
     * Redirect user based on their role
     */
    private function redirectBasedOnRole($role)
    {
        switch ($role) {
            case 'superadmin':
                return redirect()->route('dashboard');
            case 'admin_marketing':
                return redirect()->route('dashboard');
            case 'admin_purchasing':
                return redirect()->route('dashboard');
            case 'admin_keuangan':
                return redirect()->route('dashboard');
            default:
                return redirect()->route('dashboard');
        }
    }

    /**
     * Verify reCAPTCHA response
     */
    private function verifyRecaptcha($recaptchaResponse)
    {
        $secretKey = config('services.recaptcha.secret_key');
        
        if (empty($secretKey) || empty($recaptchaResponse)) {
            return false;
        }
        
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://www.google.com/recaptcha/api/siteverify',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query([
                'secret' => $secretKey,
                'response' => $recaptchaResponse,
                'remoteip' => request()->ip()
            ]),
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYPEER => true,
        ]);
        
        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
        
        if ($httpCode !== 200 || $response === false) {
            return false;
        }
        
        $result = json_decode($response, true);
        
        return isset($result['success']) && $result['success'] === true;
    }
}
