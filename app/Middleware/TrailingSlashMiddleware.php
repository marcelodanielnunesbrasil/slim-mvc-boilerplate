<?php

namespace App\Middleware;

class TrailingSlashMiddleware
{
    /**
     * Slim DI Container
     * @var \Slim\Container
     */
    protected $ci;

    /**
     * Constructor
     *
     * @param \Slim\Container $ci Slim DI Container
     */
    public function __construct($ci) {
        $this->ci = $ci;
    }

    /**
     * Redirect/rewrite all URLs that end in a / to the non-trailing / equivalent
     *
     * @param  \Psr\Http\Message\ServerRequestInterface $request  PSR7 request
     * @param  \Psr\Http\Message\ResponseInterface      $response PSR7 response
     * @param  callable                                 $next     Next middleware
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke($request, $response, $next)
    {
        $uri = $request->getUri();
        $path = $uri->getPath();

        if ($path != '/' && substr($path, -1) == '/') {
            $uri = $uri->withPath(substr($path, 0, -1));

            if ($request->getMethod() == 'GET') {
                return $response->withRedirect((string)$uri, 301);
            } else {
                return $next($request->withUri($uri), $response);
            }
        }

        $response = $next($request, $response);
        return $response;
    }
}
