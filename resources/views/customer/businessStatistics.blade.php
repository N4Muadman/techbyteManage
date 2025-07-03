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

    <p class="f-18">Thống kê giá trị hợp đồng</p>
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-7 col-xl-8">
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" aria-labelledby="analytics-tab-1" tabindex="0">
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
                                <a
                                    href="{{ route('debtContract', ['start_date' => request('start_date'), 'end_date' => request('end_date')]) }}">
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

    <p class="f-18">Thống kê số lượng khách hàng</p>
    <div class="col-12">
        <div class="card">
            <div class="card-header pb-0 pt-2">
                <ul class="nav nav-tabs analytics-tab" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation"><button class="nav-link" id="analytics-tab-1"
                            data-bs-toggle="tab" data-bs-target="#analytics-tab-1-pane" type="button" role="tab"
                            aria-controls="analytics-tab-1-pane" aria-selected="true">Ngày</button></li>
                    <li class="nav-item" role="presentation"><button class="nav-link active" id="analytics-tab-2"
                            data-bs-toggle="tab" data-bs-target="#analytics-tab-2-pane" type="button" role="tab"
                            aria-controls="analytics-tab-2-pane" aria-selected="false">Tháng</button></li>
                    <li class="nav-item" role="presentation"><button class="nav-link" id="analytics-tab-3"
                            data-bs-toggle="tab" data-bs-target="#analytics-tab-3-pane" type="button" role="tab"
                            aria-controls="analytics-tab-3-pane" aria-selected="false">Năm</button></li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade" id="analytics-tab-1-pane" role="tabpanel"
                        aria-labelledby="analytics-tab-1" tabindex="0">
                        <div class="row">
                            <div class="col-lg-7 col-xl-8">
                                <div id="chart-type-day"></div>
                            </div>


                            <div class="col-lg-5 col-xl-4">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <div class="avtar avtar-s bg-light-secondary"><i
                                                        class="ti ti-user f-20"></i>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <div class="row g-1">
                                                    <div class="col-6">
                                                        <h6 class="mb-0">Khách hàng mới</h6>
                                                        <p class="text-muted mb-1 current">Ngày hôm này</p>
                                                        <p class="text-muted mb-1 previous">Ngày hôm trước</p>
                                                    </div>
                                                    <div class="col-6 text-end">
                                                        <br>
                                                        <h6 class="mb-1 current-value">0</h6>
                                                        <h6 class="mb-1 previous-value">0</h6>
                                                        <p class="text-warning mb-0"><i
                                                                class="ti ti-arrows-left-right"></i>
                                                            0
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
                                                        class="ti ti-user f-20"></i>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <div class="row g-1">
                                                    <div class="col-6">
                                                        <h6 class="mb-0">Khách hàng cũ</h6>
                                                        <p class="text-muted mb-1 current">Ngày hôm này</p>
                                                        <p class="text-muted mb-1 previous">Ngày hôm trước</p>
                                                    </div>
                                                    <div class="col-6 text-end">
                                                        <br>
                                                        <h6 class="mb-1 current-value">0</h6>
                                                        <h6 class="mb-1 previous-value">0</h6>
                                                        <p class="text-warning mb-0"><i
                                                                class="ti ti-arrows-left-right"></i>
                                                            0
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade show active" id="analytics-tab-2-pane" role="tabpanel"
                        aria-labelledby="analytics-tab-2" tabindex="0">
                        <div class="row">
                            <div class="col-lg-7 col-xl-8">
                                <div id="chart-type-month"></div>
                            </div>

                            <div class="col-lg-5 col-xl-4">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <div class="avtar avtar-s bg-light-secondary"><i
                                                        class="ti ti-user f-20"></i>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <div class="row g-1">
                                                    <div class="col-6">
                                                        <h6 class="mb-0">Khách hàng mới</h6>
                                                        <p class="text-muted mb-1 current">Tháng này</p>
                                                        <p class="text-muted mb-1 previous">Tháng trước</p>
                                                    </div>
                                                    <div class="col-6 text-end">
                                                        <br>
                                                        <h6 class="mb-1 current-value">0</h6>
                                                        <h6 class="mb-1 previous-value">0</h6>
                                                        <p class="text-warning mb-0"><i
                                                                class="ti ti-arrows-left-right"></i>
                                                            0
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
                                                        class="ti ti-user f-20"></i>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <div class="row g-1">
                                                    <div class="col-6">
                                                        <h6 class="mb-0">Khách hàng cũ</h6>
                                                        <p class="text-muted mb-1 current">Tháng này</p>
                                                        <p class="text-muted mb-1 previous">Tháng trước</p>
                                                    </div>
                                                    <div class="col-6 text-end">
                                                        <br>
                                                        <h6 class="mb-1 current-value">0</h6>
                                                        <h6 class="mb-1 previous-value">0</h6>
                                                        <p class="text-warning mb-0"><i
                                                                class="ti ti-arrows-left-right"></i>
                                                            0
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="analytics-tab-3-pane" role="tabpanel"
                        aria-labelledby="analytics-tab-3" tabindex="0">
                        <div class="row">
                            <div class="col-lg-7 col-xl-8">
                                <div id="chart-type-year"></div>
                            </div>

                            <div class="col-lg-5 col-xl-4">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <div class="avtar avtar-s bg-light-secondary"><i
                                                        class="ti ti-user f-20"></i>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <div class="row g-1">
                                                    <div class="col-6">
                                                        <h6 class="mb-0">Khách hàng mới</h6>
                                                        <p class="text-muted mb-1 current">Năm này</p>
                                                        <p class="text-muted mb-1 previous">Năm trước</p>
                                                    </div>
                                                    <div class="col-6 text-end">
                                                        <br>
                                                        <h6 class="mb-1 current-value">0</h6>
                                                        <h6 class="mb-1 previous-value">0</h6>
                                                        <p class="text-warning mb-0"><i
                                                                class="ti ti-arrows-left-right"></i>
                                                            0
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
                                                        class="ti ti-user f-20"></i>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <div class="row g-1">
                                                    <div class="col-6">
                                                        <h6 class="mb-0">Khách hàng cũ</h6>
                                                        <p class="text-muted mb-1 current">Năm này</p>
                                                        <p class="text-muted mb-1 previous">Năm trước</p>
                                                    </div>
                                                    <div class="col-6 text-end">
                                                        <br>
                                                        <h6 class="mb-1 current-value">0</h6>
                                                        <h6 class="mb-1 previous-value">0</h6>
                                                        <p class="text-warning mb-0"><i
                                                                class="ti ti-arrows-left-right"></i>
                                                            0
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const contractStatistics = JSON.parse('<?php echo json_encode($contractStatistics); ?>');

            const categories = contractStatistics.map(item => item.date);
            const contractValue = contractStatistics.map(item => item.total_contract_value);
            const debt = contractStatistics.map(item => item.debt);

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
            new ApexCharts(document.querySelector("#overview-chart-1"), e).render();

            window.businessAnalytics = new BusinessCustomerAnalytics();
        });

        class BusinessCustomerAnalytics {
            constructor() {
                this.apiUrl = '{{ route('businessCustomerStatistics') }}';
                this.charts = {
                    day: null,
                    month: null,
                    year: null
                };
                this.currentTab = 'month';
                this.init();
            }

            init() {
                this.bindEvents();
                this.loadData('month');
            }

            bindEvents() {
                document.querySelectorAll('[data-bs-toggle="tab"]').forEach(tab => {
                    tab.addEventListener('shown.bs.tab', (event) => {
                        const tabId = event.target.getAttribute('id');
                        const type = this.getTypeFromTabId(tabId);
                        this.currentTab = type;
                        this.loadData(type);
                    });
                });

                window.addEventListener('resize', () => {
                    this.handleResize();
                });
            }

            handleResize() {
                Object.keys(this.charts).forEach(type => {
                    if (this.charts[type]) {
                        try {
                            this.charts[type].resize();
                        } catch (error) {
                            console.warn(`Error resizing ${type} chart:`, error);
                        }
                    }
                });
            }

            getTypeFromTabId(tabId) {
                const typeMap = {
                    'analytics-tab-1': 'day',
                    'analytics-tab-2': 'month',
                    'analytics-tab-3': 'year'
                };
                return typeMap[tabId] || 'month';
            }

            async loadData(type) {
                try {
                    this.showLoading(type);

                    const response = await fetch(`${this.apiUrl}?type=${type}`, {
                        method: 'GET',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });

                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }

                    const data = await response.json();
                    this.updateUI(type, data);
                    this.updateChart(type, data.statistics);

                } catch (error) {
                    console.error('Error loading data:', error);
                    this.showError(type, error.message);
                } finally {
                    this.hideLoading(type);
                }
            }

            updateUI(type, data) {
                const tabPane = document.querySelector(`#analytics-tab-${this.getTabNumber(type)}-pane`);
                if (!tabPane) return;

                const typeMap = {
                    day: 'Ngày hôm',
                    month: 'Tháng',
                    year: 'Năm'
                };

                const listItems = tabPane.querySelectorAll('.list-group-item');

                if (listItems[0]) {
                    this.updateListItem(listItems[0], {
                        currentValue: data.current?.new_customers,
                        previousValue: data.previous?.new_customers,
                        type: `${typeMap[type]}`,
                        comparison: data.comparison.new_customers,
                    });
                }

                if (listItems[1]) {
                    this.updateListItem(listItems[1], {
                        currentValue: data.current?.returning_customers,
                        previousValue: data.previous?.returning_customers,
                        type: `${typeMap[type]}`,
                        comparison: data.comparison.returning_customers,
                    });
                }
            }

            updateListItem(listItem, itemData) {
                const typeCurrent = listItem.querySelector('.col-6 .current');
                const typePrevious = listItem.querySelector('.col-6 .previous');
                const currentValueElement = listItem.querySelector('.col-6.text-end .current-value');
                const previousValueElement = listItem.querySelector('.col-6.text-end .previous-value');
                const comparisonPercentElement = listItem.querySelector('.col-6.text-end p');

                typeCurrent.textContent = itemData.type + ' này';
                typePrevious.textContent = itemData.type + ' trước';
                currentValueElement.textContent = itemData.currentValue;
                previousValueElement.textContent = itemData.previousValue;

                if (comparisonPercentElement) {
                    const {
                        difference,
                        percentage,
                        status
                    } = itemData.comparison;

                    const icon = this.getStatusIcon(status);
                    const textClass = this.getStatusClass(status);

                    comparisonPercentElement.innerHTML = `<i class="${icon}"></i> ${Math.abs(percentage)}%`;
                    comparisonPercentElement.className = `${textClass} mb-0`;
                }
            }

            getStatusIcon(status) {
                const iconMap = {
                    'increase': 'ti ti-arrow-up-right',
                    'decrease': 'ti ti-arrow-down-left',
                    'equal': 'ti ti-arrows-left-right'
                };
                return iconMap[status] || 'ti ti-arrows-left-right';
            }

            getStatusClass(status) {
                const classMap = {
                    'increase': 'text-success',
                    'decrease': 'text-danger',
                    'equal': 'text-warning'
                };
                return classMap[status] || 'text-warning';
            }

            getTabNumber(type) {
                const tabMap = {
                    'day': '1',
                    'month': '2',
                    'year': '3'
                };
                return tabMap[type] || '2';
            }

            updateChart(type, statistics) {
                const chartContainer = document.querySelector(`#chart-type-${type}`);
                if (!chartContainer) {
                    console.warn(`Chart container #chart-type-${type} not found`);
                    return;
                }

                if (this.charts[type]) {
                    try {
                        this.charts[type].destroy();
                    } catch (error) {
                        console.warn('Error destroying chart:', error);
                    }
                    this.charts[type] = null;
                }

                chartContainer.innerHTML = '';

                const chartData = this.prepareChartData(type, statistics);

                if (!chartData.categories || chartData.categories.length === 0) {
                    chartContainer.innerHTML =
                        '<div class="text-center p-4"><p class="text-muted">Không có dữ liệu để hiển thị</p></div>';
                    return;
                }

                try {
                    this.charts[type] = this.createChart(chartContainer, chartData, type);
                    if (this.charts[type]) {
                        this.charts[type].render();
                    }
                } catch (error) {
                    console.error('Error creating chart:', error);
                    chartContainer.innerHTML =
                        '<div class="text-center p-4"><p class="text-danger">Lỗi tạo biểu đồ</p></div>';
                }
            }

            prepareChartData(type, statistics) {
                let categories = [];
                let newCustomersData = [];
                let returningCustomersData = [];
                let totalCustomersData = [];

                if (!statistics || !Array.isArray(statistics)) {
                    console.warn('Invalid statistics data:', statistics);
                    return {
                        categories: [],
                        newCustomersData: [],
                        returningCustomersData: [],
                        totalCustomersData: []
                    };
                }

                statistics.forEach(item => {
                    if (!item) return;

                    try {
                        switch (type) {
                            case 'day':
                                if (item.date) {
                                    categories.push(this.formatDate(item.date));
                                    newCustomersData.push(parseInt(item.new_customers) || 0);
                                    returningCustomersData.push(parseInt(item.returning_customers) || 0);
                                }
                                break;
                            case 'month':
                                if (item.label || (item.month && item.year)) {
                                    categories.push(item.label ||
                                        `${String(item.month).padStart(2, '0')}/${item.year}`);
                                    newCustomersData.push(parseInt(item.new_customers) || 0);
                                    returningCustomersData.push(parseInt(item.returning_customers) || 0);
                                }
                                break;
                            case 'year':
                                if (item.year) {
                                    categories.push(item.year.toString());
                                    newCustomersData.push(parseInt(item.new_customers) || 0);
                                    returningCustomersData.push(parseInt(item.returning_customers) || 0);
                                }
                                break;
                        }
                    } catch (error) {
                        console.warn('Error processing chart data item:', item, error);
                    }
                });

                return {
                    categories,
                    newCustomersData,
                    returningCustomersData,
                };
            }

            createChart(container, chartData, type) {
                if (!container || !container.offsetParent) {
                    console.warn('Container not visible or not attached to DOM');
                    return null;
                }

                const options = {
                    chart: {
                        height: 350,
                        type: "bar",
                        toolbar: {
                            show: true
                        }
                    },
                    plotOptions: {
                        bar: {
                            horizontal: false,
                            columnWidth: "55%",
                            borderRadius: 4,
                            borderRadiusApplication: "end"
                        }
                    },
                    legend: {
                        show: true,
                        position: "top",
                        horizontalAlign: "left"
                    },
                    dataLabels: {
                        enabled: false
                    },
                    colors: ["#4680FF", "#4680FF"],
                    stroke: {
                        show: true,
                        width: 2,
                        colors: ["transparent"]
                    },
                    fill: {
                        opacity: [1, .5]
                    },
                    grid: {
                        strokeDashArray: 4
                    },
                    series: [{
                            name: "Khách hàng mới",
                            data: chartData.newCustomersData
                        },
                        {
                            name: "Khách hàng quay lại",
                            data: chartData.returningCustomersData
                        }
                    ],
                    xaxis: {
                        categories: chartData.categories,
                        title: {
                            text: this.getXAxisTitle(type)
                        }
                    },
                    yaxis: {
                        title: {
                            text: "Số lượng khách hàng"
                        }
                    },
                    tooltip: {
                        y: {
                            formatter: function(value, {
                                seriesIndex,
                                dataPointIndex,
                                w
                            }) {
                                const seriesName = w.config.series[seriesIndex].name;
                                return `${value} khách hàng`;
                            }
                        },
                        x: {
                            formatter: function(value) {
                                return value;
                            }
                        }
                    },
                    responsive: [{
                        breakpoint: 768,
                        options: {
                            chart: {
                                height: 300
                            },
                            plotOptions: {
                                bar: {
                                    columnWidth: "70%"
                                }
                            }
                        }
                    }]
                };

                try {
                    return new ApexCharts(container, options);
                } catch (error) {
                    console.error('Error initializing ApexChart:', error);
                    return null;
                }
            }

            getXAxisTitle(type) {
                switch (type) {
                    case 'day':
                        return 'Ngày';
                    case 'month':
                        return 'Tháng';
                    case 'year':
                        return 'Năm';
                    default:
                        return '';
                }
            }

            formatDate(dateString) {
                const date = new Date(dateString);
                return date.toLocaleDateString('vi-VN', {
                    day: '2-digit',
                    month: '2-digit'
                });
            }

            formatCurrency(amount) {
                return new Intl.NumberFormat('vi-VN', {
                    style: 'currency',
                    currency: 'VND'
                }).format(amount);
            }

            showLoading(type) {
                const tabPane = document.querySelector(`#analytics-tab-${this.getTabNumber(type)}-pane`);
                if (tabPane) {
                    tabPane.style.opacity = '0.6';
                    tabPane.style.pointerEvents = 'none';
                }
            }

            hideLoading(type) {
                const tabPane = document.querySelector(`#analytics-tab-${this.getTabNumber(type)}-pane`);
                if (tabPane) {
                    tabPane.style.opacity = '1';
                    tabPane.style.pointerEvents = 'auto';
                }
            }

            showError(type, message) {
                const tabPane = document.querySelector(`#analytics-tab-${this.getTabNumber(type)}-pane`);
                if (tabPane) {
                    console.error(`Error in ${type} tab:`, message);
                }
            }

            refresh() {
                this.loadData(this.currentTab);
            }

            setDateRange(month) {
                if (this.currentTab === 'day') {
                    this.loadData('day', {
                        month: month
                    });
                }
            }
        }
    </script>
@endsection
