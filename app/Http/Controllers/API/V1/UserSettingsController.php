<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\Utilities;
use App\Models\UserSettings;

class UserSettingsController extends Controller
{
    public function changeUserSettings(Request $request, string $id)
    {
        try {
            $request->validate([
                'active_restaurant'=> 'required|exists:restaurant,id'
            ]);
            $userSettings = UserSettings::findOrFail($id);
            $userSettings->active_restaurant = $request->active_restaurant;
            $userSettings->save();

            return response()->json(['settings'=> $userSettings], 200)->header('Content-Type', 'application');
        } catch (Exception $e) {
            return Utilities::errorsHandler($e);
        }
    }
}
