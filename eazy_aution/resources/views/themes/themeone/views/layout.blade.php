<!doctype html>

<html>

<!-- meta contains meta taga, css and fontawesome icons etc -->

@include('common.meta')

<!-- ./end of meta -->

<!--dir="rtl"-->

<body dir="{{ session('direction')}}">
	<!-- header -->

		@if(session('homeStyle')=='two' )
        	@include('common.header_two')
            @if(Request::path() == 'index' or Request::path() == '/')
            <section class="carousel-content">
              <div class="container">
                <div class="row">
                  <div class="col-12 col-lg-9 p-0"> @include('common.carousel') </div>
                  <div class="col-12 col-lg-3 p-0"> @include('common.offers') </div>
                </div>
              </div>
            </section>
            @endif
        @elseif(session('homeStyle')=='three' )
        	@include('common.header_three')
            @if(Request::path() == 'index' or Request::path() == '/')
            <section class="carousel-content">
              <div class="container">
                <div class="row">
                  <div class="col-12 p-0"> @include('common.carousel') </div>
                </div>
              </div>
            </section>
            @endif                 
       
        @else
       		@include('common.header')
            @if(Request::path() == 'index' or Request::path() == '/')
            <section class="carousel-content">
              <div class="container">
                <div class="row">
                  <div class="col-12 col-lg-3 p-0"> @include('common.categories') </div>
                  <div class="col-12 col-lg-9 p-0"> @include('common.carousel') </div>
                </div>
              </div>
            </section>
            @endif
        @endif
	<!-- ./end of header -->
        
        

	@yield('content')
	

	<section class="banner-content">
    	@include('common.banner')
    </section>
    
    @include('common.footer')
	<!-- all js scripts including custom js -->

	@include('common.scripts')

    <!-- ./end of js scripts -->
    @if(!empty($result['commonContent']['setting'][77]->value))
		<?=stripslashes($result['commonContent']['setting'][77]->value)?>
    @endif
</body>

</html>

