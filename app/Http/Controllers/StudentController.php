<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Students;
use Illuminate\Support\Facades\Storage;

class StudentController extends Controller
{
    public function create()
    {
        return view('students.create');
    }


    public function store(Request $request)
    {
        $allInterests = $request->input('interest');
        $subjectsString = implode(", ", $allInterests);

        $Student = new Students([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'contact' => $request->input('contact'),
            'passedyear' => $request->input('passedYear'),
            'previousschool' => $request->input('previouscollege'),
            'gpa' => $request->input('gpa'),
            'interest' => $subjectsString,
            'goal' => $request->input('goals'),
            'password' => bcrypt($request->input('password')),
            'educationLevel' => $request->input('educationLevel'),
        ]);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
//             $image->move(public_path('uploads'), $imageName);
             Storage::disk('public')->putFileAs('uploads', $image, $imageName);
            $Student->image = $imageName;
        }

        $Student->save();

        return view('home.index');
    }

    public function index()
    {
        $students = Students::all();
        return view('students.index', compact('students'));
    }

    public function edit()
    {
        $currentStudent = Auth::guard('student')->user();
        $student = Students::find($currentStudent->id);
        // $student->interest = array($student->interest);
        // dd($student);
        return view('home.myprofileedit', compact('student'));
    }

    // public function update(Request $request, $id)
    // {
    //     $request->validate([
    //         'name' => 'required',
    //         'email' => 'required|email',
    //         'phone' => 'required',
    //         'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
    //     ]);

    //     $student = Students::find($id);
    //     $student->name = $request->input('name');
    //     $student->email = $request->input('email');
    //     $student->phone = $request->input('phone');

    //     if ($request->hasFile('image')) {
    //         $image = $request->file('image');
    //         $imageName = time() . '.' . $image->getClientOriginalExtension();
    //         $image->move(public_path('uploads'), $imageName);
    //         $student->image = $imageName;
    //     }

    //     $student->save();

    //     return redirect()->route('students.index')->with('success', 'Student updated successfully');
    // }

    public function update(Request $request, $id)
{
    // Find the student by ID
    $student = Students::find($id);

    // Handle the case where the student is not found
    if (!$student) {
        return redirect()->back()->with('error', 'Student not found.');
    }

    // Validate the form data
    $validatedData = $request->validate([
        'name' => 'required|string',
        'email' => 'required|email',
        'contact' => 'required|string',
        'educationLevel' => 'required|string',
        'passedyear' => 'required|string',
        'previousschool' => 'required|string',
        'gpa' => 'required|string',
        'interest' => 'required',
        'goal' => 'required|string',
        'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Adjust the validation rules for the image as needed
    ]);

    // Handle image upload and update
     if ($request->hasFile('image')) {
        $image = $request->file('image');

        // Generate a unique file name based on the current timestamp
        $imageName = time() . '.' . $image->getClientOriginalExtension();

        // Save the image with the unique file name
        Storage::disk('public')->putFileAs('uploads', $image, $imageName);

        // Update the student's image field with the unique file name
        $student->image = $imageName;
    }

    $allInterests = $validatedData['interest'];
    $subjectsString = implode(", ", $allInterests);

    // Update student details with the validated data
    $student->update([
        'name' => $validatedData['name'],
        'email' => $validatedData['email'],
        'contact' => $validatedData['contact'],
        'educationLevel' => $validatedData['educationLevel'],
        'passedyear' => $validatedData['passedyear'],
        'previousschool' => $validatedData['previousschool'],
        'gpa' => $validatedData['gpa'],
        'interest' => $subjectsString,
        'goal' => $validatedData['goal'],
    ]);

    return redirect()->route('student.getById', $id)->with('success', 'Student details updated successfully.');
}


    public function destroy($id)
    {
        $student = Students::find($id);
        $student->delete();

        return redirect()->route('students.show')->with('success', 'Student deleted successfully');
    }

    public function getById()
    {
        $currentStudent = Auth::guard('student')->user();
        $student= Students::find($currentStudent->id);
        return view('home.myprofile', compact('student'));
    }

    public function show(Students $student)
    {
        $student=Students::all();
        return view('admin.studentshow',['student'=>$student]);
    }
    public function getByIdForAdmin($id)
    {
        $student = Students::find($id);
        return view('admin.studentDetailView', compact('student'));
    }

    // public function update(Request $request, $id)
    // {
    //     // Find the student by their ID
    //     $student = Students::findOrFail($id);

    //     // Validate the form data
    //     $validatedData = $request->validate([
    //         'name' => 'required|string|max:255',
    //         'email' => 'required|email|unique:students,email,' . $id,
    //         'contact' => 'required|string|max:15',
    //         'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Adjust the file validation rules
    //         'passedyear' => 'required|string|max:255',
    //         'previousschool' => 'required|string|max:255',
    //         'gpa' => 'required|string|max:10',
    //         'interest' => 'required|string',
    //         'goal' => 'required|string',
    //         // Add any additional validation rules for your fields
    //     ]);

    //     // Handle image upload if a file was provided
    //     if ($request->hasFile('image')) {
    //         $imagePath = $request->file('image')->store('images'); // Adjust the storage path
    //         $validatedData['image'] = $imagePath;
    //     }

    //     // Update the student's data with the validated data
    //     $student->update($validatedData);

    //     // Redirect or return a response as needed
    // }


    public function activateStudent($id)
    {
        $student = Students::find($id);

        if (!$student) {
            return redirect()->route('students.index')->with('error', 'Student not found');
        }

        // Set the status to "active"
        $student->status = 'active';
        $student->save();

        return redirect()->route('students.index')->with('success', 'Student status set to active');
    }

    public function getInquiries()
    {
        $currentStudent = Auth::guard('student')->user();
        $student = Students::with('inquiries.courseDetail.college')->find($currentStudent->id);

        if (!$student) {
            return redirect()->back()->with('error', 'Student not found.');
        }

        return view('home.inquiryView', compact('student'));
    }

}




