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
                @if (Auth::user()->roles == 'APOTEKER')
                    <a class="btn btn-success mb-3"
                        href="{{ route('apoteker.transaction.update.status', $transaction->id) }}"
                        onclick="return confirm('Yakin ?')">Kunci Data</a>
                @endif
                @if (Auth::user()->roles == 'ADMIN' && $transaction->status == 'PENDING')
                    <a class="btn btn-danger mb-3" href="{{ route('admin.transaction.update.status', $transaction->id) }}"
                        onclick="return confirm('Yakin ?')">Cancel Appointment</a>
                @endif
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
                            @elseif($transaction->status == 'MENUNGGU PEMBAYARAN')
                                <td>
                                    <span class="badge bg-warning">MENUNGGU PEMBAYARAN</span>

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

        @if ($transaction->status != 'CANCEL')
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title fw-semibold mb-4">Transaksi</h5>

                    @php
                    if(Auth::user()->roles == "DOKTER") {
                        $routeUpdate = route('dokter.transaction.update');

                    }else {
                        $routeUpdate = route('admin.transaction.update');
                    }
                    @endphp
                    <form action="{{ $routeUpdate }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <label for="basicInput">Rekam Medis</label>
                                    <textarea name="medical_records" id="medical_records" class="form-control" cols="30" rows="5"
                                        placeholder="Rekam Medis">{{ $transaction->medical_record ?? '' }}</textarea>
                                </div>
                                @if ($transaction->detailMedicine->isNotEmpty())
                                    <div class="form-group mb-3">
                                        <label for="basicInput">Obat</label>
                                        <span id="row-obat">

                                            @foreach ($transaction->detailMedicine as $value)
                                                @if (!empty($value->medicine_id))
                                                    <div class="row mt-3">
                                                        <div class="col">
                                                            <select name="medicine_id[]"
                                                                class="form-control select-medicine-{{ $loop->index }}">
                                                                <option value="{{ $value->medicine_id }}">
                                                                    {{ $value->medicine->name }}</option>
                                                            </select>
                                                            {{-- <input type="hidden" id="id_medicine_{{ $loop->index }}"
                                                            value="{{ $value->medicine_id }}-{{ $value->medicine->harga }}">
                                                        <input type="hidden" id="name_medicine_{{ $loop->index }}"
                                                            value="{{ $value->medicine->name }}"> --}}
                                                        </div>
                                                        <div class="col">
                                                            <input type="number" id="medicine_qty_{{ $loop->index }}"
                                                                class="form-control" value="{{ $value->qty }}"
                                                                name="medicine_qty[]" placeholder="Quantity" required>
                                                        </div>
                                                        <div class="col">
                                                            <input type="text" id="medicine_price_{{ $loop->index }}"
                                                                class="form-control" value="{{ $value->price }}"
                                                                name="medicine_price[]" placeholder="Harga" readonly>
                                                        </div>
                                                        @if ($loop->index == 0)
                                                            <div class="col-md-1" id="add-obat">
                                                                <button class="btn btn-success btn-sm"
                                                                    style="margin-top: 5px">(+)</button>
                                                            </div>
                                                        @else
                                                            <div class="col-md-1" id="add-obat">
                                                                <button class="btn btn-danger btn-sm" id="delete-row-obat"
                                                                    data-index="{{ $loop->index }}"
                                                                    style="margin-top: 5px">(-)</button>
                                                            </div>
                                                        @endif
                                                    </div>
                                                @endif
                                            @endforeach
                                        </span>
                                    </div>
                                @else
                                    <div class="form-group mb-3">
                                        <label for="basicInput">Obat</label>
                                        <span id="row-obat">
                                            <div class="row mt-3">
                                                <div class="col">
                                                    <select name="medicine_id[]" id="medicine_id"
                                                        class="form-control select-medicine-0">
                                                        <option value=""></option>
                                                    </select>
                                                </div>
                                                <div class="col">
                                                    <input type="number" id="medicine_qty_0" class="form-control"
                                                        value="" name="medicine_qty[]" placeholder="Quantity"
                                                        required>
                                                </div>
                                                <div class="col">
                                                    <input type="text" id="medicine_price_0" class="form-control"
                                                        value="" name="medicine_price[]" placeholder="Harga"
                                                        readonly>
                                                </div>
                                                <div class="col-md-1" id="add-obat">
                                                    <button class="btn btn-success btn-sm"
                                                        style="margin-top: 5px">(+)</button>
                                                </div>

                                            </div>
                                        </span>
                                    </div>
                                @endif

                                @if ($transaction->detailService->isNotEmpty())
                                    <div class="form-group mb-3">
                                        <label for="basicInput">Layanan</label>
                                        <span id="row-layanan">
                                            @foreach ($transaction->detailService as $value)
                                                <div class="row mt-3">
                                                    <div class="col">
                                                        <input type="text"
                                                            id="layanan_service_name_{{ $loop->index }}"
                                                            class="form-control" value="{{ $value->service_name }}"
                                                            name="layanan_service_name[]" placeholder="Nama Layanan"
                                                            required>
                                                    </div>
                                                    <div class="col">
                                                        <input type="number" id="layanan_qty_{{ $loop->index }}"
                                                            class="form-control" value="{{ $value->qty }}"
                                                            name="layanan_qty[]" placeholder="Quantity" required>
                                                    </div>
                                                    <div class="col">
                                                        <input type="text" id="layanan_price_{{ $loop->index }}"
                                                            class="form-control" value="{{ $value->price }}"
                                                            name="layanan_price[]" placeholder="Harga">
                                                    </div>
                                                    @if ($loop->index == 0)
                                                        <div class="col-md-1" id="add-layanan">
                                                            <button class="btn btn-success btn-sm"
                                                                style="margin-top: 5px">(+)</button>
                                                        </div>
                                                    @else
                                                        <div class="col-md-1" id="add-layanan">
                                                            <button class="btn btn-danger btn-sm" id="delete-row-layanan"
                                                                data-index="{{ $loop->index }}"
                                                                style="margin-top: 5px">(-)</button>
                                                        </div>
                                                    @endif

                                                </div>
                                            @endforeach
                                        </span>
                                    </div>
                                @else
                                    <div class="form-group mb-3">
                                        <label for="basicInput">Layanan</label>
                                        <span id="row-layanan">
                                            <div class="row mt-3">
                                                <div class="col">
                                                    <input type="text" id="layanan_service_name_0"
                                                        class="form-control" value="" name="layanan_service_name[]"
                                                        placeholder="Nama Layanan" required>
                                                </div>
                                                <div class="col">
                                                    <input type="number" id="layanan_qty_0" class="form-control"
                                                        value="" name="layanan_qty[]" placeholder="Quantity"
                                                        required>
                                                </div>
                                                <div class="col">
                                                    <input type="text" id="layanan_price_0" class="form-control"
                                                        value="" name="layanan_price[]" placeholder="Harga">
                                                </div>
                                                <div class="col-md-1" id="add-layanan">
                                                    <button class="btn btn-success btn-sm"
                                                        style="margin-top: 5px">(+)</button>
                                                </div>

                                            </div>
                                        </span>
                                    </div>
                                @endif
                            </div>

                            <div class="col-md-12">
                                <h3 class="mb-5 mt-4">Total Harga : <span id="total-price"></span></h3>
                                <input type="hidden" name="total_price" id="total_price">
                                @if ($transaction->status == 'PENDING' || $transaction->status == 'MENUNGGU PEMBAYARAN')
                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                @endif
                            </div>

                        </div>
                    </form>

                </div>
            </div>
        @endif
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
                const data = @json($dataObat);
                const dataLayanan = @json($dataLayanan);
                var index = data.length == 0 ? 0 : data.length - 1;
                var indexLayanan = dataLayanan.length == 0 ? 0 : dataLayanan.length - 1;
                var HOST_URL = "{{ url('/') }}";
                calculateTotalPrice(data, dataLayanan);
                // select 2
                if (index > 0) {
                    data.forEach((el) => loadSelect(el.index));
                } else {
                    loadSelect(index);
                }

                if (dataLayanan.length > 0) {
                    dataLayanan.forEach((el) => loadLayanan(el.index, 'old'));
                } else {
                    loadLayanan(indexLayanan);
                }

                function loadSelect(index) {
                    $(`.select-medicine-${index}`).select2({
                        placeholder: "Cari Obat",
                        theme: "bootstrap-5",
                        containerCssClass: "select2--small",
                        dropdownCssClass: "select2--small",
                        ajax: {
                            url: `${HOST_URL}/ajax/get-medicine-detail`,
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

                    $(`.select-medicine-${index}`).on("select2:select", function(e) {
                        var selectVal = $(this).val();
                        var price = selectVal.split('-')[1];
                        $(`#medicine_price_${index}`).val(price);
                        $(`#medicine_qty_${index}`).val(1);

                        const tempData = {
                            'index': index,
                            'medicine_price': price,
                            'medicine_qty': 1
                        };
                        data.push(tempData);
                        calculateTotalPrice(data, dataLayanan);
                    });

                    $(`#medicine_qty_${index}`).bind('click keyup', function() {
                        objIndex = data.findIndex((obj => obj.index == index));

                        data[objIndex].medicine_qty = $(this).val();
                        calculateTotalPrice(data, dataLayanan);
                    });
                }

                $("#add-obat").click(function(e) {
                    e.preventDefault();
                    index = index + 1;
                    var html = `
                        <div class="row mt-3">
                            <div class="col">
                                <select name="medicine_id[]" class="form-control select-medicine-${index}">
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
                                <button class="btn btn-danger btn-sm" id="delete-row-obat" data-index="${index}"
                                    style="margin-top: 5px">(-)</button>
                            </div>
                        </div>
                    `;
                    $("#row-obat").append(html);
                    loadSelect(index);
                });

                $(document).on('click', '#delete-row-obat', function() {
                    $(this).parent().parent().remove();

                    var indexRow = $(this).data('index');

                    data.splice(indexRow, 1);
                    calculateTotalPrice(data, dataLayanan);

                });

                function calculateTotalPrice(data, dataLayanan) {
                    var totalPrice = 0;
                    var totalPriceMedicine = 0;
                    var totalPriceLayanan = 0;

                    data.forEach((el) => totalPriceMedicine += parseInt(el.medicine_price) * parseInt(el
                        .medicine_qty));

                    dataLayanan.forEach((el) => totalPriceLayanan += parseInt(el.layanan_price) * parseInt(el
                        .layanan_qty));
                    var totalPrice = totalPriceMedicine + totalPriceLayanan;
                    $("#total-price").text(totalPrice);
                    $("#total_price").val(totalPrice);
                    return totalPrice;
                }
                // for layanan
                function loadLayanan(indexLayanan, status = 'new') {

                    if (status != 'old') {
                        const tempData = {
                            'index': indexLayanan,
                            'layanan_service_name': null,
                            'layanan_qty': 0,
                            'layanan_price': 0,
                        };
                        dataLayanan.push(tempData);
                    }

                    $(`#layanan_service_name_${indexLayanan}`).bind('click keyup', function() {
                        objIndex = dataLayanan.findIndex((obj => obj.index == indexLayanan));
                        dataLayanan[objIndex].layanan_service_name = $(this).val();

                    });


                    $(`#layanan_qty_${indexLayanan}`).bind('click keyup', function() {
                        objIndex = dataLayanan.findIndex((obj => obj.index == indexLayanan));

                        dataLayanan[objIndex].layanan_qty = $(this).val();

                        calculateTotalPrice(data, dataLayanan);
                    });

                    $(`#layanan_price_${indexLayanan}`).bind('click keyup', function() {
                        objIndex = dataLayanan.findIndex((obj => obj.index == indexLayanan));
                        dataLayanan[objIndex].layanan_price = $(this).val();

                        calculateTotalPrice(data, dataLayanan);
                    });
                    console.log(dataLayanan);
                    console.log(indexLayanan);
                }

                $("#add-layanan").click(function(e) {
                    e.preventDefault();
                    indexLayanan = indexLayanan + 1;
                    var html = `
                        <div class="row mt-3">
                            <div class="col">
                                <input type="text" id="layanan_service_name_${indexLayanan}" class="form-control" value=""
                                    name="layanan_service_name[]" placeholder="Nama Layanan" required>
                            </div>
                            <div class="col">
                                <input type="number" id="layanan_qty_${indexLayanan}" class="form-control" value=""
                                    name="layanan_qty[]" placeholder="Quantity" required>
                            </div>
                            <div class="col">
                                <input type="text" id="layanan_price_${indexLayanan}" class="form-control" value=""
                                    name="layanan_price[]" placeholder="Harga">
                            </div>
                            <div class="col-md-1" id="add-obat">
                                <button class="btn btn-danger btn-sm" id="delete-row-layanan" data-index="${indexLayanan}"
                                    style="margin-top: 5px">(-)</button>
                            </div>
                        </div>
                    `;
                    $("#row-layanan").append(html);
                    loadLayanan(indexLayanan);
                });

                $(document).on('click', '#delete-row-layanan', function() {
                    $(this).parent().parent().remove();

                    var indexRow = $(this).data('index');
                    dataLayanan.splice(indexRow, 1);
                    calculateTotalPrice(data, dataLayanan);
                });
            });
        </script>
    @endpush

@endsection
