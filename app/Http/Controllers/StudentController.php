<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class StudentController extends Controller
{
    private $students = [
        [
            "nim" => "123456789012345",
            "nama" => "Citra Dewi",
            "mataKuliah" => [
                [
                    "kode" => "CIE61205",
                    "nama" => "PemWeb",
                    "sks" => 3
                ],
                [
                    "kode" => "COM60015",
                    "nama" => "MatDis",
                    "sks" => 2
                ]
            ]
        ],
        [
            "nim" => "123456789012346",
            "nama" => "Andy Lau",
            "mataKuliah" => [
                [
                    "kode" => "CIE61205",
                    "nama" => "PemWeb",
                    "sks" => 3
                ],
                [
                    "kode" => "CIE61206",
                    "nama" => "JarKom",
                    "sks" => 3
                ],
                [
                    "kode" => "CIE61208",
                    "nama" => "BasDat",
                    "sks" => 3
                ]
            ]
        ]
    ];

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'nim' => 'required|digits:15',
                'nama' => 'required|string|max:50|min:3',
                'mataKuliah' => 'required|array|min:1',
                'mataKuliah.*.kode' => 'required|regex:/^[A-Z]{3}[0-9]{5}$/',
                'mataKuliah.*.nama' => 'required|string|max:50',
                'mataKuliah.*.sks' => 'required|numeric|min:1|max:6',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                "message" => "Validation failed",
                "errors" => $e->errors()
            ], 422);
        }

        return response()->json([
            "message" => "Student created successfully",
            "data" => $validated
        ], 201);
    }



    // GET /api/students
    public function index()
    {
        return response()->json([
            "message" => "Students retrieved successfully",
            "data" => $this->students
        ], 200);
    }

    public function show($nim)
    {
        foreach ($this->students as $student) {
            if ($student['nim'] === $nim) {
                return response()->json([
                    "message" => "Student retrieved successfully",
                    "data" => $student
                ], 200);
            }
        }

        return response()->json([
            "message" => "Student not found"
        ], 404);
    }

    // PUT/PATCH /api/students/{nim}
    public function update(Request $request, $nim)
    {
        try {
            $validated = $request->validate([
                'nama' => 'sometimes|required|string|max:50|min:3',
                'mataKuliah' => 'sometimes|required|array|min:1',
                'mataKuliah.*.kode' => 'sometimes|required|regex:/^[A-Z]{3}[0-9]{5}$/',
                'mataKuliah.*.nama' => 'sometimes|required|string|max:50',
                'mataKuliah.*.sks' => 'sometimes|required|numeric|min:1|max:6',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                "message" => "Validation failed",
                "errors" => $e->errors()
            ], 422);
        }

        return response()->json([
            "message" => "Student {$nim} updated successfully",
            "data" => array_merge(['nim' => $nim], $validated)
        ], 200);
    }

    public function destroy($nim)
    {
        return response()->json([
            "message" => "Student {$nim} deleted successfully"
        ], 200);
    }

    public function mataKuliahByStudent($nim)
    {
        foreach ($this->students as $student) {
            if ($student['nim'] === $nim) {
                return response()->json([
                    "message" => "Courses retrieved successfully",
                    "student_nim" => $nim,
                    "data" => $student['mataKuliah']
                ], 200);
            }
        }

        return response()->json([
            "message" => "Student not found"
        ], 404);
    }

    // GET /api/students/search
    public function search(Request $request)
    {
        if (!$request->hasAny(['nim', 'nama', 'kode_mk'])) {
            return response()->json([
                "message" => "At least one search parameter (nim, nama, kode_mk) is required."
            ], 400);
        }

        $students = [
            [
                "nim" => "123456789012345",
                "nama" => "Citra Dewi",
                "mataKuliah" => [
                    ["kode" => "CIE61205", "nama" => "PemWeb", "sks" => 3],
                    ["kode" => "COM60015", "nama" => "MatDis", "sks" => 2]
                ]
            ],
            [
                "nim" => "123456789012346",
                "nama" => "Andy Lau",
                "mataKuliah" => [
                    ["kode" => "CIE61205", "nama" => "PemWeb", "sks" => 3],
                    ["kode" => "CIE61206", "nama" => "JarKom", "sks" => 3],
                    ["kode" => "CIE61208", "nama" => "BasDat", "sks" => 3]
                ]
            ]
        ];

        $results = $students;

        if ($request->has('nim')) {
            $results = array_filter($results, fn($s) => $s['nim'] === $request->nim);
        }

        if ($request->has('nama')) {
            $results = array_filter($results, fn($s) => str_contains(
                strtolower($s['nama']), strtolower($request->nama)
            ));
        }

        if ($request->has('kode_mk')) {
            $results = array_filter($results, function ($s) use ($request) {
                foreach ($s['mataKuliah'] as $mk) {
                    if ($mk['kode'] === $request->kode_mk) return true;
                }
                return false;
            });
        }

        return response()->json(array_values($results));
    }
}
