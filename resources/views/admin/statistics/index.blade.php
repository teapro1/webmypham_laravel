@extends('layouts.admin')

@section('title', 'Thống Kê')

@section('content')
<div class="container">
    <h1 class="mb-4 text-center text-primary">Thống Kê</h1>
    <div class="row">
     <div class="row mt-4">

        <!-- Cột Tổng Tiền -->
        <div class="col-md-4">
            <div class="card border-primary mb-4 shadow rounded">
                <div class="card-body text-center">
                    <i class="fas fa-dollar-sign fa-3x text-primary"></i>
                    <h5 class="card-title mt-3">Tổng Tiền</h5>
                    <p class="card-text">{{ number_format($totalRevenue, 0) }} VNĐ</p>
                </div>
            </div>
        </div>

        <!-- Cột Tổng Đơn Hàng -->
        <div class="col-md-4">
            <div class="card border-success mb-4 shadow rounded">
                <div class="card-body text-center">
                    <i class="fas fa-shopping-basket fa-3x text-success"></i>
                    <h5 class="card-title mt-3">Tổng Đơn Hàng</h5>
                    <p class="card-text">{{ $totalOrders }} Đơn</p>
                </div>
            </div>
        </div>

        <!-- Cột Tổng Khách Hàng -->
        <div class="col-md-4">
            <div class="card border-info mb-4 shadow rounded">
                <div class="card-body text-center">
                    <i class="fas fa-users fa-3x text-info"></i>
                    <h5 class="card-title mt-3">Tổng Khách Hàng</h5>
                    <p class="card-text">{{ $totalCustomers }} Khách</p>
                </div>
            </div>
        </div>

        <!-- Cột Số Đơn Đã Thanh Toán -->
        <div class="col-md-4">
            <div class="card border-warning mb-4 shadow rounded">
                <div class="card-body text-center">
                    <i class="fas fa-money-check-alt fa-3x text-warning"></i>
                    <h5 class="card-title mt-3">Đơn Đã Thanh Toán</h5>
                    <p class="card-text">{{ array_sum($paidRevenueCount) }} Đơn</p>
                </div>
            </div>
        </div>

        <!-- Cột Số Đơn Đang Chờ Thanh Toán -->
        <div class="col-md-4">
            <div class="card border-danger mb-4 shadow rounded">
                <div class="card-body text-center">
                    <i class="fas fa-hourglass-half fa-3x text-danger"></i>
                    <h5 class="card-title mt-3">Đơn Chờ Thanh Toán</h5>
                    <p class="card-text">{{ array_sum($pendingRevenueCount) }} Đơn</p>
                </div>
            </div>
        </div>

        <!-- Cột Số Đơn Đã Hủy -->
        <div class="col-md-4">
            <div class="card border-secondary mb-4 shadow rounded">
                <div class="card-body text-center">
                    <i class="fas fa-times-circle fa-3x text-secondary"></i>
                    <h5 class="card-title mt-3">Đơn Đã Hủy</h5>
                    <p class="card-text">{{ array_sum($canceledRevenueCount) }} Đơn</p>
                </div>
            </div>
        </div>

        <!-- Cột Số Đơn Đang Vận Chuyển -->
        <div class="col-md-4">
            <div class="card border-warning mb-4 shadow rounded">
                <div class="card-body text-center">
                    <i class="fas fa-shipping-fast fa-3x text-warning"></i>
                    <h5 class="card-title mt-3">Đơn Đang Vận Chuyển</h5>
                    <p class="card-text">{{ $totalShippingOrders }} Đơn</p>
                </div>
            </div>
        </div>

        <!-- Cột Số Đơn Đã Giao -->
        <div class="col-md-4">
            <div class="card border-danger mb-4 shadow rounded">
                <div class="card-body text-center">
                    <i class="fas fa-box-open fa-3x text-danger"></i>
                    <h5 class="card-title mt-3">Đơn Đã Giao</h5>
                    <p class="card-text">{{ $totalShippedOrders }} Đơn</p>
                </div>
            </div>
        </div>

        <!-- Cột Số Đơn Đã Nhận -->
        <div class="col-md-4">
            <div class="card border-secondary mb-4 shadow rounded">
                <div class="card-body text-center">
                    <i class="fas fa-receipt fa-3x text-secondary"></i>
                    <h5 class="card-title mt-3">Đơn Đã Nhận</h5>
                    <p class="card-text">{{ $totalDeliveredOrders }} Đơn</p>
                </div>
            </div>
        </div>

        <!-- Cột Số Tiền Đơn Đã Thanh Toán -->
        <div class="col-md-4">
            <div class="card border-success mb-4 shadow rounded">
                <div class="card-body text-center">
                    <i class="fas fa-dollar-sign fa-3x text-success"></i>
                    <h5 class="card-title mt-3">Tiền Đơn Đã Thanh Toán</h5>
                    <p class="card-text">{{ number_format($totalPaidRevenue, 0) }} VNĐ</p>
                </div>
            </div>
        </div>

        <!-- Cột Số Tiền Đơn Đang Chờ Thanh Toán -->
        <div class="col-md-4">
            <div class="card border-warning mb-4 shadow rounded">
                <div class="card-body text-center">
                    <i class="fas fa-hourglass-half fa-3x text-warning"></i>
                    <h5 class="card-title mt-3">Tiền Đơn Đang Chờ Thanh Toán</h5>
                    <p class="card-text">{{ number_format($totalPendingRevenue, 0) }} VNĐ</p>
                </div>
            </div>
        </div>

        <!-- Cột Số Tiền Đơn Đã Hủy -->
        <div class="col-md-4">
            <div class="card border-danger mb-4 shadow rounded">
                <div class="card-body text-center">
                    <i class="fas fa-times-circle fa-3x text-danger"></i>
                    <h5 class="card-title mt-3">Tiền Đơn Đã Hủy</h5>
                    <p class="card-text">{{ number_format($totalCanceledRevenue, 0) }} VNĐ</p>
                </div>
            </div>
        </div>

        <!-- Cột Số Tiền Đơn Đang Vận Chuyển -->
        <div class="col-md-4">
            <div class="card border-warning mb-4 shadow rounded">
                <div class="card-body text-center">
                    <i class="fas fa-truck-loading fa-3x text-warning"></i>
                    <h5 class="card-title mt-3">Tiền Đơn Đang Vận Chuyển</h5>
                    <p class="card-text">{{ number_format($totalShippingRevenue, 0) }} VNĐ</p>
                </div>
            </div>
        </div>

        <!-- Cột Số Tiền Đơn Đã Giao -->
        <div class="col-md-4">
            <div class="card border-danger mb-4 shadow rounded">
                <div class="card-body text-center">
                    <i class="fas fa-box-open fa-3x text-danger"></i>
                    <h5 class="card-title mt-3">Tiền Đơn Đã Giao</h5>
                    <p class="card-text">{{ number_format($totalShippedRevenue, 0) }} VNĐ</p>
                </div>
            </div>
        </div>

        <!-- Cột Số Tiền Đơn Đã Nhận -->
        <div class="col-md-4">
            <div class="card border-secondary mb-4 shadow rounded">
                <div class="card-body text-center">
                    <i class="fas fa-receipt fa-3x text-secondary"></i>
                    <h5 class="card-title mt-3">Tiền Đơn Đã Nhận</h5>
                    <p class="card-text">{{ number_format($totalDeliveredOrders, 0) }} VNĐ</p>
                </div>
            </div>
        </div>

    </div>



 <!-- Thống Kê Doanh Thu Theo Phương Thức Thanh Toán -->
