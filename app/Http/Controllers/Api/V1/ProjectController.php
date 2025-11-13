<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    /**
     * Display a listing of projects (cached for 10 minutes).
     */
    public function index()
    {
        $cacheKey = 'projects.all';

        // Retrieve projects from cache or store if missing
        $projects = Cache::remember($cacheKey, 600, function () {
            return Project::with('tasks')->get();
        });

        return response()->json([
            'message' => 'Project list retrieved successfully (cached)',
            'data' => $projects
        ], 200);
    }

    /**
     * Display a single project by ID.
     */
    public function show($id)
    {
        $project = Project::with('tasks')->find($id);

        if (!$project) {
            return response()->json(['error' => 'Project not found'], 404);
        }

        return response()->json([
            'message' => 'Project retrieved successfully',
            'data' => $project
        ], 200);
    }

    /**
     * Store a newly created project (Admin only).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date'
        ]);

        $project = Project::create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'start_date' => $validated['start_date'] ?? null,
            'end_date' => $validated['end_date'] ?? null,
            'created_by' => Auth::id(),
        ]);

        // Invalidate project cache
        Cache::forget('projects.all');

        return response()->json([
            'message' => 'Project created successfully',
            'data' => $project
        ], 201);
    }

    /**
     * Update an existing project (Admin only).
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date'
        ]);

        $project = Project::find($id);

        if (!$project) {
            return response()->json(['error' => 'Project not found'], 404);
        }

        $project->update($validated);

        // Invalidate project cache
        Cache::forget('projects.all');

        return response()->json([
            'message' => 'Project updated successfully',
            'data' => $project
        ], 200);
    }

    /**
     * Remove a project (Admin only).
     */
    public function destroy($id)
    {
        $project = Project::find($id);

        if (!$project) {
            return response()->json(['error' => 'Project not found'], 404);
        }

        $project->delete();

        // Invalidate project cache
        Cache::forget('projects.all');

        return response()->json(['message' => 'Project deleted successfully'], 200);
    }
}
