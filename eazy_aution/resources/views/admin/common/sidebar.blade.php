<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="{{asset('').auth()->guard('admin')->user()->image}}" class="img-circle" alt="{{ auth()->guard('admin')->user()->first_name }} {{ auth()->guard('admin')->user()->last_name }} Image">
        </div>
        <div class="pull-left info">
          <p>{{ auth()->guard('admin')->user()->first_name }} {{ auth()->guard('admin')->user()->last_name }}</p>
          <a href="#"><i class="fa fa-circle text-success"></i> {{ trans('labels.online') }}</a>
        </div>
      </div>
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu">
        <li class="header">{{ trans('labels.navigation') }}</li>
        @if(session('dashboard_view')==1 or auth()->guard('admin')->user()->adminType=='1')
        <li class="treeview {{ Request::is('admin/dashboard/this_month') ? 'active' : '' }}">
          <a href="{{ URL::to('admin/dashboard/this_month')}}">
            <i class="fa fa-dashboard"></i> <span>{{ trans('labels.header_dashboard') }}</span>
          </a>
        </li>
        
        @endif
        @if(session('language_view')==1  or auth()->guard('admin')->user()->adminType=='1')
        <li class="treeview {{ Request::is('admin/languages') ? 'active' : '' }} {{ Request::is('admin/addlanguages') ? 'active' : '' }} {{ Request::is('admin/editlanguages/*') ? 'active' : '' }} ">
          <a href="{{ URL::to('admin/languages')}}">
            <i class="fa fa-language" aria-hidden="true"></i> <span> {{ trans('labels.languages') }} </span>
          </a>
        </li>
        @endif
        
        @if(session('manufacturer_view')==1  or auth()->guard('admin')->user()->adminType=='1')
        <li class="treeview {{ Request::is('admin/manufacturers') ? 'active' : '' }} {{ Request::is('admin/addmanufacturer') ? 'active' : '' }} {{ Request::is('admin/editmanufacturer/*') ? 'active' : '' }} ">
          <a href="{{ URL::to('admin/manufacturers')}}">
            <i class="fa fa-industry" aria-hidden="true"></i> <span>{{ trans('labels.link_manufacturer') }}</span>
          </a>
        </li>
        @endif
        @if(session('categories_view')==1  or auth()->guard('admin')->user()->adminType=='1')
        <li class="treeview {{ Request::is('admin/categories') ? 'active' : '' }} {{ Request::is('admin/addcategory') ? 'active' : '' }} {{ Request::is('admin/editCategory/*') ? 'active' : '' }} {{ Request::is('admin/subcategories') ? 'active' : '' }}  {{ Request::is('admin/addsubcategory') ? 'active' : '' }}  {{ Request::is('admin/editsubcategory/*') ? 'active' : '' }}">
          <a href="#">
            <i class="fa fa-bars" aria-hidden="true"></i>
