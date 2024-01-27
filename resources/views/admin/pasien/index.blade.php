@extends('layouts.admin')
@section('title', 'Pasien Page')
@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/custom/datatables.bundle.css') }}">
@endpush
@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title fw-semibold mb-4">List Data Pasien</h5>
                <button type="button" class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#modal-create">
                    (+) Tambah
                </button>
                <div class="table-responsive">
                    <table class="table w-100" id="table-data">
                       <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Umur</th>
                            <th>Jenis Kelamin</th>
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
            <form action="{{ route('admin.pasien.store') }}" id="form-edit" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modal-title" id="myModalLabel1">Form Tambah Data Pasien</h5>
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
                                <div class="form-group mb-3">
                                    <label for="basicInput">Email</label>
                                    <input type="email" class="form-control" value="{{ old('email') }}" id="email"
                                        name="email" placeholder="Masukan Email" required>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="basicInput">Password</label>
                                    <input type="password" class="form-control" value=""
                                        name="password" placeholder="Masukan Password" id="password" required>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="basicInput">Tanggal Lahir</label>
                                    <input type="date" class="form-control" value="{{ old('date_of_birth') }}" name="date_of_birth" id="date_of_birth"
                                        placeholder="Masukan Tanggal Lahir" required>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="basicInput">Jenis Kelamin</label>
                                    <select name="gender" class="form-control" id="gender">
                                        <option value="LAKI - LAKI" {{ old('gender') == 'LAKI - LAKI' ? 'selected' : '' }}>LAKI - LAKI</option>
                                        <option value="PEREMPUAN" {{ old('gender') == 'PEREMPUAN' ? 'selected' : '' }}>PEREMPUAN</option>
                                    </select>
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

    {{-- <div class="modal fade text-left" id="modal-edit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable" role="document">
            <form action="#" id="form-edit" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="myModalLabel1">Form Edit Data Pasien</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <label for="basicInput">Minimal Transaksi</label>
                                    <input type="integer" class="form-control" value="{{ old('min_transaction') }}"
                                        id="min_transaction" name="min_transaction" placeholder="Masukan Minimal Transaksi"
                                        required>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="basicInput">Pasien Yang Di Dapat</label>
                                    <input type="number" class="form-control" value="{{ old('pasien') }}" id="pasien"
                                        name="pasien" placeholder="Masukan Pasien Yang Di Dapat" required>
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
    </div> --}}
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
                    var email = $(this).data('email');
                    var date_of_birth = $(this).data('date_of_birth');
                    var gender = $(this).data('gender');
                    $('#name').val(name);
                    $('#email').val(email);
                    $('#date_of_birth').val(date_of_birth);
                    $('#gender').val(gender);

                    $('#form-edit').attr('action', '/admin/pasien/update/' + id);

                    $("#modal-title").text('Form Update Data Pasien');

                    $('#password').removeAttr('required');

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
                            data: "email"
                        },
                        {
                            data: "date_of_birth"
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
