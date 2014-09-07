<?php
namespace Chiara;
use Chiara\Iterators\WorkspaceAppIterator, Chiara\Remote, Chiara\PodioApp as App,
    Chiara\PodioContact as Member, Chiara\PodioTask as Task, Chiara\AuthManager as Auth; //TODO: make the task class
class PodioWorkspace
{
    protected $info;
    protected $myapps = array();
    protected $hookmanager = null;
    function __construct($info = null)
    {
        if (is_numeric($info)) {
            $info = (int) filter_var($info, FILTER_SANITIZE_NUMBER_INT);
        }
        if (is_int($info)) {
            $this->info = array('space_id' => $info);
            return;
        }
        $this->info = $info;
    }

    function retrieve()
    {
        if (!isset($this->info['space_id'])) {
            throw new \Exception('unknown space_id, cannot retrieve');
        }
        Auth::verifyNonApp('workspace');
        $this->info = Remote::$remote->get('/space/' . $this->info['space_id'])->json_body();
    }

    function createHook($podioaction, $action = null)
    {
        return HookServer::$hookserver->makeHook($this, $action, $podioaction);
    }

    function search($match, $limit = 20, $offset = 0)
    {
        return Remote::$remote->search($this, $match, $limit, $offset);
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
        if (!isset($this->info['space_id'])) {
            throw new \Exception('unknown space_id, cannot retrieve apps');
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
        if ($var === 'id') {
            return $this->info['space_id'];
        }
        if ($var === 'on' || $var === 'hook') return $this->hookmanager ? $this->hookmanager : $this->hookmanager = new Hook\Manager($this);
        if (count($this->info) == 1 && isset($this->info['space_id']) && $var !== 'space_id') {
            $this->retrieve();
        }
        return $this->info[$var];
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
                $m = $namespace;
                if ($m) $m .= '\\';
                if (isset($app->token)) {
                    Auth::getTokenManager()->saveToken($app->id, $app->token);
                }
                Auth::getTokenManager()->mapAppToClass($app->id, $m . $classname);
            }
        }
        return $ret;
    }
}