<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\ResponseController;
use App\Models\Subject;

class SubjectController extends ResponseController
{

    public function index()
    {
        $subject = Subject::select('id', 'subject')->get();
        return view('admin.subject', compact('subject'));
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), [
                'subject' => 'required|string|min:3'
            ]);
            if ($validation->fails()) {
                return $this->send_error("Validation Error", $validation->errors());
            }
            $data = $request->all();
            $subject = Subject::create($data);
            if ($subject) {
                DB::commit();
                return $this->send_success($subject, "Subject Added Successfully !");
            } else {
                DB::rollBack();
                return $this->send_error($subject, "Subject Not Added Sucessfully !");
            }
        } catch (\Exception $e) {
            dd($e->getMessage());
            dd("Error");
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
            $subject = Subject::findOrFail(base64_decode($id));
            if ($subject) {
                return $this->send_success($subject, "Subject fetch successfully !");
            } else {
                return $this->send_error($subject, "Subject not fetch successfully !");
            }
        } catch (\Exception $e) {
            return $this->fail_api($e->getMessage(), "Api are fail");
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        DB::beginTransaction();
        try {
            $subject = Subject::where('id', $id)->update(['subject' => $request->subject]);
            if ($subject) {
                DB::commit();
                return $this->send_success($subject, "Subject updated");
            } else {
                DB::rollBack();
                return $this->send_error($subject, "Subject noy updated");
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->fail_api($e->getMessage(), "Api are fail");
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $subject = Subject::destroy($id);
            if ($subject) {
                return $this->send_success($subject, "Subject Deleted Successfully !");
            } else {
                return $this->send_error($subject, "Subject not deleted");
            }
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }
}
