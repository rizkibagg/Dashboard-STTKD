@extends('Layout.master')

@section('master')

    <div class="pagetitle">
        <h1>{{ $title }}</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/home">Home</a></li>
                <li class="breadcrumb-item active">{{ $title }}</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">
        <div class="row">

            <!-- Left side columns -->
            <div class="col-lg-12">
                <div class="row">

                    <!-- Recent Sales -->
                    <div class="col-12">
                        <div class="card recent-sales overflow-auto">

                            <div class="card-body">
                                <h5 class="card-title">Akademik <span>| Today</span></h5>
                                <a type="button" class="btn btn-primary btn-sm mx-3" style="float: right;" href="/">Tambah Data</a>

                                <table class="table table-hover datatable">
                                    <thead>
                                        <tr>
                                            <th scope="col">No.</th>
                                            <th scope="col">NIM</th>
                                            <th scope="col">Nama</th>
                                            <th scope="col">Program Studi</th>
                                            <th scope="col">Mata Kuliah</th>
                                            <th scope="col">Opsi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {{-- @php
                                            $no = 1;
                                        @endphp
                                        @foreach ($data as $dosen)
                                        <tr>
                                            <td><?php echo $no++; ?>.</td>
                                            <th scope="row">{{ $dosen->nik }}</th>
                                            <td>{{ $dosen->nama }}</td>
                                            <td>{{ $dosen->pstudi }}</td>
                                            <td>{{ $dosen->matkul }}</td>
                                            <td>
                                                <a class="badge bg-warning" href="{{ url('editds/'.$dosen->nik) }}" type="submit">Edit</a>
                                                <a class="badge bg-danger" href="{{ url('') }}" type="submit" name="submit" id="deleteDs" data-nik="{{ $dosen->nik }}" data-nama="{{ $dosen->nama }}">Delete</a>
                                            </td>
                                        </tr>
                                        @endforeach --}}
                                    </tbody>
                                </table>

                            </div>

                        </div>
                    </div><!-- End Recent Sales -->
                </div>
            </div>

            <!-- Left side columns -->
            <div class="col-lg-8">
                <div class="row">

                    <!-- Card Bar Chart -->
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Status Taruna</h5>

                                <!-- Bar Chart -->
                                <canvas id="barChart" style="max-height: 400px;"></canvas>
                                <script>
                                document.addEventListener("DOMContentLoaded", () => {
                                    new Chart(document.querySelector('#barChart'), {
                                    type: 'bar',
                                    data: {
                                        labels: ['Aktif', 'Lulus', 'DO', 'Cuti'],
                                        datasets: [{
                                        label: 'Bar Chart',
                                        data: [200, 120, 10, 14],
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
                                        scales: {
                                        y: {
                                            beginAtZero: true
                                        }
                                        }
                                    }
                                    });
                                });
                                </script>
                                <!-- End Bar CHart -->

                            </div>
                        </div>
                    </div>

                    <div class="col-lg-12">
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
                    </div>

                </div>
            </div><!-- End Left side columns -->

            <!-- Right side columns -->
            <div class="col-lg-4">

                <!-- Website Traffic -->
                <div class="card">

                    <div class="card-body pb-0">
                        <h5 class="card-title">Website Traffic <span>| Today</span></h5>

                        <div id="trafficChart" style="min-height: 400px;" class="echart"></div>

                        <script>
                            document.addEventListener("DOMContentLoaded", () => {
                                echarts.init(document.querySelector("#trafficChart")).setOption({
                                    tooltip: {
                                        trigger: 'item'
                                    },
                                    legend: {
                                        top: '5%',
                                        left: 'center'
                                    },
                                    series: [{
                                        name: 'Access From',
                                        type: 'pie',
                                        radius: ['40%', '70%'],
                                        avoidLabelOverlap: false,
                                        label: {
                                            show: false,
                                            position: 'center'
                                        },
                                        emphasis: {
                                            label: {
                                                show: true,
                                                fontSize: '18',
                                                fontWeight: 'bold'
                                            }
                                        },
                                        labelLine: {
                                            show: false
                                        },
                                        data: [{
                                                value: 1048,
                                                name: 'Search Engine'
                                            },
                                            {
                                                value: 735,
                                                name: 'Direct'
                                            },
                                            {
                                                value: 580,
                                                name: 'Email'
                                            },
                                            {
                                                value: 484,
                                                name: 'Union Ads'
                                            },
                                            {
                                                value: 300,
                                                name: 'Video Ads'
                                            }
                                        ]
                                    }]
                                });
                            });
                        </script>

                    </div>
                </div><!-- End Website Traffic -->

            </div><!-- End Right side columns -->

        </div>
    </section>
@endsection
