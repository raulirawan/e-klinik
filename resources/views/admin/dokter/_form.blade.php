<div class="col-md-12">
    <div class="form-group mb-3">
        <label for="basicInput">Nama</label>
        <input type="text" class="form-control" value="{{ $dokter->name ?? old('name') }}" name="name" id="name"
            placeholder="Masukan Nama" required>
    </div>
    <div class="form-group mb-3">
        <label for="basicInput">Email</label>
        <input type="email" id="email" class="form-control" value="{{ $dokter->email ?? old('email') }}"
            name="email" placeholder="Masukan Email" required>
    </div>
    <div class="form-group mb-3">
        <label for="basicInput">Password</label>
        <input type="password" id="password" class="form-control" name="password" placeholder="Masukan Password"
            {{ isset($dokter) ? '' : 'required' }}>
    </div>
    <div class="form-group mb-3">
        <label for="basicInput">Nomor STR</label>
        <input type="text" id="no_str" class="form-control" name="no_str" placeholder="Masukan Nomor STR"
            value="{{ $dokter->no_str ?? old('no_str') }}" required>
    </div>
    <div class="form-group mb-3">
        <label for="basicInput">Jenis Kelamin</label>
        <select name="gender" class="form-control" id="gender">
            <option value="LAKI - LAKI" {{ $dokter->gender ?? old('gender') == 'LAKI - LAKI' ? 'selected' : '' }}>LAKI -
                LAKI</option>
            <option value="PEREMPUAN" {{ $dokter->gender ?? old('gender') == 'PEREMPUAN' ? 'selected' : '' }}>PEREMPUAN
            </option>
        </select>
    </div>
</div>
{{-- jam kerja --}}
@php
    $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
    $oldDayWork = isset($dokter->dayWork) ? json_decode($dokter->dayWork->day_work, true) : [];
