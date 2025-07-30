<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use App\Models\UsersoneModel; // ajustez le namespace exact
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UsersoneController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function indexUsersone()
    {
        return response()->json(UsersoneModel::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function storeUsersone(Request $request)
    {
        // app/Http/Controllers/Api/UsersoneController.php
        try {
            $data = $request->validate([
                'name'     => 'required|string|max:255',
                'email'    => 'required|email|unique:usersone,email_usersone',
                'password' => ['required','min:8','regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[A-Za-z\d]{8,}$/'],
                'role'    => 'required'
            ], [
                'name.required'     => 'Le nom est requis.',
                'email.email'       => 'L’email doit être valide.',
                'email.unique'      => 'Cet email est déjà utilisé.',
                'password.min'      => 'Le mot de passe doit faire au moins 8 caractères.',
                'password.regex'    => 'Le mot de passe doit contenir 1 majuscule, 1 minuscule et 1 chiffre.',
                'role.required'     => 'Le rôle est requis.'
            ]);

            $user = UsersoneModel::create([
                'name_usersone'     => $data['name'],
                'email_usersone'    => $data['email'],
                'role_usersone'     => $data['role'],
                'password_usersone' => Hash::make($data['password']),
            ]);

                // 5) Réponse JSON (201) avec debug SQL en dev
                return response()->json([
                    'message' => 'Enregistrement réussi.',
                    'user'    => $user,
                ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // erreurs de validation
            return response()->json([
                'errors' => $e->errors()
            ], 422);
        }

    }




    public function registerUsersone(Request $request)
    {
        // app/Http/Controllers/Api/UsersoneController.php
        try {
            $data = $request->validate([
                'name'     => 'required|string|max:255',
                'email'    => 'required|email|unique:usersone,email_usersone',
                'password' => ['required','min:8','regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[A-Za-z\d]{8,}$/']
            ], [
                'name.required'     => 'Le nom est requis.',
                'email.email'       => 'L’email doit être valide.',
                'email.unique'      => 'Cet email est déjà utilisé.',
                'password.min'      => 'Le mot de passe doit faire au moins 8 caractères.',
                'password.regex'    => 'Le mot de passe doit contenir 1 majuscule, 1 minuscule et 1 chiffre.',
            ]);

            $user = UsersoneModel::create([
                'name_usersone'     => $data['name'],
                'email_usersone'    => $data['email'],
                'password_usersone' => Hash::make($data['password']),
            ]);

                // 5) Réponse JSON (201) avec debug SQL en dev
                return response()->json([
                    'message' => 'Enregistrement réussi.',
                    'user'    => $user,
                ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // erreurs de validation
            return response()->json([
                'errors' => $e->errors()
            ], 422);
        }

    }

    /**
     * Display the specified resource.
     */
    public function loginUsersone(Request $request)
    {
        $data = $request->validate([
            'email'    => 'required|email',
            'password' => [
                'required',
                'min:8',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[A-Za-z\d]{8,}$/'
            ]
        ], [
            'email.email'       => 'L’email doit être valide.',
            'password.min'      => 'Le mot de passe doit faire au moins 8 caractères.',
            'password.regex'    => 'Le mot de passe doit contenir 1 majuscule, 1 minuscule et 1 chiffre.',
        ]);

        $user = UsersoneModel::where('email_usersone', $data['email'])->first();

        if (!$user || !Hash::check($data['password'], $user->password_usersone)) {
            return response()->json([
                'message' => 'Email ou mot de passe incorrect.'
            ], 401);
        }

        // ✅ Crée le token avec Sanctum
        $token = $user->createToken('API Token')->plainTextToken;

        return response()->json([
            'token' => $token, // C’est CE token que tu dois utiliser dans ton frontend
            'name'  => $user->name_usersone,
            'id' => $user->id
        ]);
    }


    /**
     * Update the specified resource in storage.
     */
    public function updateUsersone(Request $request, string $id)
    {
        $user = UsersoneModel::find($id);

        if (!$user) {
            return response()->json(['message' => 'Utilisateur non trouvé.'], 404);
        }

        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:usersone,email_usersone',
            'password' => 'required|min:8|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[A-Za-z\d]{8,}$/',
            'role'    => 'required'
        ], [
            'name.required'     => 'Le nom est requis.',
            'email.email'       => 'L’email doit être valide.',
            'email.unique'      => 'Cet email est déjà utilisé.',
            'password.min'      => 'Le mot de passe doit faire au moins 8 caractères.',
            'password.regex'    => 'Le mot de passe doit contenir 1 majuscule, 1 minuscule et 1 chiffre.',
            'role.required'     => 'Le rôle est requis.'
        ]);

        // Mettre à jour les champs
        if (isset($data['name'])) {
            $user->name_usersone = $data['name'];
        }
        if (isset($data['email'])) {
            $user->email_usersone = $data['email'];
        }
        if (isset($data['password'])) {
            $user->password_usersone = Hash::make($data['password']);
        }
        if (isset($data['role'])) {
            $user->role_usersone = $data['role'];
        }

        $user->save();

        return response()->json(['message' => 'Utilisateur mis à jour avec succès.', 'user' => $user]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroyUsersone(string $id)
    {
        $user = UsersoneModel::find($id);

        if (!$user) {
            return response()->json(['message' => 'Utilisateur non trouvé.'], 404);
        }

        // Supprimer l'utilisateur
        $user->delete();

        return response()->json(['message' => 'Utilisateur supprimé avec succès.']);
    }
}
