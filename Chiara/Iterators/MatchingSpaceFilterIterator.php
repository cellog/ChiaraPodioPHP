<?php
namespace Chiara\Iterators;
class MatchingSpaceFilterIterator extends \FilterIterator
{
    protected $subportion;
    protected $parent;
    function __construct(WorkspaceIterator $sub, $subportion)
    {
        if ($subportion[0] !== '^') {
            $subportion = '.*' . $subportion;
        }
        if ($subportion[strlen($subportion) - 1] != '$') {
            $subportion .= '.*';
        }
        $subportion = '/' . $subportion . '/';
        $this->subportion = $subportion;
        $this->parent = $sub;
        parent::__construct($sub);
    }

    function accept()
    {
        $current = $this->parent->rawCurrent();
        return preg_match($this->subportion, $current['name']);
    }
}
