<?php

namespace Apollo16\Controller;

/**
 * Resource sharing traits uses on controller.
 *
 * @author      mohammad.anang  <m.anangnur@gmail.com>
 * @package     complaint
 */

trait ResourceSharing
{
    /**
     * Controller data.
     *
     * @var array
     */
    protected $controllerData = [];

    /**
     * Active menu indicator.
     *
     * @var array
     */
    protected $activeMenu = [];

    /**
     * Page title.
     *
     * @var string
     */
    protected $pageTitle;

    /**
     * Body class.
     * 
     * @var string
     */
    protected $bodyClass;

    /**
     * Page meta.
     *
     * @var array
     */
    protected $pageMeta = [
        'description' => null,
        'keywords' => null,
    ];

    /**
     * Reserved variable for the controller.
     *
     * @var array
     */
    protected $reservedVariables = ['activeMenu', 'pageTitle', 'pageMeta', 'bodyClass'];

    /**
     * Serve blade templates.
     *
     * @param string $view
     * @return \Illuminate\View\View
     */
    public function view($view)
    {
        if (false === array_key_exists('pageTitle', $this->controllerData)) {
            $this->setPageTitle('Untitled');
        }

        if (false === array_key_exists('bodyClass', $this->controllerData)) {
            $this->setBodyClass('');
        }

        $this->setPageMeta('csrf_token', csrf_token());

        $this->controllerData['activeUser'] = $this->getAuthenticatedUser();
        $this->controllerData['activeMenu'] = $this->activeMenu;
        $this->controllerData['pageMeta'] = $this->pageMeta;

        return view($view, $this->controllerData);
    }

    /**
     * Set controller data.
     *
     * @param string $name
     * @param mixed $value
     * @return $this
     * @throws \Exception
     */
    public function setData($name, $value)
    {
        if(in_array($name, $this->reservedVariables)) {
            throw new \Exception("Variable [$name] is reserved be this controller");
        }

        $this->controllerData[$name] = $value;

        return $this;
    }

    /**
     * Set page meta
     *
     * @param string $metaKey
     * @param mixed $metaValue
     * @return $this
     */
    public function setPageMeta($metaKey, $metaValue)
    {
        $this->pageMeta[$metaKey] = $metaValue;

        return $this;
    }

    /**
     * Set page title.
     *
     * @param string $title
     * @return $this
     */
    public function setPageTitle($title)
    {
        $this->controllerData['pageTitle'] = $title;

        return $this;
    }

    /**
     * Set body class.
     *
     * @param string $title
     * @return $this
     */
    public function setBodyClass($class)
    {
        $this->controllerData['bodyClass'] = $class;

        return $this;
    }

    /**
     * Get authenticated user.
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable
     */
    protected function getAuthenticatedUser()
    {
        return auth()->user();
    }
}