<span>{{ trans('labels.link_categories') }} </span> <i class="fa fa-angle-left pull-right"></i>
          </a>
          <ul class="treeview-menu">
          	<li class="{{ Request::is('admin/categories') ? 'active' : '' }} {{ Request::is('admin/addcategory') ? 'active' : '' }} {{ Request::is('admin/editcategory/*') ? 'active' : '' }}"><a href="{{ URL::to('admin/categories')}}"><i class="fa fa-circle-o"></i> {{ trans('labels.link_main_categories') }}</a></li>
            <li class="{{ Request::is('admin/subcategories') ? 'active' : '' }}  {{ Request::is('admin/addsubcategory') ? 'active' : '' }}  {{ Request::is('admin/editsubcategory/*') ? 'active' : '' }}"><a href="{{ URL::to('admin/subcategories')}}"><i class="fa fa-circle-o"></i>{{ trans('labels.link_sub_categories') }}</a></li>
          </ul>
        </li>
        @endif
        @if(session('products_view')==1  or auth()->guard('admin')->user()->adminType=='1')
        <li class="treeview {{ Request::is('admin/products') ? 'active' : '' }} {{ Request::is('admin/addproduct') ? 'active' : '' }} {{ Request::is('admin/editattributes/*') ? 'active' : '' }} {{ Request::is('admin/attributes') ? 'active' : '' }}  {{ Request::is('admin/addattributes') ? 'active' : '' }} {{ Request::is('admin/addproductattribute/*') ? 'active' : '' }} {{ Request::is('admin/addinventory/*') ? 'active' : '' }} {{ Request::is('admin/addproductimages/*') ? 'active' : '' }} ">
          <a href="#">
            <i class="fa fa-database"></i> <span>{{ trans('labels.link_products') }}</span> <i class="fa fa-angle-left pull-right"></i>
          </a>
          <ul class="treeview-menu">
            <li class="{{ Request::is('admin/products') ? 'active' : '' }} {{ Request::is('admin/addproduct') ? 'active' : '' }} {{ Request::is('admin/editproduct/*') ? 'active' : '' }} {{ Request::is('admin/addproductattribute/*') ? 'active' : '' }} {{ Request::is('admin/addinventory/*') ? 'active' : '' }} {{ Request::is('admin/addproductimages/*') ? 'active' : '' }}"><a href="{{ URL::to('admin/products')}}"><i class="fa fa-circle-o"></i> {{ trans('labels.link_all_products') }}</a></li>
            
            <li class="{{ Request::is('admin/attributes') ? 'active' : '' }}  {{ Request::is('admin/addattributes') ? 'active' : '' }}  {{ Request::is('admin/editattributes/*') ? 'active' : '' }}" ><a href="{{ URL::to('admin/attributes' )}}"><i class="fa fa-circle-o"></i> {{ trans('labels.products_attributes') }}</a></li>
          </ul>
        </li>
         @endif
        @if(session('news_view')==1  or auth()->guard('admin')->user()->adminType=='1')
        <li class="treeview {{ Request::is('admin/newscategories') ? 'active' : '' }} {{ Request::is('admin/addnewscategory') ? 'active' : '' }} {{ Request::is('admin/editnewscategory/*') ? 'active' : '' }} {{ Request::is('admin/news') ? 'active' : '' }}  {{ Request::is('admin/addsubnews') ? 'active' : '' }}  {{ Request::is('admin/editsubnews/*') ? 'active' : '' }}">
          <a href="#">
            <i class="fa fa-database" aria-hidden="true"></i>
<span>{{ trans('labels.link_news') }}</span> <i class="fa fa-angle-left pull-right"></i>
          </a>
          <ul class="treeview-menu">
          	<li class="{{ Request::is('admin/newscategories') ? 'active' : '' }} {{ Request::is('admin/addnewscategory') ? 'active' : '' }} {{ Request::is('admin/editnewscategory/*') ? 'active' : '' }}"><a href="{{ URL::to('admin/newscategories')}}"><i class="fa fa-circle-o"></i>{{ trans('labels.link_news_categories') }}</a></li>
            <li class="{{ Request::is('admin/news') ? 'active' : '' }}  {{ Request::is('admin/addsubnews') ? 'active' : '' }}  {{ Request::is('admin/editsubnews/*') ? 'active' : '' }}"><a href="{{ URL::to('admin/news')}}"><i class="fa fa-circle-o"></i> {{ trans('labels.link_sub_news') }}</a></li>
          </ul>
        </li>
         @endif
        @if(session('customers_view')==1  or auth()->guard('admin')->user()->adminType=='1')
        <li class="treeview {{ Request::is('admin/customers') ? 'active' : '' }}  {{ Request::is('admin/addcustomers') ? 'active' : '' }}  {{ Request::is('admin/editcustomers/*') ? 'active' : '' }}">
          <a href="{{ URL::to('admin/customers')}}">
            <i class="fa fa-users" aria-hidden="true"></i> <span>{{ trans('labels.link_customers') }}</span>
          </a>
        </li>
         @endif
        @if(session('tax_location_view')==1  or auth()->guard('admin')->user()->adminType=='1')
        
        <li class="treeview {{ Request::is('admin/countries') ? 'active' : '' }} {{ Request::is('admin/addcountry') ? 'active' : '' }} {{ Request::is('admin/editcountry/*') ? 'active' : '' }} {{ Request::is('admin/listingZones') ? 'active' : '' }} {{ Request::is('admin/addZone') ? 'active' : '' }} {{ Request::is('admin/editZone/*') ? 'active' : '' }} {{ Request::is('admin/taxclass') ? 'active' : '' }} {{ Request::is('admin/addtaxclass') ? 'active' : '' }} {{ Request::is('admin/editTaxClass/*') ? 'active' : '' }} {{ Request::is('admin/taxrates') ? 'active' : '' }} {{ Request::is('admin/addtaxrate') ? 'active' : '' }} {{ Request::is('admin/edittaxrate/*') ? 'active' : '' }}">
          <a href="#">
            <i class="fa fa-money" aria-hidden="true"></i>
