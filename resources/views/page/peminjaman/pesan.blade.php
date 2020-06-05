@extends('layouts.page')

@section('title')
    Peminjaman Pesan
@endsection

@section('content')

<div class="content-header ml-2 mr-2">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Daftar Pemesanan</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                    <li class="breadcrumb-item">Peminjaman</li>
                    <li class="breadcrumb-item active">Daftar Pemesanan</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="content ml-3 mr-3">
    <div class="container-fluid p-3 shadow-sm mb-5 bg-white rounded">
        <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#modal_tambah_data_pesan">
            <i class="fa fa-plus mr-1"></i>Tambah Data Pesan Buku
        </button>
        <div class="table-responsive">
            <table id="dt_pesan" class="table table-hover">
                <thead class="thead-light">
                    <tr>
                        <th>Judul Buku</th>
                        <th>Nama Peminjam</th>
                        <th>Tanggal Pesan</th>
                        <th>Waktu Kedaluwarsa</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $key)
                        <tr>
                            <td>{{$key->judul}}</td>
                            <td>{{$key->nama}}</td>
                            <td>{{$key->tgl_pesan}}</td>
                            <td>{{date("Y-m-d", strtotime('+' . $batas_waktu . " days", strtotime($key->tgl_pesan)))}}

                            </td>
                            <td>
                                <button type="button" class="btn btn-sm btn-success" onclick="pesanBuku({{$key->id_pinjam}})"><i class="fa fa-book-open mr-1"></i>Pinjam Buku</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_tambah_data_pesan">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="tambah_data_pesan">
                <div class="modal-header">
                    <h4 class="modal-title">Tambah Data Pesan Buku</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="type_buku" class="font-weight-normal">Type Buku :</label>
                        <select id="type_buku" class="form-control select2" onchange="selectTypeBuku(this)" required>
                            <option></option>
                            @foreach ($list_type_buku as $key)
                                <option value="{{$key->id}}">{{$key->nama_type}}</option>
                        @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="judul_buku" class="font-weight-normal">Judul Buku :</label>
                        <select id="judul_buku" name="id_buku" class="form-control select2" disabled required>
                            <option></option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="type_user" class="font-weight-normal">Type User :</label>
                        <select id="type_user" class="form-control select2" onchange="selectTypeUser(this)" required>
                            <option></option>
                            <option value="mahasiswa">Mahasiswa</option>
                            <option value="dosen">Dosen</option>
                            <option value="karyawan">Karyawan</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="nama_user" class="font-weight-normal">Nama User :</label>
                        <select id="nama_user" name="id_user" class="form-control select2" disabled required>
                            <option></option>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="tgl_pesan" class="font-weight-normal">Tanggal Pesan :</label>
                                <input id="tgl_pesan" type="date" class="form-control" id="tgl_pesan" name="tgl_pesan" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="tambah_pesan_btn_loading" class="btn btn-primary disabled" style="display: none"><span class="spinner-border spinner-border-sm mr-1"></span>Loading</button>
                    <button id="tambah_pesan_btn_add" type="submit" class="btn btn-primary"><i class="fa fa-plus mr-1"></i>Tambah</button>
                    <button id="tambah_pesan_btn_close" type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times mr-1"></i>Tutup</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
    <script>
    // Add the following code if you want the name of the file appear on select
    $(".custom-file-input").on("change", function() {
        var fileName = $(this).val().split("\\").pop();
        $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
    });
    </script>
    <script>
        $(document).ready(function() {
            $('#dt_pesan').DataTable({
                "order": []
            });

            //Initialize Select2 Elements
            $('#type_buku').select2({
                theme: 'bootstrap4',
                placeholder: "Pilih Type Buku"
            });
            $('#judul_buku').select2({
                theme: 'bootstrap4',
                placeholder: "Pilih Judul Buku"
            });
            $('#type_user').select2({
                theme: 'bootstrap4',
                placeholder: "Pilih Type User"
            });
            $('#nama_user').select2({
                theme: 'bootstrap4',
                placeholder: "Pilih Nama User"
            });

            $("#tambah_data_pesan").submit(function(event){
                event.preventDefault();

                $('#tambah_pesan_btn_loading').show();
                $('#tambah_pesan_btn_add').hide();
                $('#tambah_pesan_btn_close').hide();

                var formData = new FormData(this);
                $.ajax({
                    type:'POST',
                    dataType: 'json',
                    url: '/peminjaman/pesan/add',
                    data:formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success:function(data){
                        $('#tambah_pesan_btn_loading').hide();
                        $('#tambah_pesan_btn_add').show();
                        $('#tambah_pesan_btn_close').show();
                        if(data.status === 'success'){
                            Swal.fire(
                                'Sukses!',
                                data.reason,
                                'success'
                            ).then(() => {
                                location.reload(true);
                            });
                        } else {
                            Swal.fire(
                                'Oops...',
                                data.reason,
                                'error'
                            )
                        }
                    }
                });
            });

        });

        function selectTypeBuku(item) {
            var formdata = new FormData();
            formdata.append('id_buku', item.options[item.selectedIndex].value);
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: '/buku/list_buku',
                data: formdata,
                contentType: false,
                cache: false,
                processData: false,
                success:function(data){
                    $('#judul_buku').empty();
                    data.forEach(item => {
                        $("#judul_buku").append($("<option />").val(item.id).text(item.judul));
                    });
                    $('#judul_buku').prop('disabled', false);
                }
            });
        }

        function selectTypeUser(item) {
            var formdata = new FormData();
            formdata.append('user_type', item.options[item.selectedIndex].value);
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: '/user/list_user',
                data: formdata,
                contentType: false,
                cache: false,
                processData: false,
                success:function(data){
                    $('#nama_user').empty();
                    data.forEach(item => {
                        $("#nama_user").append($("<option />").val(item.id).text(item.nama));
                    });
                    $('#nama_user').prop('disabled', false);
                }
            });
        }

        function pesanBuku(id){
            Swal.fire({
                title: 'Apakah anda yakin?',
                text: "Buku akan dipinjam!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                confirmButtonText: '<i class="fa fa-book-open"> Ya, pinjam buku',
                cancelButtonText: '<i class="fa fa-times"> Batal'
            }).then((result) => {
                if(result.value){
                    var formdata = new FormData();
                    formdata.append('id', id);
                    $.ajax({
                        type: 'POST',
                        dataType: 'json',
                        url: '/peminjaman/pesan/pinjam',
                        data: formdata,
                        contentType: false,
                        cache: false,
                        processData: false,
                        success:function(data){
                            if(data.status === 'success'){
                                Swal.fire(
                                    'Sukses!',
                                    data.reason,
                                    'success'
                                ).then(() => {
                                    location.reload(true);
                                });
                            } else {
                                Swal.fire(
                                    'Oops...',
                                    data.reason,
                                    'error'
                                )
                            }
                        }
                    });
                }
            });
        }

    </script>
@endpush
