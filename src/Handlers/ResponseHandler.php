<?php

namespace App\Handlers;

use App\Helpers\Arr;
use App\Helpers\Session;
use Slim\Container;
use Psr\Http\Message\ResponseInterface as Response;

class ResponseHandler extends Handler
{
    private Container $container;
    private Response $response;
    private int $statusCode; // eg: 422 or 500 or etc...
    private string $statusText; // eg: 'error' or 'success'
    private ?string $message;
    private array $body;

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

    public function getResponse(): Response
    {
        if (isset($this->response)) {
            return $this->response;
        }

        $this->response = (new Response())->withStatus($this->getStatusCode());
        return $this->response;
    }
    public function setResponse(Response $response): self
    {
        $this->response = $response;
        return $this;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
    public function setStatusCode(int $statusCode): self
    {
        $this->statusCode  = $statusCode;
        $this->statusText = $statusCode >= 200 && $statusCode <= 299 ? 'success' : 'error';
        return $this;
    }

    public function getStatusText(): string
    {
        return $this->statusText;
    }
    public function setStatusText(string $statusText): self
    {
        $this->statusText = $statusText;
        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message ?? null;
    }
    public function setMessage(string $message): self
    {
        $this->message = $message;
        return $this;
    }

    public function getBody(): ?array
    {
        if (!isset($this->statusCode)) {
            $this->setStatusCode(200); // if status code is not set then sets status code to 200
        }

        $body =  [
            'status_code' => $this->getStatusCode(),
            'status_text' => $this->getStatusText(),
        ];

        if ($this->getMessage() != null) {
            $body = array_merge($body, ['message' => $this->getMessage()]);
        }
        if (isset($this->body) && !empty($this->body)) {
            $body = array_merge($body, $this->body);
        }

        return $body;
    }
    public function setBody(array $body): self
    {
        $this->body = $body;
        return $this;
    }
    public function mergeBody(array $body): self
    {
        $this->body = array_replace($this->getBody(), $body);
        return $this;
    }
    public function appendBody(string $key, mixed $value): self
    {
        if (!isset($this->body)) {
            $this->body = array();
        }
        $this->body = array_replace($this->getBody(), Arr::dotToAssoc([$key => $value]));
        return $this;
    }

    /**
     * render returns the response as response with rendered view template
     * 
     * @param string $urlStr
     * @return Response
     */
    public function render(string $template): Response
    {
        $this->mergeBody(Session::pullRedirectData());
        $renderer = $this->getContainer()->renderer;
        return $renderer->render($this->getResponse(), $template, $this->getBody());
    }

    /**
     * json returns the response as json
     * 
     * @return Response
     */
    public function json(): Response
    {
        $this->mergeBody(Session::pullRedirectData());
        return $this->getResponse()->getBody()->withJson($this->getBody());
    }

    /**
     * redirect returns the response as redirection response
     * 
     * @param string $urlStr
     * @return Response
     */
    public function redirect(string $urlStr): Response
    {
        Session::putRedirectData($this->getBody());
        return $this->getResponse()->withHeader('Location', $urlStr);
    }
}