<span>{{ trans('labels.link_tax_location') }}</span> <i class="fa fa-angle-left pull-right"></i>
          </a>
          <ul class="treeview-menu">
            <li class="{{ Request::is('admin/countries') ? 'active' : '' }} {{ Request::is('admin/addcountry') ? 'active' : '' }} {{ Request::is('admin/editcountry/*') ? 'active' : '' }} "><a href="{{ URL::to('admin/countries')}}"><i class="fa fa-circle-o"></i> {{ trans('labels.link_countries') }}</a></li>
            <li class="{{ Request::is('admin/taxclass') ? 'active' : '' }} {{ Request::is('admin/addtaxclass') ? 'active' : '' }} {{ Request::is('admin/editTaxClass/*') ? 'active' : '' }} "><a href="{{ URL::to('admin/taxclass')}}"><i class="fa fa-circle-o"></i> {{ trans('labels.link_tax_classes') }}</a></li>
            <li class="{{ Request::is('admin/taxrates') ? 'active' : '' }} {{ Request::is('admin/addtaxrate') ? 'active' : '' }} {{ Request::is('admin/edittaxrate/*') ? 'active' : '' }} "><a href="{{ URL::to('admin/taxrates')}}"><i class="fa fa-circle-o"></i> {{ trans('labels.link_tax_rates') }}</a></li>
            <li class="{{ Request::is('admin/listingZones') ? 'active' : '' }} {{ Request::is('admin/addZone') ? 'active' : '' }} {{ Request::is('admin/editZone/*') ? 'active' : '' }}"><a href="{{ URL::to('admin/listingZones')}}"><i class="fa fa-circle-o"></i> {{ trans('labels.link_zones') }}</a></li>
          </ul>
        </li>
         @endif
        @if(session('coupons_view')==1  or auth()->guard('admin')->user()->adminType=='1')
        <li class="treeview {{ Request::is('admin/coupons') ? 'active' : '' }} {{ Request::is('admin/editcoupons/*') ? 'active' : '' }}">
          <a href="{{ URL::to('admin/coupons')}}" ><i class="fa fa-tablet" aria-hidden="true"></i> <span>{{ trans('labels.link_coupons') }}</span></a>
        </li>
         @endif
        @if(session('notifications_view')==1 or auth()->guard('admin')->user()->adminType=='1')
        
        <li class="treeview {{ Request::is('admin/devices') ? 'active' : '' }} {{ Request::is('admin/viewdevices/*') ? 'active' : '' }} {{ Request::is('admin/notifications') ? 'active' : '' }}">
          <a href="{{ URL::to('admin/devices')}} ">
            <i class="fa fa-bell-o" aria-hidden="true"></i>
