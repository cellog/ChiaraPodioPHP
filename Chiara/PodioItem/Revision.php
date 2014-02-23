<?php
namespace Chiara\PodioItem;
use Podio, Chiara\PodioItem as Item, Chiara\AuthManager as Auth, Chiara\Remote;
class Revision
{
    /**
     * @var Chiara\PodioItem
     */
    protected $item;
    function __construct(Item $item, $info = null, $retrieve = true)
    {
        if (!isset($item->app['app_id']) || !isset($item->info['item_id'])) {
            throw new \Exception('Cannot retrieve a revision for a blank item, both item_id and app_id must be set');
        }
        $this->item = $item;
        if (is_array($info) && $retrieve !== 'force') {
            $this->info = $info;
            return;
        }
        if (is_int($info)) {
            $info = array('revision_id' => $info);
        }
        $this->info = $info;
        if (!$retrieve || !$info) return;
        $this->retrieve();
    }

    function retrieve()
    {
        Auth::prepareRemote($this->item->app_id);
        $this->info = Remote::$remote->get('/item/' . $this->item->item_id . '/revision/' .
                                           $this->info['revision_id'])->json_body();
    }

    function __get($var)
    {
        if ($var === 'id') return $this->info['revision_id'];
        return $this->info[$var];
    }

    function diff(Revision $revision)
    {
        Auth::prepareRemote($this->item->app_id);
        return $this->item->getRevisionDiff($this->id, $revision->id);
    }
}