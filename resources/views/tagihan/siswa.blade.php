@extends('layouts.app')

@section('site-name','Sistem Informasi SPP')
@section('page-name', (isset($tagihan) ? 'Ubah Tagihan' : 'Tagihan Baru'))

@section('content')
<div class="row">
    <div class="col-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Detail Pembayaran Tagihan {{$tagihan->nama}}</h3>
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Bulan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bulans as $bln)
                        <tr>
                            <td>{{$bln['label']}} -
                                @if(isset($bln['lunas']))
                                <span class="tag tag-green">Lunas</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
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

</script>
@endsection
