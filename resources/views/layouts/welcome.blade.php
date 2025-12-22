<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Attendance Management System</title>
    <meta content="Admin Dashboard" name="description" />
    <meta content="Themesbrand" name="author" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="assets/images/">
    <link href="{{ URL::asset('assets/css/attendanceFront.css') }}" rel="stylesheet" type="text/css" />
    
    @include('layouts.head')
    <script>
        function updateDeviceType() {
            var isMobile = window.innerWidth <= 1024;
            document.body.setAttribute('data-device', isMobile ? 'mobile' : 'desktop');
    
            fetch('/set-device-type', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ isMobile })
            });
    
            if (isMobile && window.innerWidth > 1024) {
                document.body.innerHTML = '<p>Access denied. This application is only available on laptops or desktop devices.</p>';
            }
        }
    
        document.addEventListener('DOMContentLoaded', updateDeviceType);
        window.addEventListener('resize', updateDeviceType);
    </script>
    
</head>

<body class="pb-0" style="background:#2a3142;">
    @yield('content')
    @include('layouts.footer-script')
    @include('includes.flash')
    <script src="{{ URL::asset('assets/js/attendanceFront.js') }}"></script>
</body>

</html>