<div class="col-md-12 mt-4">
    <h5 class="text-center">Thống Kê Doanh Thu Theo Phương Thức Thanh Toán</h5>
    <select id="paymentChartType" class="form-select mb-4">
        <option value="paid">Đã Thanh Toán</option>
        <option value="pending">Đang Chờ Thanh Toán</option>
        <option value="canceled">Đã Hủy</option>
        <option value="shipping">Đang Vận Chuyển</option>
        <option value="shipped">Đã Vận Chuyển</option>
        <option value="delivered">Đã Giao Hàng</option>
    </select>
    <div class="card rounded" style="height: 400px;">
        <div class="card-body" style="height: 100%;">
            <canvas id="paymentMethodChart" style="height: 100%; border-radius: 15px;"></canvas>
        </div>
    </div>
</div>
 <!-- Biểu đồ Thống Kê Theo Tháng -->
<div class="col-md-12 mt-4">
<h5 class="text-center">Thống Kê Doanh Thu Theo Tình Trạng Đơn</h5>
    <select id="chartType" class="form-select mb-4">
        <option value="total">Doanh Thu Tổng</option>
         <option value="paid">Đã Thanh Toán</option>
        <option value="pending">Đang Chờ Thanh Toán</option>
        <option value="canceled">Đã Hủy</option>
        <option value="shipping">Đang Vận Chuyển</option>
        <option value="shipped">Đã Vận Chuyển</option>
        <option value="delivered">Đã Giao Hàng</option>
    </select>

    <div class="card rounded" style="height: 400px;">
        <div class="card-body" style="height: 100%;">
            <h5 class="card-title">Thống Kê Theo Tháng</h5>
            <canvas id="mainChart" style="height: 100%; border-radius: 15px;"></canvas>
        </div>
    </div>
