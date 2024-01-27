@extends('layouts.admin')
@section('title', 'Obat Page')
@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/custom/datatables.bundle.css') }}">
@endpush
@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title fw-semibold mb-4">List Data Obat</h5>
                <button type="button" class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#modal-create">
                    (+) Tambah
                </button>
                <div class="table-responsive">
                    <table class="table w-100" id="table-data">
                       <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama</th>
                            <th>Stock</th>
                            <th>Harga / Stip</th>
                            <th>Aksi</th>
                        </tr>
                       </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade text-left" id="modal-create" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable" role="document">
            <form action="{{ route('admin.medicine.store') }}" id="form-edit" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modal-title" id="myModalLabel1">Form Tambah Data Obat</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @include('partials.alert')
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <label for="basicInput">Nama</label>
                                    <input type="text" class="form-control" value="{{ old('name') }}" id="name"
                                        name="name" placeholder="Masukan Nama" required>
                                </div>
                                <div class="form-group mb-3" id="form_stock">
                                    <label for="basicInput">Stock Awal</label>
                                    <input type="number" class="form-control" value="{{ old('stock') }}" id="stock"
                                        name="stock" placeholder="Masukan Stock Awal" required>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="basicInput">Harga</label>
                                    <input type="number" class="form-control" value=""
                                        name="harga" placeholder="Masukan Harga" id="harga" required>
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
        @if (count($errors) > 0)
            <script type="text/javascript">
                $(document).ready(function() {
                    $('#modal-create').modal('show');
                });
            </script>
        @endif

        <script>
            $(document).ready(function() {
                $(document).on('click', '#edit', function() {
                    var id = $(this).data('id');
                    var name = $(this).data('name');
                    var harga = $(this).data('harga');
                    $('#name').val(name);
                    $('#harga').val(harga);

                    $('#form-edit').attr('action', '/admin/medicine/update/' + id);

                    $("#modal-title").text('Form Update Data Obat');

                    $('#form_stock').addClass('d-none');
                    $('#stock').removeAttr('required');

                });

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
                            data: "stock"
                        },
                        {
                            data: "harga"
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
                            targets: 4,
                            width: "150",
                            orderable: false,
                        },
                    ],
                });
            });
        </script>
    @endpush
@endsection
