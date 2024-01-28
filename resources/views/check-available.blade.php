@extends('layouts.front')
@push('stlyes')
<style>
    body {
        font-family: Arial, sans-serif;
    }

    .card {
        border: none;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .card-title {
        font-size: 1.2rem;
        margin-bottom: 0.5rem;
    }

    .card-text:first-child {
        font-weight: bold;
        margin-bottom: 0.5rem;
    }

    .card-text:nth-child(2) {
        font-size: 1.1rem;
    }
</style>
@endpush
@section('title','Appointment Page')
@section('content')
    <div class="st-content">
        <div class="st-height-b125 st-height-lg-b80" id="home"></div>
        <div class="container">
            <h1 class="text-center mt-5">Dokter Rekomendasi</h1>
            <div class="row">
                @forelse ($dayWork as $work)
                    <div class="col-md-4">
                        <div class="card mb-4">
                            <div class="card-body">
                                <h5 class="card-title">{{ $work->dokter->name }}</h5>
                                <p class="card-text">No STR: {{ $work->dokter->no_str }} </p>
                                <button data-id="{{ $work->dokter->id }}" data-nama_dokter="{{ $work->dokter->name }}"
                                    data-no_str="{{ $work->dokter->no_str }}" data-time="{{ $work->day_work }}"
                                    id="konsultasi" data-bs-toggle="modal" data-bs-target="#exampleModal"
                                    class="btn btn-primary">Mulai Konsultasi</button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center">Tidak Ada Jadwal Yang Tersedia</div>
                @endforelse
            </div>

        </div>
    </div>

    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Dokter</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('makeAppointment') }}" method="POST">
                        @csrf
                        <input type="hidden" name="booking_date" value="{{ $bookingDate }}">
                        <input type="hidden" name="day" value="{{ $day }}">
                        <input type="hidden" id="dokter_id" name="dokter_id">
                        <table>
                            <tbody>
                                <tr>
                                    <th style="width: 200px">Nama Dokter</th>
                                    <td id="nama_dokter">-</td>
                                </tr>
                                <tr>
                                    <th style="width: 200px">STR</th>
                                    <td id="no_str">-</td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="row mt-4">
                            <h5>Jam Konsultasi</h5>
                            <div class="row" id="row-konsultasi">

                            </div>

                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Book Sekarang</button>
                </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>

    <script>
        $("#konsultasi").click(function(e) {
            e.preventDefault();
            var day = "{{ $day }}";
            var bookingDate = "{{ $bookingDate }}";
            var id = $(this).data('id');
            var nama_dokter = $(this).data('nama_dokter');
            var no_str = $(this).data('no_str');
            var time = $(this).data('time');

            $("#nama_dokter").text(nama_dokter);
            $("#no_str").text(no_str);
            $("#dokter_id").val(id);

            $.ajax({
                type: "POST",
                url: `${HOST_URL}/check-available`,
                data: {
                    id_dokter: id,
                    day: day,
                    time: time,
                    bookingDate: bookingDate,
                },
                success: function(response) {
                    // Iterate over the array and append each item to the container
                    var html = ``;

                    $.each(response, function(index, item) {
                        html += `
                <div class="col-md-4 mt-4">
                    <input type="radio" class="btn-check" name="time" id="${index}"
                        autocomplete="off" value="${item.value}">
                    <label class="btn btn-outline-success" for="${index}">${item.text}</label>
                </div>
                `
                    });
                    $('#row-konsultasi').append(html);
                },
                error: function(error) {
                    console.error('Error fetching data:', error);
                }
            });
        });
    </script>
@endpush
