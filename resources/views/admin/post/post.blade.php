@extends('layouts.app')


@section('content')
    @if ($errors->any())
        <div class="alert alert-danger border-0 bg-danger alert-dismissible fade show">
            <div class="text-white">{{ $errors->first() }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (session('success'))
        <div class="alert alert-success border-0 bg-success alert-dismissible fade show">
            <div class="text-white">{{ session('success') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">

        <div class="">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Lowongan</li>
                </ol>
            </nav>
        </div>

    </div>
    {{-- {{ dd($data) }} --}}
    <button class="btn btn-outline-primary btn-sm mb-3" data-bs-toggle="modal" data-bs-target="#my-modal">Tambah
        Lowongan</button>

    <div class="table-responsive">
        <table id="example" class="table table-striped table-bordered" style="width:100%">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Link Apply</th>
                    <th>Nama Perusahaan</th>
                    <th>Deskripsi</th>
                    <th>Poster</th>
                    <th>Posisi</th>
                    <th>Pengunggah</th>
                    <th>Status</th>
                    <th>Kadaluwarsa</th>
                    <th>Diunggah</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td><a href="{{ $item['link_apply'] }}" class="text-decoration-none  font-italic">link_apply</a>
                        </td>
                        <td>{{ $item['company'] }}</td>
                        <td>{{ $item['description'] }}</td>
                        <td><img style="height: 100px; width: 100px" src="{{ $item['image'] }}" alt="foto poster"></td>
                        <td>{{ $item['position'] }}</td>
                        <td class="text-center">
                            <a href="#" class="user-info" data-bs-toggle="modal" data-bs-target="#detail-user"
                                data-id="{{ $item['id'] }}" class="mx-auto" data-user="{{ json_encode($item['user']) }}"
                                data-admin="{{ json_encode($item['admin']) }}" onclick="detailUploader(id)"><i
                                    class="fa-solid fa-circle-info"></i></a>
                        </td>
                        <td>
                            <form action="" method="post" action="" id="">
                                <div class="form-check-primary form-check form-switch">
                                    <input name="can_comment" class="form-check-input" type="checkbox"
                                        onclick="updateStatusPost(id)" @if ($item['verified']) checked @endif
                                        id="{{ $item['id'] }}">
                                </div>
                            </form>
                        </td>
                        <td>{{ date('Y-F-d H:i', strtotime($item['expired'])) }}</td>
                        <td>{{ date('Y-F-d H:i', strtotime($item['post_at'])) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th>No</th>
                    <th>Link Apply</th>
                    <th>Nama Perusahaan</th>
                    <th>Deskripsi</th>
                    <th>Poster</th>
                    <th>Posisi</th>
                    <th>Pengunggah</th>
                    <th>Status</th>
                    <th>Kadaluwarsa</th>
                    <th>Diunggah</th>
                </tr>
            </tfoot>
        </table>
    </div>

    <script>
        function updateStatusPost(id) {
            var checked = document.getElementById(id);
            var dataToSend = {
                id: id,
                verified: checked.checked // true atau false sesuai dengan kebutuhan Anda
            };
            // URL tujuan
            var url = "{{ url('/api/admin/lowongan/verified') }}";
            // Kirim permintaan AJAX
            $.ajax({
                type: "PUT",
                url: url,
                contentType: "application/json",
                data: JSON.stringify(dataToSend),
                success: function(response) {
                    // Tangani respons dari server jika diperlukan
                    console.log("Permintaan berhasil dikirim");
                    console.log(response);
                },
                error: function(xhr, status, error) {
                    // Tangani kesalahan jika terjadi
                    console.error("Terjadi kesalahan: " + error);
                }
            });
        }

        $(document).ready(function() {
            var emailInput = $('#email-user');
            var gender = $('#gender-user');
            var address = $('#address-user');
            var fullname = $('#fullname-user');
            var nim = $('#nim-user');
            var img = $('#img-uploader');
            var lvl = $('#level-uploader');

            // when click the info user run this function
            $('.user-info').click(function() {
                var newHeight = 100; // Replace with your desired height

                let user = $(this).data('user');
                let admin = $(this).data('admin');

                img.css({
                    width: newHeight + 'px',
                    height: newHeight + 'px'
                });

                if (user != null) {
                    var urlUser = "{{ url('/user/images/') }}" + user.foto;
                    img.attr('src', urlUser);

                    lvl.text("-USER");
                    emailInput.val(user.email);
                    gender.val(user.gender);
                    address.val(user.alamat);
                    nim.val(user.nim);
                    fullname.val(user.fullname);
                } else {
                    img.attr('src', "{{ asset('/') }}" + "assets/images/admin.png");
                    lvl.text("-ADMIN");
                    emailInput.val(admin.email);
                    fullname.val(admin.name);
                    nim.val(admin.npwp);
                    address.val(admin.alamat);
                }
            });

            // run when the modal is closed
            $('#detail-user').on('hidden.bs.modal', function() {
                // This event is triggered when the modal is hidden or closed
                // Clear the input values before the modal is closed
                emailInput.val('');
                gender.val('');
                address.val('');
                nim.val('');
                fullname.val('');
            });
        });
    </script>
    <x-modal id="my-modal" footer="footer" title="title" body="body">
        <x-slot name="title">Tambah Lowongan</x-slot>
        <x-slot name="id">my-modal</x-slot>
        <x-slot name="body">
            <form method="POST" action="{{ route('post-store') }}" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">Company</label>
                    <input type="text" class="form-control" name="company" id="exampleInputEmail1"
                        aria-describedby="emailHelp">
                </div>
                <div class="mb-3">
                    <label for="exampleInputPassword1" class="form-label">Position</label>
                    <input type="text" class="form-control" name="position" id="exampleInputPassword1">
                </div>
                <div class="mb-3">
                    <label for="link" class="form-label">Link Apply</label>
                    <input type="text" class="form-control" name="link_apply" id="link">
                </div>
                <div class="mb-3">
                    <label for="link" class="form-label">Expired</label>
                    <input type="datetime-local" name="expired" class="form-control" id="link">
                </div>

                <div class="mb-3">
                    <label for="formFile" class="form-label">Poster</label>
                    <input name="image" class="form-control  form-control-sm" type="file" id="formFile" required>
                </div>
                <div class="form-check-danger form-check form-switch">
                    <input name="can_comment" class="form-check-input" type="checkbox"
                        id="flexSwitchCheckCheckedDanger">
                    <label class="form-check-label" for="flexSwitchCheckCheckedDanger">Comment</label>
                </div>
                <div class="form-floating mb-3">
                    <textarea required name="description" class="form-control" placeholder="Leave a description" id="floatingTextarea"></textarea>
                    <label for="floatingTextarea">Description</label>
                </div>
                <label class="form-label"></label>
                <select name="type_jobs" class="form-select mb-3" aria-label="Multiple select example">
                    <option selected>Pilih</option>
                    <option value="Purnawaktu">Purnawaktu</option>
                    <option value="Paruh Waktu">Paruh Waktu</option>
                    <option value="Wiraswasta">Wiraswasta</option>
                    <option value="Pekerja Lepas">Pekerja Lepas</option>
                    <option value="Kontrak">Kontrak</option>
                    <option value="Musiman">Musiman</option>
                </select>
                <div class="row justify-content-end">
                    <button class="col-3 btn btn-outline-danger btn-sm" type="reset"
                        data-bs-dismiss="modal">close</button>
                    <button class="col-3 btn btn-outline-primary btn-sm mx-4">Save</button>
                </div>
            </form>
        </x-slot>
    </x-modal>


    <x-modal id="detail-user" footer="footer" title="title" body="body">
        <x-slot name="title">Detail Pengunggah <span id="level-uploader"></span></x-slot>
        <x-slot name="id">detail-user</x-slot>
        <x-slot name="body">

            <img id="img-uploader" class="rounded-circle mb-3  shadow-4-strong" alt="image-uploader" />

            <div class="row mb-3">
                <label for="input35" class="col-sm-3 col-form-label">Email</label>
                <div class="col-sm-9">
                    <input readonly type="text" class="form-control" id="email-user" placeholder="Enter Your Name">
                </div>
            </div>
            <div class="row mb-3">
                <label for="input36" class="col-sm-3 col-form-label">Fullname</label>
                <div class="col-sm-9">
                    <input readonly type="text" class="form-control" id="fullname-user" placeholder="Phone No">
                </div>
            </div>
            <div class="row mb-3">
                <label for="input37" class="col-sm-3 col-form-label">NIM / NPWP</label>
                <div class="col-sm-9">
                    <input readonly type="email" class="form-control" id="nim-user" placeholder="Email Address">
                </div>
            </div>
            <div class="row mb-3">
                <label for="input37" class="col-sm-3 col-form-label">Alamat</label>
                <div class="col-sm-9">
                    <input readonly type="email" class="form-control" id="address-user" placeholder="Email Address">
                </div>
            </div>
            <div class="row mb-3">
                <label for="input37" class="col-sm-3 col-form-label">Jenis Kelamin</label>
                <div class="col-sm-9">
                    <input readonly type="email" class="form-control" id="gender-user" placeholder="Email Address">
                </div>
            </div>
        </x-slot>
    </x-modal>
@endsection
