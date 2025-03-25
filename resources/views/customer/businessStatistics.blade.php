@extends('layout')

@section('content')
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="admin">Trang chủ</a>
                        </li>
                        <li class="breadcrumb-item"><a href="javascript: void(0)">Khách hàng doanh nghiệp</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">Thống kê doanh nghiệp</li>
                    </ul>
                </div>
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h2 class="mb-2">Thống kê</h2>
                        @if (request('start_date') && request('end_date'))
                            <p class="f-18">Thống kê từ ngày: <strong>{{ request('start_date') }}</strong> đến:
                                <strong>{{ request('end_date') }}</strong>
                            </p>
                        @elseif (request('start_date') && !request('end_date'))
                            <p class="f-18">Thống kê từ ngày: <strong>{{ request('start_date') }}</strong> đến hết tháng
                                nay</p>
                        @elseif (!request('start_date') && request('end_date'))
                            <p class="f-18">Thống kê từ đầu tháng nay đến: <strong>{{ request('end_date') }}</strong></p>
                        @else
                            <p class="f-18">Thống kê theo <strong>tháng nay</strong></p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <form action="{{ route('businessStatistics') }}" method="get">
        <div class="row">
            <div class="mb-3 col-3">
                <input type="date" class="form-control" title="Ngày bắt đầu" name="start_date"
                    value="{{ request('start_date') }}">
            </div>
            <div class="mb-3 col-3">
                <input type="date" class="form-control" title="Ngày kết thúc" name="end_date"
                    value="{{ request('end_date') }}">
            </div>
            <div class="mb-3 col-3">
                <button type="submit" class="btn btn-success">Tìm kiếm</button>
            </div>
        </div>
    </form>

    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-7 col-xl-8">
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="analytics-tab-1-pane" role="tabpanel"
                                aria-labelledby="analytics-tab-1" tabindex="0">
                                <div id="overview-chart-1"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-5 col-xl-4">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <div class="avtar avtar-s bg-light-secondary"><i class="ti ti-chart-bar f-20"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <div class="row g-1">
                                            <div class="col-6">
                                                <p class="text-muted mb-1">Tổng giá trị hợp đồng</p>
                                                <h6 class="mb-0">
                                                    {{ $totalStatistics[0]->total_contract_value ? number_format($totalStatistics[0]->total_contract_value, 0, '.', ',') : '0' }}đ
                                                </h6>
                                            </div>
                                            <div class="col-6 text-end">
                                                <h6 class="mb-1">
                                                    {{ $totalStatistics[0]->total_contract_value - $previousStatistics[0]->total_contract_value >= 0
                                                        ? '+ ' .
                                                            number_format(
                                                                $totalStatistics[0]->total_contract_value - $previousStatistics[0]->total_contract_value,
                                                                0,
                                                                '.',
                                                                ',',
                                                            )
                                                        : '- ' .
                                                            number_format(
                                                                abs($totalStatistics[0]->total_contract_value - $previousStatistics[0]->total_contract_value),
                                                                0,
                                                                '.',
                                                                ',',
                                                            ) }}
                                                    đ
                                                </h6>
                                                <p
                                                    class="mb-0 {{ $totalStatistics[0]->total_contract_value - $previousStatistics[0]->total_contract_value >= 0 ? 'text-success' : 'text-danger' }}">
                                                    <i
                                                        class="ti {{ $totalStatistics[0]->total_contract_value - $previousStatistics[0]->total_contract_value >= 0
                                                            ? 'ti-arrow-up-right text-success'
                                                            : 'ti-arrow-down-left text-danger' }} "></i>
                                                    {{ $previousStatistics[0]->total_contract_value != 0
                                                        ? round(
                                                            (($totalStatistics[0]->total_contract_value - $previousStatistics[0]->total_contract_value) /
                                                                $previousStatistics[0]->total_contract_value) *
                                                                100,
                                                            2,
                                                        )
                                                        : 100 }}
                                                    %
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <div class="avtar avtar-s bg-light-secondary"><i
                                                class="ti ti-chart-arrows-vertical f-20"></i></div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <div class="row g-1">
                                            <div class="col-6">
                                                <p class="text-muted mb-1">Tổng đã tạm ứng</p>
                                                <h6 class="mb-0">
                                                    {{ $totalStatistics[0]->advance_money ? number_format($totalStatistics[0]->advance_money, 0, '.', ',') : '0' }}
                                                    đ</h6>
                                            </div>
                                            <div class="col-6 text-end">
                                                <h6 class="mb-1">
                                                    {{ $totalStatistics[0]->advance_money - $previousStatistics[0]->advance_money >= 0
                                                        ? '+ ' . number_format($totalStatistics[0]->advance_money - $previousStatistics[0]->advance_money, 0, '.', ',')
                                                        : '- ' .
                                                            number_format(abs($totalStatistics[0]->advance_money - $previousStatistics[0]->advance_money), 0, '.', ',') }}
                                                    đ
                                                </h6>
                                                <p
                                                    class="mb-0 {{ $totalStatistics[0]->advance_money - $previousStatistics[0]->advance_money >= 0 ? 'text-success' : 'text-danger' }}">
                                                    <i
                                                        class="ti {{ $totalStatistics[0]->advance_money - $previousStatistics[0]->advance_money >= 0
                                                            ? 'ti-arrow-up-right text-success'
                                                            : 'ti-arrow-down-left text-danger' }} "></i>
                                                    {{ $previousStatistics[0]->advance_money != 0
                                                        ? round(
                                                            (($totalStatistics[0]->advance_money - $previousStatistics[0]->advance_money) /
                                                                $previousStatistics[0]->advance_money) *
                                                                100,
                                                            2,
                                                        )
                                                        : 100 }}
                                                    %
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <a href="{{ route('debtContract', ['start_date' => request('start_date'), 'end_date' => request('end_date')]) }}">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <div class="avtar avtar-s bg-light-secondary"><i
                                                    class="ti ti-shopping-cart f-20"></i></div>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <div class="row g-1">
                                                <div class="col-6">
                                                    <p class="text-muted mb-1">Tổng công nợ</p>
                                                    <h6 class="mb-0">
                                                        {{ $totalStatistics[0]->debt ? number_format($totalStatistics[0]->debt, 0, '.', ',') : '0' }}đ
                                                    </h6>
                                                </div>
                                                <div class="col-6 text-end">
                                                    <h6 class="mb-1">
                                                        {{ $totalStatistics[0]->debt - $previousStatistics[0]->debt >= 0
                                                            ? '+ ' . number_format($totalStatistics[0]->debt - $previousStatistics[0]->debt, 0, '.', ',')
                                                            : '- ' . number_format(abs($totalStatistics[0]->debt - $previousStatistics[0]->debt), 0, '.', ',') }}
                                                        đ
                                                    </h6>
                                                    <p
                                                        class="mb-0 {{ $totalStatistics[0]->debt - $previousStatistics[0]->debt >= 0 ? 'text-success' : 'text-danger' }}">
                                                        <i
                                                            class="ti {{ $totalStatistics[0]->debt - $previousStatistics[0]->debt >= 0
                                                                ? 'ti-arrow-up-right text-success'
                                                                : 'ti-arrow-down-left text-danger' }} "></i>
                                                        {{ $previousStatistics[0]->debt != 0
                                                            ? round((($totalStatistics[0]->debt - $previousStatistics[0]->debt) / $previousStatistics[0]->debt) * 100, 2)
                                                            : 100 }}
                                                        %
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const contractStatistics = JSON.parse('<?php echo json_encode($contractStatistics); ?>');
            const categories = contractStatistics.map(item => item.date); // Các ngày
            const contractValue = contractStatistics.map(item => item.total_contract_value); // Doanh thu
            const debt = contractStatistics.map(item => item.debt); // Cộng nợ

            var e = {
                chart: {
                    height: 250,
                    type: "bar",
                    toolbar: {
                        show: !1
                    }
                },
                plotOptions: {
                    bar: {
                        horizontal: !1,
                        columnWidth: "55%",
                        borderRadius: 4,
                        borderRadiusApplication: "end"
                    }
                },
                legend: {
                    show: !0,
                    position: "top",
                    horizontalAlign: "left"
                },
                dataLabels: {
                    enabled: !1
                },
                colors: ["#4680FF", "#4680FF"],
                stroke: {
                    show: !0,
                    width: 3,
                    colors: ["transparent"]
                },
                fill: {
                    colors: ["#4680FF", "#4680FF"],
                    opacity: [1, .5]
                },
                grid: {
                    strokeDashArray: 4
                },
                series: [{
                    name: "Tổng giá trị hợp đồng",
                    data: contractValue
                }, {
                    name: "Công nợ",
                    data: debt
                }],
                xaxis: {
                    categories: categories
                },
                tooltip: {
                    y: {
                        formatter: function(e) {
                            return e.toLocaleString() + " đ"
                        }
                    }
                }
            };
            new ApexCharts(document.querySelector("#overview-chart-1"), e).render()
        });
    </script>
@endsection
