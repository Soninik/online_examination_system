@extends('layout.admin_layout')

@section('title', 'Exam')

@section('contant')
    <style>
        .error {
            color: red;
            font-weight: bold;
        }
    </style>

    <h1>Subject</h1>
    <!-- Button trigger modal -->
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
        Add Exam
    </button>

    <table class="table table-bordered">
        <thead>
            <th>Id</th>
            <th>Exam Name</th>
            <th>Subject</th>
            <th>Date</th>
            <th>Time</th>
            <th>Action</th>
        </thead>
        <tbody>
            @foreach ($exam as $exm)
                <tr>
                    <td>{{ $exm->id }}</td>
                    <td>{{ $exm->exam_name }}</td>
                    <td>{{ $exm->subject->subject }}</td>
                    <td>{{ \Carbon\Carbon::parse($exm->exam_date)->format('d-m-Y') }}</td>
                    <td>{{ $exm->exam_time }}</td>
                    <td>
                        <button type="button" class="btn btn-success editExam" data-id={{ $exm->id }}
                            data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                            Edit Exam
                        </button>
                        <a href="#" class="btn btn-danger deleteExam" data-id={{ $exm->id }}>Delete Exam</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>



    <!-- Modal -->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel"> Subject</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="examForm">
                        @csrf
                        <div class="mb-4">
                            <input type="hidden" name="id" id="exam_id">
                            <label for="" class="form-control">Exam Name</label>
                            <input type="text" name="exam_name" id="exam_name" class="form-control"
                                placeholder="Enter Exam ">
                            <span class="error" id="error-exam_name"></span>
                        </div>
                        <div class="mb-4">
                            <label for="" class="form-control">Subject</label>
                            <select name="subject_id" id="subject_id" class="form-select">
                                <option value="" disabled selected>Select Subject</option>
                                @foreach (getSubject() as $subject)
                                    <option value="{{ $subject->id }}">{{ $subject->subject }}</option>
                                @endforeach
                            </select>
                            <span class="error" id="error-subject_id"></span>
                        </div>
                        <div class="mb-4">
                            <label for="" class="form-control">Exam Date</label>
                            <input type="date" name="exam_date" id="exam_date" class="form-control"
                                placeholder="Enter Exam Date ">
                            <span class="error" id="error-exam_date"></span>
                        </div>
                        <div class="mb-4">
                            <label for="" class="form-control">Exam Time</label>
                            <input type="time" name="exam_time" id="exam_time" class="form-control"
                                placeholder="Enter Exam Date ">
                            <span class="error" id="error-exam_time"></span>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary saveExam">Save Exam</button>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('script')
    <script>
        $(document).ready(function() {
            $(".saveExam").click(function(e) {
                e.preventDefault();
                let exam_id = $("#exam_id").val()
                if (exam_id) {
                    url = "{{ url('exam') }}/" + exam_id;
                    type = "PUT";
                } else {
                    url = "{{ route('exam.store') }}";
                    type = "POST";
                }
                $.ajax({
                    type: type,
                    url: url,
                    data: $("#examForm").serialize(),
                    datatype: "json",
                    success: function(res) {
                        if (res.success == false) {
                            errorMsg(res);
                        } else {
                            $("#examForm")[0].reset();
                            $("#staticBackdrop").modal('hide');
                            window.location.reload();
                        }
                    }
                });
            });
        })

        $(".deleteExam").click(function(e) {
            let id = $(this).data('id')
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {

                    $.ajax({
                        type: "DELETE",
                        url: "{{ url('exam') }}/" + id,
                        data: {
                            _token: "{{ csrf_token() }}",
                            id: id
                        },
                        datatype: "json",
                        success: function(res) {
                            console.log(res);
                            if (res.success) {
                                Swal.fire({
                                    title: "Deleted!",
                                    text: res.msg,
                                    icon: "success"
                                });
                                window.location.reload();
                            }
                        }
                    });
                }
            });
        });



        $(".editExam").click(function(e) {
            e.preventDefault();
            let id = $(this).data('id');
            $.ajax({
                type: "GET",
                url: "{{ url('exam') }}/" + id + "/edit",
                data: {
                    id: id
                },
                datatype: "json",
                success: function(res) {
                    $("#exam_id").val(res.data.id);
                    $("#exam_name").val(res.data.exam_name);
                    $("#exam_date").val(res.data.exam_date);
                    $("#exam_time").val(res.data.exam_time);
                    $("#subject_id").val(res.data.subject_id).trigger('listed:updated');
                }
            });
        });

        const errorMsg = (error) => {
            let errorMsg = error.msg;
            Object.keys(errorMsg).forEach((field) => {
                $("#error-" + [field]).text("")
                errorMsg[field].forEach((msg) => {
                    $("#error-" + [field]).text(msg)
                })
            });
        };
    </script>
@endpush
