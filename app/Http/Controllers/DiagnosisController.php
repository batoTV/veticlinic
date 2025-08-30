<?php

namespace App\Http\Controllers;

use App\Models\Pet;
use App\Models\Diagnosis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\DiagnosisImage;

class DiagnosisController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create(Pet $pet)
    {
        return view('diagnoses.create', ['pet' => $pet]);
    }

   

    /**
     * Display the specified resource.
     */
    public function show(Diagnosis $diagnosis)
    {
        return view('diagnoses.show', ['diagnosis' => $diagnosis]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Diagnosis $diagnosis)
    {
        return view('diagnoses.edit', ['diagnosis' => $diagnosis]);
    }

    public function store(Request $request, Pet $pet)
    {
         $validatedData = $request->validate([
            'pet_id' => 'required|exists:pets,id',
            'checkup_date' => 'required|date',
            'weight' => 'nullable|numeric',
            'temperature' => 'nullable|numeric',
            'attending_vet' => 'nullable|string|max:255',
            'chief_complaints' => 'required|string',
            'diagnosis' => 'nullable|string',
            'assessment' => 'nullable|string',
            'plan' => 'nullable|string',
            'xray_images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Create the main diagnosis record first
        $diagnosis = Diagnosis::create($validatedData);

        // Handle multiple image uploads
        if ($request->hasFile('xray_images')) {
            foreach ($request->file('xray_images') as $file) {
                $path = $file->store('xrays', 'public');
                $diagnosis->images()->create(['image_path' => $path]);
            }
        }

        return redirect('/pets/' . $pet->id)->with('success', 'Medical record has been added successfully.');
    }

    public function update(Request $request, Diagnosis $diagnosis)
    {
        // ... validation is similar to store() ...
         $validatedData = $request->validate([
            'checkup_date' => 'required|date',
            'weight' => 'nullable|numeric',
            'temperature' => 'nullable|numeric',
            'attending_vet' => 'nullable|string|max:255',
            'chief_complaints' => 'required|string',
            'diagnosis' => 'nullable|string',
            'assessment' => 'nullable|string',
            'plan' => 'nullable|string',
            'xray_images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        $diagnosis->update($request->except('xray_images'));

        if ($request->hasFile('xray_images')) {
            foreach ($request->file('xray_images') as $file) {
                $path = $file->store('xrays', 'public');
                $diagnosis->images()->create(['image_path' => $path]);
            }
        }

        return redirect('/pets/' . $diagnosis->pet_id)->with('success', 'Medical record has been updated successfully.');
    }

    public function destroy(Diagnosis $diagnosis)
    {
        // Delete all associated image files from storage
        foreach ($diagnosis->images as $image) {
            Storage::disk('public')->delete($image->image_path);
        }

        $petId = $diagnosis->pet_id;
        $diagnosis->delete(); // This will also delete the image records due to cascading delete

        return redirect('/pets/' . $petId);
    }
        /**
     * Remove the specified image from storage.
     */
    public function destroyImage(DiagnosisImage $image)
    {
        // Delete the file from storage
        Storage::disk('public')->delete($image->image_path);

        // Delete the record from the database
        $image->delete();

        return redirect()->back()->with('success', 'Image deleted successfully.');
    }
}
