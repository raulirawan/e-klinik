@extends('layouts.admin')
@section('title', 'Stock Page')
@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/custom/datatables.bundle.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" />
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    <!-- Or for RTL support -->
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.rtl.min.css" />
    <style>
        .select2-close-mask{
    z-index: 2099;
}
.select2-dropdown{
    z-index: 3051;
}
body .select2-container {
    z-index: 9999 !important;
}
    </style>
    <!-- Or for RTL support -->
@endpush
@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title fw-semibold mb-4">List Data Stock</h5>
                <button type="button" class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#modal-create">
                    (+) Tambah
                </button>
                <div class="table-responsive">
                    <table class="table w-100" id="table-data">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Nama Obat</th>
                                <th>Stock</th>
                                <th>Status</th>
                                <th class="none">Keterangan</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade text-left" id="modal-create" role="dialog" aria-labelledby="myModalLabel1" style="overflow:hidden;">
        <div class="modal-dialog modal-dialog-scrollable" role="document">
            <form action="{{ route('admin.stock.store') }}" id="form-edit" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modal-title" id="myModalLabel1">Form Tambah Data Stock</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @include('partials.alert')
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <label for="basicInput">Nama Obat</label>
                                    <select name="medicine_id" class="form-select form-control" id="medicine_id">

                                    </select>
                                </div>
                                <div class="form-group mb-3" id="form_stock">
                                    <label for="basicInput">Stock</label>
                                    <input type="number" class="form-control" value="{{ old('stock') }}" id="stock"
                                        name="stock" placeholder="Masukan Jumlah Stock" required>
                                </div>
                                <div class="form-group mb-3" id="form_stock">
                                    <label for="basicInput">Status</label>
                                    <select name="status" class="form-select form-control" id="status" required>
                                        <option value="">Pilih Status</option>
                                        <option value="MASUK">Barang Masuk</option>
                                        <option value="KELUAR">Barang Keluar</option>
                                    </select>
                                </div>
                                <div class="form-group mb-3" id="form_stock">
                                    <label for="basicInput">Keterangan (opsional)</label>
                                    <textarea name="keterangan" id="keterangan" class="form-control" cols="30" rows="3" placeholder="Keterangan"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn" data-bs-dismiss="modal">
                            <i class="bx bx-x d-block d-sm-none"></i>
                            <span class="d-none d-sm-block">Close</span>
                        </button>
                        <button type="submit" class="btn btn-primary ml-1">
                            <i class="bx bx-check d-block d-sm-none"></i>
                            <span class="d-none d-sm-block">Simpan</span>
                        </button>
                    </div>
                </div>
            </form>
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
                var HOST_URL = "{{ url('/') }}";
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
                            data: "created_at"
                        },
                        {
                            data: "medicine.name"
                        },
                        {
                            data: "stock"
                        },
                        {
                            data: "status"
                        },
                        {
                            data: "keterangan"
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
                            targets: 3,
                            width: "100",
                        },
                    ],
                });

                // select 2
                $("#medicine_id").select2({
                    placeholder: "Cari Obat",
                    theme: "bootstrap-5",
                    containerCssClass: "select2--small",
                    dropdownCssClass: "select2--small",
                    dropdownParent: $("#modal-create .modal-body"),
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
            });
        </script>
    @endpush
@endsection
