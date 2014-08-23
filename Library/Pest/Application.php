<?php
namespace Pest;

class Application
{

    public function autoload ($class)
    {
        if (substr($class, 0, 4) === "Pest") {
            $path = dirname(dirname(__FILE__)) . '/' .
                     str_replace("\\", DIRECTORY_SEPARATOR, $class) . '.php';
            return require_once $path;
        }
        $incpaths = get_include_path();
        $arr = explode(PATH_SEPARATOR, $incpaths);
        
        $p = str_replace("\\", DIRECTORY_SEPARATOR, $class) . '.php';
        foreach ($arr as $v) {
            $path = $v . $p;
            if (file_exists($path)) {
                return require_once $path;
                break;
            }
        }
        return false;
    }

    public function __construct ($config)
    {
        spl_autoload_register(array(
                __CLASS__,
                'autoload'
        ));
        Config::set('db', $config['db']);
    }

    /**
     * 复数判断，需要完善
     * 
     * @param string $str            
     */
    private function isPlural ($str)
    {
        return preg_match('/^[a-z]*s$/', $str);
    }

    private function caseTrans ($str, $div)
    {
        $arr = explode($div, $str);
        foreach ($arr as &$v) {
            $v = ucfirst($v);
        }
        return implode($div, $arr);
    }

    public function run ()
    {
        $request = Request::getInstance();
        $response = Response::getInstance();
        if ('/' === $request->getUri()) {
            $response->end(404);
            return;
        }
        $url = $request->getUri();
        // handle backbone sytle url, /user/12312323123213 \PUT
        if (preg_match('/.*\/\S{24}/', $request->getUri())) {
            $url = substr($request->getUri(), 0, strlen($request->getUri()) - 25);
            $id = substr($request->getUri(), 0, - 24);
            $request->appendData('id', $id);
        }
        $arr = explode('/', $url);
        $last = array_pop($arr);
        $method = $request->getMethod();
        
        $api_str = 'Api' . $this->caseTrans(str_replace('/', '\\', $url), '\\');
        
        if ($this->isPlural($last)) {
            $api_str = substr($api_str, 0, - 1);
            $method = 'all';
        }
        $api = new $api_str();
        
        if (! method_exists($api, $method)) {
            Response::sendFailure(1000);
            exit();
        }
        
        if (property_exists($api, $method)) {
            if (! $api->valid($api->$method)) {
                Response::sendFailure(1001);
                exit();
            }
        }
        ob_start();
        $api->$method();
        
        ob_flush();
    }
}