<span>{{ trans('labels.link_notifications') }}</span> <i class="fa fa-angle-left pull-right"></i>
          </a>
          <ul class="treeview-menu">
            <li class="{{ Request::is('admin/devices') ? 'active' : '' }} {{ Request::is('admin/viewdevices/*') ? 'active' : '' }}">
          		<a href="{{ URL::to('admin/devices')}}"><i class="fa fa-circle-o"></i>{{ trans('labels.link_devices') }} </a>
            </li>  
            <li class="{{ Request::is('admin/notifications') ? 'active' : '' }} ">
            	<a href="{{ URL::to('admin/notifications') }}"><i class="fa fa-circle-o"></i> {{ trans('labels.link_send_notifications') }}</a>
            </li>      
          </ul>
        </li>
         @endif
        @if(session('orders_view')==1)
        
        <li class="treeview {{ Request::is('admin/orders') ? 'active' : '' }}  {{ Request::is('admin/addOrders') ? 'active' : '' }}  {{ Request::is('admin/vieworder/*') ? 'active' : '' }}">
          <a href="{{ URL::to('admin/orders')}}" ><i class="fa fa-list-ul" aria-hidden="true"></i> <span> {{ trans('labels.link_orders') }}</span>
          </a>
        </li>
         @endif
        @if(session('shipping_methods_view')==1 or auth()->guard('admin')->user()->adminType=='1')
        <li class="treeview {{ Request::is('admin/shippingmethods') ? 'active' : '' }} {{ Request::is('admin/upsShipping') ? 'active' : '' }} {{ Request::is('admin/flateRate') ? 'active' : '' }}">
          <a href="{{ URL::to('admin/shippingmethods')}}"><i class="fa fa-truck" aria-hidden="true"></i> <span> {{ trans('labels.link_shipping_methods') }}</span>
          </a>
        </li>
         @endif
        @if(session('payment_methods_view')==1 or auth()->guard('admin')->user()->adminType=='1')
        <li class="treeview {{ Request::is('admin/paymentsetting') ? 'active' : '' }}">
          <a  href="{{ URL::to('admin/paymentsetting')}}"><i class="fa fa-credit-card" aria-hidden="true"></i> <span>
          {{ trans('labels.link_payment_methods') }}</span>
          </a>
        </li>
         @endif
        @if(session('reports_view')==1 or auth()->guard('admin')->user()->adminType=='1')
        <li class="treeview {{ Request::is('admin/statscustomers') ? 'active' : '' }} {{ Request::is('admin/outofstock') ? 'active' : '' }} {{ Request::is('admin/statsproductspurchased') ? 'active' : '' }} {{ Request::is('admin/statsproductsliked') ? 'active' : '' }} {{ Request::is('admin/lowinstock') ? 'active' : '' }}">
          <a href="#">
            <i class="fa fa-file-text-o" aria-hidden="true"></i>
<span>{{ trans('labels.link_reports') }}</span> <i class="fa fa-angle-left pull-right"></i>
          </a>
          <ul class="treeview-menu">
            <li class="{{ Request::is('admin/lowinstock') ? 'active' : '' }} "><a href="{{ URL::to('admin/lowinstock')}}"><i class="fa fa-circle-o"></i> {{ trans('labels.link_products_low_stock') }}</a></li>
            <li class="{{ Request::is('admin/outofstock') ? 'active' : '' }} "><a href="{{ URL::to('admin/outofstock')}}"><i class="fa fa-circle-o"></i> {{ trans('labels.link_out_of_stock_products') }}</a></li>  
           <!-- <li class="{{ Request::is('admin/productsstock') ? 'active' : '' }} "><a href="{{ URL::to('admin/stockin')}}"><i class="fa fa-circle-o"></i> {{ trans('labels.stockin') }}</a></li>-->
            <li class="{{ Request::is('admin/statscustomers') ? 'active' : '' }} "><a href="{{ URL::to('admin/statscustomers')}}"><i class="fa fa-circle-o"></i> {{ trans('labels.link_customer_orders_total') }}</a></li>             
            <li class="{{ Request::is('admin/statsproductspurchased') ? 'active' : '' }}"><a href="{{ URL::to('admin/statsproductspurchased')}}"><i class="fa fa-circle-o"></i> {{ trans('labels.link_total_purchased') }}</a></li>
            <li class="{{ Request::is('admin/statsproductsliked') ? 'active' : '' }}"><a href="{{ URL::to('admin/statsproductsliked')}}"><i class="fa fa-circle-o"></i> {{ trans('labels.link_products_liked') }}</a></li>
          </ul>
        </li>
         @endif
         
        @if(session('website_setting_view')==1 or auth()->guard('admin')->user()->adminType=='1')
        
        @if($web_setting[67]->value=='1')
        <li class="treeview {{ Request::is('admin/sliders') ? 'active' : '' }} {{ Request::is('admin/addsliderimage') ? 'active' : '' }} {{ Request::is('admin/editslide/*') ? 'active' : '' }} {{ Request::is('admin/webpages') ? 'active' : '' }}  {{ Request::is('admin/addwebpage') ? 'active' : '' }}  {{ Request::is('admin/editwebpage/*') ? 'active' : '' }} {{ Request::is('admin/websettings') ? 'active' : '' }} {{ Request::is('admin/webthemes') ? 'active' : '' }} {{ Request::is('admin/customstyle') ? 'active' : '' }} {{ Request::is('admin/constantbanners') ? 'active' : '' }} {{ Request::is('admin/addconstantbanner') ? 'active' : '' }} {{ Request::is('admin/editconstantbanner/*') ? 'active' : '' }}" >
          <a href="#">
            <i class="fa fa-gears" aria-hidden="true"></i>
