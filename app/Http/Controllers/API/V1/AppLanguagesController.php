<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AppLanguage;
use App\Helpers\Utilities;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Exception;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

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
                "code"=>"required|unique:app_languages",
                "file"=>"required|mimes:svg"
            ]);

            $file = public_path("locales/{$request->code}/translation.json");
            $relativePath = Str::after($file, public_path() . DIRECTORY_SEPARATOR);;
            $directory = public_path("locales/{$request->code}");
            if (!File::exists($directory)) {
                File::makeDirectory($directory, 0755, true);
            }
            File::put($file, json_encode([], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

            $path = $request->file('file')->store('app/languages/falgs', 'public');
            $appLanguage = new AppLanguage;
            $appLanguage->language = $request->language;
            $appLanguage->code = $request->code;
            $appLanguage->jsonPath = $relativePath;
            $appLanguage->icon = $path;
            $appLanguage->save();
            return Response()->json(['language'=> $appLanguage], 200)->header('Content-Type', 'application/json');
        } catch (Exception $e) {
            return Utilities::errorsHandler($e);
        }
    }

    public function show(string $langCode)
    {
        try {
            $path = public_path("locales/{$langCode}/translation.json");
            if (!File::exists($path)) {
                return response()->json(['message' => 'Translation file not found'], 404);
            }
            $contents = File::get($path);
            $json = json_decode($contents, true);
            return Response()->json(['language'=> $json], 200)->header('Content-Type', 'application/json');
        } catch (Exception $e) {
            return Utilities::errorsHandler($e);
        }
    }

    public function update(Request $request, string $id)
    {
        try {
            $request->validate([
                'language'=> 'required',
                // "code"=>"required|unique:app_languages",
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
            $appLanguage->code = $appLanguage->code;
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
    public function updateFile(Request $request, $code) {
        try {
            $request->validate([
                'content'=> 'required'
            ]);
            $appLanguage = AppLanguage::where('code', $code)->first();
            if(!$appLanguage) {
                throw new NotFoundHttpException("language not found");
            }
            $path = public_path($appLanguage->jsonPath);
            File::put($path, json_encode($request->content, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

            return Response('updated', 200)->header('Content-Type', 'plain/text');
        } catch (Exception $e) {
            return Utilities::errorsHandler($e);
        }
    }

    public function getTranslationsFiles (Request $request, $code) {
        try {
            if($code == "all") {
                $appLanguages = AppLanguage::all();
                return Response()->json(['languages'=> $appLanguages], 200)->header('Content-Type', 'application/json');
            }
            $appLanguage = AppLanguage::where('code', $code)->first();
            if(!$appLanguage) {
                throw new NotFoundHttpException("language not found");
            }
            if (!File::exists($appLanguage->jsonPath)) {
                throw new NotFoundHttpException("Translation file not found");
            }
            $contents = File::get($appLanguage->jsonPath);
            $json = json_decode($contents, true);
            return Response()->json(['translation'=> $json], 200)->header('Content-Type', 'application/json');
        } catch (Exception $e) {
            return Utilities::errorsHandler($e);
        }
    }
}