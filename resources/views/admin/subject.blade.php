@extends('layout.admin_layout')

@section('title', 'Subject')

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
        Add Subject
    </button>

    <table class="table table-bordered">
        <thead>
            <th>Id</th>
            <th>Subject</th>
            <th>Action</th>
        </thead>
        <tbody>
            @foreach ($subject as $sub)
                <tr>
                    <td>{{ $sub->id }}</td>
                    <td>{{ $sub->subject }}</td>
                    <td>
                        <button type="button" class="btn btn-success editsub" data-id={{ base64_encode($sub->id) }}
                            data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                            Edit Subject
                        </button>
                        <a class="btn btn-danger deleteSub" data-id={{ $sub->id }}>Delete</a>


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
                    <form id="subjectForm">
                        @csrf
                        <div class="mb-4">
                            <input type="hidden" name="id" id="subject_id">
                            <label for="" class="form-control">Subject</label>
                            <input type="text" name="subject" id="subject" class="form-control">
                            <span class="error" id="error-subject"></span>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary saveSubject">Save Subject</button>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('script')
    <script>
        $(document).ready(function() {
            $(".saveSubject").click(function(e) {
                let showSubjectId = $("#subject_id").val()
                if (showSubjectId != "" && showSubjectId != null) {
                    url = "{{ url('subject') }}/" + showSubjectId;
                    type = "PUT";
                } else {
                    url = "{{ route('subject.store') }}";
                    type = "POST";
                }
                e.preventDefault();
                $.ajax({
                    type: type,
                    url: url,
                    data: $("#subjectForm").serialize(),
                    datatype: "json",
                    success: function(res) {
                        if (res.success == false) {
                            errorFunction(res);
                        } else {
                            alert(res.msg)
                            $("#subjectForm")[0].reset();
                            $("#staticBackdrop").modal('hide');
                            window.location.reload();
                        }
                    }
                });
            });
        });

        $(".editsub").click(function(e) {
            let id = $(this).data('id');
            e.preventDefault();
            $.ajax({
                type: "GET",
                url: "{{ url('subject') }}/" + id + "/edit",
                data: {
                    id: id
                },
                datatype: "json",
                success: function(res) {
                    $("#subject_id").val(res.data.id)
                    $("#subject").val(res.data.subject)
                }
            });
        });

        $(".deleteSub").click(function(e) {
            let id = $(this).data('id');
            e.preventDefault();

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
                        url: "{{ url('subject') }}/" + id,
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

        function errorFunction(res) {

            let errorMsg = res.data;
            Object.keys(errorMsg).forEach((field) => {
                $("#error-" + [field]).text("")
                errorMsg[field].forEach((error) => {
                    $("#error-" + [field]).text(error)

                });
            })
        }
    </script>
@endpush
