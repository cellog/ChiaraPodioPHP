<?php
namespace Chiara\Podio;
class Item extends \Chiara\PodioItem
{
    protected $MYAPPID=6686618;
    function __construct($info = null, $retrieve = true)
    {
        parent::__construct($info, new \Chiara\Podio\Testing, $retrieve);
    }

    /**
     * handle an item.create hook in here
     * @param array any url-specific parameters passed in to
     *              differentiate between hooks.  The item is already set up
     *              and can be used immediately.
     */
    function onItemCreate($params)
    {
        parent::onItemCreate($params);
    }

    function onItemUpdate($params)
    {
        parent::onItemUpdate($params);
    }

    function onItemDelete($params)
    {
        parent::onItemDelete($params);
    }

    function onCommentCreate($params)
    {
        parent::onCommentCreate($params);
    }

    function onCommentDelete($params)
    {
        parent::onCommentDelete($params);
    }

    function onFileChange($params)
    {
        parent::onFileChange($params);
    }
}
