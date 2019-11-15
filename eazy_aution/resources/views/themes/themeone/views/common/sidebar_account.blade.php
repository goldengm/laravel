<div class="sidebar">
    <div class="widget block-categories">
        <div class="block-title">
            <h2>@lang('website.My Account')</h2>
        </div>
        <div class="block-content">
            <ul class="list-categories">
                <li>
                    <a href="{{ URL::to('/add-listing')}}">@lang('website.PostListing')</a>
                </li>
                <li>
                    <a href="{{ URL::to('/profile')}}">@lang('website.Profile')</a>
                </li>
                <li>
                    <a href="{{ URL::to('/wishlist')}}">@lang('website.Wishlist')</a>
                </li>
                <li>
                    <a href="{{ URL::to('/messages')}}">@lang('website.Messages')</a>
                </li>
                <li>
                    <a href="{{ URL::to('/orders')}}">@lang('website.Orders')</a>
                </li>
                <li>
                    <a href="{{ URL::to('/shipping-address')}}">@lang('website.Shipping Address')</a>
                </li>
                <li>
                    <a href="{{ URL::to('/logout')}}">@lang('website.Logout')</a>
                </li>
            </ul>
        </div>
    </div>
    <div class="widget block-images">
        @if(count($result['commonContent']['homeBanners'])>0)
            <ul class="list-images ">
            @foreach(($result['commonContent']['homeBanners']) as $homeBanners)                
                @if($homeBanners->type==3 or $homeBanners->type==4 or $homeBanners->type==5)
                <li> <a title="Banner Image" href="{{ $homeBanners->banners_url}}"><img src="{{asset('').$homeBanners->banners_image}}" alt="image"></a></li>                    
                @endif                
            @endforeach
            </ul>
        @endif
    </div>
</div>