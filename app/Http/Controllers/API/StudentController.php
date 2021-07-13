<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StudentController extends Controller
{
    // Register API
    public function register(Request $request){

        // Validation
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:students',
            'password' => 'required|confirmed'
        ]);

        // Create Student
        $student = new Student();

        $student->name = $request->name;
        $student->email = $request->email;
        $student->password = Hash::make($request->password);
        $student->phone_no = isset($request->phone_no) ? $request->phone_no : "";

        $student->save();

        // Response
        return response()->json([
            'status' => 1,
            'messsage' => 'Student created successfully'
        ]);

    }

    // Login API
    public function Login(Request $request){

        // Validation
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $student = Student::where('email', $request->email)->first();

        if(isset($student->id)){
            if(Hash::check($request->password, $student->password)){
                // Create Sanctum Token
                $token = $student->createToken('auth_token')->plainTextToken;

                // Send a response
                return response()->json([
                    'status' => 1,
                    'message' => 'Student logged successfully',
                    'token' => $token
                ]);
            }
            else{
                return response()->json([
                    'status' => 0,
                    'message' => 'password didnt match'
                ],404);
            }
        }
        else{
            return response()->json([
                'status' => 0,
                'message' => 'email not found'
            ],404);
        }

    }

    // Profile API
    public function profile(){
        return response()->json([
            'status' => 1,
            'message' => 'Student profile information',
            'data' => auth()->user()
        ]);
    }

    // Logout API
    public function logout(){
        auth()->user()->tokens()->delete();

        return response()->json([
            'status' => 1,
            'message' => 'Student logout successfully'
        ]);
    }
}
