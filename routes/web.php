<?php

use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Permission;
use App\Http\Controllers\CustomerController;
use Illuminate\Routing\Controllers\Middleware;
use App\Http\Controllers\Dashboard\HomeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/



$classes = getControllerMethods();


foreach ($classes as $class => $methods) {
    Route::controller($class)->prefix(strtolower(str_replace('Controller','',basename(str_replace('\\', '/', $class)))))

            ->group(function() use ($methods, $class){

                array_map(function ($method) use ($class) {
                    if(!isIgnored($class, $method)){

                        $middleware = setMiddlewares($class,$method);
                        $httpMethod = getHttpMethod($class, $method);

                        Route::middleware($middleware)->match($httpMethod, str_replace('index','/', $method) . getPathParameters($class, $method), $method);
                    }
                }, $methods);

    });
}

return  getMethodDocInfo(CustomerController::class, 'index', 'middleware');



function getControllerMethods()
{
    $controllersPath = app_path('Http/Controllers');
    $controllerClasses = [];

    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($controllersPath)
    );

    foreach ($iterator as $file) {
        if ($file->isDir()) {
            continue; // Skip directories
        }

        $filePath = $file->getPathname();
        if (!str_ends_with($filePath, '.php')) {
            continue; // Skip non-PHP files
        }

        if($file->getFileinfo()->getFilename() == 'Controller.php'){
            continue;
        }

        $relativePath = str_replace([$controllersPath . '/', '.php'], ['',''], $filePath);
        $className = 'App\\Http\\Controllers\\' . str_replace('/', '\\', $relativePath);

        $reflectionClass = new ReflectionClass($className);

        if ($reflectionClass->isSubclassOf(Controller::class) && !$reflectionClass->isAbstract()) {
            $methods = $reflectionClass->getMethods(ReflectionMethod::IS_PUBLIC);

            $controllerMethods = [];
            foreach ($methods as $method) {
                if ($method->class === $className) {
                    $controllerMethods[] = $method->name;
                }
            }

            $controllerClasses[$className] = $controllerMethods;
        }
    }

    return $controllerClasses;
}



function getMethodDocInfo(string $className, $methodName, $identifier)
{
    $reflection = new ReflectionMethod($className, $methodName);
    $docComment = $reflection->getDocComment();

    // Use regex pattern to match the identifier and extract the text after it
    $pattern = '/@' . preg_quote($identifier, '/') . '\s+(.*)/';
    preg_match($pattern, $docComment, $matches);

    if (isset($matches[1])) {
        trim($matches[1]);
        $end = strpos($matches[1], ' ',0);
        if($end){
            return substr($matches[1], 0, $end);
        }
        return $matches[1];
    }

    return null;
}

function getClassDocBlock($className, $identifier)
{
    $reflection = new ReflectionClass($className);
    $docComment = $reflection->getDocComment();
    $pattern = '/@' . preg_quote($identifier, '/') . '\s+(.*)/';
     preg_match($pattern, $docComment, $matches);

    if (isset($matches[1])) {
        trim($matches[1]);
        $end = strpos($matches[1], ' ',0);
        if($end){
            return substr($matches[1], 0, $end);
        }

        return $matches[1];
    }
}

function setMiddlewares($class, $method):array
{
    /** Combines the middleware set on the class to that set on the method  */

    $middlewares = getClassDocBlock($class, 'middleware');


    $methodMiddlewares = getMethodDocInfo($class, 'store', 'middleware');

    if($methodMiddlewares){
        $middlewares .= ','.$methodMiddlewares;
    }

    if(!$middlewares){
        return [];
    }

    $routeMiddlewares = explode(',', $middlewares);


    //create missing roles and permissions
    array_map(function ($middleware) {

        if (strpos($middleware, 'role:') !== false) {
            $role = str_replace('role:', '', $middleware);
            $roleArray = explode(',', $role);
            Role::firstOrCreate([
                'name' => trim($role)
            ]);
        }

        if (strpos($middleware, 'permission:') !== false) {
            $permission = str_replace('permission:', '', $middleware);
            $permissionArray = explode(',', $permission);
            Permission::firstOrCreate([
                'name' => trim($permission)
            ]);
        }

        if (strpos($middleware, 'can:') !== false) {
            $permission = str_replace('can:', '', $middleware);
            $permissionArray = explode(',', $permission);
            Permission::firstOrCreate([
                'name' => trim($permission)
            ]);
        }


    }, $routeMiddlewares);


    return $routeMiddlewares;



}


function isIgnored($class, $method) : bool
{
    return getMethodDocInfo($class, $method, 'ignore') != null;
}

function getHttpMethod($class, $method) : array
{
    $methods = explode(',', getMethodDocInfo($class, $method, 'httpMethod'));

    $httpVerbs = [
        'index' => 'GET',
        'show' => 'GET',
        'create' => 'GET',
        'store' => 'POST',
        'edit' => 'GET',
        'update' => 'PUT',
        'destroy' => 'DELETE',
    ];

    if($methods != null and array_intersect($methods, array_unique(array_values($httpVerbs)))){
        return  $methods;
    }

    return [$httpVerbs[$method]];
}


function getPathParameters(string $className, string $methodName) : string
{

    $reflectionMethod = new ReflectionMethod($className, $methodName);
    $parametersInfo = $reflectionMethod->getParameters();
    $paths = [];



    foreach ($parametersInfo as $parameterInfo) {
        if(($parameterInfo->getType() instanceof ReflectionNamedType)
            and (
                is_subclass_of($parameterInfo->getType()->getName(),  'Illuminate\Database\Eloquent\Model')
                ||
                in_array($parameterInfo->getType()->getName(), ['string','int','float','bool','array','object','mixed'])
            )){
                $paths[] = [
                    'name' => $parameterInfo->getName(),
                    'type' => $parameterInfo->getType(),
                    'optional' => $parameterInfo->isOptional(),
                ];
            }
    }


    $parameter = '/';

    foreach ($paths as $path) {


        if($path['optional']){
            $parameter .= count($paths) == 1 ? '{'.$path['name'].'?}/' : $path['name'].'/{'.$path['name'].'?}/';
        }else{
            $parameter .= count($paths) == 1 ? '{'.$path['name'].'}/' : $path['name'].'/{'.$path['name'].'}/';
        }


    }

    return ($parameter);
}
