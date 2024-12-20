@extends('layouts.app')

@section('content')
    <h1>Đơn hàng chưa thanh toán</h1>

    @if (isset($pendingOrders) && $pendingOrders->isEmpty())
        <p>Không có đơn hàng chưa thanh toán.</p>
    @elseif (isset($pendingOrders))
        @foreach ($pendingOrders as $order)
            <div>
                <h2>Thanh Toán Đơn Hàng</h2>
                <p>Mã đơn hàng: {{ $order->id }}</p>
                <p>Tổng tiền: {{ number_format($order->total, 0, ',', '.') }} VNĐ</p>

                <form action="{{ route('customer.checkout.retry', $order->id) }}" method="GET">
                    @csrf
                    <button type="submit" class="btn btn-primary">Thanh toán lại</button>
                </form>
            </div>
        @endforeach
    @else
        <p>Đã xảy ra lỗi khi tải đơn hàng. Vui lòng thử lại.</p>
    @endif
@endsection
