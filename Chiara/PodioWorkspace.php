<?php
namespace Chiara;
use Chiara\Iterators\WorkspaceAppIterator, Chiara\Remote, Chiara\PodioApp as App,
    Chiara\PodioContact as Member, Chiara\PodioTask as Task, Chiara\AuthManager as Auth; //TODO: make the task class
class PodioWorkspace
{
    protected $info;
    protected $myapps = array();
    function __construct($info = null)
    {
        if (is_int($info)) {
            $this->info = array('space_id' => $info);
            return;
        }
        $this->info = $info;
    }

    function createHook($podioaction, $action = null)
    {
        return HookServer::$hookserver->makeHook($this, $action, $podioaction);
    }

    function __invoke($post, $params)
    {
        if (isset($post['app_id'])) {
            $context = new App($post['app_id'], false);
        } elseif (isset($post['task_id'])) {
            $context = new Task($post['task_id'], false);
        } elseif (isset($post['user_id'])) {
            $context = new Member(array('user_id' => $post['user_id'], 'space_id' => $post['space_id']));
        }
        $func = explode('.', $post['type']);
        $func = array_map($func, function($a){return ucfirst($a);});
        $function = 'on' . implode('', $func);
        $this->$function($context, $params);
    }

    /**
     * override these to handle events
     */
    function onAppCreate(App $app, $params) {}
    function onTaskCreate(Task $task, $params) {}
    function onTaskDelete(Task $task, $params) {}
    function onTaskUpdate(Task $task, $params) {}
    function onMemberAdd(Member $member, $params) {}
    function onMemberRemove(Member $member, $params) {}

    function getApps($include_inactive = false)
    {
        if (count($this->myapps)) {
            return $this->myapps;
        }
        Auth::verifyNonApp('workspace');
        $this->myapps = Remote::$remote->get('/app/space/' . $this->info['space_id'] . '/',
                                             array('include_inactive' => (int) $include_inactive))->json_body();
        return $this->myapps;
    }

    function __get($var)
    {
        if ($var === 'apps') {
            return new WorkspaceAppIterator($this);
        }
    }

    function __set($var, $value)
    {
        if ($var === 'apps') {
            $this->myapps = $value;
        }
    }

    function __toString()
    {
        return $this->info['name'];
    }

    /**
     * Generate helper classes for an entire workspace
     *
     * @param directory name to save the files
     * @param string prefix for class name, if any
     * @param namespace name, if any
     */
    function generateClasses($directory, $namespace = null, $mapclasses = true, $classprefix = null,
                             $podioitemclass = 'Chiara\PodioItem', array $implements = array())
    {
        $ret = array();
        if (!file_exists($directory . DIRECTORY_SEPARATOR . 'Structure')) {
            mkdir($directory . DIRECTORY_SEPARATOR . 'Structure');
        }
        foreach ($this->apps as $app) {
            $classname = explode('-', $app->url_label);
            $classname = $classprefix . implode('', array_map(function($a){return ucfirst($a);}, $classname));
            $structurenamespace = $namespace ? $namespace . '\\Structure' : 'Structure';
            $appdefinition = $app->generateClass($classname, $app->id, $structurenamespace . '\\' . $classname, $namespace, $implements,
                                $directory . '/' . $classname . '.php', $podioitemclass);
            $structuredefinition = $app->generateStructureClass($classname, $structurenamespace, $directory . '/Structure/' . $classname . '.php');
            $ret[$app->id] = array($appdefinition, $structuredefinition);
            if ($mapclasses) {
                if ($namespace) $namespace .= '\\';
                Auth::getTokenManager()->mapAppToClass($app->id, $namespace . $classname);
            }
        }
        return $ret;
    }
}