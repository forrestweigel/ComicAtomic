<?php
namespace lobby;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class lobbyCode implements MessageComponentInterface {
    protected $clients;
    private $users;
    private $ids;
    private $games;
    private $test;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
        $this->users = [];
        $this->games = [];
        $this->ids = [];
        $this->test = [];
    }

    public function onOpen(ConnectionInterface $conn) 
    {
        $this->clients->attach($conn);

        $this->users[$conn->resourceId] = [
            'connection' => $conn
        ];

        echo "New connection! ({$conn->resourceId})\n";
      
        $this->eventconnect($conn);   
    }  

    private function eventconnect($from)
    {
        //NEED TO USE SQL DATABASE TO LIST USERS
    }

    function sendStart($from, $msg)
    {
        if(is_object($msg) || is_array($msg)) 
        {
            $msg = json_encode($msg);
            var_dump($msg);
        }

        var_dump($msg);

        foreach ($this->clients as $client) 
        {
            $client->send($msg);    
        }
    }

    function sendMessageToAll($from,$msg)
    {       
        if(is_object($msg) || is_array($msg)) 
        {
           $msg = json_encode($msg);
        }

        $sender = array(
            "command"=> $msg,
            "id"=> $from
    );

    $sender= json_encode($sender);

        foreach ($this->clients as $client) 
        {
            $client->send($sender);     
        }
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        $numRecv = count($this->clients) - 1;
        echo sprintf('Connection %d sending message "%s" to %d other connection%s' . "\n"
            , $from->resourceId, $msg, $numRecv, $numRecv == 1 ? '' : 's');
        $data = json_decode($msg);

        
       
        switch($data->command){
            case "create":
                $d = $data->command;
                $id = $from->resourceId;

                $games[$id] = [
                    'user1' => $id,
                    'user2' => ''
                ];
                
                $this->sendMessageToAll($id,$d);
                break;

            case "join":
                $test = $data->id;             
                $sender = array("command"=> 'join',"id1"=> $test,"id2"=> (string)$from->resourceId);

                $games[$test] = [
                    'user1' => $test,
                    'user2' => (string)$from->resourceId     
                ];

                $this->sendStart($test,$sender);
                break;

            case "start":
                $user1 = $data->id1;
                $user2 = $data->id2;
                $this->onJoin($user1,$user2);
                break;

            case "packet":
                
                break;
        }
    }

    private function packet()
    {}

    private function onJoin($user1, $user2){

        $sender = array("command"=> 'start', "id1"=> $user1, "id2"=> $user2);

        $sender = json_encode($sender);

        foreach ($this->clients as $client) 
        {
            if ($user1 == $client->resourceId || $user2 == $client->resourceId) 
            {
                $client->send($sender);
            }          
        }
    }

    public function onClose(ConnectionInterface $conn) 
    {
         $this->clients->detach($conn);

         echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) 
    {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }
}