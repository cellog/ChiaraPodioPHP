<?php
namespace Chiara;
use Chiara\AuthManager as Auth, Chiara\Remote;
class PodioStatus
{
    protected $space;
    protected $newfiles = array();
    protected $newembed = null;
    function __construct($info = null, PodioWorkspace $space = null, $retrieve = true)
    {
        $this->info = $info;
        $this->space = $space;
        if (is_array($info) && $retrieve !== 'force') {
            $this->info = $info;
            return;
        }
        if (is_int($info)) {
            $info = array('status_id' => $info);
        }
        if (!$retrieve) return;
        $this->retrieve();
    }

    function retrieve()
    {
        Auth::verifyNonApp('status message');
        $this->info = Remote::$remote->get('/status/' . $this->info['status_id'])->json_body();
    }

    function delete()
    {
        Auth::verifyNonApp('status message');
        return Remote::$remote->delete('/status/' . $this->info['status_id']);
    }

    function __get($var)
    {
        if ($var === 'id') return $this->info['status_id'];
        if (isset($this->info[$var])) {
            return $this->info[$var];
        }
    }

    function save($alert_invite = false)
    {
        $info = array();
        $info['value'] = $this->info['value'];
        if (count($this->newfiles)) {
            $info['file_ids'] = $this->newfiles;
        }
        if (isset($this->newembed)) {
            if (isset($this->newembed->embed_url)) {
                $info['embed_url'] = $this->newembed->embed_url;
            } elseif (isset($this->newembed->embed_id)) {
                $info['embed_id'] = $this->newembed->id;
            }
        }
        if (isset($this->id)) {
            Remote::$remote->put('/status/' . $this->id, $info);
        } else {
            if (!$this->space) {
                throw new \Exception('Cannot add status, no workspace set');
            }
            if (isset($this->newquestion)) {
                $info['question'] = $this->newquestion;
            }
            $this->info = Remote::$remote->post('/status/space/' . $this->space->id, $info, array('alert_invite' => $alert_invite))->json_body();
            $this->newquestion = null;
            $this->newembed = null;
            $this->newfiles = array();
        }
        return $this;
    }

    function addQuestion($question, array $options)
    {
        foreach ($answers as $a) {
            if (!is_string($a)) {
                throw new \Exception('all question options must be strings');
            }
        }
        if (!is_string($question)) {
            throw new \Exception('question must be a string');
        }
        $this->newquestion = array(
            'text' => $question,
            'options' => $options
        );
        return $this;
    }

    function addFile($file)
    {
        if (!is_int($file)) {
            throw new \Exception('Cannot add file directly yet, first upload using the Podio API and pass the file_id here');
        }
        return $this;
    }

    function __set($var, $value)
    {
        if ($var === 'id') $var = 'status_id';
        if ($var == 'embed') {
            $this->newembed = new PodioEmbed($value);
        }
        if ($var == 'newfiles') {
            if (is_array($value)) {
                foreach ($value as $file) {
                    $this->addFile($file);
                }
            } else {
                $this->addFile($file);
            }
        }
        $this->info[$var] = $value;
    }

    function __isset($var)
    {
        if ($var === 'id') $var = 'status_id';
        return is_array($this->info) && isset($this->info[$var]);
    }

    function __toString()
    {
        return $this->info['value'];
    }
}