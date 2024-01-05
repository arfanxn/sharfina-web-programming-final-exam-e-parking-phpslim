<?php

namespace App\Handlers;

use App\Helpers\Session;
use Slim\Container;
use Psr\Http\Message\ResponseInterface as ResponseInterface;
use Slim\Http\Response;

class ResponseHandler extends Handler
{
    use \App\Traits\HttpableTrait;
    use \App\Traits\HttpableMessageTrait;

    private Container $container;
    private ResponseInterface $response;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }
    public static function new(Container $container): self
    {
        return new self($container);
    }

    public function getContainer(): Container
    {
        return $this->container;
    }
    public function setContainer(Container $container): self
    {
        $this->container = $container;
        return $this;
    }

    public function withAuth()
    {
        if ($auth = Session::auth()) {
            $this->appendBody('auth', $auth);
        }
    }

    public function getResponse(): ResponseInterface
    {
        if (isset($this->response)) {
            return $this->response;
        }

        $this->response = (new Response())->withStatus($this->getStatusCode());
        return $this->response;
    }
    public function setResponse(ResponseInterface $response): self
    {
        $this->response = $response;
        return $this;
    }

    /**
     * render returns the response as response with rendered view template
     * 
     * @param string $urlStr
     * @return ResponseInterface
     */
    public function render(string $template): ResponseInterface
    {
        $this->withAuth();
        $this->mergeBody(Session::pullRedirectData());
        $renderer = $this->getContainer()->renderer;
        return $renderer->render($this->getResponse(), $template, $this->getBody());
    }

    /**
     * json returns the response as json
     * 
     * @return ResponseInterface
     */
    public function json(): ResponseInterface
    {
        $this->withAuth();
        $this->mergeBody(Session::pullRedirectData());
        return $this->getResponse()->withJson($this->getBody());
    }

    /**
     * redirect returns the response as redirection response
     * 
     * @param string $urlStr
     * @return ResponseInterface
     */
    public function redirect(string $urlStr): ResponseInterface
    {
        $this->withAuth();
        Session::putRedirectData($this->getBody());
        return $this->getResponse()->withHeader('Location', $urlStr);
    }
}
