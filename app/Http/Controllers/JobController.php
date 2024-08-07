<?php

namespace App\Http\Controllers;

use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobController extends Controller
{
    public function jobpost(Request $request)
    {

        $job = Job::create([
            'title' => $request->input('title'),
            'requirement' => $request->input('requirement'),
            'location' => $request->input('location'),
            'jobType' => $request->input('jobType'),
            'salary' => $request->input('salary'),
            'description' => $request->input('description'),
            'companyName' => $request->input('companyName'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'address' => $request->input('address'),
            'companyLocation' => $request->input('companyLocation'),
            'additionalInfo' => $request->input('additionalInfo'),
            'user_id' => $request->input('user_id'),
        ]);

        return response()->json(['message' => 'Job posted successfully!', 'job' => $job], 201);
    }

    public function getUserJobs(Request $request)
    {
        $jobs = Job::all();
        return response()->json([
            'message' => 'All jobs retrieved successfully!',
            'jobs' => $jobs
        ], 200);
    }

    public function findUserJobs($user_id)
    {

        $jobs = Job::where('user_id', $user_id)->get();
        if ($jobs->isEmpty()) {
            return response()->json(['message' => 'No jobs found for this user.'], 404);
        }
        return response()->json(['message' => 'User jobs retrieved successfully!', 'jobs' => $jobs], 200);

    }

    public function show($id)
    {
        $job = Job::find($id);
        if (!$job) {
            return response()->json(['error' => 'Job not found', 404]);
        }
        return response()->json(['job' => $job]);

    }

    public function deleteJob($id)
    {
        $job = Job::find($id);
        if ($job) {
            $job->delete();
            return response()->json(['message' => 'Job deleted successfully!'], 200);

        }
        return response()->json(['error' => 'Job not found'], 404);
    }

    public function update(Request $req, $id)
    {
        $job = Job::find($id);

        if (!$job) {
            return response()->json(['message' => 'Job not found'], 404);
        }

        $job->title = $req->input('title');
        $job->requirement = $req->input('requirement');
        $job->location = $req->input('location');
        $job->jobType = $req->input('jobType');
        $job->salary = $req->input('salary');
        $job->description = $req->input('description');
        $job->companyName = $req->input('companyName');
        $job->email = $req->input('email');
        $job->phone = $req->input('phone');
        $job->address = $req->input('address'); 
        $job->companyLocation = $req->input('companyLocation');
        $job->additionalInfo = $req->input('additionalInfo');
        $job->save();

        return response()->json(['message' => 'Job updated successfully!', 'job' => $job], 200);
    }

    public function search(Request $request)
    {
        $keyword = $request->input('q');
        $location = $request->input('location');
    
        // Log the request parameters
        \Log::info('Search Query:', ['keyword' => $keyword, 'location' => $location]);
    
        if (!$keyword) {
            return response()->json([
                'error' => 'No search keyword provided'
            ], 400);
        }
    
        $jobs = Job::where(function($query) use ($keyword, $location) {
            $query->where('title', 'like', "%{$keyword}%")
                  ->orWhere('requirement', 'like', "%{$keyword}%")
                  ->orWhere('location', 'like', "%{$keyword}%")
                  ->orWhere('jobType', 'like', "%{$keyword}%")
                  ->orWhere('companyName', 'like', "%{$keyword}%")
                  ->orWhere('description', 'like', "%{$keyword}%")
                  ->orWhere('address', 'like', "%{$keyword}%")
                  ->orWhere('companyLocation', 'like', "%{$keyword}%")
                  ->orWhere('additionalInfo', 'like', "%{$keyword}%");
    
            if ($location) {
                $query->where('location', 'like', "%{$location}%");
            }
        })->paginate(10);
    
        // Log the result count
        \Log::info('Search Result Count:', ['count' => $jobs->total()]);
    
        if ($jobs->isEmpty()) {
            return response()->json([
                'message' => 'No jobs found',
                'data' => []
            ], 200);
        }
    
        return response()->json([
            'data' => $jobs->items(),
            'total' => $jobs->total(),
            'current_page' => $jobs->currentPage(),
            'last_page' => $jobs->lastPage(),
            'per_page' => $jobs->perPage()
        ]);
    }
    

    
}
