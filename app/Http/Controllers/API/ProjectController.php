<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    // Create Project API
    public function createProject(Request $request){
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'duration' => 'required'
        ]);

        $student_id = auth()->user()->id;

        $project = new Project();

        $project->name = $request->name;
        $project->description = $request->description;
        $project->duration = $request->duration;
        $project->student_id = $student_id;

        $project->save();

        return response()->json([
            'status' => 1,
            'message' => 'Project created successfully.'
        ]);
    }

    // List Project API
    public function listProject(){

        $student_id = auth()->user()->id;

        if(Project::where('student_id',$student_id)->exists()){
            $data = Project::where('student_id',$student_id)->get();

            return response()->json([
                'status' => 1,
                'message' => 'List all project details',
                'data' => $data
            ]);
        }

        return response()->json([
            'status' => 0,
            'message' => "projects not found"
        ],404);

    }

    // Single Project API
    public function singleProject($id){

        $student_id = auth()->user()->id;

        if(Project::where([
            'id' => $id,
            'student_id' => $student_id
        ])->exists()){
            $project = Project::where([
                'id' => $id,
                'student_id' => $student_id
                ])->first();

            return response()->json([
                'status' => 1,
                'message' => 'Get single project details',
                'data' => $project
            ]);
        }
        else{
            return response()->json([
                'status' => 0,
                'message' => 'Project not found'
            ],404);
        }

    }

    // Delete Project API
    public function deleteProject($id){

        $student_id = auth()->user()->id;

        if(Project::where([
            'id' => $id,
            'student_id' => $student_id
        ])->exists()){
            Project::where([
                'id' => $id,
                'student_id' => $student_id
            ])->delete();

            return response()->json([
                'status' => 1,
                'message' => 'project deleted successfully'
            ]);
        }
        else{
            return response()->json([
                'status' => 0,
                'message' => 'project not found'
            ],404);
        }

    }
}
