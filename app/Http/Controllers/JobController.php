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

        // Update other fields as necessary
        $job->save();

        return response()->json(['message' => 'Job updated successfully!', 'job' => $job], 200);
    }

}
