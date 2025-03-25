@extends('layout')
@section('content')

<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="admin">Trang chủ</a>
                    </li>
                    <li class="breadcrumb-item"><a href="javascript: void(0)">Khách hàng từ đâu</a>
                    </li>
                    <li class="breadcrumb-item" aria-current="page">Thống kê</li>
                </ul>
            </div>
            <div class="col-md-12">
                <div class="page-header-title">
                    <h2 class="mb-2">Thống kê</h2>
                    @if (request('start_date') && request('end_date'))
                        <p class="f-18">Thống kê từ ngày: <strong>{{ request('start_date') }}</strong> đến: <strong>{{ request('end_date') }}</strong></p>
                    @elseif (request('start_date') && !request('end_date'))
                        <p class="f-18">Thống kê từ ngày: <strong>{{ request('start_date') }}</strong> đến hết tháng nay</p>
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
<form action="{{ route('customerStatistics') }}" method="get">
    <div class="row">
        <div class="mb-3 col-3">
            <input type="date" class="form-control" title="Ngày bắt đầu" name="start_date" value="{{ request('start_date') }}">
        </div>
        <div class="mb-3 col-3">
            <input type="date" class="form-control" title="Ngày kết thúc" name="end_date" value="{{ request('end_date') }}">
        </div>
        <div class="mb-3 col-3">
            <button type="submit" class="btn btn-success">Tìm kiếm</button>
        </div>
    </div>
</form>
    <div class="row">
        <div class="col-xxl-6 col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h5 class="mb-0">Tỉ lệ chốt deal</h5>
                    </div>
                    <div class="my-3">
                        <div id="deal-rate"></div>
                    </div>
                    <div class="row g-3 text-center">
                        <div class="col-6 col-lg-4 col-xxl-4">
                            <div class="overview-product-legends">
                                <p class="text-secondary mb-1"><span>Tổng khách hàng</span></p>
                                <h6 class="mb-0">{{ $dealRate[0] }}</h6>
                            </div>
                        </div>
                        <div class="col-6 col-lg-4 col-xxl-4">
                            <div class="overview-product-legends">
                                <p class="text-primary mb-1"><span>Chưa chốt</span></p>
                                <h6 class="mb-0">{{ $dealRate[2] }}</h6>
                            </div>
                        </div>
                        <div class="col-6 col-lg-4 col-xxl-4">
                            <div class="overview-product-legends">
                                <p class="text-danger mb-1"><span>Đã chốt</span></p>
                                <h6 class="mb-0">{{ $dealRate[1] }}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xxl-6 col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h5 class="mb-0">Khách hàng đến từ đâu</h5>
                    </div>
                    <div class="my-3">
                        <div id="social-network-rate"></div>
                    </div>
                    <div class="row g-3 text-center">
                        <div class="col-4 col-lg-4 col-xxl-4">
                            <div class="overview-product-legends">
                                <p class="text-secondary mb-1"><span>Tiktok</span></p>
                                <h6 class="mb-0">{{ $socialNetworkRate[0]['total_tiktok'] }}</h6>
                            </div>
                        </div>
                        <div class="col-4 col-lg-4 col-xxl-4">
                            <div class="overview-product-legends">
                                <p class="text-primary mb-1"><span>Facebook</span></p>
                                <h6 class="mb-0">{{ $socialNetworkRate[0]['total_facebook'] }}</h6>
                            </div>
                        </div>
                        <div class="col-4 col-lg-4 col-xxl-4">
                            <div class="overview-product-legends">
                                <p class="text-danger mb-1"><span>Youtube</span></p>
                                <h6 class="mb-0">{{ $socialNetworkRate[0]['total_youtube'] }}</h6>
                            </div>
                        </div>
                        <div class="col-4 col-lg-4 col-xxl-4">
                            <div class="overview-product-legends">
                                <p class="text-warning mb-1"><span>Web</span></p>
                                <h6 class="mb-0">{{ $socialNetworkRate[0]['total_web'] }}</h6>
                            </div>
                        </div>
                        <div class="col-4 col-lg-4 col-xxl-4">
                            <div class="overview-product-legends">
                                <p class="text-info mb-1"><span>Được giới thiệu</span></p>
                                <h6 class="mb-0">{{ $socialNetworkRate[0]['total_referral'] }}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const dealRate = JSON.parse('<?php echo json_encode($dealRate); ?>');
            console.log(dealRate);

            new ApexCharts(document.querySelector("#deal-rate"), {
                chart: {
                    height: 350,
                    type: "pie"
                },
                labels: ["Đã chốt", "Chưa chốt"],
                series: [dealRate[3], dealRate[4]],
                colors: ["#e23232", "#4680FF"],
                fill: {
                    opacity: [.8, .8]
                },
                legend: {
                    show: !1
                },
                dataLabels: {
                    enabled: !0,
                    dropShadow: {
                        enabled: !1
                    }
                },
                responsive: [{
                    breakpoint: 575,
                    options: {
                        chart: {
                            height: 250
                        },
                        dataLabels: {
                            enabled: !1
                        }
                    }
                }]
            }).render();

            const socialNetworkRate = JSON.parse('<?php echo json_encode($socialNetworkRate[0]); ?>');
            new ApexCharts(document.querySelector("#social-network-rate"), {
                chart: {
                    height: 350,
                    type: "pie"
                },
                labels: ["Tiktok", "Facebook", "Youtube", "Web", "Được giới thiệu"],
                series: [socialNetworkRate.tiktok_rate, socialNetworkRate.facebook_rate, socialNetworkRate.youtube_rate, socialNetworkRate.web_rate,socialNetworkRate.referral_rate],
                colors: ["#212529", "#4680FF", "#e23232", "#e58a00","#3ec9d6",],
                fill: {
                    opacity: [.8, .8, .8, .8, .8]
                },
                legend: {
                    show: !1
                },
                dataLabels: {
                    enabled: !0,
                    dropShadow: {
                        enabled: !1
                    }
                },
                responsive: [{
                    breakpoint: 575,
                    options: {
                        chart: {
                            height: 250
                        },
                        dataLabels: {
                            enabled: !1
                        }
                    }
                }]
            }).render();
        });
    </script>
@endsection
