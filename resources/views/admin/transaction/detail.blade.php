@extends('layouts.admin')
@section('title', 'Transaction Detail Page')
@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" />
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    <!-- Or for RTL support -->
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.rtl.min.css" />
@endpush
@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title fw-semibold mb-4">Detail Transaction</h5>
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th style="width: 400px">Tanggal Transaksi</th>
                            <td>{{ $transaction->created_at ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th style="width: 400px">Kode Transaksi</th>
                            <td>{{ $transaction->code ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th style="width: 400px">Tanggal Appointment</th>
                            <td>{{ $transaction->booking_date ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th style="width: 400px">Hari Appointment</th>
                            <td>{{ $transaction->day ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th style="width: 400px">Jam Appointment</th>
                            <td>{{ $transaction->time ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th style="width: 400px">Nama Pasien</th>
                            <td>{{ $transaction->pasien->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th style="width: 400px">Nama Dokter</th>
                            <td>{{ $transaction->dokter->name }}</td>
                        </tr>
                        <tr>
                            <th style="width: 400px">Status Transaksi</th>
                            @if ($transaction->status == 'PAID')
                                <td>
                                    <span class="badge bg-success">PAID</span>
                                </td>
                            @elseif($transaction->status == 'PENDING')
                                <td>
                                    <span class="badge bg-warning">PENDING</span>

                                </td>
                            @else
                                <td>
                                    <span class="badge bg-danger">CANCEL</span>

                                </td>
                            @endif
                        </tr>
                        <tr>
                            <th style="width: 400px">Total Harga</th>
                            <td>Rp.{{ number_format($transaction->total_price) ?? '-' }}</td>
                        </tr>
                        @if ($transaction->status == 'PAID')
                            <tr>
                                <th style="width: 400px">Total Point Di Gunakan</th>
                                <td>Rp.{{ number_format($transaction->total_point_exchanged) }}</td>
                            </tr>
                            <tr>
                                <th style="width: 400px">Total Point Di Dapat</th>
                                <td>Rp.{{ number_format($transaction->total_point_earned) }}</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title fw-semibold mb-4">Transaksi</h5>
                <form action="{{ route('admin.dokter.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group mb-3">
                                <label for="basicInput">Rekam Medis</label>
                                <textarea name="medical_records" id="medical_records" class="form-control" cols="30" rows="5"
                                    placeholder="Rekam Medis"></textarea>
                            </div>
                            <div class="form-group mb-3">
                                <label for="basicInput">Obat</label>
                                <span id="row-obat">
                                    <div class="row mt-3">
                                        <div class="col">
                                            <select name="medicine_id[]" id="medicine_id"
                                                class="form-control select-medicine">
                                                <option value=""></option>
                                            </select>
                                        </div>
                                        <div class="col">
                                            <input type="number" id="medicine_qty_0" class="form-control" value=""
                                                name="medicine_qty[]" placeholder="Quantity" required>
                                        </div>
                                        <div class="col">
                                            <input type="text" id="medicine_price_0" class="form-control" value=""
                                                name="medicine_price[]" placeholder="Harga" readonly>
                                        </div>
                                        <div class="col-md-1" id="add-obat">
                                            <button class="btn btn-success btn-sm" style="margin-top: 5px">(+)</button>
                                        </div>

                                    </div>
                                </span>
                            </div>

                            <div class="form-group mb-3">
                                <label for="basicInput">Layanan</label>
                                <div class="row mt-3">
                                    <div class="col">
                                        <input type="email" id="email" class="form-control" value=""
                                            name="layanan_service_name" placeholder="Nama Layanan" required>
                                    </div>
                                    <div class="col">
                                        <input type="number" id="qty" class="form-control" value=""
                                            name="layanan_qty[]" placeholder="Quantity" required>
                                    </div>
                                    <div class="col">
                                        <input type="text" id="" class="form-control" value=""
                                            name="layanan_price[]" placeholder="Harga">
                                    </div>
                                    <div class="col-md-1" id="add-layanan">
                                        <button class="btn btn-success btn-sm" style="margin-top: 5px">(+)</button>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <h3 class="mb-5 mt-4">Total Harga : <span id="total-price"></span></h3>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="{{ asset('assets/custom/datatables.bundle.js') }}"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        @if (count($errors) > 0)
            <script type="text/javascript">
                $(document).ready(function() {
                    $('#modal-create').modal('show');
                });
            </script>
        @endif

        <script>
            $(document).ready(function() {
                var data = [];
                var index = 0;
                var HOST_URL = "{{ url('/') }}";
                // select 2
                loadSelect(index);

                function loadSelect(index) {
                    $(`.select-medicine`).select2({
                        placeholder: "Cari Obat",
                        theme: "bootstrap-5",
                        containerCssClass: "select2--small",
                        dropdownCssClass: "select2--small",
                        ajax: {
                            url: `${HOST_URL}/ajax/get-medicine`,
                            type: "post",
                            dataType: "json",
                            delay: 250,
                            data: function(params) {
                                return {
                                    search: params.term, // search term
                                };
                            },
                            processResults: function(response) {
                                return {
                                    results: response,
                                };
                            },
                            cache: true,
                        },
                    });

                    $('.select-medicine').on("select2:select", function(e) {
                        $(`#medicine_price_${index}`).val(10000);
                        $(`#medicine_qty_${index}`).val(1);
                    });
                }

                $("#add-obat").click(function(e) {
                    e.preventDefault();
                    index = index + 1;
                    var html = `
                        <div class="row mt-3">
                            <div class="col">
                                <select name="medicine_id[]" class="form-control select-medicine">
                                    <option value=""></option>
                                </select>
                            </div>
                            <div class="col">
                                <input type="number" id="medicine_qty_${index}" class="form-control" value=""
                                    name="medicine_qty[]" placeholder="Quantity" required>
                            </div>
                            <div class="col">
                                <input type="text" class="form-control" value=""
                                    name="medicine_price[]" placeholder="Harga" id="medicine_price_${index}" readonly>
                            </div>
                            <div class="col-md-1" id="add-obat">
                                <button class="btn btn-danger btn-sm" id="delete-row-obat"
                                    style="margin-top: 5px">(-)</button>
                            </div>
                        </div>
                    `;
                    $("#row-obat").append(html);
                    loadSelect(index);
                });

                $(document).on('click', '#delete-row-obat', function() {
                    $(this).parent().parent().remove();
                });


            });
        </script>
    @endpush

@endsection
