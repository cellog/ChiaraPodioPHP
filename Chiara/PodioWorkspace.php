<?php
namespace Chiara;
use Chiara\Iterators\WorkspaceAppIterator, Chiara\Remote, Chiara\PodioApp as App,
    Chiara\PodioContact as Member, Chiara\PodioTask as Task; //TODO: make the task class
class PodioWorkspace
{
    protected $info;
    protected $apps = array();
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
        if (count($this->apps)) {
            return $this->apps;
        }
        Auth::verifyNonApp('workspace');
        $this->apps = Remote::$remote->get('/app/space/' . $this->info['space_id'], array('include_inactive' => $include_inactive))->json_body;
        return $this->apps;
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
            $this->apps = $value;
        }
    }

    /**
     * Generate helper classes for an entire workspace
     *
     * @param directory name to save the files
     * @param string prefix for class name, if any
     * @param namespace name, if any
     */
    function generateClasses($directory, $namespace = null, $classprefix = null, $podioitemclass = 'Chiara\PodioItem', array $implements = array())
    {
        $ret = array();
        foreach ($this->apps as $app) {
            $classname = $classprefix . str_replace('-', '_', $app->url_label);
            $structureclassname = $classprefix . $classname . 'Structure';
            $appdefinition = $app->generateClass($classname, $app->id, $structureclass, $namespace, $implements,
                                $directory . '/' . $classname . '.php', $itemclass);
            $structuredefinition = $app->generateStructureClass($structureclassname, $namespace, $directory . '/' . $structureclassname . '.php');
            $ret[$app->id] = array($appdefinition, $structuredefinition);
        }
        return $ret;
    }
}