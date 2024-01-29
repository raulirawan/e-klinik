@extends('layouts.admin')
@section('title', 'Dashboard Page')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3">
                <!-- Monthly Earnings -->
                <div class="card">
                    <div class="card-body">
                        <div class="row alig n-items-start">
                            <div class="col-10">
                                <h5 class="card-title mb-9 fw-semibold">Total Transaksi</h5>
                                <h4 class="fw-semibold mb-3">{{ $totalTransaksi }}</h4>
                            </div>
                            <div class="col-2">
                                <div class="d-flex justify-content-end">
                                    <div
                                        class="text-white bg-secondary rounded-circle p-6 d-flex align-items-center justify-content-center">
                                        <i class="ti ti-file-invoice fs-6"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="earning"></div>
                </div>
            </div>
            <div class="col-lg-3">
                <!-- Monthly Earnings -->
                <div class="card">
                    <div class="card-body">
                        <div class="row alig n-items-start">
                            <div class="col-10">
                                <h5 class="card-title mb-9 fw-semibold"> Transaksi Paid </h5>
                                <h4 class="fw-semibold mb-3">{{ $transaksiSuccess }}</h4>
                            </div>
                            <div class="col-2">
                                <div class="d-flex justify-content-end">
                                    <div
                                        class="text-white bg-success rounded-circle p-6 d-flex align-items-center justify-content-center">
                                        <i class="ti ti-check fs-6"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="earning"></div>
                </div>
            </div>

            <div class="col-lg-3">
                <!-- Monthly Earnings -->
                <div class="card">
                    <div class="card-body">
                        <div class="row alig n-items-start">
                            <div class="col-10">
                                <h5 class="card-title mb-9 fw-semibold"> Transaksi Pending </h5>
                                <h4 class="fw-semibold mb-3">{{ $transaksiPending }}</h4>
                            </div>
                            <div class="col-2">
                                <div class="d-flex justify-content-end">
                                    <div
                                        class="text-white bg-warning rounded-circle p-6 d-flex align-items-center justify-content-center">
                                        <i class="ti ti-loader fs-6"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="earning"></div>
                </div>
            </div>

            <div class="col-lg-3">
                <!-- Monthly Earnings -->
                <div class="card">
                    <div class="card-body">
                        <div class="row alig n-items-start">
                            <div class="col-10">
                                <h5 class="card-title mb-9 fw-semibold"> Transaksi Cancel </h5>
                                <h4 class="fw-semibold mb-3">{{ $transaksiCancel }}</h4>

                            </div>
                            <div class="col-2">
                                <div class="d-flex justify-content-end">
                                    <div
                                        class="text-white bg-danger rounded-circle p-6 d-flex align-items-center justify-content-center">
                                        <i class="ti ti-square-x fs-6"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="earning"></div>
                </div>
            </div>

        </div>
    </div>
@endsection
