<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TrainingsModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TrainingsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function indexTrainings()
    {
        return response()->json(TrainingsModel::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function storeTrainings(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'software' => 'required|in:AutaCAD,Revit,ArciCAD',
            'price' => 'required|numeric|min:0',
            'video' => 'required|file|mimetypes:video/mp4,video/avi,video/mpeg,video/quicktime|max:102400',
        ], [
            'title.required' => 'Le titre est requis.',
            'description.required' => 'La description est requise.',
            'software.required' => 'Le logiciel est requis.',
            'software.in' => 'Le logiciel doit être l\'un des suivants : AutaCAD, Revit, ArciCAD.',
            'price.required' => 'Le prix est requis.',
            'video.required' => 'La vidéo est requise.',
            'video.file' => 'La vidéo doit être un fichier.',
            'video.mimetypes' => 'Le fichier vidéo doit être de type mp4, avi, mpeg ou quicktime.',
            'video.max' => 'La vidéo ne doit pas dépasser 50 Mo.'
        ]);
        // Vérifier si les donnees sont valides
        if ($request->fails()) {
            return response()->json($request->errors(), 422); // Retourne une réponse JSON avec les erreurs de validation
        }
        if ($request->hasFile('video')) {
            $file = $request->file('video');

            if (!$file->isValid()) {
                return response()->json(['error' => 'Fichier non valide.'], 400);
            }

            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $date = now()->format('Ymd_His');
            $slug = Str::slug($originalName);
            $fileName = $slug . '_' . $date . '_' . uniqid() . '.' . $extension;

            $path = $file->storeAs('trainings', $fileName, 'public');

            if (!$path) {
                return response()->json(['error' => 'Échec du stockage'], 500);
            }

            // Vérif fichier physique
            $fullPath = storage_path('app/public/trainings/' . $fileName);
            if (!file_exists($fullPath)) {
                return response()->json(['error' => 'Fichier non présent après le storeAs', 'chemin' => $fullPath], 500);
            }

            $validated['video'] = Storage::url('trainings/' . $fileName);
        }


        $training = TrainingsModel::create([
            'title_trainings' => $validated['title'],
            'description_trainings' => $validated['description'],
            'software_trainings' => $validated['software'],
            'price_trainings' => $validated['price'],
            'video_trainings' => $validated['video'],
        ]);

        return response()->json([
            'message' => 'Formation ajoutée avec succès.',
            'training' => $training
        ], 201);
    }



    /**
     * Display the specified resource.
     */
    public function showTrainings($id)
    {
        $training = TrainingsModel::find($id);

        if (!$training) {
            return response()->json(['message' => 'Training not found'], 404);
        }

        return response()->json(['training' => $training], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateTrainings(Request $request, $id)
    {
        $training = TrainingsModel::find($id);

        if (!$training) {
            return response()->json(['message' => 'Training not found'], 404);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'software' => 'required|in:AutaCAD,Revit,ArciCAD',
            'price' => 'required|numeric|min:0',
            'video' => 'required|file|mimetypes:video/mp4,video/avi,video/mpeg,video/quicktime',
        ],
        [
            'title.required' => 'Le titre est requis.',
            'title.string' => 'Le titre doit être une chaîne de caractères.',
            'title.max' => 'Le titre ne doit pas dépasser 255 caractères.',
            'description.required' => 'La description est requise.',
            'description.string' => 'La description doit être une chaîne de caractères.',
            'software.required' => 'Le logiciel est requis.',
            'software.in' => 'Le logiciel doit être l\'un des suivants : AutaCAD, Revit, ArciCAD.',
            'price.required' => 'Le prix est requis.',
            'price.numeric' => 'Le prix doit être un nombre.',
            'price.min' => 'Le prix doit être supérieur ou égal à 0.',
            'video.required' => 'La vidéo est requise.',
            'video.file' => 'La vidéo doit être un fichier.',
            'video.mimetypes' => 'Le fichier vidéo doit être de type mp4, avi, mpeg ou quicktime.'
        ]);
        // Vérifier si les donnees sont valides
        if ($request->fails()) {
            return response()->json($request->errors(), 422); // Retourne une réponse JSON avec les erreurs de validation
        }

        if ($request->hasFile('video')) {
            $originalName = pathinfo($request->file('video')->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $request->file('video')->getClientOriginalExtension();
            $date = now()->format('Ymd_His');
            $fileName = Str::slug($originalName) . '_' . $date . '.' . $extension;

            $path = $request->file('video')->storeAs('trainings', $fileName, 'public');
            $validated['video'] = Storage::url($path);
        }

        $training->update([
            'title_trainings' => $validated['title'] ?? $training->title_trainings,
            'description_trainings' => $validated['description'] ?? $training->description_trainings,
            'software_trainings' => $validated['software'] ?? $training->software_trainings,
            'price_trainings' => $validated['price'] ?? $training->price_trainings,
            'video_trainings' => $validated['video'] ?? $training->video_trainings
    ]);

        return response()->json([
            'message' => 'Formation mise à jour avec succès.',
            'training' => $training
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroyTrainings(string $id)
    {
        $training = TrainingsModel::find($id);

        if (!$training) {
            return response()->json(['message' => 'Formation non trouvee'], 404);
        }

        $training->delete();

        return response()->json(['message' => 'Formation supprimée avec succès.'], 200);
    }
}
