<?php

namespace Mvc\Framework\Core\Forge;

class View
{


    /**
     * @var string
     */
    private string $document = '';


    /**
     * @var string
     */
    private string $view = '';




    /**
     * Template constructor.
     * @param string $view
     * @param array $parameters
     */
    public function __construct(string $view_path, public array $parameters = [])
    {
        $this->document = file_get_contents(__DIR__ . '/../../Views/base.forge.html');
        $this->view = file_get_contents(__DIR__ . '/../../Views/'.$view_path.'.forge.html');
    }

    public function build(): string
    {
        $this->document = str_replace('<router></router>', $this->view, $this->document);
        return Parser::parse($this->document, $this->parameters);
    }




}