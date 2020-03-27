<?php
namespace Core\App\Middleware;

use Core\App\Exception\HttpException;
use GuzzleHttp\Psr7\Stream;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use function GuzzleHttp\Psr7\stream_for;

class Templater extends MiddlewareAbstract
{
	public function beforeProcess(ServerRequestInterface $request) : ?ResponseInterface
	{
		//ob_start();
		return null;
	}

	public function afterProcess(ServerRequestInterface $request, ResponseInterface $response) : ResponseInterface
	{
		//$buffer = ob_get_clean();
		if(\Config::get('HTMLTemplate') === null || \Config::get('HTMLTemplate')->getTemplate() === null || \Config::get('HTMLTemplate')->getNoTemplate() || \Config::get('Vars')->getConfig('modeAjax') || $request->getAttribute('SAPI')=='CLI')
		{
			return $response;
		}

		//$buffer = $this->render($response->getBody(), );

		return \Config::get('HTMLTemplate')->render($response);
	}
}