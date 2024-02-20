<?php 

namespace App\Twig;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class RouterExtension extends AbstractExtension
{
    private $router;

    public function __construct($router)
    {
        $this->router = $router;
    }

    public function getfunctions()
    {
        return [
            new TwigFunction('path', [$this, 'generatePath'])
        ];
    }

    public function generatePath(string $name): string
    {
        return $this->router->getPath($name);
    }
}