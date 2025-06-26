<?php

namespace App\Http\Controllers;

use App\Models\Stagiaire;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class StagiaireController extends Controller
{
   

    public function index(string $id)
    {
        $stagiaire = Stagiaire::findOrFail($id);
        return view('StagiairePage.index', compact('stagiaire'));
    }

    
    public function edit(string $id)
    {
        $stagiaire = Stagiaire::findOrFail($id);
        return view('StagiairePage.edit', compact('stagiaire'));
    }
    public function update(Request $request, Stagiaire $stagiaire)
    {
        $validatedData = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $stagiaire->user->id,
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'cv' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'date_naissance' => 'required|date',
            'date_debut' => 'sometimes|date|after_or_equal:today',
            'description' => 'nullable|string|max:1000',
            'date_fin' => 'nullable|date|after:date_debut',
            'telephone' => 'required|string|max:15'
        ]);
        if ($request->filled('new_password')) {
            $request->validate([
                'current_password' => 'required',
                'new_password' => 'required|min:8|confirmed',
                'new_password_confirmation' => 'required'
            ]);
            if (!Hash::check($request->current_password, $stagiaire->user->password)) {
                return back()->withErrors(['current_password' => 'Le mot de passe actuel est incorrect']);
            }
     $stagiaire->user->update([
                'password' => Hash::make($request->new_password)
            ]);
        }
    if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($stagiaire->image && Storage::disk('public')->exists($stagiaire->image)) {
                Storage::disk('public')->delete($stagiaire->image);
            }
    
            $imagePath = $request->file('image')->store('stagiaires/images', 'public');
            $validatedData['image'] = $imagePath;
        }
        if ($request->hasFile('cv')) {
            // Delete old CV if exists
            if ($stagiaire->cv && Storage::disk('public')->exists($stagiaire->cv)) {
                Storage::disk('public')->delete($stagiaire->cv);
            }
    
            $cvPath = $request->file('cv')->store('stagiaires/cv', 'public');
            $validatedData['cv'] = $cvPath;
        }
    
        // Update stagiaire information
        $stagiaire->update($validatedData);
    
        // Update user email if changed
        if ($stagiaire->user->email !== $validatedData['email']) {
            $stagiaire->user->update(['email' => $validatedData['email']]);
        }
    
        return redirect()->route('stagiaire.index', $stagiaire->id)
            ->with('success', 'Vos données ont été mises à jour avec succès');
    }
   
}
