<?php
namespace Core;

use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Core\App\Exception\HttpException;

class Core
{
	private $config;

	public function __construct()
	{
		error_reporting(-1);
		ini_set('display_errors', true);

		define('DS',DIRECTORY_SEPARATOR);
		define('CORE', __DIR__ . DS);
		define('APP',CORE . 'App' . DS);

		//define('BASE_URL',self::$request->get_subfolder());

		class_alias(\Core\Classes\Debug::class,'Debug');
		class_alias(\Core\App\Config::class,'Config');
		class_alias(\Core\App\Http::class,'HTTP');
		class_alias(\Core\App\Html::class,'HTML');
		class_alias(\Core\App\Auth::class,'Auth');
		class_alias(\Core\App\ACL::class,'ACL');

		require_once APP . 'Require.php';

		\Config::init();
		\Auth::init();

		\Config::createElement('Vars',\Core\App\Config\Vars::class);
		\Config::createElement('Middlewares',\Core\App\Config\Middlewares::class);
		\Config::createElement('Routes',\Core\App\Config\Routes::class);
		\Config::createElement('Providers',\Core\App\Config\Providers::class);
	}

	public function run(\Psr\Http\Message\ServerRequestInterface $request) : \Psr\Http\Message\ResponseInterface
	{
		\Config::get('Vars')->LoadConfig();
		\Config::get('Vars')->addConfig(SPECS . 'config.inc.php',false);
		\Config::get('Middlewares')->LoadConfig();
		\Config::get('Routes')->LoadConfig();

		$response = new Response();

		$response = (new \Core\App\Dispatcher())
				->handle($request);

		return $response;
	}

	/*
	public function run(\Psr\Http\Message\ServerRequestInterface $request) : \Psr\Http\Message\ResponseInterface
	{
		$uri = $request->getUri()->getPath();
		if(!empty($uri) && $uri[-1] === "/")
		{
			return (new Response())
				->withStatus(301)
				->withHeader('Location',substr($uri,0,-1));
		}
		
		$route = $this->router->match($request);
		if(is_null($route))
		{
			return new Response(404,[],'Not Found');
		}

		$params = $route->getParams();
		$request = array_reduce(array_keys($params), function($request, $key) use ($params) {
			return $request->withAttribute($key, $params[$key]);
		}, $request);

		$response = call_user_func_array($route->getCallback(),[$request]);

		if(is_string($response))
		{
			return new Response(200,[],$response);
		}
		elseif($response instanceof ResponseInterface)
		{
			return $response;
		}
		else
		{
			throw new \Exception('The response is not correct.');
		}
	}*/
}