<span> {{ trans('labels.link_site_settings') }}</span> <i class="fa fa-angle-left pull-right"></i>
          </a>
          <ul class="treeview-menu">
          	<li class="{{ Request::is('admin/constantbanners') ? 'active' : '' }} {{ Request::is('admin/addconstantbanner') ? 'active' : '' }} {{ Request::is('admin/editconstantbanner/*') ? 'active' : '' }}"><a href="{{ URL::to('admin/constantbanners')}}"><i class="fa fa-circle-o"></i> {{ trans('labels.link_Constant_Banners') }}</a></li>
            <li class="{{ Request::is('admin/sliders') ? 'active' : '' }} {{ Request::is('admin/addsliderimage') ? 'active' : '' }} {{ Request::is('admin/editslide/*') ? 'active' : '' }} "><a href="{{ URL::to('admin/sliders')}}"><i class="fa fa-circle-o"></i> {{ trans('labels.link_Sliders') }}</a></li>
          
            <li class="{{ Request::is('admin/webpages') ? 'active' : '' }}  {{ Request::is('admin/addwebpage') ? 'active' : '' }}  {{ Request::is('admin/editwebpage/*') ? 'active' : '' }}"><a href="{{ URL::to('admin/webpages')}}"><i class="fa fa-circle-o"></i> {{ trans('labels.content_pages') }}</a></li>
            
            <li class="{{ Request::is('admin/webthemes') ? 'active' : '' }} "><a href="{{ URL::to('admin/webthemes')}}"><i class="fa fa-circle-o"></i> {{ trans('labels.website_themes') }}</a></li>
            
            <li class="{{ Request::is('admin/seo') ? 'active' : '' }} "><a href="{{ URL::to('admin/seo')}}"><i class="fa fa-circle-o"></i> {{ trans('labels.seo content') }}</a></li>
            
            <li class="{{ Request::is('admin/customstyle') ? 'active' : '' }} "><a href="{{ URL::to('admin/customstyle')}}"><i class="fa fa-circle-o"></i> {{ trans('labels.custom_style_js') }}</a></li>
            
            <li class="{{ Request::is('admin/websettings') ? 'active' : '' }}"><a href="{{ URL::to('admin/websettings')}}"><i class="fa fa-circle-o"></i> {{ trans('labels.link_setting') }}</a></li>
          </ul>
        </li>
        @endif
        
         @endif
        @if(session('application_setting_view')==1 or auth()->guard('admin')->user()->adminType=='1')
        
        @if($web_setting[66]->value=='1')
        <li class="treeview {{ Request::is('admin/banners') ? 'active' : '' }} {{ Request::is('admin/addbanner') ? 'active' : '' }} {{ Request::is('admin/editbanner/*') ? 'active' : '' }} {{ Request::is('admin/pages') ? 'active' : '' }}  {{ Request::is('admin/addpage') ? 'active' : '' }}  {{ Request::is('admin/editpage/*') ? 'active' : '' }}  {{ Request::is('admin/appSettings') ? 'active' : '' }} {{ Request::is('admin/admobSettings') ? 'active' : '' }} {{ Request::is('admin/applabel') ? 'active' : '' }} {{ Request::is('admin/addappkey') ? 'active' : '' }} {{ Request::is('admin/applicationapi') ? 'active' : '' }}">
          <a href="#">
            <i class="fa fa-gears" aria-hidden="true"></i>