</div>

<!-- Biểu Đồ Tỷ Lệ Đơn Hàng -->
<div class="col-md-6 mt-4">
<h5 class="text-center">Thống Kê Tỷ Lệ</h5>
    <div class="card rounded" style="height: 300px;">
        <div class="card-body" style="height: 100%;">
            <h5 class="card-title">Tỷ Lệ Đơn Hàng</h5>
            <canvas id="statusChart" style="width: 100%; height: 100%; border-radius: 15px;"></canvas>
        </div>
    </div>
</div>


<!-- Thêm Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<style>
    .chartjs-render-monitor {
        font-family: 'Arial', sans-serif;
    }
    canvas {
        display: block;
        max-height: 100%;
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    var months = {!! json_encode($months ?? []) !!};
    var monthlyRevenue = {!! json_encode($revenueData ?? []) !!};
    var paidOrdersMonthly = {!! json_encode($paidRevenueCount ?? []) !!};
    var pendingOrdersMonthly = {!! json_encode($pendingRevenueCount ?? []) !!};
    var canceledOrdersMonthly = {!! json_encode($canceledRevenueCount ?? []) !!};
    var shippingOrdersMonthly = {!! json_encode($shippingRevenueCount ?? []) !!};
    var shippedOrdersMonthly = {!! json_encode($shippedRevenueCount ?? []) !!};
    var deliveredOrdersMonthly = {!! json_encode($deliveredRevenueCount ?? []) !!};

   
    var paidRevenueMonthly = {!! json_encode($paidRevenueMonthly ?? []) !!}; 
    var pendingRevenueMonthly = {!! json_encode($pendingRevenueMonthly ?? []) !!}; 
    var canceledRevenueMonthly = {!! json_encode($canceledRevenueMonthly ?? []) !!};
    var shippingRevenueMonthly = {!! json_encode($shippingRevenueMonthly ?? []) !!};
    var shippedRevenueMonthly = {!! json_encode($shippedRevenueMonthly ?? []) !!};
    var deliveredRevenueMonthly = {!! json_encode($deliveredRevenueMonthly ?? []) !!};

    var paymentMethods = {!! json_encode(array_keys($paymentMethodsRevenue)) !!};
    var paymentRevenues = {!! json_encode(array_values($paymentMethodsRevenue)) !!};

    var paymentRevenueData = {!! json_encode($paymentRevenueData) !!}; // Dữ liệu doanh thu theo phương thức thanh toán


    var totalData = months.map(m => monthlyRevenue[m] || 0);
    var totalOrdersMonthly = months.map(m => {
        return (paidOrdersMonthly[m] || 0) + (pendingOrdersMonthly[m] || 0) + (canceledOrdersMonthly[m] || 0) + (shippingOrdersMonthly[m] || 0)+(shippedOrdersMonthly[m] || 0)+(deliveredOrdersMonthly[m] || 0);
    });

    function ucfirst(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);}
