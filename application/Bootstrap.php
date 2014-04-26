<?php
/**
 * Application bootstrap
 *
 * @uses    Zend_Application_Bootstrap_Bootstrap
 * @package QuickStart
 */
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

    protected function _initAppAutoload()
    {
        $autoloader = new Zend_Application_Module_Autoloader(array(
            'namespace' => 'App',
            'basePath' => dirname(__FILE__),
        ));
        return $autoloader;
    }

    protected function _initPlugins()
    {
        $language = array(
            short => 'de'
        );
        Zend_Registry::set('language',$language);

        $front = Zend_Controller_Front::getInstance();

        $front->registerPlugin(new Zend_Controller_Plugin_ErrorHandler());
        $front->registerPlugin(new RF_Controller_NavigationPlugin());

        if(strstr(APPLICATION_ENV,'development')){
            $front->throwExceptions(true);
        }else{
            $front->throwExceptions(false);
        }

        //$front->setBaseUrl(HTTP_ROOT);

        Zend_View_Helper_PaginationControl::setDefaultViewPartial('pagination.phtml');
    }

    protected function _initLogger(){
        if(strstr(APPLICATION_ENV,'development')){
            $writer = new Zend_Log_Writer_Firebug();
            $logger = new Zend_Log($writer);
            $logger->registerErrorHandler();
            Zend_Registry::set('logger', $logger);
        }else{
            Zend_Registry::set('logger',null);
        }
    }

    protected function _initOutputCache(){

        // Output Cache for Social Media and other external feeds

        $frontendOptions = array(
            'lifeTime' => 300
        );
        $backendOptions = array(
            'cache_dir' => APPLICATION_PATH.'/../cache/'
        );
        $outputCache = Zend_Cache::factory('Output', 'File', $frontendOptions, $backendOptions);
        Zend_Registry::set('outputCache', $outputCache);

    }

    protected function _initImageCache(){

        // Image Output cache for resized images

        $frontendOptions = array(
            'lifeTime' => 0
        );
        $backendOptions = array(
            'cache_dir' => APPLICATION_PATH.'/../cache/images'
        );
        $imageCache = Zend_Cache::factory('Output', 'File', $frontendOptions, $backendOptions);
        Zend_Registry::set('imageCache',$imageCache);
    }

    protected function _initModuleLayouts()
    {
        $front = Zend_Controller_Front::getInstance();

        Zend_Layout::startMvc(array(
            "layoutPath" => APPLICATION_PATH.'/modules/default/views/scripts/',
            "layout" => "layout"
        ));

        $layoutModulePlugin = new RF_Controller_LayoutPlugin();

        $layoutModulePlugin->registerModuleLayout("default",APPLICATION_PATH."/modules/default/views/scripts","layout");

        $front->registerPlugin($layoutModulePlugin);
    }

    protected function _initViewHelpers()
    {

        $this->bootstrap('view');
        $view = $this->getResource('view');
        $view->addHelperPath(APPLICATION_PATH . '/../library/RF/View/Helper', 'RF_View_Helper');
        $view->addHelperPath(APPLICATION_PATH . '/../library/Local/View/Helper', 'Local_View_Helper');
        $view->doctype('XHTML1_STRICT');
        $view->addScriptPath(APPLICATION_PATH.'/views');
    }

    protected function _initRouterImage()
    {
        // Router for Image controller, making nice
        // looking Image-URL with extensions for Compatibility

        $route = new Zend_Controller_Router_Route_Regex(
            'image_rendered\/(\w*?)\/([a-zA-Z0-9\-\.]*?)\/([0-9]*?)x([0-9]*?)_([a-z]*?)\.(jpg|png|gif)',
            array(
                'module' => 'default',
                'controller' => 'image',
                'action'     => 'index'
            ),
            array(
                1 => 'path',
                2 => 'filename',
                3 => 'w',
                4 => 'h',
                5 => 'crop'
            ),
            'image_rendered/%s/%s/%sx%s_%s.jpg'
        );
        Zend_Controller_Front::getInstance()->getRouter()->addRoute('image', $route);
    }

    protected function _initRouterStatic(){

    }

    protected function _initRestRoute()
    {
        $front = Zend_Controller_Front::getInstance();
        $router = $front->getRouter();
        $restRoute = new Zend_Rest_Route($front, array(), array(
            'api',
        ));
        $router->addRoute('rest', $restRoute);
    }

    protected function _initMail()
    {
        if(APPLICATION_ENV=='staging' || APPLICATION_ENV=='testing' || APPLICATION_ENV=='live'){
            $transport = new Zend_Mail_Transport_Smtp('localhost');
            Zend_Mail::setDefaultTransport($transport);
            Local_Automailer::setDefaultTransport($transport);
        }else{
            // Standard Sendmail
        }
    }

}