<span> {{ trans('labels.link_app_settings') }}</span> <i class="fa fa-angle-left pull-right"></i>
          </a>
          <ul class="treeview-menu">
            <li class="{{ Request::is('admin/banners') ? 'active' : '' }} {{ Request::is('admin/addbanner') ? 'active' : '' }} {{ Request::is('admin/editbanner/*') ? 'active' : '' }}"><a href="{{ URL::to('admin/banners')}}"><i class="fa fa-circle-o"></i> {{ trans('labels.link_Banners') }}</a></li>
                       
            <li class="{{ Request::is('admin/pages') ? 'active' : '' }}  {{ Request::is('admin/addpage') ? 'active' : '' }}  {{ Request::is('admin/editpage/*') ? 'active' : '' }}"><a href="{{ URL::to('admin/pages')}}"><i class="fa fa-circle-o"></i> {{ trans('labels.content_pages') }}</a></li>
            
            <li class="{{ Request::is('admin/admobSettings') ? 'active' : '' }}"><a href="{{ URL::to('admin/admobSettings')}}"><i class="fa fa-circle-o"></i> {{ trans('labels.link_admob') }}</a></li>
            
            <li class="android-hide {{ Request::is('admin/applabel') ? 'active' : '' }} {{ Request::is('admin/addappkey') ? 'active' : '' }}"><a href="{{ URL::to('admin/applabel')}}"><i class="fa fa-circle-o"></i> {{ trans('labels.labels') }}</a></li>
                                  
            <li class="{{ Request::is('admin/applicationapi') ? 'active' : '' }}"><a href="{{ URL::to('admin/applicationapi')}}"><i class="fa fa-circle-o"></i> {{ trans('labels.applicationApi') }}</a></li>
            
            <li class="{{ Request::is('admin/appsettings') ? 'active' : '' }}"><a href="{{ URL::to('admin/appsettings')}}"><i class="fa fa-circle-o"></i> {{ trans('labels.link_setting') }}</a></li>
            
          </ul>
        </li>
        @endif
        
         @endif
        @if(session('general_setting_view')==1 or auth()->guard('admin')->user()->adminType=='1')
        
        <li class="treeview {{ Request::is('admin/facebooksettings') ? 'active' : '' }} {{ Request::is('admin/setting') ? 'active' : '' }} {{ Request::is('admin/googlesettings') ? 'active' : '' }} {{ Request::is('admin/pushnotification') ? 'active' : '' }} {{ Request::is('admin/orderstatus') ? 'active' : '' }} {{ Request::is('admin/addorderstatus') ? 'active' : '' }} {{ Request::is('admin/editorderstatus/*') ? 'active' : '' }} {{ Request::is('admin/alertsetting') ? 'active' : '' }} {{ Request::is('admin/units') ? 'active' : '' }} {{ Request::is('admin/addunit') ? 'active' : '' }} {{ Request::is('admin/editunit/*') ? 'active' : '' }} ">
          <a href="#">
            <i class="fa fa-gears" aria-hidden="true"></i>
