@extends('layout.app')
@section('judul', 'Halaman Profile')
@section('profile','active')

@section('content')
<div class="content-body">
    <div class="container-fluid">


        <div class="row page-titles">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active"><a href="javascript:void(0)">{{ config('app.name') }}</a></li>
                <li class="breadcrumb-item"><a href="javascript:void(0)">@yield('judul')</a></li>
            </ol>
        </div>
        <!-- row -->
        <div class="row">
            <div class="col-lg-12">
                <div class="profile card card-body px-3 pt-3 pb-0">
                    <div class="profile-head">
                        <div class="photo-content" style="background: url({{ json_decode($sampul)->data }});">
                            <div class="cover-photo rounded"></div>
                        </div>
                        <div class="profile-info">
                            <div class="profile-photo">
                                <img src="{{ json_decode($profile)->image_link }}" class="img-fluid rounded-circle" alt="">
                            </div>
                            <div class="profile-details">
                                <div class="profile-name px-3 pt-2">
                                    <h4 class="text-primary mb-0">{{ $auth->nama }}</h4>
                                    <p>Hallo {{ $auth->nama }}</p>
                                </div>
                                <div class="dropdown ms-auto">
                                    <a href="#" class="btn btn-primary light sharp" data-bs-toggle="dropdown" aria-expanded="true"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="18px" height="18px" viewbox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"></rect><circle fill="#000000" cx="5" cy="12" r="2"></circle><circle fill="#000000" cx="12" cy="12" r="2"></circle><circle fill="#000000" cx="19" cy="12" r="2"></circle></g></svg></a>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li class="dropdown-item" data-bs-toggle="modal" data-bs-target="#sendMessageModalFoto"><i class="fa fa-user-circle text-primary me-2"></i> Ganti Foto</li>
                                        <li class="dropdown-item" data-bs-toggle="modal" data-bs-target="#sendMessageModalSampul"><i class="fa fa-users text-primary me-2"></i> Ganti Sampul</li>
                                </div>
                            </div>
                            <!-- Modal ganti foto Sampul-->
                            <div class="modal fade" id="sendMessageModalSampul">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Ganti Sampul</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form class="comment-form" method="POST" action="" enctype="multipart/form-data">
                                                @csrf
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <div class="mb-3">
                                                            <label class="text-black font-w600 form-label">Foto <span class="required">*</span></label>
                                                            <input type="file" class="form-control" name="sampul" value="{{ old('sampul') }}" accept=".jpg, .jpeg, .png" />
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12">
                                                        <div class="mb-3 mb-0">
                                                            <input type="submit" value="Kirim" class="submit btn btn-primary" name="submitSampul">
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- end modal --}}
                            <!-- Modal ganti foto profile-->
                            <div class="modal fade" id="sendMessageModalFoto">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Ganti Foto Profile</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form class="comment-form" method="POST" action="" enctype="multipart/form-data">
                                                @csrf
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <div class="mb-3">
                                                            <label class="text-black font-w600 form-label">Foto <span class="required">*</span></label>
                                                            <input type="file" class="form-control" name="foto" value="{{ old('foto') }}" accept=".jpg, .jpeg, .png" />
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12">
                                                        <div class="mb-3 mb-0">
                                                            <input type="submit" value="Kirim" class="submit btn btn-primary" name="submitProfile">
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- end modal --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-12">
                @if ($errors->any())
                    @foreach ($errors->all() as $error)
                    <div class="alert alert-danger" role="alert">
                        <ul>
                            <li>{{ $error }}</li>
                        </ul>
                    </div>
                    @endforeach
                @endif
                <div class="card">
                    <div class="card-body">
                        <div class="profile-tab">
                            <div class="custom-tab-1">
                                <div class="tab-content">
                                    <div id="about-me" class="tab-pane fade active show">
                                        <div class="profile-about-me">
                                            <div class="pt-4 border-bottom-1 pb-3">
                                                <h4 class="text-primary">Deskripsi</h4>
                                                <p class="mb-2">Pakcoy (Brassica rapa L.) adalah jenis tanaman sayur – sayuran yang termasuk keluarga Brassicaceae. Daun pakcoy bertangkai, berbentuk oval, berwarna hijau tua, dan mengkilat, tidak membentuk kepala, tumbuh agak tegak atau setengah mendatar, tersusun dalam spiral rapat, melekat pada batang yang tertekan. Tangkai daun, berwarna putih atau hijau muda, gemuk dan berdaging, tanaman mencapai tinggi 15 – 30 cm.

                                                    Adapun kandungan yang terdapat dalam tanaman pakcoy ini yaitu kalori, protein, lemak, karbohidrat, serat, Ca, P, Fe, serta vitamin A, B, C dan E. Nutrisi magnesium yang terdapat pada pakcoy bisa mereduksi stress dan membantu dalam hal pola tidur yang baik, selain itu pakcoy memiliki manfaat yang lain seperti menghilangkan rasa gatal ditenggorokan pada penderita batuk, dapat menyembuhkan sakit kepala, memperbaiki fungsi ginjal, bahan pembersih darah dan dapat memperlancar pencernaan dikarenakan adanya kandungan serat yang tinggi.

                                                    Wilayah Indonesia mempunyai kecocokan terhadap iklim, cuaca dan tanahnya sehingga tanaman pakcoy dapat dikembangkan di Indonesia. sehingga dapat diusahakan dari dataran rendah maupun dataran tinggi. Meskipun demikian pada kenyataannya hasil yang diperoleh lebih baik di dataran tinggi, dengan suhu berkisar antara 20°C - 30°C. Media tanam yang cocok untuk ditanami pakcoy adalah tanah gembur, banyak mengandung humus, subur, pembuangan airnya baik serta kelembapan tanah yang optimal berkisar antara 50% - 70%. Pakcoy sudah bisa dipanen pada umur 30 – 35 Hari Setelah Tanam (HST).</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
