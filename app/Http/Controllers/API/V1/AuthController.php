<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\{ Request, Response };
use App\Models\{User, Restaurant, ForgotPassword};
use App\Helpers\Utilities;
use Illuminate\Support\Facades\{Hash, Http, Log};
use Exception;
use Illuminate\Auth\Events\PasswordReset;
use App\Notifications\ResetPasswordNotification;
use Google\Client as GoogleClient;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function login(Request $request) {
        try {
            $request->validate([
                'email'=> 'required|email',
                'password'=> 'required'
            ]);
            $user = User::where('email', $request->input('email'))->first();
            if(!$user || !Hash::check($request->password, $user->password)) {
                return Response()->json(['message'=> 'Ivalid email or password'], 422)->header('Content-Type', 'application/json');
            }
            $user->tokens()->delete();
            // check if user has restaurant
            $token = $user->createToken($request->email);
            $token_expires = config('sanctum.expiration');
            $user = ['firstname'=> $user->firstname, 'lastname'=> $user->lastname, 'id'=> $user->id, 'role'=> $user->role];
            return Response()->json(['token'=> $token->plainTextToken, 'user'=>$user, 'expires_in'=> $token_expires])->header('Content-Type', 'application/json');
        } catch (Exception $e) {
            return Utilities::errorsHandler($e);
        }
    }
    public function register(Request $request) {
        try {
            $request->validate([
                'firstname' => 'required|max:50',
                'lastname' => 'required|max:50',
                'email' => 'required',
                'password' => 'required|confirmed',
                'photo' => 'nullable|max:100',
                // 'account_confirmation' => 'boolean',
                'email_verified_at' => 'nullable'
            ]);
            $userExists = User::where('email', $request->email)->first();
            if($userExists):
                $msg =$userExists->google_user ? "GU-- ?? --GU" : "SU-- ?? --SU";
                return Response()->json(['message'=> $msg], 409)->header('Content-Type','application/json');
            endif;
            $user = new User;
            $user->firstname = $request->firstname;
            $user->lastname = $request->lastname;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->photo = $request->photo;
            $user->role = $request->role ?? 'client';
            $user->google_user = false;
            $user->account_confirmation = false;
            $user->plans_id = 1;
            $user->save();
            $token = $user->createToken($request->email);
            $token_expires = config('sanctum.expiration');
            $user = ['firstname'=> $user->firstname, 'lastname'=> $user->lastname, 'id'=> $user->id];
            return Response()->json(['user'=> $user, 'token'=> $token->plainTextToken, 'expires_in'=> $token_expires], 200)->header('Content-Type', 'application/json');
        }
        catch (Exception $e) {
            return Utilities::errorsHandler($e);
        }
    }

    public function forgotPassword (Request $request) {
        try {
            $request->validate([
                'email'=> 'required|email'
            ]);
            $user = User::where('email', $request->email)->first();
            if(!$user) {
                return Response()->json(['message'=> 'Account not found'], 404)->header('Content-Type', 'application/json');
            }
            $resetPasswordCode = str_pad(random_int(1, 9999), '0', STR_PAD_LEFT);
            $forgotPass;
            if(!ForgotPassword::where('email', $request->email)->first()) {
                $forgotPass = new ForgotPassword;
                $forgotPass->email = $request->email;
                $forgotPass->token = $resetPasswordCode;
                $forgotPass->save();
            } else {
                ForgotPassword::where('email', $request->email)->update([
                    'token'=> $resetPasswordCode
                ]);
            }
            $user->notify(
                new ResetPasswordNotification($user, $resetPasswordCode)
            );
            return Response()->json(['success'=> 'We send a code to your mail, checkout your mailbox!', 'created_at'=> time()], 200)->header('Content-Type', 'application/json');
        } catch (Exception $e) {
            return Utilities::errorsHandler($e);
        }
    }
    public function resetPassword (Request $request) {
        try {
            $request->validate([
                'email'=> 'required|email',
                'code'=> 'required',
                'password'=> 'required|confirmed'
            ]);
            $user = ForgotPassword::where('email', $request->email)->first();
            if(!$user) {
                return Response()->json(['message'=> 'Account not found'], 404)->header('Content-Type', 'application/json');
            }
            if($user->token != $request->code) {
                return Response()->json(['message'=> 'Invalid Code'], 422)->header('Content-Type', 'application/json');
            }
            $user = User::where('email', $request->email);
            $user->update([
                'password'=> Hash::make($request->password)
            ]);
            $user = $user->first();
            $user->tokens()->delete();
            $token = $user->createToken($user->email);
            return Response()->json(['token'=> $token->plainTextToken])->header('Content-Type', 'application/json');
        } catch (Exception $e) {
            return Utilities::errorsHandler($e);
        }
    }

    public function logout (Request $request) {
        try {
            $request->user()->tokens()->delete();
            return Response()->json(['logout'=> 'logout successfully']);
        } catch (Exception $e) {
            return Utilities::errorsHandler($e);
        }
    }

    public function googleAuth(Request $request)
    {
        try {
            $request->validate([
                'code' => 'required'
            ]);
            $rndStr = new Utilities();
            $googleUser = Socialite::driver('google')->stateless()->user();
            $guser = $googleUser->user;
            $user = User::firstOrCreate(
                ['email' => $guser['email']],
                [
                    'firstname' => $guser['given_name'] ?? $rndStr->randomStr(4),
                    'lastname' => $guser['family_name'] ?? $rndStr->randomStr(3), 
                    'email' => $guser['email'],
                    'account_confirmation' => $guser['email_verified'] ?? false,
                    'google_user' => true,
                    'email_verified_at' => now(),
                    'password' => Hash::make(Str::random(16)), 
                    'photo' => $guser['picture'] ?? null,
                    'plans_id' => 1,
                ]
            );
            $user->tokens()->delete();
            $token = $user->createToken($guser['email']);
            $token_expires = config('sanctum.expiration');

            $userData = [
                'firstname' => $user->firstname,
                'lastname' => $user->lastname,
                'id' => $user->id,
            ];
            return response()->json([
                'token' => $token->plainTextToken,
                'user' => $userData,
                'expires_in' => $token_expires,
            ]);
        } catch (Exception $e) {
            return Utilities::errorsHandler($e);
        }
    }

    public function update(Request $request) {
        try {
            $request->validate([
                'firstname' => 'required|max:50',
                'lastname' => 'required|max:50',
                'file' => 'nullable|max:3072',
            ]);
            $path;
            if($request->file) {
                $path = $request->file('file')->store('users', 'public');
            }else {
                $path = null;
            }
            $user = User::find($request->user()->id);
            $user->firstname = $request->firstname;
            $user->lastname = $request->lastname;
            // $user->email = $request->email;
            if($user->photo && $path != null) {
                $filePath = public_path('assets/'.$user->photo);
                if(file_exists($filePath)):
                    unlink($filePath);
                endif;
            }
            if($path) {
                $user->photo = $path;
            }
            $user->save();
            return Response()->json(['user'=> $user]);
        } catch (Exception $e) {
            return Utilities::errorsHandler($e);
        }
    }

    public function getUsers(Request $request) {
        try {
            $users = User::all();
            return Response()->json(['users'=> $users]);
        } catch (Exception $e) {
            return Utilities::errorsHandler($e);
        }
    }

    public function show (Request $request) {
        try {
            $user = $request->user();
            return $user;
        } catch (Exception $e) {
            return Utilities::errorsHandler($e);
        }
    }
}
