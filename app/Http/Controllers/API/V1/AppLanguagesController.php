<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AppLanguage;
use App\Helpers\Utilities;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Exception;

class AppLanguagesController extends Controller
{
    public function index()
    {
        try {
            $appLanguages = AppLanguage::all();
            return Response()->json(['languages'=> $appLanguages], 200)->header('Content-Type', 'application/json');
        } catch (Exception $e) {
            return Utilities::errorsHandler($e);
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                "language"=>"required",
                "code"=>"required",
                "file"=>"required|mimes:svg"
            ]);
            $path = $request->file('file')->store('app/languages/falgs', 'public');
            $appLanguage = new AppLanguage;
            $appLanguage->language = $request->language;
            $appLanguage->code = $request->code;
            $appLanguage->icon = $path;
            $appLanguage->save();
            return Response()->json(['language'=> $appLanguage], 200)->header('Content-Type', 'application/json');
        } catch (Exception $e) {
            return Utilities::errorsHandler($e);
        }
    }

    public function show(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        try {
            $request->validate([
                'language'=> 'required',
                'code'=> 'required',
                'file'=> 'nullable',
                'path'=> 'nullable'
            ]);
            $path = $request->path;
            if($request->file) {
                $path = $request->file('file')->store('app/languages/falgs', 'public');
            }
            $appLanguage = AppLanguage::find($id);
            if(!$appLanguage) {
                throw new NotFoundHttpException("language not found");
            }
            if($appLanguage->icon && $request->file) {
                $filePath = public_path('assets/'.$appLanguage->icon);
                if(file_exists($filePath)):
                    unlink($filePath);
                endif;
            }
            $appLanguage->language = $request->language;
            $appLanguage->code = $request->code;
            $appLanguage->icon = $path;
            $appLanguage->save();
            return Response()->json(['language'=> $appLanguage], 200)->header('Content-Type', 'application/json');
        } catch (Exception $e) {
            return Utilities::errorsHandler($e);
        }
    }

    public function destroy(string $id)
    {
        try {
            $appLanguage = AppLanguage::find($id);
            if(!$appLanguage) {
                throw new NotFoundHttpException("language not found");
            }
            $appLanguage->delete();
            return Response()->json(['language'=> $appLanguage], 200)->header('Content-Type', 'application/json');
        } catch(Exception $e) {
            return Utilities::errorsHandler($e);
        }
    }
}