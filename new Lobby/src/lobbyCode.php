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
        //make client array insteda of splobject
        $this->users = [];
        $this->games = [];
        $this->ids = [];
        $this->test = [];

       // $this->clients = [];
    }

    

    public function onOpen(ConnectionInterface $conn) {
        // Store the new connection to send messages to later
        
       $this->clients->attach($conn);

        $this->users[$conn->resourceId] = [
            'connection' => $conn

            //need to send username info from the auth page as a get or something? need to do this eithe
            // in private function or here. just attach username data to the client array
           // 'username' = $username;
        ];

       
        echo "New connection! ({$conn->resourceId})\n";
        
      
       $this->eventconnect($conn);
       
        
    }

    

    private function eventconnect($from){

     //NEED TO USE SQL DATABASE TO LIST USERS
                   
           // $me = $from->resourceId;
          //  $this->$ids = $me;
            //var_dump($ids);
           // $this->sendMessageToAll($ids,$from);
         
        

    }

    function sendStart($from, $msg){

        if(is_object($msg) || is_array($msg)) {
            $msg = json_encode($msg);
             var_dump($msg);
         }
         var_dump($msg);
         foreach ($this->clients as $client) {
          
        
            //var_dump($sender);
             $client->send($msg);
 
            
         }

    }

    function sendMessageToAll($from,$msg)
    {
        
        if(is_object($msg) || is_array($msg)) {
           $msg = json_encode($msg);
            //var_dump($msg);
        }

      $sender = array(
            "command"=> $msg,
            "id"=> $from
    );

    $sender= json_encode($sender);

        foreach ($this->clients as $client) {
          
           // $obj = (object) [
                //'command' => $msg,
                //'id' => (string)($from->resourceId)
           // ];

            //var_dump($obj);
            //$s = array( 'command' => $msg->command, 'id'=>$from->resourceId);
            //json_encode($obj);

           // var_dump($obj);
           //var_dump($sender);
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
                 // connection data for user 1 stored
               
                $d = $data->command;
                $id = $from->resourceId;
                //var_dump($id);
                //$test[$id] = $id;

                $games[$id] = [
                    'user1' => $id,
                    'user2' => ''
        
                   
                ];
                
                $this->sendMessageToAll($id,$d);
                //var_dump($games);
                //var_dump($test);
              
                break;

            case "join":
                //Connection data for user 2 stored
               // var_dump($data);
                $test = $data->id;
                
                $sender = array(
                    "command"=> 'join',
                    "id1"=> $test,
                    "id2"=> (string)$from->resourceId

                );

               // $d = $data->command;
                //$id2 = $from->resourceId;
               // $id1 = $data->$id;

               //need to store this here in database
                $games[$test] = [
                    'user1' => $test,
                    'user2' => (string)$from->resourceId
        
                   
                ];

                //var_dump($sender);;
                $this->sendStart($test,$sender);

               // onJoin($id,$from->resourceId);

                break;

            case "start":
                $user1 = $data->id1;
                $user2 = $data->id2;
                $this->onJoin($user1,$user2);
                break;
        }
           
    
           
            
 
       //foreach ($this->clients as $client) {
            //if ($from !== $client) {
                // The sender is not the receiver, send to each client connected
              //  $client->send($msg);

               // var_dump($msg);
          
     //   }

        //this needs to be refactored for game code !!!!
        //add game id in parameters

    }

   

    private function onJoin($user1, $user2){

        $sender = array(
            "command"=> 'start',
            "id1"=> $user1,
            "id2"=> $user2

        );

        $sender = json_encode($sender);


        foreach ($this->clients as $client) {
            if ($user1 == $client->resourceId || $user1 == $client->resourceId) {
                //echo "MADE IT!";
                // The sender is not the receiver, send to each client connected
                $client->send($sender);

               //var_dump($sender);
            }
          
        }

    //    foreach ($this->users as $user) {

            //if ($user !== $user1 || $user !== $user2) {
                 //The sender is not the receiver, send to each client connected
               // $user->send($msg);

                //var_dump($msg);
          
       // }
        //send message to two clients to open the game page and make a another array possiblyh
        // to store their connection data for the game side.

    }

    public function onClose(ConnectionInterface $conn) {
         // The connection is closed, remove it, as we can no longer send it messages
         $this->clients->detach($conn);

         echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }
}