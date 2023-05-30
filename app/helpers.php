<?php
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Permission;

function getClassFromFilePath($filePath)
{
    $appPath = app_path();
    $relativePath = "App" . Str::after($filePath, $appPath);
    $namespace = Str::replace('/', '\\', Str::beforeLast($relativePath, '.php'));
    $className = Str::studly($namespace);

    return $className;


}
function initializeRoutes(string $path){
    $classes = getControllerMethods($path);
    // dd($classes);
    foreach ($classes as $class => $property) {
         $methods = $property['methods'];

        $prefix = $property['prefix'] != '' ? $property['prefix']. '/' : '';

        Route::controller($class)->prefix($prefix . strtolower(str_replace('Controller','',basename(str_replace('\\', '/', $class)))))

                ->group(function() use ($methods, $class){

                    array_map(function ($method) use ($class) {
                        if(!isIgnored($class, $method)){

                            $middleware = setMiddlewares($class,$method);
                            $httpMethod = getHttpMethod($class, $method);

                            Route::middleware($middleware)->match($httpMethod,  getPathParameters($class, $method), $method);
                        }
                    }, $methods);

        });
    }
}


function getControllerMethods(string $path = 'Http/Controllers')
{
    $controllersPath = app_path($path);
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
        // dd($filePath);
        $className =   (getClassFromFilePath($filePath));

        $reflectionClass = new ReflectionClass($className);

        if ($reflectionClass->isSubclassOf(Controller::class) && !$reflectionClass->isAbstract()) {
            $methods = $reflectionClass->getMethods(ReflectionMethod::IS_PUBLIC);

            $controllerMethods = [];
            foreach ($methods as $method) {
                if ($method->class === $className) {
                    $controllerMethods[] = $method->name;
                }
            }
            $root_path = (explode('/',"App/" . $path));

            $controllerClasses[$className]['methods'] = $controllerMethods;
            $controllerClasses[$className]['prefix'] =  str_replace('\\','',(strtolower(str_replace($root_path, "", $reflectionClass->getNamespaceName()))));

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


    $methodMiddlewares = getMethodDocInfo($class, $method, 'middleware');

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
    return  [ ($httpVerbs[$method])  ?? 'GET' ];
}


function getPathParameters(string $className, string $methodName) : string
{
    $parameter = '';

    if(!in_array($methodName, ['index','create','show','store','edit','update','destroy'])){
         $parameter = $methodName;
    }

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




    foreach ($paths as $path) {


        if($path['optional']){
            $parameter .= count($paths) == 1 ? '{'.$path['name'].'?}/' : $path['name'].'/{'.$path['name'].'?}/';
        }else{
            $parameter .= count($paths) == 1 ? '{'.$path['name'].'}/' : $path['name'].'/{'.$path['name'].'}/';
        }


    }

    return ($parameter);
}

