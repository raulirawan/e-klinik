@extends('layouts.admin')
@section('title', 'Transaction Page')
@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/custom/datatables.bundle.css') }}">
@endpush
@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title fw-semibold mb-4">List Data Transaction</h5>
                <div class="table-responsive">
                    <table class="table w-100" id="table-data">
                        <thead>
                            <tr>
                                <th>Tanggal Transaksi</th>
                                <th>Kode</th>
                                <th>Tanggal Appointment</th>
                                <th>Pasien</th>
                                <th>Dokter</th>
                                <th class="none">Hari</th>
                                <th class="none">Jam</th>
                                <th>Status</th>
                                <th>Total Harga</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
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
                    columns: [{
                            data: "created_at"
                        },
                        {
                            data: "code"
                        },
                        {
                            data: "booking_date"
                        },
                        {
                            data: "pasien.name"
                        },
                        {
                            data: "dokter.name"
                        },
                        {
                            data: "day"
                        },
                        {
                            data: "time"
                        },
                        {
                            data: "status"
                        },
                        {
                            data: "total_price",
                            render: $.fn.dataTable.render.number(',', '.', 0, 'Rp ')
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
                            width: "200",
                        },
                        {
                            targets: 9,
                            width: "50",
                            orderable: false,
                        },
                    ],
                });
            });
        </script>
    @endpush
@endsection