<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Students;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Validation\ValidationException;
use App\Models\Course;
use App\Models\Booking;
use App\Models\College;
use Illuminate\Support\Facades\DB;

class StudentAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function dashboard()
    {
        return view('dashboard');
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    // Process student login
    public function login(Request $request)
    {
        // Validate the user's input
        $credentials = $request->only('email', 'password');
        $credentials['user_type'] = 'students';

        if (Auth::guard('student')->attempt($credentials)) {
            // Authentication successful, redirect to the index page
            return redirect()->intended(route('home'));
        }

        // Authentication failed, display an error message
        // return redirect('/admin/login');
        return back()->withErrors(['email' => 'Invalid credentials']);
    }

    // Log out the student user
    public function logout()
    {
        Auth::guard('student')->logout();
        return redirect('/student/login');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'newPassword' => 'required',
            'confirmPassword' => 'required|same:newPassword',
        ]);

        $user = Auth::guard('student')->user();

        $user->update([
            'password' => Hash::make($request->input('newPassword')),
        ]);

        return redirect()->route('home')->with('success', 'Password updated successfully!');
    }

    public function getHome(){
         $topRecommendedCourses = [];

         // Build recommendation data if student is logged in
         if (Auth::guard('student')->check()) {
             $currentStudent = Auth::guard('student')->user();
             $student = Students::find($currentStudent->id);

             // Tokenize and preprocess helper
             $tokenize = function ($text) {
                 $text = (string) ($text ?? '');
                 $words = str_word_count($text, 1);
                 $terms = [];
                 foreach ($words as $word) {
                     $term = strtolower($word);
                     $term = preg_replace("/[^a-zA-Z]+/", "", $term);
                     if (!empty($term)) {
                         $terms[] = $term;
                     }
                 }
                 $stopwords = ["a","an","and","are","as","at","be","by","for","from","has","in","is","it","its","of","on","that","the","to","was","were","will","with","want","to","a","become","is","am","are","those","these"];
                 return array_values(array_filter($terms, function($t) use ($stopwords){ return !in_array($t, $stopwords); }));
             };

             $calculateTF = function (array $terms) {
                 $termFrequency = [];
                 $totalTerms = count($terms);
                 foreach ($terms as $term) {
                     if (isset($termFrequency[$term])) {
                         $termFrequency[$term]++;
                     } else {
                         $termFrequency[$term] = 1;
                     }
                 }
                 foreach ($termFrequency as &$tf) {
                     $tf /= max(1, $totalTerms);
                 }
                 return $termFrequency;
             };

             $calculateTFIDF = function (array $tf, array $idf) {
                 $tfidf = [];
                 foreach ($tf as $term => $tfScore) {
                     $tfidf[$term] = $tfScore * ($idf[$term] ?? 1);
                 }
                 return $tfidf;
             };

             $cosineSimilarity = function (array $vectorA, array $vectorB) {
                 $dotProduct = 0;
                 $normA = 0;
                 $normB = 0;
                 foreach ($vectorA as $term => $tfidfA) {
                     if (isset($vectorB[$term])) {
                         $dotProduct += $tfidfA * $vectorB[$term];
                     }
                     $normA += pow($tfidfA, 2);
                 }
                 foreach ($vectorB as $term => $tfidfB) {
                     $normB += pow($tfidfB, 2);
                 }
                 if ($normA == 0 || $normB == 0) {
                     return 0;
                 }
                 return $dotProduct / (sqrt($normA) * sqrt($normB));
             };

             // Student TF-IDF
             $interestTerms = $tokenize($student->interest ?? '');
             $goalTerms = $tokenize($student->goal ?? '');
             $tfProfile = $calculateTF($interestTerms);
             $tfGoal = $calculateTF($goalTerms);
             $combinedTF = array_merge($tfProfile, $tfGoal);
             $combinedIDF = array_fill_keys(array_keys($combinedTF), 1);
             $studentTFIDF = $calculateTFIDF($combinedTF, $combinedIDF);

             // Courses by education level
             $educationLevel = (string) ($student->educationLevel ?? '');
             if (strcasecmp($educationLevel, 'SEE') === 0) {
                 $courses = Course::where('stream', '+2')->whereNotNull('description')->get();
             } else {
                 $courses = Course::where('stream', 'Bachelor')->whereNotNull('description')->get();
             }
             if ($courses->isEmpty()) {
                 $courses = Course::whereNotNull('description')->get();
             }

             $recommendedCourses = [];
             foreach ($courses as $index => $course) {
                 $terms = $tokenize((string) ($course->description ?? ''));
                 $tf = $calculateTF($terms);
                 $idf = array_fill_keys(array_keys($tf), 1);
                 $tfidfCourse = $calculateTFIDF($tf, $idf);
                 $similarity = $cosineSimilarity($studentTFIDF, $tfidfCourse);
                 $recommendedCourses[$index] = [
                     'course_id' => $course->id,
                     'similarity' => $similarity,
                     'name' => $course->name,
                 ];
             }
             usort($recommendedCourses, function ($a, $b) { return $b['similarity'] <=> $a['similarity']; });
             $topRecommendedCourses = array_values(array_filter($recommendedCourses, function ($c) { return $c['similarity'] > 0; }));
         }

         // Inquiry rank data
         $approvedColleges = College::where('status', 'APPROVED')->get();
         $bookingCounts = Booking::join('coursedetail', 'bookings.coursedetail_id', '=', 'coursedetail.id')
             ->where('bookings.status', 'booked')
             ->select('coursedetail.college_id', DB::raw('COUNT(*) as cnt'))
             ->groupBy('coursedetail.college_id')
             ->pluck('cnt', 'coursedetail.college_id');
         $items = $approvedColleges->map(function ($college) use ($bookingCounts) {
             $count = (int) ($bookingCounts[$college->id] ?? 0);
             return [ 'college' => $college, 'bookings' => $count ];
         })->sortByDesc('bookings')->values();

         return view('home.index', compact('topRecommendedCourses', 'items'));

    }
}