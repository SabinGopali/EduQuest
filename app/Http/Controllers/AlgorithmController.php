<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Course;
use App\Models\Students;

class AlgorithmController extends Controller
{
    protected function tokenizeAndPreprocess($text) {
        // Normalize to string and handle nulls
        $text = (string) ($text ?? '');

        // Tokenize the text into words
        $words = str_word_count($text, 1);

        // Preprocess: Convert to lowercase and remove punctuation
        $terms = [];
        foreach ($words as $word) {
            $term = strtolower($word);
            $term = preg_replace("/[^a-zA-Z]+/", "", $term); // Remove non-alphabetic characters
            if (!empty($term)) {
                $terms[] = $term;
            }
        }
        // dd($terms);
        // Define stopwords
        $stopwords = ["a", "an", "and", "are", "as", "at", "be", "by", "for", "from", "has", "in", "is", "it", "its", "of", "on", "that", "the", "to", "was", "were", "will", "with","want","to","a","become","is","am","are","those","these"];

        // Remove stopwords
        $terms_all = array_filter($terms, function($term) use ($stopwords) {
            return !in_array($term, $stopwords);
        });
        // dd($terms_all);
        return $terms_all;
    }
    // Function to calculate TF (Term Frequency)
    protected function calculateTF($terms) {
        $termFrequency = [];
        $totalTerms = count($terms);

        foreach ($terms as $term) {
            if (isset($termFrequency[$term])) {
                $termFrequency[$term]++;
            } else {
                $termFrequency[$term] = 1;
            }
        }

        // Normalize TF by dividing by the total number of terms 0 and 1
        foreach ($termFrequency as &$tf) {
            $tf /= max(1, $totalTerms);
        }

        return $termFrequency;
    }
    // Function to calculate TF-IDF (Term Frequency-Inverse Document Frequency)
    protected function calculateTFIDF($tf, $idf) {
        $tfidf = [];

        foreach ($tf as $term => $tfScore) {
            $tfidf[$term] = $tfScore * ($idf[$term] ?? 1);
        }

        return $tfidf;
    }
    // Function to calculate cosine similarity between two TF-IDF vectors
    protected function cosineSimilarity($vectorA, $vectorB) {
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
            return 0; // Handle the case of division by zero (zero similarity)
        }

        return $dotProduct / (sqrt($normA) * sqrt($normB));
    }
    protected function calculateTFIDFForStudent($student) {

        // Tokenize and preprocess the student's profile
        $interestTerms = $this->tokenizeAndPreprocess($student->interest ?? '');

        // Tokenize and preprocess the student's goal
        $goalTerms = $this->tokenizeAndPreprocess($student->goal ?? '');

        // Calculate TF for the profile and goal
        $tfProfile = $this->calculateTF($interestTerms);
        $tfGoal = $this->calculateTF($goalTerms);

        // Combine the term frequencies and IDF scores for profile and goal
        $combinedTF = array_merge($tfProfile, $tfGoal);

        // In this case, IDF is 1 since there's only one document (the student's profile and goal).
        $combinedIDF = array_fill_keys(array_keys($combinedTF), 1);

        // Calculate TF-IDF scores for the combined TF and IDF
        $tfidfCombined = $this->calculateTFIDF($combinedTF, $combinedIDF);

        // Now you have TF-IDF scores for the combined student's profile and goal
        // dd($tfidfCombined);
        return $tfidfCombined;
    }
    public function recommend(){
        // Ensure student is authenticated
        if (!Auth::guard('student')->check()) {
            return redirect('/student/login');
        }

        // Function to calculate TF-IDF scores for a specific student by ID
        $currentStudent = Auth::guard('student')->user();
        $student= Students::find($currentStudent->id);

        // Calculate TF-IDF for the specific student (safe even if fields are null)
        $studentTFIDF = $this->calculateTFIDFForStudent($student);

        // Select courses based on education level, defaulting safely
        $educationLevel = (string) ($student->educationLevel ?? '');
        if (strcasecmp($educationLevel, 'SEE') === 0) {
            // Find courses with 'stream' value of '+2'
            $courses = Course::where('stream', '+2')->whereNotNull('description')->get();
        } else {
            // Find courses with 'stream' value of 'Bachelor'
            $courses = Course::where('stream', 'Bachelor')->whereNotNull('description')->get();
        }

        // If none found by stream, fallback to any with description
        if ($courses->isEmpty()) {
            $courses = Course::whereNotNull('description')->get();
        }

        // Calculate TF-IDF for each course description and calculate cosine similarity
        $recommendedCourses = [];
        foreach ($courses as $index => $course) {
            $desc = (string) ($course->description ?? '');
            $terms = $this->tokenizeAndPreprocess($desc);
            $tf = $this->calculateTF($terms);

            // In this case, IDF is 1 since there's only one document (the course description).
            $idf = array_fill_keys(array_keys($tf), 1);

            $tfidfCourse = $this->calculateTFIDF($tf, $idf);

            // Calculate cosine similarity between the student's combined TF-IDF and the course's TF-IDF
            $similarity = $this->cosineSimilarity($studentTFIDF, $tfidfCourse);

            // Store the course and its similarity score
            $recommendedCourses[$index] = [
                'course_id' => $course->id, // You can replace this with the actual course ID
                'similarity' => $similarity,
                'name' => $course->name,
            ];
        }

        usort($recommendedCourses, function ($a, $b) {
            // Sort in descending order based on the 'similarity' field
            return $b['similarity'] <=> $a['similarity'];
        });

        $topRecommendedCourses = array_filter($recommendedCourses, function ($course) {
            return $course['similarity'] > 0;
        });

        return view('home.recommend', compact('topRecommendedCourses'));
    }

}