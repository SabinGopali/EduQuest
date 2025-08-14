<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\College;
use App\Models\CollegeImage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class CollegeController extends Controller
{
    public function create()
    {
        return view('college.create');
    }


    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:colleges,email',
            'password' => 'required|string',
            'address' => 'required|string',
            'latitude' => 'required',
            'longitude' => 'required',
            'contact' => 'required|string',
            'description' => 'required|string',
            'logo' => 'image|mimes:jpeg,png,jpg,gif',
            'gallery.*' => 'image|mimes:jpeg,png,jpg,gif',
        ]);

        // Hash the password and store it in the $data array
        $data['password'] = bcrypt($request->input('password'));


        // Handle Logo Upload
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('logos', 'public');
            $data['logo'] = $logoPath;
        }

        // Create College
        $college = College::create($data);

        // Handle Gallery Image Uploads
        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $image) {
                $imagePath = $image->store('gallery', 'public');
                $college->images()->create(['path' => $imagePath]);
            }
        }

        return redirect()->route('home')->with('success', 'College registered successfully!');
    }


     function show(College $college)
    {
        $college=College::all();
        return view('admin.collegeshow',['college'=>$college]);
    }

     function showForStudent(College $college)
    {
        $college=College::all();
        return view('home.college',['college'=>$college]);
    }

    public function getById($id)
    {
        $college= College::find($id);
        return view('college.viewcollegedes', compact('college'));
    }

    public function getByIdForAdmin($id)
    {
        $college = College::with('courseDetails.course')->find($id);
        return view('admin.collegeDetailView', compact('college'));
    }
    public function getByIdForStudent($id)
    {
        $college = College::with('courseDetails.course')->find($id);
        return view('home.collegeDetailView', compact('college'));
    }

    public function getEditForm(){
        $currentCollege = Auth::guard('college')->user();
        // $college = College::find($currentCollege->id);
        $college = College::with('images')->find($currentCollege->id);
        return view('college.editForm',compact('college'));
    }

    public function update(Request $request, College $college)
    {
        $currentCollege = Auth::guard('college')->user();
        $oldCollege = College::with('images')->find($currentCollege->id);
        // Validate the input data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'contact' => 'required|string|max:255',
            'description' => 'required|string',
            'logo' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Adjust allowed image types and size
            'gallery.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Adjust allowed image types and size
        ]);

        if ($validator->fails()) {
            return redirect()->route('college.edit', $college->id)
                ->withErrors($validator)
                ->withInput();
        }

        // Update the college record
        $oldCollege->name = $request->input('name');
        $oldCollege->address = $request->input('address');
        $oldCollege->contact = $request->input('contact');
        $oldCollege->description = $request->input('description');

        // Update logo if a new file is provided
        if ($request->hasFile('logo')) {
            // Delete the old logo file if it exists
            if ($oldCollege->logo) {
                Storage::disk('public')->delete($oldCollege->logo);
            }

            $logoPath = $request->file('logo')->store('logos', 'public');
            $oldCollege->logo = $logoPath;
        }

        $oldCollege->save();

        $galleryToRemove = $request->input('remove_gallery', []);
        foreach ($galleryToRemove as $galleryId) {
            // Assuming you have a Gallery model
            $gallery = CollegeImage::find($galleryId);

            if ($gallery) {
                $gallery->delete();
            }
        }

        // Update gallery images
        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $image) {
                $imagePath = $image->store('gallery', 'public');
                $oldCollege->images()->create(['path' => $imagePath]);
            }
        }
        return redirect()->route('college.editForm', $college->id)->with('success', 'College updated successfully.');
    }

    public function activateCollege(College $college)
    {
        // Update the status to "active"
        $college->update(['status' => 'active']);

        return redirect()->route('home')->with('success', 'College status updated to active!');
    }

    public function getCollegeByCourseId($id)
    {
        // return view('home.courseCollegeView', compact('college'));
        return view('home.courseCollegeView');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\College;  $college
     * @return \Illuminate\Http\Response
     */
    public function destroy(College $college, $id)
    {
        $college=College::find($id);
        $college->delete();
        return redirect('/admin/college/show')->with('success', 'College deleted!');
    }

}
