<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $general->sitename(__($pageTitle)) }}</title>
    @include('partials.seo')
    <link rel="icon" type="image/png" href="{{getImage(imagePath()['logoIcon']['path'] .'/favicon.png')}}" sizes="16x16">
    <link rel="stylesheet" href="{{asset($activeTemplateTrue . 'frontend/css/lib/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset($activeTemplateTrue . 'frontend/css/all.min.css')}}"> 
    <link rel="stylesheet" href="{{asset($activeTemplateTrue . 'frontend/css/line-awesome.min.css')}}"> 
    <link rel="stylesheet" href="{{asset($activeTemplateTrue . 'frontend/css/lib/slick.css')}}">
    <link rel="stylesheet" href="{{asset($activeTemplateTrue . 'frontend/css/main.css')}}">
    <link rel="stylesheet" href="{{asset($activeTemplateTrue . 'css/custom.css')}}">
    @stack('style-lib')
    @stack('style')
    <link href="{{ asset($activeTemplateTrue . 'css/color.php') }}?color={{$general->base_color}}&secondColor={{$general->secondary_color}}" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://fullcalendar.io/js/fullcalendar-2.2.5/fullcalendar.css">
    <script src="{{asset($activeTemplateTrue . 'frontend/js/lib/jquery-3.6.0.min.js')}}"></script>
</head>
<body>
    @stack('fbComment')
    {{auto_logout()}}
    <div class="preloader-holder">
        <div class="preloader"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>
    </div>
    @include($activeTemplate .'partials.header')
    @if(!request()->routeIs('home') && !request()->routeIs('candidate.profile') && !request()->routeIs('profile'))
        @include($activeTemplate . 'partials.breadcrumb')
    @endif
    <div class="main-wrapper">
        @yield('content')
    </div>
    @include($activeTemplate . 'partials.footer')
    
    <script src="{{asset($activeTemplateTrue . 'frontend/js/lib/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset($activeTemplateTrue . 'frontend/js/lib/slick.min.js')}}"></script>
    <script src="{{asset($activeTemplateTrue . 'frontend/js/lib/wow.min.js')}}"></script>
    <script src="{{asset($activeTemplateTrue . 'frontend/js/app.js')}}"></script>  
    <script src="https://fullcalendar.io/js/fullcalendar-2.2.5/lib/moment.min.js"></script> 
    <script src="https://fullcalendar.io/js/fullcalendar-2.2.5/fullcalendar.min.js"></script> 
    @stack('script-lib')
    @stack('script')
    @include('partials.plugins')
    @include('partials.notify')
    <script>
        (function ($) {
            "use strict";
            $(".langSel").on("change", function() {
                window.location.href = "{{route('home')}}/change/"+$(this).val() ;
            });
        })(jQuery);
    </script>


    <!--script>
/*  
Added by LS No Right Click/View Source 05/27/22
*/


//disable f12 key
$(document).keydown(function(e){
    if(e.which === 123){
       return false;
    }
});
//disable right click
$(document).bind("contextmenu",function(e) {
	e.preventDefault();
});

document.onkeydown = function(e) {
    if(event.keyCode == 123) {
        return false;
    }
    if(e.ctrlKey && e.keyCode == 'E'.charCodeAt(0)){
        return false;
    }
    if(e.ctrlKey && e.shiftKey && e.keyCode == 'I'.charCodeAt(0)){
        return false;
    }
    if(e.ctrlKey && e.shiftKey && e.keyCode == 'J'.charCodeAt(0)){
        return false;
    }
    if(e.ctrlKey && e.keyCode == 'U'.charCodeAt(0)){
        return false;
    }
    if(e.ctrlKey && e.keyCode == 'S'.charCodeAt(0)){
        return false;
    }
    if(e.ctrlKey && e.keyCode == 'H'.charCodeAt(0)){
        return false;
    }
    if(e.ctrlKey && e.keyCode == 'A'.charCodeAt(0)){
        return false;
    }
    if(e.ctrlKey && e.keyCode == 'F'.charCodeAt(0)){
        return false;
    }
    if(e.ctrlKey && e.keyCode == 'E'.charCodeAt(0)){
        return false;
    }
    if(e.ctrlKey && e.keyCode == 'P'.charCodeAt(0)){
        return false;
    }
    if(e.ctrlKey && e.keyCode == 'S'.charCodeAt(0)){
        return false;
    }
}

</script-->
</body>
</html> 
