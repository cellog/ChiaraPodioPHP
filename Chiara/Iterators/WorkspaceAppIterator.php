<?php
namespace Chiara\Iterators;
use Chiara\PodioWorkspace as Workspace, Chiara\PodioApp;
class WorkspaceAppIterator extends \ArrayIterator
{
    protected $workspace;
    protected $map = array();
    function __construct(Workspace $workspace)
    {
        $this->workspace = $workspace;
        parent::__construct($workspace->getApps());
        foreach ($this->getArrayCopy() as $i => $field) {
            $this->map[$field['app_id']] = $this->map[$field['url_label']] = $i;
        }
    }

    function current()
    {
        $info = parent::current();
        return new PodioApp($info, 'force');
    }

    function offsetGet($index)
    {
        if (is_int($index) && $index < 30) {
            $info = parent::offsetGet($index);
        } else {
            $info = parent::offsetGet($this->map[$index]);
        }
        return new PodioApp($info, 'force');
    }
}