<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\{ Request, Response };
use App\Models\Qrcode;
use App\Helpers\Utilities;
use Exception;

class QrcodeController extends Controller
{
    public function create(Request $request) {
        try {
            $request->validate([
                'url'=> 'required|unique:qrcode',
                'name'=> 'required|unique:qrcode'
            ]);
            $qrcode = new Qrcode;   
            $qrcode->url = $request->url;
            $qrcode->name = $request->name;
            $qrcode->save();
            return Response()->json(['qrcode'=> $qrcode, 'success'=>true])->header('Content-Type', 'application/json');
        } catch (Exception $e) {
            return Utilities::errorsHandler($e);
        }
    }
    public function isUnique(Request $request) {
        try {
            $request->validate([
                'name'=> 'required'
            ]);
            $qrcodeExists = Qrcode::where('url', $request->name)->first();
            if($qrcodeExists):
                return Response()->json(['unique'=> false], 422)->header('Content-Type', 'application/json');
            endif;
            return Response()->json(['unique'=> true])->header('Content-Type', 'application/json');
        } catch (Exception $e) {
            return Utilities::errorsHandler($e);
        }
    }
    public function getQrcode($id) {
        try {
            $qrcode = Qrcode::find($id);
            if($qrcode) {
                return Response()->json(['qrcode'=> $qrcode, 'url'=> $qrcode->url], 200)->header('Content-Type','application/json');
            }
            return Response()->json(['message'=> 'Qrcode not found'], 404)->header('Content-Type', 'application/json');
        } catch (Exception $e) {
            return Utilities::errorsHandler($e);
        }
    }
}
