@extends('layouts.app')

@section('site-name','Sistem Informasi SPP')
@section('page-name', (isset($tagihan) ? 'Ubah Tagihan' : 'Tagihan Daftar Ulang Baru'))

@section('content')
<div class="row">
    <div class="col-8">
        <form action="{{ (isset($tagihan) ? route('daful.update', $tagihan->id) : route('daful.index')) }}" method="post" class="card">
            <div class="card-header">
                <h3 class="card-title">@yield('page-name')</h3>
            </div>
            <div class="card-body">
                @if($errors->any())
                <div class="alert alert-danger">
                    @foreach($errors->all() as $error)
                    {{ $error }}<br>
                    @endforeach
                </div>
                @endif
                <div class="row">
                    <div class="col-12">
                        @csrf
                        <div class="form-group">
                            <label class="form-label">Nama</label>
                            <input type="text" class="form-control" name="keterangan" placeholder="Nama" value="{{ isset($tagihan) ? $tagihan->nama : old('nama') }}" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Jumlah</label>
                            <input type="number" class="form-control" name="jumlah" value="{{ isset($tagihan) ? $tagihan->jumlah : old('jumlah') }}" required>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer text-right">
                <div class="d-flex">
                    <a href="{{ url()->previous() }}" class="btn btn-link">Batal</a>
                    <button type="submit" class="btn btn-primary ml-auto">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
@section('css')
<link href="{{ asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" />
<style>
    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        color: black;
    }

    .select2 {
        width: 100% !important;
    }
</style>
@endsection
@section('js')
<script>
    require(['jquery', 'selectize', 'select2'], function($, selectize) {
        $(document).ready(function() {
            $('#select-beast').selectize({});
        });
        $('#hanya-kelas').select2({
            placeholder: "Pilih Kelas",
        });
        $('#hanya-siswa').select2({
            placeholder: "Pilih Siswa",
        });

        $('.custom-switch-input').change(function() {
            if (this.value == 2) {
                $('#form-kelas').show()
                $('#form-siswa').hide()

                $('#hanya-kelas').prop('required', true)
                $('#hanya-siswa').prop('required', false)
            } else if (this.value == 3) {
                $('#form-kelas').hide()
                $('#form-siswa').show()

                $('#hanya-kelas').prop('required', false)
                $('#hanya-siswa').prop('required', true)
            } else {
                $('#form-kelas').hide()
                $('#form-siswa').hide()

                $('#hanya-kelas').prop('required', false)
                $('#hanya-siswa').prop('required', false)
            }
        })
    });
</script>
@endsection
