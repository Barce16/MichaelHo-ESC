<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Booking Confirmed - Michael Ho Events</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Libre+Caslon+Display&family=Style+Script&display=swap"
        rel="stylesheet">
    <style>
        .font-libre {
            font-family: 'Libre Caslon Display', serif;
        }

        .font-style-script {
            font-family: 'Style Script', cursive;
        }

        body {
            background-color: #f9fafb;
            position: relative;
            min-height: 100vh;
        }

        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('{{ asset("images/background.png") }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            opacity: 0.2;
            z-index: -1;
        }

        @keyframes checkmark {
            0% {
                stroke-dashoffset: 100;
            }

            100% {
                stroke-dashoffset: 0;
            }
        }

        @keyframes scaleIn {
            0% {
                transform: scale(0);
                opacity: 0;
            }

            50% {
                transform: scale(1.1);
            }

            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        .checkmark-circle {
            animation: scaleIn 0.5s ease-out forwards;
        }

        .checkmark-check {
            stroke-dasharray: 100;
            stroke-dashoffset: 100;
            animation: checkmark 0.8s 0.3s ease-out forwards;
        }
    </style>
</head>

<body>
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <!--  elements -->
        <div
            class="absolute top-0 left-0 w-64 h-64 bg-gradient-to-br from-slate-100 to-transparent rounded-full blur-3xl opacity-40">
        </div>
        <div
            class="absolute bottom-0 right-0 w-96 h-96 bg-gradient-to-tl from-slate-100 to-transparent rounded-full blur-3xl opacity-40">
        </div>

        <div class="max-w-2xl w-full space-y-8 relative z-10">
            <!-- Success Card -->
            <div
                class="bg-white/90 backdrop-blur-sm rounded-2xl shadow-xl p-8 md:p-12 text-center border border-gray-100">
                <!-- Success Icon -->
                <div class="flex justify-center mb-6">
                    <svg class="w-24 h-24" viewBox="0 0 100 100">
                        <circle class="checkmark-circle" cx="50" cy="50" r="45" fill="none" stroke="#10b981"
                            stroke-width="4" />
                        <path class="checkmark-check" fill="none" stroke="#10b981" stroke-width="6"
                            stroke-linecap="round" stroke-linejoin="round" d="M30 50 L45 65 L70 35" />
                    </svg>
                </div>

                <!-- Logo -->
                <a href="{{ url('/') }}" class="inline-block mb-6">
                    <img src="{{ asset('images/favicon.png') }}" alt="Logo" class="h-16 mx-auto">
                </a>

                <!-- Title -->
                <h1 class="text-3xl md:text-4xl font-bold font-libre text-gray-900 mb-4">
                    Booking Request Received!
                </h1>

                <!-- Message -->
                <div class="space-y-4 text-gray-600 mb-8">
                    <p class="text-lg">
                        Thank you for choosing <span class="font-semibold text-gray-900">Michael Ho Events</span>!
                    </p>
                    <p>
                        Your booking request has been successfully submitted. Our team will review your request and
                        contact you shortly to confirm the details.
                    </p>
                    @if(session('success'))
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4 text-green-800">
                        <p class="text-sm">{{ session('success') }}</p>
                    </div>
                    @endif
                </div>

                <!-- What's Next -->
                <div class="bg-slate-50 rounded-lg p-6 mb-8 text-left">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4 text-center">What happens next?</h2>
                    <ul class="space-y-3 text-gray-700">
                        <li class="flex items-start gap-3">
                            <svg class="w-6 h-6 text-green-600 flex-shrink-0 mt-0.5" fill="currentColor"
                                viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd" />
                            </svg>
                            <span><strong>Step 1:</strong> Our team will review your booking request within 24
                                hours</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-6 h-6 text-green-600 flex-shrink-0 mt-0.5" fill="currentColor"
                                viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd" />
                            </svg>
                            <span><strong>Step 2:</strong> We'll contact you via email or phone to discuss your event
                                details</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-6 h-6 text-green-600 flex-shrink-0 mt-0.5" fill="currentColor"
                                viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd" />
                            </svg>
                            <span><strong>Step 3:</strong> You'll receive login credentials via email to access your
                                customer dashboard where you can monitor your booking status in real-time</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-6 h-6 text-green-600 flex-shrink-0 mt-0.5" fill="currentColor"
                                viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd" />
                            </svg>
                            <span><strong>Step 4:</strong> Once confirmed, you'll receive a detailed proposal and
                                downpayment instructions</span>
                        </li>
                    </ul>
                </div>

                <!-- Contact Info -->
                <div class="bg-gray-50 rounded-lg p-6 mb-8">
                    <h3 class="font-semibold text-gray-900 mb-3">Need immediate assistance?</h3>
                    <div class="space-y-2 text-sm text-gray-600">
                        <p class="flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            <a href="mailto:michaelhoevents@gmail.com"
                                class="text-indigo-600 hover:text-indigo-800">michaelhoevents@gmail.com</a>
                        </p>
                        <p class="flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                            <a href="tel:+639173062531" class="text-indigo-600 hover:text-indigo-800">+63 917 306
                                2531</a>
                        </p>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ url('/') }}"
                        class="px-8 py-3 bg-slate-600 text-white font-semibold rounded-lg hover:bg-slate-700 transition shadow-md">
                        Back to Home
                    </a>
                    <a href="{{ route('services.index') }}"
                        class="px-8 py-3 bg-white text-slate-700 font-semibold rounded-lg hover:bg-gray-50 transition shadow-md border-2 border-slate-300">
                        View All Packages
                    </a>
                </div>

                <!-- Footer Note -->
                <p class="mt-8 text-lg text-gray-500 font-style-script">
                    Creating memories, one event at a time
                </p>
            </div>
        </div>
    </div>
</body>

</html>