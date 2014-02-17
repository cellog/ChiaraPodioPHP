<?php
namespace Chiara;
use Podio, Chiara\AuthManager as Auth, Chiara\HookServer;
class PodioApp
{
    protected $info;
    protected $hookmanager = null;
    function __construct($appid = null, $retrieve = true)
    {
        if (is_array($appid)) {
            $this->info = $appid;
            if ($retrieve !== 'force') return;
        } else {
            $this->info = array('app_id' => $appid);
        }
        if (!$retrieve) return;
        $this->retrieve();
    }

    function __invoke($post, $params)
    {
        $this->info['app_id'] = $post['app_id'];
        $func = explode('.', $post['type']);
        $func = array_map($func, function($a){return ucfirst($a);});
        $function = 'on' . implode('', $func);
        $this->$function($params);
    }

    /**
     * override these to handle events
     */
    function onAppUpdate($params) {}
    function onAppDelete($params) {}

    function retrieve()
    {
        Auth::prepareRemote($this->id);
        $this->info = Remote::$remote->get('/app/' . $this->id)->json_body();
    }

    function createHook($podioaction, $action = null)
    {
        return HookServer::$hookserver->makeHook($this, $action, $podioaction);
    }

    function __get($var)
    {
        if ($var === 'info') return $this->info;
        if ($var === 'fields') return new Iterators\AppFieldIterator($this);
        if ($var === 'id') return $this->info['app_id'];
        if ($var === 'on') return $this->hookmanager ? $this->hookmanager : $this->hookmanager = new Hook\Manager($this);
        if (isset($this->info[$var])) {
            return $this->info[$var];
        }
    }

    function generateStructureClass($classname, $namespace = null, $filename = null)
    {
        $structure = new PodioApplicationStructure;
        $structure->structureFromApp($this);
        return $structure->generateStructureClass($this->space_id, $this->app_id, $classname, $namespace, $filename);
    }

    function generateClass($classname, $appid, $structureclass, $namespace = null, array $implements = array(), $filename = null,
                           $itemclass = 'Chiara\PodioItem')
    {
        if (is_object($itemclass)) {
            $itemclass = get_class($itemclass);
        }
        $ret = "<?php\n";
        if ($namespace) {
            $ret .= "namespace $namespace;\n";
        }
        if ($implements) {
            $implements = ' implements ' . implode(', ', $implements);
        } else {
            $implements = '';
        }
        $ret .= "class $classname$implements extends \\" . $itemclass . "\n";
        $ret .= "{\n";
        $ret .= "    protected \$MYAPPID=" . $this->id . ";\n";
        $ret .= '    function __construct($info = null, $retrieve = true)' . "\n";
        $ret .= "    {\n";
        $ret .= "        parent::__construct(\$info, new \\$structureclass, \$retrieve);\n";
        $ret .= "    }\n";
        $ret .= "\n";
        $ret .= "    /**\n";
        $ret .= "     * handle an item.create hook in here\n";
        $ret .= "     * @param array any url-specific parameters passed in to\n";
        $ret .= "     *              differentiate between hooks.  The item is already set up\n";
        $ret .= "     *              and can be used immediately.\n";
        $ret .= "     */\n";
        $ret .= "    function onItemCreate(\$params)\n";
        $ret .= "    {\n";
        $ret .= "        parent::onItemCreate(\$params);\n";
        $ret .= "    }\n";
        $ret .= "\n";
        $ret .= "    function onItemUpdate(\$params)\n";
        $ret .= "    {\n";
        $ret .= "        parent::onItemUpdate(\$params);\n";
        $ret .= "    }\n";
        $ret .= "\n";
        $ret .= "    function onItemDelete(\$params)\n";
        $ret .= "    {\n";
        $ret .= "        parent::onItemDelete(\$params);\n";
        $ret .= "    }\n";
        $ret .= "\n";
        $ret .= "    function onCommentCreate(\$params)\n";
        $ret .= "    {\n";
        $ret .= "        parent::onCommentCreate(\$params);\n";
        $ret .= "    }\n";
        $ret .= "\n";
        $ret .= "    function onCommentDelete(\$params)\n";
        $ret .= "    {\n";
        $ret .= "        parent::onCommentDelete(\$params);\n";
        $ret .= "    }\n";
        $ret .= "\n";
        $ret .= "    function onFileChange(\$params)\n";
        $ret .= "    {\n";
        $ret .= "        parent::onFileChange(\$params);\n";
        $ret .= "    }\n";
        $ret .= "}\n";
        if ($filename) {
            file_put_contents($filename, $ret);
        }
        return $ret;
    }

    function dump()
    {
        var_export($this->info);
    }
}