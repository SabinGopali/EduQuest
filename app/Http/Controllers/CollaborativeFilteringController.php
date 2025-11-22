<?php

namespace App\Http\Controllers;

use App\Services\CollaborativeFilteringService;
use App\Models\CourseDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CollaborativeFilteringController extends Controller
{
    public function recommendPage(CollaborativeFilteringService $service)
    {
        if (!Auth::guard('student')->check()) {
            return redirect()->route('student.loginFrom');
        }

        $student = Auth::guard('student')->user();
        $service->updateRecommendationsForStudent((int)$student->id);
        $items = $service->getRecommendationsForStudent((int)$student->id);

        $ids = array_map(function ($r) { return (int)($r['coursedetail_id'] ?? 0); }, $items);
        $ids = array_values(array_filter(array_unique($ids)));

        $courseDetails = [];
        if (!empty($ids)) {
            $courseDetails = CourseDetail::with(['course', 'college'])
                ->whereIn('id', $ids)
                ->get()
                ->keyBy('id');
        }

        $ranked = [];
        foreach ($items as $row) {
            $id = (int)($row['coursedetail_id'] ?? 0);
            if ($id && isset($courseDetails[$id])) {
                $ranked[] = [
                    'detail' => $courseDetails[$id],
                    'score' => $row['score'] ?? 0,
                ];
            }
        }

        return view('home.recommendations', [
            'ranked' => $ranked,
        ]);
    }
    public function myRecommendations(CollaborativeFilteringService $service)
    {
        if (!Auth::guard('student')->check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $student = Auth::guard('student')->user();
        // Ensure recommendations exist or refresh on demand
        $service->updateRecommendationsForStudent((int)$student->id);

        $items = $service->getRecommendationsForStudent((int)$student->id);
        return response()->json(['student_id' => (int)$student->id, 'recommendations' => $items]);
    }

    public function forStudent(int $studentId, CollaborativeFilteringService $service)
    {
        if (!Auth::guard('admin')->check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $service->updateRecommendationsForStudent($studentId);
        $items = $service->getRecommendationsForStudent($studentId);
        return response()->json(['student_id' => $studentId, 'recommendations' => $items]);
    }
}


