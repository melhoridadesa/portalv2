<?php

namespace app\Controller;

abstract class BaseController
{
    private $view;
    private $conn;
    protected $app;
    private $viewName;
    private $folder;
    private $menu;
    const   MODULE = 'modules/adminportal';
    public function __construct(\Pimple $container, $request)
    {
        $this->view = $container['twig'];
        //$this->conn = $container['db'];
        $this->app = $container;
        $this->request = $request;
        $this->path = $this->getView();
        $this->register();
    }
    protected function fetchAll($query)
    {
        return $this->conn->fetchAll($query);
    }
    
    protected function render($tpl, $params = [], $folder = 'pages')
    {
        $this->setPathView($folder);
        
        return $this->view->render(
            $this->getFullPath($tpl),
            $this->setParams($params)
        );
    }
    
    private function getDefaultParams()
    {
        /*return [
                'error' => $this->app['security.last_error']($this->request),
                'last_username' => $this->app['session']->get('_security.last_username'),
            ];*/
        return [
            "menu" => $this->menu()
        ]; 
    }
    
    private function setParams($params = [])
    {
        return array_merge((array) $this->getDefaultParams(), (array) $params);
    }
    
    public function setPathView($folder)
    {
        $this->viewName = $this->getPathView($folder);
    }
    
    protected function getFullPath($tpl, $extension = '.tpl')
    {
        return $this->viewName . $tpl . $extension;
    }
    
    public function getView()
    {
        $class = static::class;
        
        $this->viewName = strtolower(
            str_replace('Controller', '', end(explode('\\', $class)))
        );
        
        return $this;
    }
    
    public function getPathView($path)
    {
       
        $this->viewName = sprintf('%s/%s/', $path, $this->viewName);
        
        return $this->viewName;
    }
    
    public function register()
    {
        
    }

    public function menu($tipo_acesso = 'u')
    {
        $sql = "SELECT * FROM menu WHERE tipo_acesso = '{$tipo_acesso}'" ;
        $dados = $this->app['db']->fetchAll($sql);
        
        $valoresMenu = array();
        foreach($dados as $menu) {
            $valoresMenu[] = $menu;

        }
        return $valoresMenu;
        
    }
}