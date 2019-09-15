<?php
namespace Core\App\Middleware;

use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class FileLoader extends MiddlewareAbstract
{
	public function beforeProcess(ServerRequestInterface $request) : ?ResponseInterface
	{
		$uri = ltrim($request->getUri()->getPath(),'/');

		if(($uri === '') || (strpos($uri,'.') === false))
		{
			return null;
		}

		if(($file = \Core\App\Loader::file($uri)) !== null)
		{
			$response = \HTTP::showFile($file);

			return $response;
		}
		elseif(($file = \Core\App\Loader::fileVendor($uri)) !== null)
		{
			return \HTTP::showFile($file);
		}
		return null;
	}
}