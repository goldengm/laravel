<div class="sidebar">
    <div class="widget block-categories">
        <div class="block-title">
            <h2>Info Pages</h2>
        </div>
        <div class="block-content">
            <ul class="list-categories">
            
                @if(count($result['commonContent']['pages']))
                @foreach($result['commonContent']['pages'] as $page)
                    <li> <a href="{{ URL::to('/page?name='.$page->slug)}}">{{$page->name}}</a> </li>
                @endforeach
                @endif
                
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