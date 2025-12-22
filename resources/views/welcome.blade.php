@include('layouts.welcome')
@detect('mobile')
    <p>This content is for mobile users.</p>
@elsedetect('desktop')
    <div class="content" style="position: relative; width: 100vw; height: 100vh; overflow: hidden;">
        <div class="flex-center position-ref full-height" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;">
            <div class="top-right links" style="position: absolute; top: 20px; right: 20px; z-index: 1;">
                <a style="color: white; font-size: 18px; text-decoration: none;" href="{{ route('user.login') }}">Login</a>
            </div>

            <div class="content">
                <div class="title m-b-md">
                    <div class="clockStyle" id="clock">123</div>
                </div>
    
                
            </div>
        </div>
    </div>
@enddetect
