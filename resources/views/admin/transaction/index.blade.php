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
                <form>
                    <div class="row input-daterange ml-2 mt-2 mb-5">
                        <div class="col-md-3">
                            <label for="">Dari Tanggal</label>
                            <input type="date" name="from_date" id="from_date" class="form-control datatable-input" data-col-index="0" value="{{ date('Y-m-d') }}"
                                placeholder="From Date" />
                        </div>
                        <div class="col-md-3">
                            <label for="">Sampai Tanggal</label>
                            <input type="date" name="to_date" id="to_date" class="form-control datatable-input" data-col-index="0" value="{{ date('Y-m-d') }}"
                                placeholder="To Date" />
                        </div>
                        <div class="col-md-3">
                            <label for="">Status</label>
                            <select name="status" id="status" class="form-control datatable-input" data-col-index="7">
                                <option value="">Pilih Status</option>
                                <option value="PAID">PAID</option>
                                <option value="PENDING">PENDING</option>
                                <option value="MENUNGGU PEMBAYARAN">MENUNGGU PEMBAYARAN</option>
                                <option value="CANCEL">CANCEL</option>
                            </select>
                        </div>
                        <div class="col-md-3 mt-4">
                            <button type="button" name="filter" id="filter" class="btn btn-primary">Filter</button>
                            <button type="button" name="refresh" class="btn btn-danger" id="reset"
                                class="btn btn-default">Refresh</button>
                        </div>

                    </div>
                </form>
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
                        @if (Auth::user()->roles == 'ADMIN')
                            <tfoot>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        @endif
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
                    footerCallback: function(row, data) {
                        var api = this.api(),
                            data;

                        // Remove the formatting to get integer data for summation
                        var intVal = function(i) {
                            return typeof i === 'string' ?
                                i.replace(/[\$,]/g, '') * 1 :
                                typeof i === 'number' ?
                                i : 0;
                        };

                        total = api
                            .column(8)
                            .data()
                            .reduce(function(a, b) {
                                return intVal(a) + intVal(b);
                            }, 0);

                        // Total over this page
                        price = api
                            .column(8, {
                                page: 'current'
                            })
                            .data()
                            .reduce(function(a, b) {
                                return intVal(a) + intVal(b);
                            }, 0);

                        $(api.column(8).footer()).html(
                            'Rp' + price
                        );

                        var numFormat = $.fn.dataTable.render.number('\,', 'Rp').display;
                        $(api.column(8).footer()).html(
                            'Rp ' + numFormat(price)
                        );
                    }
                });
            });
        </script>
    @endpush
@endsection
