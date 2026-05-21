<?php

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class Router
{
    private array $routes = [];
    private Environment $twig;

    public function __construct()
    {
        $loader = new FilesystemLoader(__DIR__ . '/../../resources/views');

        $this->twig = new Environment($loader, [
            'cache' => __DIR__ . '/../../cache/twig/', 
            'debug' => $_ENV['TWIG_DEBUG'], 
        ]);
    }

    public function get(string $path, callable|array $callback): void
    {
        $this->routes['GET'][$path] = $callback;
    }
    
    public function post(string $path, callable|array $callback): void
    {
        $this->routes['POST'][$path] = $callback;
    }
    
    public function resolve(): mixed
    {
        $method = $_SERVER['REQUEST_METHOD'];
        // Gets the page url and removes qeury perameters
        $path = explode('?', $_SERVER['REQUEST_URI'])[0];
        
        $callback = $this->routes[$method][$path] ?? null;

        // If no route is found get 404 error
        if ($callback === null) {
            http_response_code(404);
            return $this->twig->render('404.twig');
        }
        
        // Create an ArrayObject to collect template variables
        $templateContext = new ArrayObject();
        $viewName = null;
        
        if (is_array($callback)) {
            [$class, $methodName] = $callback;
            // It's helpful to pass Twig into the controller if it needs it directly
            $controller = new $class($this->twig); 
            $viewName = $controller->$methodName($templateContext);
        } else {
            // Pass the data object into the closure
            $viewName = $callback($templateContext);
        }
        
        // If the callback returns a string, handle it as a Twig template path
        if (is_string($viewName)) {
            if (!str_ends_with($viewName, '.twig')) {
                $viewName .= '.twig';
            }
            
            // Render the template with the accumulated data
            return $this->twig->render($viewName, $templateContext->getArrayCopy());
        }
        
        return $viewName;
    }
}