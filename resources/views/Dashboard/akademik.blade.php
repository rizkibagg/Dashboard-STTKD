@extends('Layout.master')

@section('master')

    <div class="pagetitle">
        <h1>{{ $title }}</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active">{{ $title }}</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">
        <div class="row">

            <!-- Left side columns -->
            <div class="col-lg-12">
                <div class="row">

                    <div class="card-body">
                        {{-- <form method="post" action="/akademik">
                            @csrf
                            <div class="modal-body">
                                <div class="form-group row mt-3">
                                    <div class="col-sm-10">
                                        <select class="form-select" class="form-control" name="year" required>
                                            <option value="">Pilih Tahun</option>
                                            @for ($i = 0; $i < count($data["filter_tahun"]); $i++)
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
                        </form> --}}
                        <form method="post" action="/akademik">
                            @csrf
                            <div class="modal-body">
                                <div class="form-group row mt-3">
                                    <div class="col-sm-10">
                                        <select class="form-select" class="form-control" name="semester" required>
                                            <option value="">Pilih Tahun dan Semester</option>
                                            {{-- @for ($i = 0; $i < count($data["filter_tahun"]); $i++)
                                                <option value='{{ $data["filter_tahun"][$i]["filterTahun"] }}'>{{ $data["filter_tahun"][$i]["filterTahun"] }}</option>
                                            @endfor --}}
                                            @for ($i = 0; $i < count($data["filter_semester"]); $i++)
                                            @if ($data["filter_semester"][$i]["filterSemester"] == $semester)
                                                <option value='{{ $data["filter_semester"][$i]["filterSemester"] }}' selected>{{ $data["filter_semester"][$i]["filterSemester"] }}</option>
                                            @else
                                                <option value='{{ $data["filter_semester"][$i]["filterSemester"] }}'>{{ $data["filter_semester"][$i]["filterSemester"] }}</option>
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

                    <!-- Card Bar Chart -->
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Taruna Bayar Lunas</h5>

                                <!-- Bar Chart -->
                                <canvas id="mhs_byr_lunas" style="max-height: 400px;"></canvas>
                                <script>
                                document.addEventListener("DOMContentLoaded", () => {
                                    new Chart(document.querySelector('#mhs_byr_lunas'), {
                                    type: 'bar',
                                    data: {
                                        datasets: [{
                                        label: 'Taruna',
                                        data: <?php echo $data["Mhs_byr_Lunas"]; ?>,
                                        backgroundColor: [
                                            'rgba(255, 99, 132, 0.2)',
                                            'rgba(255, 159, 64, 0.2)',
                                            'rgba(255, 205, 86, 0.2)',
                                            'rgba(75, 192, 192, 0.2)',
                                            'rgba(54, 162, 235, 0.2)',
                                            'rgba(153, 102, 255, 0.2)',
                                            'rgba(201, 203, 207, 0.2)'
                                        ],
                                        borderColor: [
                                            'rgb(255, 99, 132)',
                                            'rgb(255, 159, 64)',
                                            'rgb(255, 205, 86)',
                                            'rgb(75, 192, 192)',
                                            'rgb(54, 162, 235)',
                                            'rgb(153, 102, 255)',
                                            'rgb(201, 203, 207)'
                                        ],
                                        borderWidth: 1
                                        }]
                                    },
                                    options: {
                                        parsing: {
                                            xAxisKey: 'prodi',
                                            yAxisKey: 'total'
                                        }
                                    }
                                    });
                                });
                                </script>
                                <!-- End Bar CHart -->

                            </div>
                        </div>
                    </div>

                    {{-- <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                            <h5 class="card-title">Prodi by Chart</h5>

                            <!-- Column Chart -->
                            <div id="columnChart"></div>

                            <script>
                                document.addEventListener("DOMContentLoaded", () => {
                                new ApexCharts(document.querySelector("#columnChart"), {
                                    series: [{
                                    name: 'Teknik Elektro',
                                    data: [34]
                                    }, {
                                    name: 'Rekayasa Mesin',
                                    data: [40]
                                    }, {
                                    name: 'Teknik Dirgantara',
                                    data: [35]
                                    }, {
                                    name: 'Manajemen Transportasi',
                                    data: [25]
                                    }, {
                                    name: 'Aeronautika',
                                    data: [37]
                                    }],
                                    chart: {
                                    type: 'bar',
                                    height: 350
                                    },
                                    plotOptions: {
                                    bar: {
                                        horizontal: false,
                                        columnWidth: '55%',
                                        endingShape: 'rounded'
                                    },
                                    },
                                    dataLabels: {
                                    enabled: false
                                    },
                                    stroke: {
                                    show: true,
                                    width: 2,
                                    colors: ['transparent']
                                    },
                                    xaxis: {
                                    categories: ['Tahun Ajaran 2022/2023'],
                                    },
                                    yaxis: {
                                    title: {
                                        text: 'Jumlah Taruna'
                                    }
                                    },
                                    fill: {
                                    opacity: 1
                                    },
                                    tooltip: {
                                    y: {
                                        formatter: function(val) {
                                        return val + " Taruna"
                                        }
                                    }
                                    }
                                }).render();
                                });
                            </script>
                            <!-- End Column Chart -->

                            </div>
                        </div>
                    </div> --}}

                </div>
            </div><!-- End Left side columns -->

        </div>
    </section>
@endsection
