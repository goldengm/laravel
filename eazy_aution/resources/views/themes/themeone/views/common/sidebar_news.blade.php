<div class="sidebar">
    <div class="widget block-categories">
        <div class="block-title">
            <h2>@lang('website.Categories')</h2>
        </div>
        <div class="block-content">
            <ul class="list-categories">
                @foreach($result['commonContent']['newsCategories'] as $categories) 
                    <li>
                        <a href="{{ URL::to('/news?category='.$categories->slug)}}">{{$categories->name}}</a>   
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
    @if($result['commonContent']['featuredNews']['success']==1)
    <div class="widget block-recent-posts">
        <div class="block-title">
            <h2>@lang('website.Featured News')</h2>
        </div>
        <div class="block-content">                                
         @foreach($result['commonContent']['featuredNews']['news_data'] as $key=>$news_data)
            <div class="media">
              <img class="img-fluid" src="{{asset('').$news_data->news_image}}" alt="{{$news_data->news_name}}">
              <div class="media-body">
                <h5 class="media-title"><a href="{{ URL::to('/news-detail/'.$news_data->news_slug)}}">{{$news_data->news_name}} </a> <span class="badge badge-success">@lang('website.Featured')</span></h5>
                <div class="media-content"><?=stripslashes($news_data->news_description)?></div>
                <em><?php
                        $timestamp = strtotime($news_data->news_date_added);
                        echo date('d M, Y',$timestamp);
                     ?></em>
              </div>
            </div>
         @endforeach
        </div>
    </div>
    @endif
</div>