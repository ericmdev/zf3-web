<?php

namespace App\Action;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Expressive\Template;

class DocsAction
{
    public function __construct(array $config, Template\TemplateRendererInterface $template = null)
    {
        $this->template = $template;
        $this->config   = $config;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next = null)
    {
        $ver = $request->getAttribute('ver', false);

        if (false === $ver) {
            $components = json_decode(file_get_contents('http://zendframework.github.io/zf-mkdoc-theme/scripts/zf-component-list.json'));
            return new HtmlResponse($this->template->render("app::learn", [ 'components' => $components ]));
        }
        $ver = (int) substr($ver, 2);
        return new HtmlResponse($this->template->render("app::api", [ 'zf' => $ver, 'versions' => $this->config ]));
    }
}
