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

                    <div class="card-body">
                        <form method="post" action="/ptb">
                            @csrf
                            <div class="modal-body">
                                <div class="form-group row mt-3">
                                    <div class="col-sm-10">
                                        <select class="form-select" class="form-control" name="year" required>
                                            <option value="" selected>Pilih Tahun</option>
                                            {{-- @for ($i = 0; $i < count($data["filter_tahun"]); $i++)
                                                <option value='{{ $data["filter_tahun"][$i]["filterTahun"] }}'>{{ $data["filter_tahun"][$i]["filterTahun"] }}</option>
                                            @endfor --}}
                                            <option value="2022">2022</option>
                                            <option value="2023">2023</option>
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
