<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PlansModel;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class PlansController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function indexPlans()
    {
        return response()->json(PlansModel::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function storePlans(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'file' => 'required|file|mimetypes:application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document|max:51200',
        ], [
            'title.required' => 'Le titre est requis.',
            'title.string' => 'Le titre doit être une chaîne de caractères.',
            'title.max' => 'Le titre ne doit pas dépasser 255 caractères.',
            'description.required' => 'La description est requise.',
            'description.string' => 'La description doit être une chaîne de caractères.',
            'price.required' => 'Le prix est requis.',
            'price.numeric' => 'Le prix doit être un nombre.',
            'price.min' => 'Le prix doit être supérieur ou égal à 0.',
            'image.required' => 'L\'image est requise.',
            'image.image' => 'L\'image doit être un fichier image.',
            'image.mimes' => 'L\'image doit être de type jpeg, png, jpg ou gif.',
            'image.max' => 'L\'image ne doit pas dépasser 2Mo.',
            'file.required' => 'Le fichier est requis.',
            'file.file' => 'Le fichier doit être un fichier.',
            'file.mimetypes' => 'Le fichier doit être de type PDF ou Word.',
            'file.max' => 'Le fichier ne doit pas dépasser 50Mo.'
        ]);
                // Vérifier si les donnees sont valides
        if ($request->fails()) {
            return response()->json($request->errors(), 422); // Retourne une réponse JSON avec les erreurs de validation
        }

        if ($request->hasFile('image')) {
            $originalName = pathinfo($request->file('image')->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $request->file('image')->getClientOriginalExtension();
            $date = now()->format('Ymd_His');
            $slug = Str::slug($originalName);
            $fileName = $slug . '_' . $date . '_' . uniqid() . '.' . $extension;

            $path = $request->file('image')->storeAs('plans/image', $fileName, 'public');
            $validated['image'] = Storage::url($path);
        }

        if ($request->hasFile('file')) {
            $file = $request->file('file');

            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $date = now()->format('Ymd_His');
            $slug = Str::slug($originalName);
            $fileName = $slug . '_' . $date . '_' . uniqid() . '.' . $extension;

            $path = $file->storeAs('plans/file', $fileName, 'public');

            $validated['file'] = Storage::url($path); 
        }

        $plan = PlansModel::create([
            'title_plans' => $validated['title'],
            'description_plans' => $validated['description'],
            'price_plans' => $validated['price'],
            'image_plans' => $validated['image'],
            'file_plans' => $validated['file'],
        ]);

        return response()->json(['message' => 'Plan ajoutée avec succès.', 'plan' => $plan], 201);
    }

    /**
     * Display the specified resource.
     */
    public function showPlans($id)
    {
        try {
            $plan = PlansModel::where('id', $id)->first();

            if (!$plan) {
                return response()->json(['message' => 'plan not found'], 404);
            }

            return response()->json(['plan' => $plan], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // erreurs de validation
            return response()->json([
                'errors' => $e->errors()
            ], 422);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function updatePlans(Request $request, string $id)
    {
        $plan = PlansModel::find($id);

        if (!$plan) {
            return response()->json(['message' => 'Plan not found'], 404);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'file' => 'nullable|file|mimetypes:application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document|max:51200',
        ],
        [
            'title.required' => 'Le titre est requis.',
            'title.string' => 'Le titre doit être une chaîne de caractères.',
            'title.max' => 'Le titre ne doit pas dépasser 255 caractères.',
            'description.required' => 'La description est requise.',
            'description.string' => 'La description doit être une chaîne de caractères.',
            'price.required' => 'Le prix est requis.',
            'price.numeric' => 'Le prix doit être un nombre.',
            'price.min' => 'Le prix doit être supérieur ou égal à 0.',
            'image.image' => 'L\'image doit être un fichier image.',
            'image.mimes' => 'L\'image doit être de type jpeg, png, jpg ou gif.',
            'image.max' => 'L\'image ne doit pas dépasser 2Mo.',
            'file.file' => 'Le fichier doit être un fichier.',
            'file.mimetypes' => 'Le fichier doit être de type PDF ou Word.',
            'file.max' => 'Le fichier ne doit pas dépasser 50Mo.'
        ]);
        // Vérifier si les donnees sont valides
        if ($request->fails()) {
            return response()->json($request->errors(), 422); // Retourne une réponse JSON avec les erreurs de validation
        }
        if ($request->hasFile('image')) {
            $originalName = pathinfo($request->file('image')->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $request->file('image')->getClientOriginalExtension();
            $date = now()->format('Ymd_His');
            $slug = Str::slug($originalName);
            $fileName = $slug . '_' . $date . '_' . uniqid() . '.' . $extension;

            $path = $request->file('image')->storeAs('plans/image', $fileName, 'public');
            $validated['image'] = Storage::url($path);
        }

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $date = now()->format('Ymd_His');
            $slug = Str::slug($originalName);
            $fileName = $slug . '_' . $date . '_' . uniqid() . '.' . $extension;
            $path = $file->storeAs('plans/file', $fileName, 'public');
            $validated['file'] = Storage::url($path);
        }
        try {
            $plan->update([
                'title_plans' => $validated['title'] ?? $plan->title_plans,
                'description_plans' => $validated['description'] ?? $plan->description_plans,
                'price_plans' => $validated['price'] ?? $plan->price_plans,
                'image_plans' => $validated['image'] ?? $plan->image_plans,
                'file_plans' => $validated['file'] ?? $plan->file_plans,
            ]);
            return response()->json(['message' => 'Plan mise à jour avec succès.', 'plan' => $plan], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erreur lors de la mise à jour des fichiers.'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroyPlans(string $id)
    {
        $plan = PlansModel::find($id);

        if (!$plan) {
            return response()->json(['message' => 'Plan not found'], 404);
        }

        // Supprimer l'image si elle existe
        if ($plan->image_plans && Storage::disk('public')->exists($plan->image_plans)) {
            Storage::disk('public')->delete($plan->image_plans);
        }

        // Supprimer le fichier si il existe
        if ($plan->file_plans && Storage::disk('public')->exists($plan->file_plans)) {
            Storage::disk('public')->delete($plan->file_plans);
        }

        try {
            $plan->delete();
            return response()->json(['message' => 'Plan supprimée avec succès.'],200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erreur lors de la suppression du plan.'], 500);
        }
        

    }
}
