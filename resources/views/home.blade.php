@extends('layouts.app')



@section('title', 'AUTOTECH')

@section('content')

<section class="full-bg" id="home">
  <div class="section-content hero-content">
    <h3>ABOUT US</h3>
    <h1>
      At AUTOTECH, we make cars louder,<br />
      sleeker, and better.
      From powerful speakers to tinted
      windows, custom stickers, and advanced scans - we've got your
      ride covered.
    </h1>
  </div>
</section>

<section class="shop-hero" id="shop">
  <h1 class="shop-title">Shop</h1>

  <div class="shop-cards">
    <a href="{{ url('/sound_system') }}" class="shop-card">
      <img src="{{ asset('images/sound-system.png') }}" alt="Sound System">
      <div class="card-label">Sound System</div>
    </a>

    <a href="{{ url('/accessories') }}" class="shop-card">
      <img src="{{ asset('images/Accessories.png') }}" alt="Accessories">
      <div class="card-label">Accessories</div>
    </a>

    <a href="{{ url('/packages') }}" class="shop-card">
      <img src="{{ asset('images/Packages.png') }}" alt="Packages">
      <div class="card-label">Packages</div>
    </a>
  </div>
</section>

<section class="services-hero" id="services">
  <h2 class="section-title">Services</h2>

  <div class="service-cards">
    @forelse($serviceCategories as $category)
      <a href="{{ route('services.front.category', $category->id) }}" class="service-category-card">

        @if($category->image)
          <img src="{{ asset($category->image) }}" class="service-category-image">
        @else
          <div class="service-category-no-image">
            {{ $category->name }}
          </div>
        @endif

        <div class="service-category-label">
          {{ $category->name }}
        </div>

      </a>
    @empty
      <p style="text-align:center;">No services available right now.</p>
    @endforelse
  </div>
</section>

<section class="contact-section" id="contact">
  <div class="contact-left">
    <h2>CONTACT US</h2>
    <p>For any query contact us using the details below.</p>

    <div class="contact-info">
      <span>Phone: XXXXXXXX</span>
      <span>Email: xxxxxxxxx@gmail.com</span>
      <span>Address: XXXXXXX</span>
      <span>Opening hours: 09:00 – 18:00</span>
    </div>
  </div>
</section>

@endsection