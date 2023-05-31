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

            <div class="col-lg-12">
                <div class="row">

                    <div class="card-body">
                        <form method="post" action="/ptb">
                            @csrf
                            <div class="modal-body">
                                <div class="form-group row mt-3">
                                    <div class="col-sm-10">
                                        <select class="form-select" class="form-control" name="year" required>
                                            <option value="" selected>Pilih Tahun</option>
                                            @for ($i = 0; $i < count($data["filter_tahun"]); $i++)
                                                <option value='{{ $data["filter_tahun"][$i]["filterTahun"] }}'>{{ $data["filter_tahun"][$i]["filterTahun"] }}</option>
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

                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Jumlah Taruna Yang Daftar dalam Line Chart</h5>

                                <div>
                                    <?php echo $data["catar_pendaftar_perbulan"] ?>
                                </div>

                                <!-- Line Chart -->
                                <canvas id="lineChart1" style="max-height: 400px;"></canvas>
                                <script>
                                    document.addEventListener("DOMContentLoaded", () => {
                                        new Chart(document.querySelector('#lineChart1'), {
                                        type: 'line',
                                        data: {
                                            // labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
                                            datasets: [{
                                            label: 'Taruna',
                                            data: <?php echo $data["catar_pendaftar_perbulan"] ?>,
                                            fill: false,
                                            borderColor: 'rgb(75, 192, 192)',
                                            tension: 0.1
                                            }]
                                        },
                                        options: {
                                            parsing: {
                                                xAxisKey: 'bulan',
                                                yAxisKey: 'total'
                                            }
                                        }
                                        });
                                    });
                                </script>
                                <!-- End Line CHart -->

                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Jumlah Taruna Yang Daftar dalam Bar Chart</h5>

                                <!-- Bar Chart -->
                                <canvas id="catarPendaftarPerBulan" style="max-height: 400px;"></canvas>
                                <script>
                                    document.addEventListener("DOMContentLoaded", () => {
                                        new Chart(document.querySelector('#catarPendaftarPerBulan'), {
                                        type: 'bar',
                                        data: {
                                            datasets: [{
                                                data: <?php echo $data["catar_pendaftar_perbulan"] ?>,
                                                label: 'Taruna',
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
                                            }],
                                        },
                                        options: {
                                            parsing: {
                                                xAxisKey: 'bulan',
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

                    <!-- Card Bar Chart -->
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Jumlah Taruna Sudah Bayar Heregistrasi Dalam Tahun</h5>

                                <!-- Bar Chart -->
                                <canvas id="catarBayarHeregPerbulan" style="max-height: 400px;"></canvas>
                                <script>
                                    document.addEventListener("DOMContentLoaded", () => {
                                        new Chart(document.querySelector('#catarBayarHeregPerbulan'), {
                                        type: 'bar',
                                        data: {
                                            datasets: [{
                                                data: <?php echo $data["catar_hereg_perbulan"] ?>,
                                                label: 'Taruna',
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
                                            }],
                                        },
                                        options: {
                                            parsing: {
                                                xAxisKey: 'bulan',
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

                    <!-- Card Bar Chart -->
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Jumlah Taruna By Jenis Kelamin</h5>

                                <!-- Bar Chart -->
                                <canvas id="catarByJenisKelamin" style="max-height: 400px;"></canvas>
                                <script>
                                document.addEventListener("DOMContentLoaded", () => {
                                    new Chart(document.querySelector('#catarByJenisKelamin'), {
                                    type: 'bar',
                                    data: {
                                        datasets: [{
                                        label: 'Taruna',
                                        data: <?php echo $data["catar_by_gender"] ?>,
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

                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Jumlah Taruna By Jenis Kelamin By Program Studi</h5>

                                <!-- Bar Chart -->
                                <canvas id="catarByJKByProdi" style="max-height: 400px;"></canvas>
                                <script>
                                document.addEventListener("DOMContentLoaded", () => {
                                    new Chart(document.querySelector('#catarByJKByProdi'), {
                                    type: 'bar',
                                    data: {
                                        datasets: [{
                                        label: 'Taruna',
                                        data: <?php echo $data["catar_bygender_byprodi"] ?>,
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
                            <h5 class="card-title">Jumlah Taruna By Program Studi (Belum)</h5>

                            <div>
                                <?php echo $data["catar_bygender_byprodi"] ?>
                            </div>

                            <!-- Column Chart -->
                            <div id="columnChart"></div>

                            <script>
                                document.addEventListener("DOMContentLoaded", () => {
                                new ApexCharts(document.querySelector("#columnChart"), {
                                    series: [{
                                    name: 'Laki Laki',
                                    data: <?php echo $data["catar_bygender_byprodi"] ?>
                                    }, {
                                    name: 'Perempuan',
                                    data: <?php echo $data["catar_bygender_byprodi"] ?>
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

                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Jumlah Taruna By Provinsi</h5>

                                <!-- Bar Chart -->
                                <div id="barChart2"></div>

                                <script>
                                document.addEventListener("DOMContentLoaded", () => {
                                    new ApexCharts(document.querySelector("#barChart2"), {
                                    series: [{
                                        name:"Taruna",
                                        data: <?php echo $data["catar_by_provinsi"] ?>
                                    }],
                                    chart: {
                                        type: 'bar',
                                        height: 350
                                    },
                                    plotOptions: {
                                        bar: {
                                        borderRadius: 4,
                                        horizontal: true,
                                        }
                                    },
                                    dataLabels: {
                                        enabled: false
                                    },
                                    xaxis: {
                                        type:"category"
                                    }
                                    }).render();
                                });
                                </script>
                                <!-- End Bar Chart -->

                            </div>
                        </div>
                    </div>

                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Jumlah Taruna By Sumber Informasi Pendaftaran</h5>

                                <!-- Pie Chart -->
                                <div id="pieChart"></div>

                                <script>
                                document.addEventListener("DOMContentLoaded", () => {
                                    new ApexCharts(document.querySelector("#pieChart"), {
                                    series: [44, 55, 13, 43, 22, 10],
                                    chart: {
                                        height: 350,
                                        type: 'pie',
                                        toolbar: {
                                        show: true
                                        }
                                    },
                                    labels: ['Team A', 'Team B', 'Team C', 'Team D', 'Team E']
                                    }).render();
                                });
                                </script>
                                <!-- End Pie Chart -->

                            </div>
                        </div>
                    </div>

                </div>
            </div><!-- End Left side columns -->

            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Line Chart</h5>

                        <!-- Line Chart -->
                        <div id="lineChart"></div>

                        <script>
                        document.addEventListener("DOMContentLoaded", () => {
                            new ApexCharts(document.querySelector("#lineChart"), {
                            series: [{
                                name: "Desktops",
                                data: [10, 41, 35, 51, 49, 62, 69, 91, 148]
                            }],
                            chart: {
                                height: 350,
                                type: 'line',
                                zoom: {
                                enabled: false
                                }
                            },
                            dataLabels: {
                                enabled: false
                            },
                            stroke: {
                                curve: 'straight'
                            },
                            grid: {
                                row: {
                                colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
                                opacity: 0.5
                                },
                            },
                            xaxis: {
                                categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep'],
                            }
                            }).render();
                        });
                        </script>
                        <!-- End Line Chart -->

                    </div>
                </div>
            </div>

        </div>
    </section>
@endsection
