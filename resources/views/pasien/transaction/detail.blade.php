@extends('layouts.admin')
@section('title', 'Transaction Detail Page')

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

        <div class="card">
            <div class="card-body">
                <h5 class="card-title fw-semibold mb-4">Transaction</h5>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Nama Obat/Layanan</th>
                                <th>Jumlah</th>
                                <th>Harga</th>
                            </tr>
                        </thead>
                        <tbody id="tbody">
                            @foreach ($transaction->detailMedicine as $value)
                                <tr>
                                    <td>{{ $value->medicine->name }}</td>
                                    <td>{{ $value->qty }}</td>
                                    <td>{{ number_format($value->price) }}</td>
                                </tr>
                            @endforeach
                            @foreach ($transaction->detailService as $value)
                                <tr>
                                    <td>{{ $value->service_name }}</td>
                                    <td>{{ $value->qty }}</td>
                                    <td>{{ number_format($value->price) }}</td>
                                </tr>
                            @endforeach
                            <tr class="row-price">
                                <td colspan="2">Total Yang Harus Di Bayar</td>
                                <td id="total_price_text">{{ number_format($transaction->total_price) }}</td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" value="true" id="pointSwitch"
                            {{ Auth::user()->point <= 0 ? 'disabled' : '' }}>
                        <label class="form-check-label" for="flexSwitchCheckChecked">Point Anda
                            {{ number_format(Auth::user()->point) }}</label>
                        <div class="text-danger"><small>Anda Dapat Menggunakan Point Anda</small></div>
                    </div>
                    <form method="POST" action="{{ route('pasien.transaction.payment', $transaction->id) }}">
                        <input type="hidden" id="total_price" name="total_price">
                        <input type="hidden" id="total_point" name="total_point">
                        <button class="btn btn-success mt-3" type="submit">Lakukan Pembayarran</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-switch/3.3.4/js/bootstrap-switch.min.js"></script>
        <script>
            function numberWithCommas(x) {
                return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            }
            var myPoint = "{{ Auth::user()->point }}";
            var totalPrice = "{{ $transaction->total_price }}";


            $('#pointSwitch').on('change', function() {

                if ($(this).is(':checked')) {
                    totalPrice = parseInt(totalPrice) - parseInt(myPoint);
                    var html = `
                    <tr id="data-point">
                        <td>Point Di Tukar</td>
                        <td>-</td>
                        <td>${numberWithCommas(totalPrice)}</td>
                    </tr>
                    `
                    // set price
                    $(html).insertBefore('.row-price');
                    $("#total_price").val(totalPrice);
                    $("#total_point").val(myPoint);
                    $("#total_price_text").text(numberWithCommas(totalPrice));
                } else {
                    $("#data-point").remove();
                    totalPrice = parseInt(totalPrice) + parseInt(myPoint);
                    $("#total_price").val(totalPrice);
                    $("#total_point").val(0);
                    $("#total_price_text").text(numberWithCommas(totalPrice));
                }

            });
        </script>
    @endpush

@endsection
