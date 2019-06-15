<?php

namespace App\Core\Controller;

abstract class Controller
{
    protected $layout = 'default';

    abstract public function single();

    public function view($template, array $data = []): void
    {
        $viewsPath = app('path.views');

        $object = get_queried_object();

        $template = $viewsPath . DIRECTORY_SEPARATOR . $template .$this->templateName($object) . '.php';

        if (!is_file($template)) {
            throw new \Exception(sprintf('Template file %s not found', $template));
        }

        extract($data);

        require_once $viewsPath . DIRECTORY_SEPARATOR . 'layouts' . DIRECTORY_SEPARATOR . 'header.php';

        require_once $template;

        require_once $viewsPath . DIRECTORY_SEPARATOR . 'layouts' . DIRECTORY_SEPARATOR . 'footer.php';
    }

    protected function templateName($object): string
    {
        return ($object instanceof \WP_Post && isset($object->page_template_name))
            ? '-' . strtolower($object->page_template_name)
            : '';
    }
}
