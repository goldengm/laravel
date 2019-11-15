
@if($result['news']['success']==1)
@foreach($result['news']['news_data'] as $key=>$news_data)

    <div class="blog-post">
        <article>
            <div class="blog-thumb">
                @if($news_data->is_feature==1)
                    <span class="badge badge-success">@lang('website.Featured')</span>
                @endif
                <span class="blog-date">
                    <strong>
                        <?php
                            $timestamp = strtotime($news_data->news_date_added);
                            echo date('d',$timestamp);
                        ?>
                    </strong>
                    <?php
                        
                        echo date('M',$timestamp);
                    ?>
                </span>
                
                <div class="blog-overlay">
                    <a href="{{ URL::to('/news-detail/'.$news_data->news_slug)}}" class="fa fa-search-plus"></a>
                </div>
                
                <img class="img-fluid" src="{{asset('').$news_data->news_image}}" alt="{{$news_data->news_name}}">
            </div>
            <div class="blog-block">
            
                <a href="{{ URL::to('/news-detail/'.$news_data->news_slug)}}" class="blog-title">{{$news_data->news_name}}</a>
    
                <div class="blog-text">
                    <?=stripslashes($news_data->news_description)?>
                </div>
    
                <!--<a href="{{ URL::to('/news-detail/'.$news_data->news_slug)}}">@lang('website.Readmore')</a>-->
            </div>
        </article>
    </div>

@endforeach
@if(count($result['news']['news_data'])> 0 and $result['limit'] > count($result['news']['news_data']))
     <style>
        #load_news{
            display: none;
        }
        #loaded_content{
            display: block !important;
        }
        #loaded_content_empty{
            display: none !important;
        }
     </style>
@endif
@elseif(count($result['news']['news_data'])== 0 or $result['news']['success']==0 or count($result['news']['news_data']) < $result['limit'])
    <style>
        #load_news{
            display: none;
        }
        #loaded_content{
            display: none !important;
        }
        #loaded_content_empty{
            display: block !important;
        }
    </style>
@endif
