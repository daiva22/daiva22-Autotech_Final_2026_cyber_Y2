@extends('layouts.app')

@section('title', 'Payment')

@section('content')

<div class="cart-page">
    <div class="cart-container">
        <h1 class="cart-title">Payment</h1>

        @php
            $cart = session('cart', []);
            $total = 0;

            foreach ($cart as $item) {
                $total += $item['price'] * $item['quantity'];
            }
        @endphp

        @if(count($cart) > 0)

            <div class="cart-summary">
                <div class="cart-total">
                    Total Amount:
                    <span>Rs {{ number_format($total, 2) }}</span>
                </div>

                <p style="margin-top: 15px;">
                    Click the button below to complete your payment.
                </p>

                <!-- SIMPLE PAYMENT BUTTON -->
                <form action="{{ route('payment.success') }}" method="POST">
                    @csrf

                    <div class="cart-actions" style="margin-top: 20px;">
                        <a href="{{ route('cart') }}" class="cart-link-btn">
                            Back to Cart
                        </a>

                        <button type="submit" class="cart-link-btn cart-link-btn-primary">
                            Pay Now
                        </button>
                    </div>
                </form>

            </div>

        @else

            <div class="cart-empty">
                <h2>No items found</h2>
                <p>Your cart is empty. Please add items before payment.</p>

                <a href="{{ url('/#shop') }}" class="cart-link-btn">
                    Go to Shop
                </a>
            </div>

        @endif
    </div>
</div>

@endsection