<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProjectRequest;
use App\Mail\SendProjectLink;
use App\Models\ProjectRequest;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ProjectRequestController extends Controller
{
    public function store(StoreProjectRequest $request)
    {
        try {
            $validated = $request->validated();
            ProjectRequest::create([
                'email' => $validated['email'],
            ]);
            $githubLink = 'https://github.com/Habibur-Rahman0927/laravel-curd-builder-pro';

            Mail::to($validated['email'])->send(new SendProjectLink($validated['email'], $githubLink));

            return response()->json([
                'message' => 'Thank you! The code link will be sent to ' . $validated['email']
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Something went wrong. Please try again later.',
                'error' => 'Something went wrong. Please try again later.'
            ], 500);
        }
    }
}
