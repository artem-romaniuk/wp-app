<?php

namespace App\Core\Controller;


abstract class Controller
{
    protected $layout = 'default';


    abstract public function single();

    public function view($template, array $data = [])
    {
        $viewsPath = app('path.views');

        $individualTemplate = is_file($viewsPath . DIRECTORY_SEPARATOR . $template . '-' . $this->templateName() . '.php') ? $viewsPath . DIRECTORY_SEPARATOR . $template . '-' . $this->templateName() . '.php' : null;
        $requestedTemplate = is_file($viewsPath . DIRECTORY_SEPARATOR . $template . '.php') ? $viewsPath . DIRECTORY_SEPARATOR . $template . '.php' : null;
        $defaultTemplate = $viewsPath . DIRECTORY_SEPARATOR . 'layouts' . DIRECTORY_SEPARATOR . 'default.php';

        $contentTemplate = $individualTemplate ? $individualTemplate : ($requestedTemplate ? $requestedTemplate : $defaultTemplate);

        extract($data);

        require_once $viewsPath . DIRECTORY_SEPARATOR . 'layouts' . DIRECTORY_SEPARATOR . 'header.php';

        require_once $contentTemplate;

        require_once $viewsPath . DIRECTORY_SEPARATOR . 'layouts' . DIRECTORY_SEPARATOR . 'footer.php';
    }

    protected function templateName()
    {
        $id = get_queried_object_id();

        $pageName = get_query_var('pagename');

        if (!$pageName && $id) {
            $post = get_queried_object();

            if ($post) {
                $pageName = $post->post_name;
            }
        }

        return $pageName ? urldecode($pageName) : ($id ? $id : '');
    }
}