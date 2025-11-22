<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\College;
use App\Models\CollegeImage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Models\CourseDetail;

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
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'contact' => 'required|string',
            'description' => 'required|string',
            'logo' => 'image|mimes:jpeg,png,jpg,gif',
            'gallery.*' => 'image|mimes:jpeg,png,jpg,gif',
        ]);

        // Hash the password and store it in the $data array
        $data['password'] = bcrypt($request->input('password'));

        // Normalize coordinate types
        $data['latitude'] = (float) $data['latitude'];
        $data['longitude'] = (float) $data['longitude'];

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
        $college = College::orderByRaw("FIELD(status, 'PENDING','APPROVED','REJECTED')")->orderBy('name')->get();
        return view('admin.collegeshow',['college'=>$college]);
    }

    public function showForStudent(Request $request)
    {
        $colleges = College::where('status', 'APPROVED')->get();

        $nearestColleges = collect();
        $shouldAutoLocate = !$request->has(['latitude', 'longitude']);

        $validator = Validator::make($request->only(['latitude', 'longitude']), [
            'latitude'  => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
        ]);

        if (!$validator->fails() && $request->filled(['latitude', 'longitude'])) {
            $userLat = (float) $request->query('latitude');
            $userLon = (float) $request->query('longitude');

            $nearestColleges = College::select('id', 'name', 'address', 'logo', 'latitude', 'longitude', 'status')
                ->where('status', 'APPROVED')
                ->whereNotNull('latitude')
                ->whereNotNull('longitude')
                ->get()
                ->filter(function ($college) {
                    if (!is_numeric($college->latitude) || !is_numeric($college->longitude)) {
                        return false;
                    }
                    $lat = (float) $college->latitude;
                    $lon = (float) $college->longitude;

                    if ($lat === 0.0 && $lon === 0.0) {
                        return false;
                    }

                    return $lat >= -90 && $lat <= 90 && $lon >= -180 && $lon <= 180;
                })
                ->map(function ($college) use ($userLat, $userLon) {
                    $collegeLat = (float) $college->latitude;
                    $collegeLon = (float) $college->longitude;

                    // Prefer Vincenty for better accuracy; falls back to Haversine if needed
                    $distanceMeters = $this->vincentyDistanceMeters($userLat, $userLon, $collegeLat, $collegeLon);
                    $college->distance = (int) round($distanceMeters);

                    return $college;
                })
                ->sortBy('distance')
                ->values();

            $shouldAutoLocate = false;
        }

        return view('home.college', [
            'college'          => $colleges,
            'nearestColleges'  => $nearestColleges,
            'shouldAutoLocate' => $shouldAutoLocate,
        ]);
    }

    private function haversineDistance(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        // Use IUGG mean Earth radius for better accuracy (km)
        $earthRadius = 6371.0088;

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        $lat1 = deg2rad($lat1);
        $lat2 = deg2rad($lat2);

        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos($lat1) * cos($lat2) *
            sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }

    private function vincentyDistanceMeters(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        if (abs($lat1 - $lat2) < 1e-12 && abs($lon1 - $lon2) < 1e-12) {
            return 0.0;
        }
        $a = 6378137.0;
        $f = 1 / 298.257223563;
        $b = (1 - $f) * $a;

        $phi1 = deg2rad($lat1);
        $phi2 = deg2rad($lat2);
        $L = deg2rad($lon2 - $lon1);

        $U1 = atan((1 - $f) * tan($phi1));
        $U2 = atan((1 - $f) * tan($phi2));
        $sinU1 = sin($U1); $cosU1 = cos($U1);
        $sinU2 = sin($U2); $cosU2 = cos($U2);

        $lambda = $L;
        $lambdaPrev = 0;
        $iterLimit = 200;

        while ($iterLimit-- > 0) {
            $sinLambda = sin($lambda);
            $cosLambda = cos($lambda);
            $sinSigma = sqrt(
                ($cosU2 * $sinLambda) * ($cosU2 * $sinLambda) +
                ( ($cosU1 * $sinU2) - ($sinU1 * $cosU2 * $cosLambda) ) *
                ( ($cosU1 * $sinU2) - ($sinU1 * $cosU2 * $cosLambda) )
            );
            if ($sinSigma == 0) return 0.0;
            $cosSigma = $sinU1 * $sinU2 + $cosU1 * $cosU2 * $cosLambda;
            $sigma = atan2($sinSigma, $cosSigma);
            $sinAlpha = $cosU1 * $cosU2 * $sinLambda / $sinSigma;
            $cosSqAlpha = 1 - $sinAlpha * $sinAlpha;
            $cos2SigmaM = $cosSqAlpha == 0 ? 0 : ($cosSigma - 2 * $sinU1 * $sinU2 / $cosSqAlpha);
            $C = $f / 16 * $cosSqAlpha * (4 + $f * (4 - 3 * $cosSqAlpha));
            $lambdaPrev = $lambda;
            $lambda = $L + (1 - $C) * $f * $sinAlpha * (
                $sigma + $C * $sinSigma * (
                    $cos2SigmaM + $C * $cosSigma * (-1 + 2 * $cos2SigmaM * $cos2SigmaM)
                )
            );
            if (abs($lambda - $lambdaPrev) < 1e-12) break;
        }
        if ($iterLimit <= 0) {
            return $this->haversineDistance($lat1, $lon1, $lat2, $lon2) * 1000.0;
        }
        $uSq = $cosSqAlpha * ($a * $a - $b * $b) / ($b * $b);
        $A = 1 + $uSq / 16384 * (4096 + $uSq * (-768 + $uSq * (320 - 175 * $uSq)));
        $B = $uSq / 1024 * (256 + $uSq * (-128 + $uSq * (74 - 47 * $uSq)));
        $deltaSigma = $B * $sinSigma * (
            $cos2SigmaM + $B / 4 * (
                $cosSigma * (-1 + 2 * $cos2SigmaM * $cos2SigmaM) -
                $B / 6 * $cos2SigmaM * (-3 + 4 * $sinSigma * $sinSigma) * (-3 + 4 * $cos2SigmaM * $cos2SigmaM)
            )
        );
        $s = $b * $A * ($sigma - $deltaSigma);
        return $s;
    }

    public function getById($id)
    {
        $college= College::find($id);
        return view('college.viewcollegedes', compact('college'));
    }

    public function getByIdForAdmin($id)
    {
        $college = College::with(['courseDetails' => function($q){ $q->with('course'); }, 'images'])->findOrFail($id);
        return view('admin.collegeDetailView', compact('college'));
    }
    public function getByIdForStudent($id)
    {
        $college = College::with('courseDetails.course')->findOrFail($id);
        if ($college->status !== 'APPROVED') {
            abort(404);
        }
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
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
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

        if ($request->filled('latitude') && $request->filled('longitude')) {
            $oldCollege->latitude = (float) $request->input('latitude');
            $oldCollege->longitude = (float) $request->input('longitude');
        }

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
        $college->status = 'APPROVED';
        $college->save();

        return redirect()->route('home')->with('success', 'College status updated to approved!');
    }

    public function approve($id)
    {
        $college = College::findOrFail($id);
        $college->status = 'APPROVED';
        $college->save();

        // Auto-approve all course details for this college
        CourseDetail::where('college_id', $college->id)->update(['status' => 'APPROVED']);

        return redirect()->back()->with('success', 'College approved successfully.');
    }

    public function reject($id)
    {
        $college = College::findOrFail($id);
        $college->status = 'REJECTED';
        $college->save();

        return redirect()->back()->with('success', 'College rejected successfully.');
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