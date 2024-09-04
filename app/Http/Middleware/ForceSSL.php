<?php
namespace App\Http\Middleware;
use Closure, Request;
use App\Helpers\Custom;
class ForceSSL
{
    public function handle($request, Closure $next)
    {
		// echo '<pre>';print_r($_SERVER);die();
        $site_settings = Custom::site_settings();

			if ($site_settings->force_ssl == 'on')
			{
				if (strpos($_SERVER['HTTP_HOST'], 'www.') === false) {

					header('Location: https://www.'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);

					exit;

				}elseif(!isset($_SERVER['HTTP_X_FORWARDED_PROTO']) || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'http')) {
					$www = strpos($_SERVER['HTTP_HOST'], 'www.') === false ? 'www.' : '';
					header('Location: https://'.$www.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);

					exit;

			}
        }
        return $next($request);
    }
}