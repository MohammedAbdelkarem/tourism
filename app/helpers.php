<?php

use Carbon\Carbon;
use App\Models\{User , Admin, Guide};
use Illuminate\Support\Facades\Cache;


// if(!function_exists('hashingPassword'))
// {
//     function hashingPassword($data)
//     {
//         $data['password'] = bcrypt($data['password']);
//     }
// }

if(!function_exists('hashing_password'))
{
    function hashing_password(&$password):void
    {
        $password = bcrypt($password);
    }
}
if(!function_exists('hashing_data'))
{
    function hashing_data(&$data):void
    {
        $data['password'] = bcrypt($data['password']);
    }
}

if(!function_exists('RandomCode'))
{
    function RandomCode()
    {
        return mt_rand(100000 , 999999);
    }
}


if(!function_exists('photoPath'))
{
    function photoPath($image)
    {
        $path = time().'.'.$image->getClientOriginalExtension();
        $image->move(public_path('image') , $path);
        $path = '/image/'.$path;

        return $path;
    }
}

if(!function_exists('locationPath'))
{
    function locationPath($data)
    {
        $path = 'https://www.google.com/maps?q='.$data['latitude'].','.$data['longitude'];
        return $path;
    }
}

if(!function_exists('admin_id'))
{
    function admin_id()
    {
        return Admin::AdminEmail()->pluck('id')->first();
    }
}
if(!function_exists('guide_id'))
{
    function guide_id()
    {
        return Guide::guideEmail()->pluck('id')->first();
    }
}
if(!function_exists('user_id'))
{
    function user_id()
    {
        return User::UserEmail()->pluck('id')->first();
    }
}
if(!function_exists('admin_name'))
{
    function admin_name()
    {
        return Cache::get('admin_name');
    }
}
if(!function_exists('user_email'))
{
    function user_email()
    {
        return Cache::get('user_email');
    }
}
if(!function_exists('admin_email'))
{
    function admin_email()
    {
        return Cache::get('admin_email');
    }
}
if(!function_exists('guide_email'))
{
    function guide_email()
    {
        return Cache::get('guide_email');
    }
}

if(!function_exists('is_in_progress'))
{
    function is_in_progress($start_time , $end_time)
    {
        $now = Carbon::now();
        return $now >= $start_time && $now < $end_time;
    }
}

