@extends('layouts.app')

@section('page-name','Daftar Siswa Menunggak')

@section('content')
<div class="page-header">
    <h1 class="page-title">
        @yield('page-name')
    </h1>
</div>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Nama siswa yang belum membayar</h3>
            </div>
            <div class="table-responsive">
                <div class="p-2">
                    <select name="" id="select_tagihan" class="form-control">
                        <option selected disabled>Pilih tagihan</option>
                        <option value="daful">Daftar Ulang</option>
                        @foreach($tagihan as $item)
                        <option value="{{$item->id}}">{{$item->nama}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="spinner-border" role="status" id="loading-data" style="display: none;">
                    <span class="sr-only">Loading...</span>
                </div>
                <div id="no-data" class="p-2">
                    <h2>Pilih tagihan dahulu</h2>
                </div>
                <table class="table card-table table-hover table-vcenter text-wrap" id="data-table" style="display: none;">
                    <thead>
                        <tr>
                            <th class="w-1">No.</th>
                            <th>Nama Siswa</th>
                            <th>Tagihan</th>
                        </tr>
                    </thead>
                    <tbody id="body-data">

                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                <div class="d-flex">
                    <div class="ml-auto mb-0">

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
<script>
    require(['jquery', 'select2', 'sweetalert'], function($, select2, sweetalert) {
        $(document).ready(function() {
            $('#select_tagihan').on('change', function() {
                $.ajax({
                    url: "{{route('api.tunggakan')}}/" + this.value,
                    beforeSend: function() {
                        // show loading
                        $('#data-table')[0].style.display = 'none';
                        $('#no-data')[0].style.display = 'none';
                        $('#loading-data')[0].style.display = "block";
                    },
                    success: function(result) {
                        if (result.length > 0) {
                            $('#body-data').empty();
                            $('#body-data').append(result);
                            $('#data-table')[0].style.display = 'table';
                            $('#no-data')[0].style.display = 'none';
                        } else {
                            alert('tidak ada tunggakan siswa');
                            $('#no-data')[0].style.display = 'block';
                        }
                    },
                    error: function(error) {
                        console.log(error);
                        $('#data-table')[0].style.display = 'none';
                        $('#no-data')[0].style.display = 'block';
                    },
                    complete: function() {
                        $('#loading-data')[0].style.display = "none";
                    }
                })
            });
        })
    })
</script>
@endsection
