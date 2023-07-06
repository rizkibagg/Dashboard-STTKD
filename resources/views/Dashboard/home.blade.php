@extends('Layout.master')

@section('master')

    <div class="pagetitle">
        <h1>Dashboard</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item active">Home</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">
        <div class="row">

            <div class="col-lg-12">
                <div class="row">

                    <div class="card-body">
                        <form method="post" action="/">
                            @csrf
                            <div class="modal-body">
                                <div class="form-group row mt-3">
                                    <div class="col-sm-10">
                                        <select class="form-select" class="form-control" name="year" required>
                                            <option value="">Pilih Tahun</option>
                                            @for ($i = 0; $i < count($data["filter_tahun"]); $i++)
                                                {{-- <option value='{{ $data["filter_tahun"][$i]["filterTahun"] }}'>{{ $data["filter_tahun"][$i]["filterTahun"] }}</option> --}}
                                                @if ($data["filter_tahun"][$i]["filterTahun"] == $year)
                                                    <option value='{{ $data["filter_tahun"][$i]["filterTahun"] }}' selected>{{ $data["filter_tahun"][$i]["filterTahun"] }}</option>
                                                @else
                                                    <option value='{{ $data["filter_tahun"][$i]["filterTahun"] }}'>{{ $data["filter_tahun"][$i]["filterTahun"] }}</option>
                                                @endif
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="col-sm-2">
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>
            </div>

            <!-- Left side columns -->
            <div class="col-lg-12">
                <div class="row">

                    <!-- Akademik Card -->
                    {{-- <div class="col-xxl-4 col-md-3">
                        <div class="card info-card sales-card">

                            <div class="card-body">
                                <h5 class="card-title">Akademik</h5>

                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="fa-solid fa-user-nurse fa-beat"></i>
                                    </div>
                                    <div class="ps-3">
                                        <span class="text-success small pt-1 fw-bold">Total Aktif :</span>
                                        <h6>145</h6>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div><!-- End Akademik Card --> --}}

                    <!-- SDM Card -->
                    {{-- <div class="col-xxl-4 col-md-3">
                        <div class="card info-card sales-card">

                            <div class="card-body">
                                <h5 class="card-title">Sumber Daya Manusia</h5>

                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="fa-solid fa-user-tie fa-beat"></i>
                                    </div>
                                    <div class="ps-3">
                                        <span class="text-success small pt-1 fw-bold">Total Aktif :</span>
                                        <h6>145</h6>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div><!-- End SDM Card --> --}}

                    <!-- PTB Card -->
                    {{-- <div class="col-xxl-4 col-md-3">
                        <div class="card info-card sales-card">

                            <div class="card-body">
                                <h5 class="card-title">Penerimaan Taruna Baru</h5>

                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="fa-solid fa-user fa-beat"></i>
                                    </div>
                                    <div class="ps-3">
                                        <span class="text-success small pt-1 fw-bold">Total :</span>
                                        <h6>145</h6>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div><!-- End PTB Card --> --}}

                    <!-- Alumni Card -->
                    {{-- <div class="col-xxl-4 col-md-3">
                        <div class="card info-card sales-card">

                            <div class="card-body">
                                <h5 class="card-title">Alumni</h5>

                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="fa-solid fa-user-graduate fa-beat"></i>
                                    </div>
                                    <div class="ps-3">
                                        <span class="text-success small pt-1 fw-bold">Total :</span>
                                        <h6>145</h6>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div><!-- End Alumni Card --> --}}

                    <!-- Pendaftar yang Bayar Card -->
                    <div class="col-xxl-4 col-md-3">
                        <div class="card info-card sales-card">

                            <div class="card-body">
                                <h5 class="card-title">Pendaftar yang Bayar</h5>

                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="fa-solid fa-user fa-beat"></i>
                                    </div>
                                    <div class="ps-3">
                                        <span class="text-success small pt-1 fw-bold">Total :</span>
                                        <h6><?php echo $totalBP; ?></h6>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div><!-- End Pendaftar yang Bayar Card -->

                    <!-- Bayar Uang Masuk Card -->
                    <div class="col-xxl-4 col-md-3">
                        <div class="card info-card sales-card">

                            <div class="card-body">
                                <h5 class="card-title">Bayar Uang Masuk</h5>

                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="fa-solid fa-user fa-beat"></i>
                                    </div>
                                    <div class="ps-3">
                                        <span class="text-success small pt-1 fw-bold">Total :</span>
                                        <h6><?php echo $totalBUM; ?></h6>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div><!-- End Bayar Uang Masuk Card -->

                    <!-- Bayar SPP Card -->
                    <div class="col-xxl-4 col-md-3">
                        <div class="card info-card sales-card">

                            <div class="card-body">
                                <h5 class="card-title">Bayar SPP</h5>

                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="fa-solid fa-user fa-beat"></i>
                                    </div>
                                    <div class="ps-3">
                                        <span class="text-success small pt-1 fw-bold">Total :</span>
                                        <h6><?php echo $totalBSPP; ?></h6>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div><!-- End Bayar SPP Card -->

                    <!-- Bayar Asrama Card -->
                    <div class="col-xxl-4 col-md-3">
                        <div class="card info-card sales-card">

                            <div class="card-body">
                                <h5 class="card-title">Bayar Asrama</h5>

                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="fa-solid fa-user fa-beat"></i>
                                    </div>
                                    <div class="ps-3">
                                        <span class="text-success small pt-1 fw-bold">Total :</span>
                                        <h6><?php echo $totalBA; ?></h6>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div><!-- End Bayar Asrama Card -->

                    <!-- Customers Card -->
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">About this Website</h5>

                            <!-- Bordered Tabs Justified -->
                            <ul class="nav nav-tabs nav-tabs-bordered d-flex" id="borderedTabJustified" role="tablist">
                                <li class="nav-item flex-fill" role="presentation">
                                    <button class="nav-link w-100 active" id="akademik-tab" data-bs-toggle="tab" data-bs-target="#bordered-justified-akademik" type="button" role="tab" aria-controls="akademik" aria-selected="true">Akademik</button>
                                </li>
                                <li class="nav-item flex-fill" role="presentation">
                                    <button class="nav-link w-100" id="sdm-tab" data-bs-toggle="tab" data-bs-target="#bordered-justified-sdm" type="button" role="tab" aria-controls="sdm" aria-selected="false">Sumber Daya Manusia</button>
                                </li>
                                <li class="nav-item flex-fill" role="presentation">
                                    <button class="nav-link w-100" id="ptb-tab" data-bs-toggle="tab" data-bs-target="#bordered-justified-ptb" type="button" role="tab" aria-controls="ptb" aria-selected="false">Penerimaan Taruna Baru</button>
                                </li>
                                <li class="nav-item flex-fill" role="presentation">
                                    <button class="nav-link w-100" id="alumni-tab" data-bs-toggle="tab" data-bs-target="#bordered-justified-alumni" type="button" role="tab" aria-controls="alumni" aria-selected="false">Alumni</button>
                                </li>
                            </ul>
                            <div class="tab-content pt-2" id="borderedTabJustifiedContent">
                                <div class="tab-pane fade show active" id="bordered-justified-akademik" role="tabpanel" aria-labelledby="akademik-tab">
                                    Sunt est soluta temporibus accusantium neque nam maiores cumque temporibus. Tempora libero non est unde veniam est qui dolor. Ut sunt iure rerum quae quisquam autem eveniet perspiciatis odit. Fuga sequi sed ea saepe at unde.
                                    <br><a class="btn btn-primary btn-sm" style="margin-top: 10px;" type="submit" href="/akademik" >Go Visit..</a>
                                </div>
                                <div class="tab-pane fade" id="bordered-justified-sdm" role="tabpanel" aria-labelledby="sdm-tab">
                                    Saepe animi et soluta ad odit soluta sunt. Nihil quos omnis animi debitis cumque. Accusantium quibusdam perspiciatis qui qui omnis magnam. Officiis accusamus impedit molestias nostrum veniam. Qui amet ipsum iure. Dignissimos fuga tempore dolor.
                                    <br><a class="btn btn-primary btn-sm" style="margin-top: 10px;" type="submit" href="/sdm" >Go Visit..</a>
                                </div>
                                <div class="tab-pane fade" id="bordered-justified-ptb" role="tabpanel" aria-labelledby="ptb-tab">
                                    Nesciunt totam et. Consequuntur magnam aliquid eos nulla dolor iure eos quia. Accusantium distinctio omnis et atque fugiat. Itaque doloremque aliquid sint quasi quia distinctio similique. Voluptate nihil recusandae mollitia dolores.
                                    <br><a class="btn btn-primary btn-sm" style="margin-top: 10px;" type="submit" href="/ptb" >Go Visit..</a>
                                </div>
                                <div class="tab-pane fade" id="bordered-justified-alumni" role="tabpanel" aria-labelledby="alumni-tab">
                                    Nesciunt totam et. Consequuntur magnam aliquid eos nulla dolor iure eos quia. Accusantium distinctio omnis et atque fugiat. Itaque doloremque aliquid sint quasi quia distinctio similique. Voluptate nihil recusandae mollitia dolores.
                                    <br><a class="btn btn-primary btn-sm" style="margin-top: 10px;" type="submit" href="/alumni" >Go Visit..</a>
                                </div>
                            </div><!-- End Bordered Tabs Justified -->

                        </div>
                    </div>

                </div>
            </div><!-- End Left side columns -->

        </div>
    </section>
@endsection