@endphp
@foreach ($days as $key => $day)
    <div class="row">
        <div class="col-md-4">
            <div class="form-group mb-3">
                <label for="basicInput">Hari Kerja</label>
                <input type="text" class="form-control" name="day[]" value="{{ $day }}"readonly>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group mb-3">
                <label for="basicInput">Jam Mulai</label>
                <select name="time_start[]" class="form-control">
                    @if ($day == isset($oldDayWork[$key]['day']) ? $oldDayWork[$key]['day'] : null)
                        <option {{ $oldDayWork[$key]['time_start'] == '00:00' ? 'selected' : '' }}>00:00</option>
                        <option {{ $oldDayWork[$key]['time_start'] == '01:00' ? 'selected' : '' }}>01:00</option>
                        <option {{ $oldDayWork[$key]['time_start'] == '02:00' ? 'selected' : '' }}>02:00</option>
                        <option {{ $oldDayWork[$key]['time_start'] == '03:00' ? 'selected' : '' }}>03:00</option>
                        <option {{ $oldDayWork[$key]['time_start'] == '04:00' ? 'selected' : '' }}>04:00</option>
                        <option {{ $oldDayWork[$key]['time_start'] == '05:00' ? 'selected' : '' }}>05:00</option>
                        <option {{ $oldDayWork[$key]['time_start'] == '06:00' ? 'selected' : '' }}>06:00</option>
                        <option {{ $oldDayWork[$key]['time_start'] == '07:00' ? 'selected' : '' }}>07:00</option>
                        <option {{ $oldDayWork[$key]['time_start'] == '08:00' ? 'selected' : '' }}>08:00</option>
                        <option {{ $oldDayWork[$key]['time_start'] == '09:00' ? 'selected' : '' }}>09:00</option>
                        <option {{ $oldDayWork[$key]['time_start'] == '10:00' ? 'selected' : '' }}>10:00</option>
                        <option {{ $oldDayWork[$key]['time_start'] == '11:00' ? 'selected' : '' }}>11:00</option>
                        <option {{ $oldDayWork[$key]['time_start'] == '12:00' ? 'selected' : '' }}>12:00</option>
                        <option {{ $oldDayWork[$key]['time_start'] == '13:00' ? 'selected' : '' }}>13:00</option>
                        <option {{ $oldDayWork[$key]['time_start'] == '14:00' ? 'selected' : '' }}>14:00</option>
                        <option {{ $oldDayWork[$key]['time_start'] == '15:00' ? 'selected' : '' }}>15:00</option>
                        <option {{ $oldDayWork[$key]['time_start'] == '16:00' ? 'selected' : '' }}>16:00</option>
                        <option {{ $oldDayWork[$key]['time_start'] == '17:00' ? 'selected' : '' }}>17:00</option>
                        <option {{ $oldDayWork[$key]['time_start'] == '18:00' ? 'selected' : '' }}>18:00</option>
                        <option {{ $oldDayWork[$key]['time_start'] == '19:00' ? 'selected' : '' }}>19:00</option>
                        <option {{ $oldDayWork[$key]['time_start'] == '20:00' ? 'selected' : '' }}>20:00</option>
                        <option {{ $oldDayWork[$key]['time_start'] == '21:00' ? 'selected' : '' }}>21:00</option>
                        <option {{ $oldDayWork[$key]['time_start'] == '22:00' ? 'selected' : '' }}>22:00</option>
                        <option {{ $oldDayWork[$key]['time_start'] == '23:00' ? 'selected' : '' }}>23:00</option>
                    @else
                        <option>00:00</option>
                        <option>01:00</option>
                        <option>02:00</option>
                        <option>03:00</option>
                        <option>04:00</option>
                        <option>05:00</option>
                        <option>06:00</option>
                        <option>07:00</option>
                        <option>08:00</option>
                        <option>09:00</option>
                        <option>10:00</option>
                        <option>11:00</option>
                        <option>12:00</option>
                        <option>13:00</option>
                        <option>14:00</option>
                        <option>15:00</option>
                        <option>16:00</option>
                        <option>17:00</option>
                        <option>18:00</option>
                        <option>19:00</option>
                        <option>20:00</option>
                        <option>21:00</option>
                        <option>22:00</option>
                        <option>23:00</option>
                    @endif
                </select>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group mb-3">
                <label for="basicInput">Jam Selesai</label>
                <select name="time_end[]" class="form-control">
                    @if ($day == isset($oldDayWork[$key]['day']) ? $oldDayWork[$key]['day'] : null)
                        <option {{ $oldDayWork[$key]['time_end'] == '00:00' ? 'selected' : '' }}>00:00</option>
                        <option {{ $oldDayWork[$key]['time_end'] == '01:00' ? 'selected' : '' }}>01:00</option>
                        <option {{ $oldDayWork[$key]['time_end'] == '02:00' ? 'selected' : '' }}>02:00</option>
                        <option {{ $oldDayWork[$key]['time_end'] == '03:00' ? 'selected' : '' }}>03:00</option>
                        <option {{ $oldDayWork[$key]['time_end'] == '04:00' ? 'selected' : '' }}>04:00</option>
                        <option {{ $oldDayWork[$key]['time_end'] == '05:00' ? 'selected' : '' }}>05:00</option>
                        <option {{ $oldDayWork[$key]['time_end'] == '06:00' ? 'selected' : '' }}>06:00</option>
                        <option {{ $oldDayWork[$key]['time_end'] == '07:00' ? 'selected' : '' }}>07:00</option>
                        <option {{ $oldDayWork[$key]['time_end'] == '08:00' ? 'selected' : '' }}>08:00</option>
                        <option {{ $oldDayWork[$key]['time_end'] == '09:00' ? 'selected' : '' }}>09:00</option>
                        <option {{ $oldDayWork[$key]['time_end'] == '10:00' ? 'selected' : '' }}>10:00</option>
                        <option {{ $oldDayWork[$key]['time_end'] == '11:00' ? 'selected' : '' }}>11:00</option>
                        <option {{ $oldDayWork[$key]['time_end'] == '12:00' ? 'selected' : '' }}>12:00</option>
                        <option {{ $oldDayWork[$key]['time_end'] == '13:00' ? 'selected' : '' }}>13:00</option>
                        <option {{ $oldDayWork[$key]['time_end'] == '14:00' ? 'selected' : '' }}>14:00</option>
                        <option {{ $oldDayWork[$key]['time_end'] == '15:00' ? 'selected' : '' }}>15:00</option>
                        <option {{ $oldDayWork[$key]['time_end'] == '16:00' ? 'selected' : '' }}>16:00</option>
                        <option {{ $oldDayWork[$key]['time_end'] == '17:00' ? 'selected' : '' }}>17:00</option>
                        <option {{ $oldDayWork[$key]['time_end'] == '18:00' ? 'selected' : '' }}>18:00</option>
                        <option {{ $oldDayWork[$key]['time_end'] == '19:00' ? 'selected' : '' }}>19:00</option>
                        <option {{ $oldDayWork[$key]['time_end'] == '20:00' ? 'selected' : '' }}>20:00</option>
                        <option {{ $oldDayWork[$key]['time_end'] == '21:00' ? 'selected' : '' }}>21:00</option>
                        <option {{ $oldDayWork[$key]['time_end'] == '22:00' ? 'selected' : '' }}>22:00</option>
                        <option {{ $oldDayWork[$key]['time_end'] == '23:00' ? 'selected' : '' }}>23:00</option>
                    @else
                        <option>00:00</option>
                        <option>01:00</option>
                        <option>02:00</option>
                        <option>03:00</option>
                        <option>04:00</option>
                        <option>05:00</option>
                        <option>06:00</option>
                        <option>07:00</option>
                        <option>08:00</option>
                        <option>09:00</option>
                        <option>10:00</option>
                        <option>11:00</option>
                        <option>12:00</option>
                        <option>13:00</option>
                        <option>14:00</option>
                        <option>15:00</option>
                        <option>16:00</option>
                        <option>17:00</option>
                        <option>18:00</option>
                        <option>19:00</option>
                        <option>20:00</option>
                        <option>21:00</option>
                        <option>22:00</option>
                        <option>23:00</option>
                    @endif
                </select>
            </div>
        </div>
    </div>
@endforeach
<div class="text-danger mb-4">Notes: Jika Ingin Libur Jadikan Jam Mulai 00:00 dan Jam Selesai 00:00</div>
<div class="col-md-12">
    <button type="submit" class="btn btn-primary">Simpan</button>
</div>
