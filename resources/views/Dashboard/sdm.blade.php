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
            {{-- <div class="col-lg-12">
                <div class="row">

                    <div class="card-body">
                        <form method="post" action="/ptb">
                            @csrf
                            <div class="modal-body">
                                <div class="form-group row mt-3">
                                    <div class="col-sm-10">
                                        <select class="form-select" class="form-control" name="year" required>
                                            <option value="">Pilih Tahun</option>
                                            @for ($i = 0; $i < count($data['filter_tahun']); $i++)
                                                @if ($data['filter_tahun'][$i]['filterTahun'] == $year)
                                                    <option value='{{ $data['filter_tahun'][$i]['filterTahun'] }}' selected>
                                                        {{ $data['filter_tahun'][$i]['filterTahun'] }}</option>
                                                @else
                                                    <option value='{{ $data['filter_tahun'][$i]['filterTahun'] }}'>
                                                        {{ $data['filter_tahun'][$i]['filterTahun'] }}</option>
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
            </div> --}}

            <!-- Left side columns -->
            <div class="col-lg-12">
                <div class="row">

                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Jumlah Dosen by Prodi</h5>

                                <!-- Bar Chart -->
                                <canvas id="sdmDosenbyHomeBaseProdi" style="max-height: 400px;"></canvas>
                                <script>
                                    document.addEventListener("DOMContentLoaded", () => {
                                        new Chart(document.querySelector('#sdmDosenbyHomeBaseProdi'), {
                                            type: 'bar',
                                            data: {
                                                datasets: [{
                                                    data: <?php echo $data['Dosen_by_HomeBaseProdi']; ?>,
                                                    label: 'Dosen',
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
                                                    xAxisKey: 'hbBase',
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


                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Jumlah Dosen by Gender</h5>

                                <!-- Bar Chart -->
                                <canvas id="sdmDosenbyGender" style="max-height: 400px;"></canvas>
                                <script>
                                    document.addEventListener("DOMContentLoaded", () => {
                                        new Chart(document.querySelector('#sdmDosenbyGender'), {
                                            type: 'bar',
                                            data: {
                                                datasets: [{
                                                    data: <?php echo $data['Dosen_by_Gender']; ?>,
                                                    label: 'Dosen',
                                                    backgroundColor: [
                                                        'rgba(54, 162, 235, 0.2)',
                                                        'rgba(255, 99, 132, 0.2)',
                                                        'rgba(255, 205, 86, 0.2)',
                                                        'rgba(75, 192, 192, 0.2)',
                                                        'rgba(54, 162, 235, 0.2)',
                                                        'rgba(153, 102, 255, 0.2)',
                                                        'rgba(201, 203, 207, 0.2)'
                                                    ],
                                                    borderColor: [
                                                        'rgb(54, 162, 235)',
                                                        'rgb(255, 99, 132)',
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
                                                    xAxisKey: 'karJK',
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


                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Jumlah Dosen by Gender by Status</h5>

                                <!-- Bar Chart -->
                                <canvas id="sdmDosenbyGenderbyStatus" style="max-height: 400px;"></canvas>
                                <script>
                                    document.addEventListener("DOMContentLoaded", () => {
                                        new Chart(document.querySelector('#sdmDosenbyGenderbyStatus'), {
                                            type: 'bar',
                                            data: {
                                                datasets: [{
                                                    data: <?php echo $data['Dosen_by_GenderAndStatus']; ?>,
                                                    label: 'Dosen',
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
                                                    xAxisKey: 'karJK_sdStatus',
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

                </div>
            </div><!-- End Left side columns -->

        </div>
    </section>
@endsection
