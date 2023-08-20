<?php
use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;
class Chat implements MessageComponentInterface
{
    protected $clients = null;
    protected $users = [];
    protected $db = null;
    public function __construct($db)
    {
        $this->clients = new SplObjectStorage;
        $this->db = $db;
    }
    public function onOpen(ConnectionInterface $conn): void
    {
        $this->clients->attach($conn);
    }
    public function onMessage(ConnectionInterface $from, $msg): void
    {
        $package = json_decode($msg);
        if (is_object($package) == true) {
            switch ($package->type) {
                case 'message':
                    if (ENABLE_DATABASE == true) {
                        if (isset($package->from_user) && isset($package->to_user)) {
                            $date = date("Y-m-d");
                            $this->db->insert(
                                $package->to_user,
                                $package->from_user,
                                $package->message,
                                $date
                            );
                            $insert = $this->db->insertid($package->from_user,$package->to_user);
                            $resourceId = $this->db->getpwd($package->to_user,$package->usertype);
                            $notify = $this->db->pushnotify($package->from_user,$package->to_user,$package->message,$insert['id'],$package->usertype); 
                        
                            
                            $sendpackage = array(
                                'to_res'            =>  $package->to_user,
                                'from_user'         =>  $package->from_user,
                                'chatid'            =>  $insert['id'],
                                'fromurl'           =>  $resourceId['username'],
                                'fromimgsrc'        =>  'avatar2.jpg',
                                'chattime'          =>  $insert['chattime'],
                                'msg'               =>  $package->message,
                                'message'           =>  $package->message,
                                'resourceId'        =>  $resourceId,
                                'notify'            =>  $notify,
                                'type'              =>  'receive'
                            );
                            
                            $package = json_encode($sendpackage);
                            foreach ($this->clients as $key => $client) {
                                if($client->resourceId  == $resourceId['pwd']){
                                    $client->send($package);
                                }
                                if($client->resourceId  == $from->resourceId){
                                    $client->send($package);
                                }
                            }
                        }
                    }       
                    break;
                case 'registration':
                    $this->db->update_pwd($package->user->id,$from->resourceId,$package->user->usertype);
                    break;
                case 'unregistration':
                    $this->db->update_pwd($from->id,'');
                    break;
            }
        }
    }
    public function onClose(ConnectionInterface $conn): void
    {
        unset($this->users[$conn->resourceId]);
        $this->clients->detach($conn);
    }
    public function onError(ConnectionInterface $conn, \Exception $e): void
    {
        unset($this->users[$conn->resourceId]);
        $conn->close();
    }
}
