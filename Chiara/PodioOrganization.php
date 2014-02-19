<?php
namespace Chiara;
use Chiara\AuthManager as Auth, Chiara\Iterators\WorkspaceIterator;
class PodioOrganization
{
    protected $info = null;
    function __construct($org_info = null)
    {
        $this->info = $org_info;
    }

    function __get($var)
    {
        if ($var === 'workspaces') {
            return new WorkspaceIterator($this, $this->info['spaces']);
        }
        if (is_array($this->info) && isset($this->info[$var])) {
            return $this->info[$var];
        }
    }

    static function mine()
    {
        Auth::verifyNonApp('Organizations');
        $orgs = Remote::$remote->get('/org')->json_body();
        return new Iterators\OrganizationIterator($orgs);
    }

    static function sharedOrganizations($user)
    {
        if ($user instanceof self) {
            if (!isset($user->user_id)) {
                throw new \Exception('Contact ' . $user . ' is a space contact, not a podio contact, cannot list shared organizations');
            }
            $user = $user->user_id;
        } elseif (is_array($user)) {
            if (!isset($user['user_id'])) {
                throw new \Exception('Contact array has no user_id component, cannot list shared organizations');
            }
            $user = $user['user_id'];
        }
        if (!is_int($user)) {
            throw new \Exception('Cannot list shared organizations, user id is not an integer');
        }
        $orgs = Remote::$remote->get('/org/shared/' . $user)->json_body();
        return new Iterators\OrganizationIterator($orgs);
    }
}
