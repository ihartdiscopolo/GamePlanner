<?php

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
require_once __DIR__ . "/../Modules/ProfileRepo.php";

class Router
{
    private array $routes = [];
    private Environment $twig;
    private ProfileRepo $profileRepo;

    public function __construct()
    {
        $loader = new FilesystemLoader(__DIR__ . '/../../resources/views');

        $this->twig = new Environment($loader, [
            'cache' => __DIR__ . '/../../cache/twig/', 
            'debug' => $_ENV['TWIG_DEBUG'] ?? false, 
        ]);
        
        $this->profileRepo = new ProfileRepo();

        if($_SESSION['profileLoggedIn']) {
            $profile = $this->profileRepo->getProfileById($_SESSION['profileId']);
            $this->twig->addGlobal('profile', $profile);
        }

        $this->twig->addGlobal('currentPath',  parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
        $this->twig->addGlobal('session', $_SESSION);
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
        $path = explode('?', $_SERVER['REQUEST_URI'])[0];
        
        $callback = null;
        $params = [];

        // Loop through registered routes for the current HTTP method
        foreach ($this->routes[$method] ?? [] as $routePath => $routeCallback) {
            // Convert a route like '/user/{id}' into a regex pattern: '#^/user/(?P<id>[^/]+)$#'
            $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<$1>[^/]+)', $routePath);
            $pattern = '#^' . $pattern . '$#';

            // Check if the current URL matches the regex pattern
            if (preg_match($pattern, $path, $matches)) {
                $callback = $routeCallback;
                
                // Extract only the named URL capture groups (e.g., 'id' => 5)
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                break;
            }
        }

        // If no route matches, return 404
        if ($callback === null) {
            http_response_code(404);
            return $this->twig->render('404.twig');
        }
        
        $templateContext = new ArrayObject();
        $viewName = null;
        
        // Combine $templateContext with the extracted URL parameters
        $arguments = array_merge([$templateContext], $params);
        
        if (is_array($callback)) {
            [$class, $methodName] = $callback;
            $controller = new $class($this->twig); 
            // Pass arguments into the controller method dynamically
            $viewName = call_user_func_array([$controller, $methodName], $arguments);
        } else {
            // Pass arguments into the closure dynamically
            $viewName = call_user_func_array($callback, $arguments);
        }
        
        if (is_string($viewName)) {
            if (!str_ends_with($viewName, '.twig')) {
                $templateContext['viewName'] = $viewName;
                $viewName = 'container.twig';
            }
            
            return $this->twig->render($viewName, $templateContext->getArrayCopy());
        }
        
        return $viewName;
    }
}