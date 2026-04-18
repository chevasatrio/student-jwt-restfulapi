<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{

    // GET /api/students — List all students
    public function index()
    {
        $students = Student::all();
        return response()->json([
            'success' => true,
            'message' => 'Data semua mahasiswa',
            'data' => $students
        ], 200);
    }

    // GET /api/students/{id} — Get student by ID
    public function show($id)
    {
        $student = Student::find($id);
        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'Mahasiswa tidak ditemukan'
            ], 404);
        }
        return response()->json([
            'success' => true,
            'data' => $student
        ], 200);
    }

    // POST /api/students — Create a student
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'nim' => 'required|string|unique:students',
            'email' => 'required|email|unique:students',
            'major' => 'required|string',
            'semester' => 'required|integer|min:1|max:14',
        ]);

        $student = Student::create($validated);
        return response()->json([
            'success' => true,
            'message' => 'Mahasiswa berhasil dibuat',
            'data' => $student
        ], 201);
    }

    // PUT /api/students/{id} — Edit a student
    public function update(Request $request, $id)
    {
        $student = Student::find($id);
        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'Mahasiswa tidak ditemukan'
            ], 404);
        }

        $validated = $request->validate([
            'name' => 'sometimes|string|max:100',
            'nim' => 'sometimes|string|unique:students,nim,' . $id,
            'email' => 'sometimes|email|unique:students,email,' . $id,
            'major' => 'sometimes|string',
            'semester' => 'sometimes|integer|min:1|max:14',
        ]);

        $student->update($validated);
        return response()->json([
            'success' => true,
            'message' => 'Data mahasiswa diperbarui',
            'data' => $student
        ], 200);
    }

    // DELETE /api/students/{id} — Delete a student
    public function destroy($id)
    {
        $student = Student::find($id);
        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'Mahasiswa tidak ditemukan'
            ], 404);
        }
        $student->delete();
        return response()->json([
            'success' => true,
            'message' => 'Mahasiswa berhasil dihapus'
        ], 200);
    }
}
