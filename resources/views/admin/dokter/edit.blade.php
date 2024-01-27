@extends('layouts.admin')
@section('title', 'Dokter Edit Page')

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title fw-semibold mb-4">Form Tambah Data Dokter</h5>
                <form action="{{ route('admin.dokter.update', $dokter->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                       @include('admin.dokter._form')
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
