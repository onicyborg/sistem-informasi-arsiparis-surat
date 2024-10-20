@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="row">
                    <div class="col-md-6 col-xl-3 mb-4">
                        <div class="card shadow border-0">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-3 text-center">
                                        <span class="circle circle-sm bg-primary">
                                            <i class="fe fe-mail fe-16 text-white"></i>
                                        </span>
                                    </div>
                                    <div class="col">
                                        <p class="small text-muted mb-0">Surat Diterima Hari Ini</p>
                                        <span class="h3 mb-0">{{ $incomingtoday }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-xl-3 mb-4">
                        <div class="card shadow border-0">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-3 text-center">
                                        <span class="circle circle-sm bg-primary">
                                            <i class="fe fe-send fe-16 text-white"></i>
                                        </span>
                                    </div>
                                    <div class="col">
                                        <p class="small text-muted mb-0">Surat Terkirim Hari Ini</p>
                                        <span class="h3 mb-0">{{ $outcomingtoday }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-xl-3 mb-4">
                        <div class="card shadow border-0">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-3 text-center">
                                        <span class="circle circle-sm bg-primary">
                                            <i class="fe fe-mail fe-16 text-white"></i>
                                        </span>
                                    </div>
                                    <div class="col">
                                        <p class="small text-muted mb-0">Total Surat Diterima</p>
                                        <span class="h3 mb-0">{{ $incomingall }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-xl-3 mb-4">
                        <div class="card shadow border-0">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-3 text-center">
                                        <span class="circle circle-sm bg-primary">
                                            <i class="fe fe-send fe-16 text-white"></i>
                                        </span>
                                    </div>
                                    <div class="col">
                                        <p class="small text-muted mb-0">Total Surat Terkirim</p>
                                        <span class="h3 mb-0">{{ $outcomingall }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- end section -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="card shadow">
                            <div class="card-header text-center">
                                <strong class="card-title mb-0">Rekap surat diterima dan surat dikirim dalam 1 minggu terakhir</strong>
                            </div>
                            <div class="card-body">
                                <div id="columnChart"></div>
                            </div> <!-- /.card-body -->
                        </div> <!-- /.card -->
                    </div> <!-- /. col -->
                </div>
            </div>
        </div> <!-- .row -->
        <!-- .row -->
    </div>
    <!-- .container-fluid -->
@endsection

@push('styles')
@endpush

@push('scripts')
    <script>
        var columnChart,
            columnChartoptions = {
                series: [{
                        name: "Surat Masuk",
                        data: @json($incoming),
                    },
                    {
                        name: "Surat Keluar",
                        data: @json($outcoming),
                    },
                ],
                chart: {
                    type: "bar",
                    height: 350,
                    stacked: !1,
                    columnWidth: "70%",
                    zoom: {
                        enabled: !0
                    },
                    toolbar: {
                        show: !1
                    },
                    background: "transparent",
                },
                dataLabels: {
                    enabled: !1
                },
                theme: {
                    mode: colors.chartTheme
                },
                responsive: [{
                    breakpoint: 480,
                    options: {
                        legend: {
                            position: "bottom",
                            offsetX: -10,
                            offsetY: 0
                        }
                    },
                }, ],
                plotOptions: {
                    bar: {
                        horizontal: !1,
                        columnWidth: "40%",
                        radius: 30,
                        enableShades: !1,
                        endingShape: "rounded",
                    },
                },
                xaxis: {
                    categories: @json($dates),
                    labels: {
                        show: !0,
                        trim: !0,
                        minHeight: void 0,
                        maxHeight: 120,
                        style: {
                            colors: colors.mutedColor,
                            cssClass: "text-muted",
                            fontFamily: base.defaultFontFamily,
                        },
                    },
                    axisBorder: {
                        show: !1
                    },
                },
                yaxis: {
                    labels: {
                        show: !0,
                        trim: !1,
                        offsetX: -10,
                        minHeight: void 0,
                        maxHeight: 120,
                        style: {
                            colors: colors.mutedColor,
                            cssClass: "text-muted",
                            fontFamily: base.defaultFontFamily,
                        },
                    },
                },
                legend: {
                    position: "top",
                    fontFamily: base.defaultFontFamily,
                    fontWeight: 400,
                    labels: {
                        colors: colors.mutedColor,
                        useSeriesColors: !1
                    },
                    markers: {
                        width: 10,
                        height: 10,
                        strokeWidth: 0,
                        strokeColor: "#fff",
                        fillColors: [extend.primaryColor, extend.primaryColorLighter],
                        radius: 6,
                        customHTML: void 0,
                        onClick: void 0,
                        offsetX: 0,
                        offsetY: 0,
                    },
                    itemMargin: {
                        horizontal: 10,
                        vertical: 0
                    },
                    onItemClick: {
                        toggleDataSeries: !0
                    },
                    onItemHover: {
                        highlightDataSeries: !0
                    },
                },
                fill: {
                    opacity: 1,
                    colors: [base.primaryColor, extend.primaryColorLighter],
                },
                grid: {
                    show: !0,
                    borderColor: colors.borderColor,
                    strokeDashArray: 0,
                    position: "back",
                    xaxis: {
                        lines: {
                            show: !1
                        }
                    },
                    yaxis: {
                        lines: {
                            show: !0
                        }
                    },
                    row: {
                        colors: void 0,
                        opacity: 0.5
                    },
                    column: {
                        colors: void 0,
                        opacity: 0.5
                    },
                    padding: {
                        top: 0,
                        right: 0,
                        bottom: 0,
                        left: 0
                    },
                },
            },
            columnChartCtn = document.querySelector("#columnChart");
        columnChartCtn &&
            (columnChart = new ApexCharts(columnChartCtn, columnChartoptions)).render();
    </script>
@endpush
