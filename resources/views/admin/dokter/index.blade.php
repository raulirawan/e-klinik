@extends('layouts.admin')
@section('title', 'Dokter Page')
@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/custom/datatables.bundle.css') }}">
@endpush
@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title fw-semibold mb-4">List Data Dokter</h5>
                <a href="{{ route('admin.dokter.create') }}" class="btn btn-success mb-3">
                    (+) Tambah
                </a>
                <div class="table-responsive">
                    <table class="table w-100" id="table-data">
                       <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>STR</th>
                            <th>Jenis Kelamin</th>
                            <th>Aksi</th>
                        </tr>
                       </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
        <script src="{{ asset('assets/custom/datatables.bundle.js') }}"></script>
        <script>
            $(document).ready(function() {
                $("#table-data").DataTable({
                    responsive: true,
                    paging: true,
                    lengthMenu: [10, 25, 50, 100],
                    pageLength: 10,
                    processing: true,
                    serverSide: true,
                    searching: true,
                    ajax: {
                        url: "{{ url()->current() }}",
                        type: "GET",
                    },
                    order: [
                        [0, "desc"]
                    ],
                    columns: [
                        {
                            data: "id"
                        },
                        {
                            data: "name"
                        },
                        {
                            data: "email"
                        },
                        {
                            data: "no_str"
                        },
                        {
                            data: "gender"
                        },
                        {
                            data: "action"
                        },
                    ],
                    columnDefs: [{
                            defaultContent: "-",
                            targets: "_all",
                        },
                        {
                            targets: 0,
                            width: "50",
                        },
                        {
                            targets: 5,
                            width: "150",
                            orderable: false,
                        },
                    ],
                });
            });
        </script>
    @endpush
@endsection
