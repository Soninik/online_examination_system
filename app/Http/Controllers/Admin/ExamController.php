<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\ResponseController;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\Exam;

class ExamController extends ResponseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $exam = Exam::with('subject')->select('id', 'exam_name', 'subject_id', 'exam_date', 'exam_time')->get();

        return view('admin.exam', compact('exam'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $validator = Validator::make($request->all(), [
                'exam_name' => 'required|string|min:3',
                'subject_id' => 'required',
                'exam_date' => 'required|date',
                'exam_time' => 'required|date_format:H:i'
            ]);
            if ($validator->fails()) {
                return $this->send_error($validator->errors(), "Validation Error");
            }
            $exam = Exam::create([
                'exam_name' => $request->exam_name,
                'subject_id' => $request->subject_id,
                'exam_date' => $request->exam_date,
                'exam_time' => $request->exam_time,
            ]);
            if ($exam) {
                DB::commit();
                return $this->send_success($exam, "Exam Added Sucessfully !");
            } else {
                DB::rollBack();
                return $this->send_error($exam, "Exam Not added successfully !");
            }
        } catch (\Exception $e) {
            dd($e->getMessage());
            return $this->fail_api($e->getMessage(), "Api not working");
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $exam = Exam::findOrFail($id);
            if ($exam) {
                return $this->send_success($exam, "Exam Data Fetch Successfully !");
            } else {
                return $this->send_error($exam, "Exam data not fetch");
            }
        } catch (\Exception $e) {
            return $this->fail_api($e->getMessage(), "Api are Fail");
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        DB::beginTransaction();
        try {
            $validator = Validator::make($request->all(), [
                'exam_name' => 'required|string|min:3',
                'subject_id' => 'required',
                'exam_date' => 'required|date',
                'exam_time' => 'required|date_format:H:i'
            ]);
            if ($validator->fails()) {
                return $this->send_error($validator->errors(), "Validation Error");
            }
            $exam = Exam::where('id', $id)->update([
                'exam_name' => $request->exam_name,
                'subject_id' => $request->subject_id,
                'exam_date' => $request->exam_date,
                'exam_time' => $request->exam_time,
            ]);
            if ($exam) {
                DB::commit();
                return $this->send_success($exam, "Exam Updated Successfully !");
            } else {
                DB::rollBack();
                return $this->send_error($exam, "Exam Not Updated");
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->fail_api($e->getMessage(), "Api fail");
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $exam = Exam::destroy($id);
            if ($exam) {
                return $this->send_success($exam, "Exam Deleted Successfully !");
            } else {
                return $this->send_error($exam, "Exam Not Deleted");
            }
        } catch (\Exception $e) {
            return $this->fail_api($e->getMessage(), "Api fail");
        }
    }
}
