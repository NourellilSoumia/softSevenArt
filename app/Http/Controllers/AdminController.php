<?php

namespace App\Http\Controllers;

use App\Models\Stagiaire;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;


class AdminController extends Controller
{

 public function index()
    {
        $stagiaires = Stagiaire::with('user')->orderBy('created_at', 'desc')->paginate(10);

        return view('AdminPage.index', compact('stagiaires'));
    }

    public function edit($id)
    {
        $stagiaire = Stagiaire::findOrFail($id);
        return view('AdminPage.edit', compact('stagiaire'));
    }


    public function update(Request $request, $id)
    {
        $stagiaire = Stagiaire::findOrFail($id);

        if ($request->hasFile('attestation_stage')) {
            $attestation = $request->file('attestation_stage')->store('attestation_stage', 'public');
            $stagiaire->attestation_de_stage = $attestation;
        }
        $stagiaire->save();

        return redirect()->route('admin.show', $id)
            ->with('success', 'Mis à jour avec succès');
    }



    public function showStagiaire(string $id)
    {
        $stagiaire = Stagiaire::findOrFail($id);
        return view('AdminPage.show', compact('stagiaire'));
    }


    public function AcceptStagiaire($id)
    {
        $stagiaire = Stagiaire::findOrFail($id);
        $stagiaire->update(['status' => 'accepte']);
        return redirect()->route('admin.index')->with('success', 'Stagiaire accepté avec succès.');
    }


    public function RefuseStagiaire($id)
    {
        $stagiaire = Stagiaire::findOrFail($id);
        $stagiaire->update(['status' => 'refuse']);
        return redirect()->route('admin.index')->with('success', 'Stagiaire refuse avec succès.');
    }



    public function ajouter(Request $request)
    {

      
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'password' => 'required|string|min:8',
            'date_naissance' => 'nullable|date',
            'date_debut' => 'nullable|date',
            'date_fin' => 'nullable|date|after:date_debut',
            'cv' => 'nullable|file|mimes:pdf|max:5120',
            'description' => 'nullable|string|max:1000',
            'telephone' => 'nullable|string|max:15'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();
        try {
            $existingUser = User::where('email', $request->email)->first();
            if ($existingUser) {
                throw new \Exception('Un utilisateur avec cet email existe déjà.');
            }
            $user = User::create([
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
            if (!$user) {
                throw new \Exception('Échec de la création de l\'utilisateur.');
            }
            $stagiaireData = [
                'nom' => $request->nom,
                'prenom' => $request->prenom,
                'user_id' => $user->id,
                'date_naissance' => $request->date_naissance,
                'date_debut' => $request->date_debut,
                'date_fin' => $request->date_fin,
                'telephone' => $request->telephone,
            ];
       

            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('images', 'public');
                $stagiaireData['image'] = $imagePath;
            }

            if ($request->hasFile('cv')) {
                $cvPath = $request->file('cv')->store('cvs', 'public');
                $stagiaireData['cv'] = $cvPath;
            }

            $stagiaire = Stagiaire::create($stagiaireData);

            if (!$stagiaire) {
                throw new \Exception('Échec de la création du stagiaire.');
            }

            DB::commit();

            return redirect()->route('admin.index')
                ->with('success', 'Stagiaire ajouté avec succès');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur lors de l\'ajout du stagiaire : ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Une erreur est survenue : ' . $e->getMessage())
                ->withInput();
        }
    }

    public function add()
    {
        return view('AdminPage.add');
    }
}
