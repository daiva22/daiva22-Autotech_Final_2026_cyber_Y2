@extends('layouts.app')

@section('title', 'Payment Successful')

@section('content')

<div class="cart-page">
    <div class="cart-container">
        <div class="cart-empty">

            <h1>Payment Done ✅</h1>

            <p>Your payment has been completed successfully.</p>
            <p>Your cart has been cleared.</p>

            <div class="cart-actions" style="margin-top: 30px; justify-content: center;">

                <a href="{{ route('home') }}" class="cart-link-btn cart-link-btn-primary">
                    Back to Home
                </a>

                <a href="{{ url('/#shop') }}" class="cart-link-btn">
                    Continue Shopping
                </a>

                <a href="{{ route('services.front.index') }}" class="cart-link-btn">
                    Book a Service
                </a>

            </div>

        </div>
    </div>
</div>

@endsection