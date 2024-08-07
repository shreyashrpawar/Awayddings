<?php
class Cors extends Middleware{
protected function handle($request, Closure $next)
{
    return $next($request)
        ->header('Access-Control-Allow-Origin', 'http://localhost:3000') // Replace with your Vue.js origin
        ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, PATCH, DELETE, OPTIONS')
        ->header('Access-Control-Allow-Headers', 'Content-Type, X-Auth-Token, X-Requested-With');
}}
?>