// Dữ liệu doanh thu theo phương thức thanh toán
  document.addEventListener("DOMContentLoaded", function () {
        const ctxPayment = document.getElementById('paymentMethodChart').getContext('2d');

        function updatePaymentChart(status) {
            const labels = Object.keys(paymentRevenueData[status]);
            const data = Object.values(paymentRevenueData[status]);

            const paymentMethodChart = new Chart(ctxPayment, {
                type: 'bar',
                data: {
                    labels: labels.map(method => ucfirst(method)),
                    datasets: [{
                        label: 'Doanh Thu',
                        data: data,
                        backgroundColor: 'rgba(75, 192, 192, 0.6)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1,
                        borderRadius: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Doanh Thu (VNĐ)',
                            },
                            ticks: {
                                callback: function(value) {
                                    return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.') + ' VNĐ';
                                }
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Phương Thức Thanh Toán'
                            }
                        }
                    }
                }
            });

            return paymentMethodChart;
        }

        // Khởi tạo biểu đồ với trạng thái đã thanh toán
        let paymentChart = updatePaymentChart('paid');

        // Xử lý sự kiện thay đổi dropdown
        document.getElementById('paymentChartType').addEventListener('change', function () {
            const selectedStatus = this.value;
            paymentChart.destroy(); // Hủy biểu đồ cũ
            paymentChart = updatePaymentChart(selectedStatus); // Tạo biểu đồ mới
        });
    });
    document.addEventListener("DOMContentLoaded", function () {
        const ctxMain = document.getElementById('mainChart').getContext('2d');

        const mainChart = new Chart(ctxMain, {
            type: 'bar',
            data: {
                labels: months.map(m => `Tháng ${m}`),
                datasets: [{
                    label: 'Doanh Thu',
                    data: totalData,
                    backgroundColor: 'rgba(75, 192, 192, 0.6)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1,
                    borderRadius: 8,
                    yAxisID: 'yRevenue'
                }, {
                    label: 'Số Đơn Hàng',
                    data: totalOrdersMonthly,
                    backgroundColor: 'rgba(255, 99, 132, 0.6)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1,
                    borderRadius: 8,
                    yAxisID: 'yOrders'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    yRevenue: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Doanh Thu (VNĐ)',
                        },
                        position: 'left'
                    },
                    yOrders: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Số Đơn Hàng',
                        },
                        position: 'right',
                        grid: {
                            drawOnChartArea: false
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Tháng'
                        }
                    }
                },
                plugins: {
                    legend: {
                        labels: {
                            font: {
                                size: 14,
                                weight: 'bold'
                            }
                        }
                    }
                }
            }
        });

        // Biểu đồ tỷ lệ đơn hàng
        const ctxStatus = document.getElementById('statusChart').getContext('2d');
        const statusChart = new Chart(ctxStatus, {
            type: 'doughnut',
            data: {
                labels: ['Đã Thanh Toán', 'Đang Chờ Thanh Toán', 'Đã Hủy','Đang Giao Hàng','Đã Giao Hàng','Đã Nhận Hàng'],
                datasets: [{
                    data: [
                        months.reduce((sum, m) => sum + (paidRevenueMonthly[m] || 0), 0) / 100,
                        months.reduce((sum, m) => sum + (pendingRevenueMonthly[m] || 0), 0) / 100,
                        months.reduce((sum, m) => sum + (canceledRevenueMonthly[m] || 0), 0) / 100,
                        months.reduce((sum, m) => sum + (shippingRevenueMonthly[m] || 0), 0) / 100,
                        months.reduce((sum, m) => sum + (shippedRevenueMonthly[m] || 0), 0) / 100,
                        months.reduce((sum, m) => sum + (deliveredRevenueMonthly[m] || 0), 0) / 100
                    ],
                    backgroundColor: [
    'rgba(255, 99, 132, 0.8)', 
    'rgba(54, 162, 235, 0.8)', 
    'rgba(255, 206, 86, 0.8)', 
    'rgba(75, 192, 192, 0.8)',
    'rgba(153, 102, 255, 0.8)',
    'rgba(255, 159, 64, 0.8)' 
],
                    borderColor: ['#ffffff', '#ffffff', '#ffffff','#ffffff', '#ffffff', '#ffffff'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '50%',
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom',
                        labels: {
                            boxWidth: 20,
                            padding: 25,
                            font: {
                                size: 15,
                                weight: 'bold'
                            }
                        }
                    }
                }
            }
        });

        // Xử lý sự kiện thay đổi biểu đồ
        document.getElementById('chartType').addEventListener('change', function () {
            const selectedType = this.value;

            switch (selectedType) {
                case 'paid':
                    mainChart.data.datasets[0].data = months.map(m => paidRevenueMonthly[m] || 0);
                    mainChart.data.datasets[1].data = months.map(m => paidOrdersMonthly[m] || 0);
                    mainChart.data.datasets[0].label = 'Doanh Thu Đơn Đã Thanh Toán';
                    mainChart.data.datasets[1].label = 'Số Đơn Đã Thanh Toán';
                    break;
                case 'pending':
                    mainChart.data.datasets[0].data = months.map(m => pendingRevenueMonthly[m] || 0);
                    mainChart.data.datasets[1].data = months.map(m => pendingOrdersMonthly[m] || 0);
                    mainChart.data.datasets[0].label = 'Doanh Thu Đơn Đang Chờ Thanh Toán';
                    mainChart.data.datasets[1].label = 'Số Đơn Đang Chờ Thanh Toán';
                    break;
                case 'canceled':
                    mainChart.data.datasets[0].data = months.map(m => canceledRevenueMonthly[m] || 0);
                    mainChart.data.datasets[1].data = months.map(m => canceledOrdersMonthly[m] || 0);
                    mainChart.data.datasets[0].label = 'Doanh Thu Đơn Đã Hủy';
                    mainChart.data.datasets[1].label = 'Số Đơn Đã Hủy';
                    break;
                case 'shipping':
                    mainChart.data.datasets[0].data = months.map(m => shippingRevenueMonthly[m] || 0);
                    mainChart.data.datasets[1].data = months.map(m => shippingOrdersMonthly[m] || 0);
                    mainChart.data.datasets[0].label = 'Doanh Thu Đơn Đang Giao Hàng';
                    mainChart.data.datasets[1].label = 'Số Đơn Đang Giao Hàng';
                    break;
                case 'shipped':
                    mainChart.data.datasets[0].data = months.map(m => shippedRevenueMonthly[m] || 0);
                    mainChart.data.datasets[1].data = months.map(m => shippedOrdersMonthly[m] || 0);
                    mainChart.data.datasets[0].label = 'Doanh Thu Đơn Đã Giao Hàng';
                    mainChart.data.datasets[1].label = 'Số Đơn Đơn Đã Giao Hàng';
                    break;
                case 'delivered':
                    mainChart.data.datasets[0].data = months.map(m => deliveredRevenueMonthly[m] || 0);
                    mainChart.data.datasets[1].data = months.map(m => deliveredOrdersMonthly[m] || 0);
                    mainChart.data.datasets[0].label = 'Doanh Thu Đơn Đã Nhận';
                    mainChart.data.datasets[1].label = 'Số Đơn Đã Nhận';
                    break;
                default:
                    mainChart.data.datasets[0].data = totalData;
                    mainChart.data.datasets[1].data = totalOrdersMonthly;
                    mainChart.data.datasets[0].label = 'Doanh Thu Tổng';
                    mainChart.data.datasets[1].label = 'Số Đơn Tổng';
                    break;
            }
            
            mainChart.update();
        });
    });
</script>

@endsection
