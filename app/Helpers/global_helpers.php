<?php

if (!function_exists('currencyPosition')) {
    function currencyPosition($price): string
    {
        if (config('settings.site_currency_icon_position') === 'left') {
            return config('settings.site_currency_icon') . $price;
        } else {
            return $price . config('settings.site_currency_icon');
        }
    }
}


/* admin side bar active */
    if (!function_exists('setSidebarActive')) {
        function setSidebarActive(array $routes)
        {
            foreach($routes as $route){
                if(request()->routeIs($route)){
                    return 'active';
                }
            }
            return '';
        }
    }


