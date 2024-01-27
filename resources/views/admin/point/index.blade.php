@extends('layouts.admin')
@section('title', 'Point Page')
@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/custom/datatables.bundle.css') }}">
@endpush
@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title fw-semibold mb-4">List Data Point</h5>
                <button type="button" class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#modal-create">
                    (+) Tambah
                </button>
                <div class="table-responsive">
                    <table class="table w-100" id="table-data">
                       <thead>
                        <tr>
                            <th>ID</th>
                            <th>Minimal Transaksi</th>
                            <th>Point Yang Di Dapatkan</th>
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
            <form action="{{ route('admin.point.store') }}" id="form-edit" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modal-title" id="myModalLabel1">Form Tambah Data Point</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <label for="basicInput">Minimal Transaksi</label>
                                    <input type="number" class="form-control" value="{{ old('min_transaction') }}"
                                        name="min_transaction" id="min_transaction" placeholder="Masukan Minimal Transaksi" required>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="basicInput">Point Yang Di Dapat</label>
                                    <input type="number" id="point" class="form-control" value="{{ old('point') }}" name="point"
                                        placeholder="Masukan Point Yang Di Dapat" required>
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
                    var min_transaction = $(this).data('min_transaction');
                    var point = $(this).data('point');
                    $('#min_transaction').val(min_transaction);
                    $('#point').val(point);

                    $('#form-edit').attr('action', '/admin/point/update/' + id);
                    $('#modal-title').text('Form Edit Data Point');
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
                        [1, "asc"]
                    ],
                    columns: [
                        {
                            data: "id"
                        },
                        {
                            data: "min_transaction"
                        },
                        {
                            data: "point"
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
                            targets: 3,
                            width: "150",
                            orderable: false,
                        },
                    ],
                });
            });
        </script>
    @endpush
@endsection
