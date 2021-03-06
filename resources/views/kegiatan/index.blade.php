@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="p-2">
            <a class="btn btn-outline-primary" href="{{ url('admin/kegiatan/create') }}">Create</a>          
        </div>
    </div>
    <div class="col-12">
            <!-- Flash Message baik sukses maupun error -->
            @if(session('success-msg'))
            <div class="alert alert-success">
              {{session('success-msg')}}
            </div>
            @elseif(session('fail-msg'))
            <div class="alert alert-danger">
              {{session('fail-msg')}}
            </div>
            @endif
            <!-- EndFlash -->
            <div class="card mt-5">
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                            <th>No</th>
                            <th>Author</th>
                            <th>Judul</th>
                            <th>Jenis</th>   
                            <th>foto</th>
                            <th>Created</th>
                            <th>Update</th>
                            <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($kegiatans as $kegiatan)
                            <tr id="{{ $kegiatan->id }}">
                                <td>{{ $i++ }}</td>
                                <td>{{ $kegiatan->user->name }}</td>
                                <td>{{ $kegiatan->judul }}</td>
                                <td>{{ $kegiatan->jenis }}</td>
                                <td>
                                    <img width="100px" height="auto" src="{{ asset('uploads/images/kegiatan') }}/{{ $kegiatan->foto }}" alt="">
                                </td>
                                <td>{{ $kegiatan->created_at->diffForHumans() }}</td>
                                <td>{{ $kegiatan->updated_at->diffForHumans() }}</td>
                                <td><a class="show btn btn-outline-primary" href="{{ url('admin/kegiatan') }}/{{ $kegiatan->id }}/show" id="{{ $kegiatan->id }}">View</a>
                                    <button type="button" class="delete btn btn-outline-danger" id="{{ $kegiatan->id }}">Hapus</button>|
                                    <a class="edit btn btn-outline-success" id="{{ $kegiatan->id }}" href="{{ url('admin/kegiatan') }}/{{ $kegiatan->id }}/edit">Edit</button></td>
                            </tr>
                            @endforeach
                    </tbody>
                </table>        
            </div>
    </div>
</div>
<script>
        $('.delete').click(function(e) {
            e.preventDefault();
            var id = $(this).attr('id');
            swal({
                title: "Hapus Data",
                text: "Anda yakin ingin menghapus data",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {
            if(willDelete) {
                $.ajax({
                    type: 'GET',
                    url: 'kegiatan/'+id+'/delete',
                    dataType: "JSON",
                    success: function(data) {
                        swal("Data Telah dihapus", {
                            icon: "success"
                        }).then(() => {
                            location.reload();
                        })
                    }
                })
            }
        });                     
    })        
</script>
@endsection