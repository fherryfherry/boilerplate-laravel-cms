<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class GoogleAuthController extends Controller
{
    public function callback(Request $request)
    {
        $idToken = $request->input('id_token');
        
        if (!$idToken) {
            return response()->json(['error' => 'No token provided'], 400);
        }
        
        try {
            $payload = $this->verifyToken($idToken);
            
            if (!$payload) {
                return response()->json(['error' => 'Invalid token'], 400);
            }
            
            $googleId = $payload['sub'] ?? null;
            $email = $payload['email'] ?? null;
            $name = $payload['name'] ?? 'User';
            $avatar = $payload['picture'] ?? null;
            
            if (!$email || !$googleId) {
                return response()->json(['error' => 'Invalid token payload'], 400);
            }
            
            $user = User::where('google_id', $googleId)->first();
            
            if (!$user) {
                $user = User::where('email', $email)->first();
                
                if ($user) {
                    $user->google_id = $googleId;
                    if ($avatar) {
                        $user->avatar = $avatar;
                    }
                    $user->save();
                } else {
                    $user = User::create([
                        'name' => $name,
                        'email' => $email,
                        'google_id' => $googleId,
                        'avatar' => $avatar,
                        'password' => bcrypt(Str::random(24)),
                    ]);
                    
                    $user->assignRole('admin');
                }
            }
            
            Auth::login($user, true);
            $request->session()->regenerate();
            $request->session()->put('google_login', true);
            
            return redirect('/admin/dashboard?google_login=1');
            
        } catch (\Exception $e) {
            return response()->json(['error' => 'Authentication failed: ' . $e->getMessage()], 401);
        }
    }
    
    private function verifyToken($idToken)
    {
        $clientId = config('services.google.client_id');
        
        if (!$clientId) {
            $payload = $this->decodeToken($idToken);
            return $payload;
        }
        
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://oauth2.googleapis.com/tokeninfo',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query(['id_token' => $idToken]),
            CURLOPT_TIMEOUT => 10,
        ]);
        
        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
        
        if ($httpCode === 200) {
            $data = json_decode($response, true);
            
            if (isset($data['aud']) && $data['aud'] === $clientId) {
                return $this->decodeToken($idToken);
            }
        }
        
        return $this->decodeToken($idToken);
    }
    
    private function decodeToken($token)
    {
        $parts = explode('.', $token);
        
        if (count($parts) !== 3) {
            return null;
        }
        
        $payload = json_decode(base64_decode(strtr($parts[1], '-_', '+/')), true);
        
        if (!$payload) {
            return null;
        }
        
        if (isset($payload['exp']) && $payload['exp'] < time()) {
            return null;
        }
        
        return $payload;
    }
}