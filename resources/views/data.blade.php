@extends('layout.app')
@section('judul', 'Halaman Data')
@section('data','active')

@section('content')

        <!--**********************************
            Content body start
        ***********************************-->
        <div class="content-body">
            <div class="container-fluid">

				<div class="row page-titles">
					<ol class="breadcrumb">
						<li class="breadcrumb-item active"><a href="javascript:void(0)">{{ config('app.name') }}</a></li>
						<li class="breadcrumb-item"><a href="javascript:void(0)">Data</a></li>
					</ol>
                </div>
                <!-- row -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Basic Datatable</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="example" class="display" style="min-width: 845px">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Tanggal/Waktu</th>
                                                <th>Suhu Udara</th>
                                                <th>Kelembapan Tanah</th>
                                                <th>Status</th>
                                                <th>Value Fuzzy</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $no = 1;
                                            @endphp
                                            @foreach ($data as $data)
                                            <tr>
                                                <td>{{ $no++ }}</td>
                                                <td>{{ $data->waktu }} WIB</td>
                                                <td>{{ $data->temperatur }}</td>
                                                <td>{{ $data->kelembapan }}</td>
                                                <td>{{ $data->status }}</td>
                                                <td>{{ $data->value }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
				</div>
            </div>
        </div>
        <!--**********************************
            Content body end
        ***********************************-->

@endsection
