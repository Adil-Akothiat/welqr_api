<?php

namespace App\Http\Controllers\API\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;
use App\Helpers\Utilities;
use App\Models\Visit;

class VisitController extends Controller
{
    public function getVisitByQrcodeId($qrcodeId) {
        try {
            // $visits = Visit::where('qrcode_id', $qrcodeId);
            $visits = Visit::selectRaw('DATE(created_at) as day, COUNT(*) as count')
            ->where('qrcode_id', $qrcodeId)
            ->groupBy('day')
            ->orderBy('day', 'asc')
            ->get();
            return Response()->json(['visits'=> $visits])->header('Content-Type', 'application/json');
        } catch (Exception $e) {
            return Utilities::errorsHandler($e);
        }
    }
    public function create(Request $request) {
        try {
            $validated = $request->validate([
                'url' => 'required|string',
                'referrer' => 'nullable|string',
                'userAgent' => 'nullable|string',
                'qrcode_id' => 'required'
            ]);

            $visit = Visit::create([
                'url' => $validated['url'],
                'referrer' => $validated['referrer'] ?? null,
                'userAgent' => $validated['userAgent'],
                'qrcode_id' => $validated['qrcode_id'],
            ]);

            return response()->json(['visit' => $visit], 201)->header('Content-Type', 'application/json');
        } catch (Exception $e) {
            return Utilities::errorsHandler($e);
        }
    }
}