<span> {{ trans('labels.link_general_settings') }}</span> <i class="fa fa-angle-left pull-right"></i>
          </a>
          <ul class="treeview-menu">
          	<li class="{{ Request::is('admin/units') ? 'active' : '' }} {{ Request::is('admin/addunit') ? 'active' : '' }} {{ Request::is('admin/editunit/*') ? 'active' : '' }} "><a href="{{ URL::to('admin/units')}}"><i class="fa fa-circle-o"></i> {{ trans('labels.link_units') }}</a></li>
          	<li class="{{ Request::is('admin/orderstatus') ? 'active' : '' }} {{ Request::is('admin/addorderstatus') ? 'active' : '' }} {{ Request::is('admin/editorderstatus/*') ? 'active' : '' }} "><a href="{{ URL::to('admin/orderstatus')}}"><i class="fa fa-circle-o"></i> {{ trans('labels.link_order_status') }}</a></li>
          	<li class="{{ Request::is('admin/facebooksettings') ? 'active' : '' }}"><a href="{{ URL::to('admin/facebooksettings')}}"><i class="fa fa-circle-o"></i> {{ trans('labels.link_facebook') }}</a></li>
            
            <li class="{{ Request::is('admin/googlesettings') ? 'active' : '' }}"><a href="{{ URL::to('admin/googlesettings')}}"><i class="fa fa-circle-o"></i> {{ trans('labels.link_google') }}</a></li>
            <li class="{{ Request::is('admin/pushnotification') ? 'active' : '' }}"><a href="{{ URL::to('admin/pushnotification')}}"><i class="fa fa-circle-o"></i> {{ trans('labels.link_push_notification') }}</a></li>
             <li class="{{ Request::is('admin/alertsetting') ? 'active' : '' }}"><a href="{{ URL::to('admin/alertsetting')}}"><i class="fa fa-circle-o"></i> {{ trans('labels.alertSetting') }}</a></li>
            <li class="{{ Request::is('admin/setting') ? 'active' : '' }}"><a href="{{ URL::to('admin/setting')}}"><i class="fa fa-circle-o"></i> {{ trans('labels.link_setting') }}</a></li>
            
          </ul>
        </li>
         @endif
        @if(session('manage_admins_view')==1 or auth()->guard('admin')->user()->adminType=='1')
         <li class="treeview {{ Request::is('admin/admins') ? 'active' : '' }} {{ Request::is('admin/addadmins') ? 'active' : '' }} {{ Request::is('admin/editadmin/*') ? 'active' : '' }} {{ Request::is('admin/manageroles') ? 'active' : '' }} {{ Request::is('admin/addadminType') ? 'active' : '' }} {{ Request::is('admin/editadminType/*') ? 'active' : '' }}">
          <a href="#">
            <i class="fa fa-users" aria-hidden="true"></i>
<span> {{ trans('labels.Manage Admins') }}</span> <i class="fa fa-angle-left pull-right"></i>
          </a>
          
          <ul class="treeview-menu">
          	<li class="{{ Request::is('admin/admins') ? 'active' : '' }} {{ Request::is('admin/addadmins') ? 'active' : '' }} {{ Request::is('admin/editadmin/*') ? 'active' : '' }}"><a href="{{ URL::to('admin/admins')}}"><i class="fa fa-circle-o"></i> {{ trans('labels.link_admins') }}</a></li> 
            <li class="{{ Request::is('admin/manageroles') ? 'active' : '' }} {{ Request::is('admin/addadminType') ? 'active' : '' }} {{ Request::is('admin/editadminType/*') ? 'active' : '' }}"><a href="{{ URL::to('admin/manageroles')}}"><i class="fa fa-circle-o"></i> {{ trans('labels.link_manage_roles') }}</a></li>
            
            <li class="{{ Request::is('admin/categoriesroles') ? 'active' : '' }} {{ Request::is('admin/categoriesroles') ? 'active' : '' }} {{ Request::is('admin/categoriesroles/*') ? 'active' : '' }}"><a href="{{ URL::to('admin/categoriesroles')}}"><i class="fa fa-circle-o"></i> {{ trans('labels.link_categoriesroles') }}</a></li>
            
          </ul>
        </li>
         @endif

         <li class="treeview {{ Request::is('admin/addlisting') ? 'active' : '' }}">
          <a href="{{ URL::to('admin/addlisting')}}" ><i class="fa fa-list-ul" aria-hidden="true"></i> <span> {{ trans('labels.Post Listing') }}</span>
          </a>
        </li>
        <!-- <li class="treeview {{ Request::is('admin/messages') ? 'active' : '' }}">
          <a href="{{ URL::to('admin/messages')}}" ><i class="fa fa-list-ul" aria-hidden="true"></i> <span> {{ trans('labels.Messages') }}</span>
          </a>
        </li> -->

               
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>