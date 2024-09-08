<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gdood</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<body class="font-sans antialiased">
<!-- Main Container -->
<div class="min-h-screen bg-gray-50 text-black flex flex-col items-center -mt-8">
    <!-- Header and Title Section -->
    <div class="text-center py-12">
        <img src="/images/logo.png" alt="Logo" class="mx-auto mb-4 w-[320px;]"> <!-- Placeholder for the logo -->
        <h1 class="text-4xl sm:text-5xl md:text-6xl font-bold">
            We are <span class="text-blue-600">Almost</span> there!
        </h1>
        <p class="text-lg sm:text-xl mt-2 text-gray-600">Stay tuned for something amazing!!!</p>
        <div class="mt-4">
            @if (session('success'))
                <div class="bg-green-100 text-green-700 p-4 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="bg-red-100 text-red-700 p-4 rounded mb-4">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
        <!-- Subscription Form -->
        <form action="{{ route('newsletter.subscribe') }}" method="POST" class="mt-8 max-w-lg mx-auto flex justify-center items-center border-b border-blue-500 py-2">
            @csrf
            <input
                type="email"
                placeholder="Enter your Email Address"
                class="appearance-none bg-transparent border-none w-full text-gray-700 py-2 px-4 leading-tight focus:outline-none"
                id="email_address"
                name="email_address"
                value="{{ old('email_address') }}"
                required>
            <button
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded ml-2"
                type="submit">
                Subscribe
            </button>
        </form>
    </div>

    <!-- Map and Contact Form Section -->
    <div class="container mx-auto px-4 py-10 grid gap-8 lg:grid-cols-2">
        <!-- Google Map Section -->
        <div class="w-full h-full">
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3622.594748047074!2d46.64435099999999!3d24.775080199999998!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3e2ee3131555e7b5%3A0xdd267451b99f24bf!2z2KzYp9iv2KkgSmFkYSAzMA!5e0!3m2!1sen!2ssa!4v1704815583979!5m2!1sen!2ssa"
                width="100%" height="350" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
        </div>

        <!-- Contact Form -->
        <div class="w-full bg-white shadow-md rounded-lg p-8">
            <form action="{{ route('contact.submit') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label for="name" class="block text-gray-700 font-bold">Name</label>
                    <input
                        type="text" id="name" name="name" placeholder="Your Name"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 focus:outline-none focus:shadow-outline"
                        value="{{ old('name') }}" required>
                </div>

                <div>
                    <label for="sender_email" class="block text-gray-700 font-bold">Email</label>
                    <input
                        type="email" id="sender_email" name="sender_email" placeholder="Your Email Address"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 focus:outline-none focus:shadow-outline"
                        value="{{ old('sender_email') }}" required>
                </div>

                <div>
                    <label for="message" class="block text-gray-700 font-bold">Message</label>
                    <textarea
                        id="message" name="message" placeholder="Your Message"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 focus:outline-none focus:shadow-outline"
                        required>{{ old('message') }}</textarea>
                </div>

                <!-- reCAPTCHA widget -->
                <div class="g-recaptcha" data-sitekey="{{ env('RECAPTCHA_SITE_KEY') }}"></div>

                <div class="mt-4">
                    <button
                        type="submit"
                        class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                    >
                        Send Message
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>
