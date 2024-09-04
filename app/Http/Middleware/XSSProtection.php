<?php
namespace App\Http\Middleware;
use Closure, Request;
use App\Helpers\Custom;
class XSSProtection
{
    /**
     * The following method loops through all request input and strips out all tags from
     * the request. This to ensure that users are unable to set ANY HTML within the form
     * submissions, but also cleans up input.
     *
     * @param Request $request
     * @param callable $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        $input = $request->all();

        array_walk_recursive($input, function(&$input) {

            $input = strip_tags($input);

            $input = str_replace(['<', '>'], ['', ''], $input);

            // $config = \HTMLPurifier_Config::createDefault();
            // $purifier = new \HTMLPurifier($config);
            // $input = $purifier->purify($input);

        });

        $request->merge($input);

        return $next($request);
    }
}