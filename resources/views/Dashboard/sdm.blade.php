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
                                <h5 class="card-title">SDM <span>| Today</span></h5>
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
            <div class="col-lg-12">
                <div class="row">

                    <!-- Card Bar Chart -->
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                            <h5 class="card-title">Column Chart</h5>

                            <!-- Column Chart -->
                            <div id="columnChart"></div>

                            <script>
                              document.addEventListener("DOMContentLoaded", () => {
                                new ApexCharts(document.querySelector("#columnChart"), {
                                  series: [{
                                    name: 'Net Profit',
                                    data: [44, 55, 57, 56, 61, 58, 63, 60, 66, 54, 60, 70]
                                  }, {
                                    name: 'Revenue',
                                    data: [76, 85, 101, 98, 87, 105, 91, 114, 94, 100, 97, 88]
                                  }, {
                                    name: 'Free Cash Flow',
                                    data: [35, 41, 36, 26, 45, 48, 52, 53, 41, 60, 65, 90]
                                  }, {
                                    name: 'Clash Clan',
                                    data: [30, 42, 54, 60, 66, 72, 102, 98, 90, 80, 75, 46]
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
                                    categories: ['Jan','Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Des'],
                                  },
                                  yaxis: {
                                    title: {
                                      text: '$ (thousands)'
                                    }
                                  },
                                  fill: {
                                    opacity: 1
                                  },
                                  tooltip: {
                                    y: {
                                      formatter: function(val) {
                                        return "$ " + val + " thousands"
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

        </div>
    </section>
@endsection
