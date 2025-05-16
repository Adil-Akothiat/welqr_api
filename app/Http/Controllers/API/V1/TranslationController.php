<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Translation;
use App\Helpers\Utilities;
use Illuminate\Validation\ValidationException;
use Exception;

class TranslationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $translations = Translation::all();
            return response()->json(['translations' => $translations], 200)
                ->header('Content-Type', 'application/json');
        } catch (Exception $e) {
            return Utilities::errorsHandler($e);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'key' => 'required|unique:translations',
                'translation' => 'required',
            ]);

            $translation = Translation::create($validated);

            return response()->json([
                'message' => 'Translation created successfully',
                'translation' => $translation,
            ], 201);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (Exception $e) {
            return Utilities::errorsHandler($e);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $translation = Translation::findOrFail($id);
            return response()->json(['translation' => $translation], 200);
        } catch (Exception $e) {
            return Utilities::errorsHandler($e);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $translation = Translation::findOrFail($id);

            $validated = $request->validate([
                'translation' => 'sometimes|string',
            ]);

            $translation->update($validated);

            return response()->json([
                'message' => 'Translation updated successfully',
                'translation' => $translation,
            ], 200);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (Exception $e) {
            return Utilities::errorsHandler($e);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $translation = Translation::findOrFail($id);
            $translation->delete();

            return response()->json(['message' => 'Translation deleted successfully'], 200);
        } catch (Exception $e) {
            return Utilities::errorsHandler($e);
        }
    }
}