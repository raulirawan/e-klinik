@extends('layouts.admin')
@section('title', 'Dokter Create Page')

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title fw-semibold mb-4">Form Tambah Data Dokter</h5>
                @include('partials.alert')
                <form action="{{ route('admin.dokter.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        @include('admin.dokter._form')
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
