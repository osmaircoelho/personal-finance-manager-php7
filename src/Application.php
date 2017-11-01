<?php
declare(strict_types=1);

namespace SONFin;


use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SONFin\Plugins\PluginInterface;
use Zend\Diactoros\Response\RedirectResponse;
use Zend\Diactoros\Response\SapiEmitter;

class Application
{
    private $serviceContainer;
    private $befores = [];

    /**
     * Application constructor.
     * @param $serviceContainer
     */
    public function __construct(ServiceContainerInterface $serviceContainer)
    {
        $this->serviceContainer = $serviceContainer;
    }

    public function service($name)
    {
        return $this->serviceContainer->get($name);
    }
    public function addService(String $name, $service):void
    {
        if(is_callable($service)){
            $this->serviceContainer->addLazy($name, $service);
        }else{
            $this->serviceContainer->add($name, $service);
        }
    }

    public function plugin(PluginInterface $plugin):void
    {
        $plugin->register($this->serviceContainer);
    }

    public function get($path, $action, $name = null): Application
    {
        $routing = $this->service('routing');
        $routing->get($name, $path, $action);
        return $this;
    }
    public function post($path, $action, $name = null): Application
    {
        $routing = $this->service('routing');
        $routing->post($name, $path, $action);
        return $this;
    }

    public function redirect($path): ResponseInterface
    {
        return new RedirectResponse($path);
    }

    public function route(string $name, array $params = []): ResponseInterface
    {
        $generator = $this->service('routing.generator');
        $path = $generator->generate($name, $params);
        return $this->redirect($path);
    }

    public function before(callable $callback): Application{
        array_push($this->befores, $callback);
        return $this;
    }

    protected function runBefores(): ?ResponseInterface{
        foreach ($this->befores as $callback){
            $result = $callback($this->service(RequestInterface::class));
            if($result instanceof ResponseInterface){
                return $result;
            }
        }
        return null;
    }

    public function start(): void
    {
        $route = $this->service('route');
        /** @var ServerRequestInterface $request*/
        $request = $this->service(RequestInterface::class);
        if (!$route){
            echo "Page not found";
            exit;
        }
        foreach ($route->attributes as $key => $value){
                $request = $request->withAttribute($key, $value);
        }

        $result = $this->runBefores();
        if($result){
            $this->emitResponse($result);
            return;
        }


        $callable = $route->handler;
        $response = $callable($request);
        $this->emitResponse($response);
    }
    protected function emitResponse(ResponseInterface $response): void
    {
        $emitter = new SapiEmitter(); // Sapi - Server API - Server Application Promma
        $emitter->emit($response);
    }
}