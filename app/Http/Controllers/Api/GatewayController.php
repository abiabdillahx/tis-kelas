<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\StudentController;
class GatewayController extends Controller
{
    public function getStudents(Request $request)
    {
        $studentController = new StudentController();
        return response()->json([
            "gateway" => "API Gateway",
            "message" => "Request forwarded to Student Service",
            "result" => $studentController->index()->getData(),
        ]);
    }
    public function createStudent(Request $request)
    {
        $studentController = new StudentController();
        return $studentController->store($request);
    }
    public function updateStudent(Request $request, $nim)
    {
        $studentController = new StudentController();
        return $studentController->update($request, $nim);
    }
    public function deleteStudent($nim)
    {
        $studentController = new StudentController();
        return $studentController->destroy($nim);
    }
}
