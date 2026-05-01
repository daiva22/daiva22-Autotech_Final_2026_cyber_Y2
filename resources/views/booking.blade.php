@extends('layouts.app')

@section('title', 'Book a Service')

@section('content')

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<div class="booking-container">
    <h1>Book a Service</h1>

    @if(session('success'))
        <div class="alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert-error">
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert-error">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @auth
    <form action="{{ route('booking.store') }}" method="POST" id="bookingForm">
        @csrf

        <div class="form-group">
            <label for="service_id">Select Service</label>
            <select name="service_id" id="service_id" required>
                <option value="">-- Choose a Service --</option>
                @foreach($services as $service)
                    <option value="{{ $service->id }}">
                        {{ $service->name }} - Rs {{ number_format($service->price, 2) }} ({{ $service->duration_minutes }} mins)
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="car_brand">Car Brand</label>
            <input type="text" name="car_brand" id="car_brand" placeholder="Example: Toyota" required>
        </div>

        <div class="form-group">
            <label for="car_model">Car Model</label>
            <input type="text" name="car_model" id="car_model" placeholder="Example: Corolla" required>
        </div>

        <div class="form-group">
            <label for="date">Booking Date</label>
            <input type="text" name="date" id="date" placeholder="Select a date" required readonly>
            <div class="helper-text">
                Closed dates and full dates are disabled automatically.
            </div>
        </div>

        <div class="form-group">
            <label>Available Time Slots</label>

            <input type="hidden" name="time" id="time" required>

            <div class="slot-box">
                <div class="slot-status" id="slotStatus">
                    Select a service first, then choose a date.
                </div>

                <div class="slot-grid" id="slotGrid"></div>

                <div class="selected-slot-preview" id="selectedSlotPreview"></div>
            </div>
        </div>

        <div class="form-group">
            <label for="notes">Notes</label>
            <textarea name="notes" id="notes" placeholder="Extra details..."></textarea>
        </div>

        <button type="submit" class="btn-book" id="submitButton" disabled>
            Book Now
        </button>
    </form>

    @else
        <div class="login-required-box">
            <div class="login-icon">🔒</div>

            <h2>Login Required</h2>

            <p>
                You must be logged in before you can book a service.
                Please login to continue with your booking.
            </p>

            <a href="{{ route('account') }}" class="btn-login">
                Login to Continue
            </a>
        </div>
    @endauth
</div>

@endsection


@section('scripts')

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const serviceSelect = document.getElementById('service_id');
    const dateInput = document.getElementById('date');
    const timeInput = document.getElementById('time');
    const slotGrid = document.getElementById('slotGrid');
    const slotStatus = document.getElementById('slotStatus');
    const selectedSlotPreview = document.getElementById('selectedSlotPreview');
    const submitButton = document.getElementById('submitButton');

    let selectedTime = null;

    function resetSlots(message = 'Select a service first, then choose a date.') {
        slotGrid.innerHTML = '';
        timeInput.value = '';
        selectedTime = null;
        selectedSlotPreview.textContent = '';
        slotStatus.textContent = message;
        setSubmitState();
    }

    function setSubmitState() {
        submitButton.disabled = !(serviceSelect.value && dateInput.value && timeInput.value);
    }

    function renderSlots(slots) {
        slotGrid.innerHTML = '';
        timeInput.value = '';
        selectedTime = null;
        selectedSlotPreview.textContent = '';

        if (!slots || slots.length === 0) {
            slotStatus.textContent = 'No available time slots for this date.';
            setSubmitState();
            return;
        }

        slotStatus.textContent = 'Click one available time slot.';

        slots.forEach(slot => {
            const button = document.createElement('button');
            button.type = 'button';
            button.className = 'slot-btn';
            button.textContent = slot.label;
            button.dataset.value = slot.value;

            button.addEventListener('click', function () {
                document.querySelectorAll('.slot-btn').forEach(btn => {
                    btn.classList.remove('active');
                });

                this.classList.add('active');
                timeInput.value = this.dataset.value;
                selectedTime = this.dataset.value;

                selectedSlotPreview.textContent = 'Selected time: ' + this.textContent;

                setSubmitState();
            });

            slotGrid.appendChild(button);
        });

        setSubmitState();
    }

    function checkAvailability() {
        const serviceId = serviceSelect.value;
        const selectedDate = dateInput.value;

        resetSlots();

        if (!serviceId) {
            slotStatus.textContent = 'Please select a service first.';
            return;
        }

        if (!selectedDate) {
            slotStatus.textContent = 'Please choose a date.';
            return;
        }

        slotStatus.textContent = 'Checking availability...';

        fetch(`/booking/availability?service_id=${serviceId}&date=${selectedDate}`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            console.log(data);

            /*
                Expected JSON examples:

                If date is closed by admin:
                {
                    "closed": true,
                    "message": "This date is closed."
                }

                If date is open:
                {
                    "closed": false,
                    "slots": [
                        {"label": "09:00 AM", "value": "09:00"},
                        {"label": "10:00 AM", "value": "10:00"}
                    ]
                }
            */

            if (data.closed === true) {
                resetSlots(data.message || 'This date is closed by admin.');
                return;
            }

            if (data.full === true) {
                resetSlots(data.message || 'This date is fully booked.');
                return;
            }

            renderSlots(data.slots || []);
        })
        .catch(error => {
            console.error(error);
            resetSlots('Something went wrong while checking availability.');
        });
    }

    flatpickr("#date", {
        dateFormat: "Y-m-d",
        minDate: "today",
        disableMobile: true,
        onChange: function () {
            checkAvailability();
        }
    });

    serviceSelect.addEventListener('change', function () {
        dateInput.value = '';
        resetSlots('Now choose a booking date.');
    });

    setSubmitState();
});
</script>

@endsection