<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="My Monitor - Track your transactions, salary, rent, food, and expenses easily.">
    <meta name="theme-color" content="#6a11cb">

    <link rel="shortcut icon" type="image/png" href="/images/favicon.png">

    <title>{{ env("APP_NAME") }}</title>

    <!-- Google Fonts: Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

    <!-- Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>

    <!-- Custom Styles -->
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #6a11cb, #2575fc);
            --secondary-gradient: linear-gradient(180deg, #1a1a2e 0%, #16213e 100%);
            --text-primary: #2c3e50;
            --text-secondary: #6c757d;
            --bg-light: #f4f4f9;
            --amber-soft: #f59e0b;
            --amber-bg: #fbbf24;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: var(--bg-light);
            /* Fallback */
            background: var(--primary-gradient);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            overflow-x: hidden;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
        }

        ::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.5);
        }

        /* Smooth Scrolling */
        html {
            scroll-behavior: smooth;
        }

        /* Global Utility Classes */
        .fw-medium {
            font-weight: 500;
        }

        .fw-semibold {
            font-weight: 600;
        }

        .shadow-soft {
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- jQuery (Required by Select2) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

</head>

<body>
    @if(auth()->user())
        @include('layouts.navigation')
    @endif