<nav id="categories" class="navbar navbar-expand-lg p-0 categories">
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-categories" aria-controls="navbar-categories" aria-expanded="false" aria-label="Toggle navigation">
		@lang('website.All Categories')
  </button>
  
  <div class="collapse navbar-collapse" id="navbar-categories">

    <ul class="navbar-nav flex-column">
     @foreach($result['commonContent']['categories'] as $categories_data)
     
      <li class="nav-item dropdown">
        <a href="{{ URL::to('/shop')}}?category={{$categories_data->slug}}" class="nav-link dropdown-toggle">
          <img class="img-fuild" src="{{asset('').$categories_data->icon}}">{{$categories_data->name}} @if(count($categories_data->sub_categories)>0) <i class="fa fa-angle-right " aria-hidden="true"></i> @endif
        </a>
        
        @if(count($categories_data->sub_categories)>0)
        <ul class="dropdown-menu multi-level">
        	@foreach($categories_data->sub_categories as $sub_categories_data)            
            <li class="dropdown-submenu">
              <a  class="dropdown-item" tabindex="-1" href="{{ URL::to('/shop')}}?category={{$sub_categories_data->sub_slug}}">
                <img class="img-fuild" src="{{asset('').$sub_categories_data->sub_icon}}">
                {{$sub_categories_data->sub_name}}
              </a>              
            </li>            
            @endforeach 
          </ul>
          @endif
        </li>
        @endforeach
    </ul>
  </div>
</nav>


