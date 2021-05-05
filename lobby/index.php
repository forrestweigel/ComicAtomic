<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lobby</title>
    <!-- <script src="https://cdn.socket.io/3.1.3/socket.io.min.js" integrity="sha384-cPwlPLvBTa3sKAgddT6krw0cJat7egBga3DJepJyrLl4Q9/5WLra3rrnMcyTyOnh" crossorigin="anonymous"></script> -->
    <!-- Bootstrap -->
    <link href="css/bootstrap-4.4.1.css" rel="stylesheet">

</head>
   <body>
      <div class="jumbotron">  </div>    
         <div class ="row">
            <div class="col-md-6 text-center col-xl-4">
               <div class="card">
                  <div class="card-body">
                     <h3>&nbsp;</h3>
                     
                     <p><button type="button" class="btn btn-success btn-md" id="create">Create Game</button></p>
                     <button type="button" class="btn btn-success btn-md" id="join">&nbsp&nbsp Join Game&nbsp</button>
                  </div>
               </div>
            </div>
            
            <div class="col-md-6 text-center col-xl-4">
               <div class="card">
               <h3>Games&nbsp;</h3>
                  <div class="card-body" id = "games">
                     <select class="form-select" aria-label="Default select example" id="gamelist">
                           
                     </select>
                  </div>			  
               </div>
            </div> 
         </div>

      <script> 

         var conn = new WebSocket('ws://localhost:1337');

         conn.onopen = function(e) 
         {
            console.log("connection established!");
         }
   
         conn.onmessage = function(e) 
         {
            var msg = JSON.parse(e.data);  
            console.log(msg)         
            
            if(msg.command == "create")
            {
               var li = document.createElement('option');            
               var list = document.getElementById("gamelist");

               li.setAttribute('value', msg.id);
               li.setAttribute('id', msg.id);
               li.appendChild(document.createTextNode(msg.id + "'s game"));
               list.appendChild(li);
            }

            else if(msg.command == "join")
            {
               var item = document.getElementById(msg.id);

               var remove = document.getElementById("gamelist");
               remove.remove(remove.item);

               conn.send(JSON.stringify({command: "start", id1:msg.id1, id2:msg.id2}));

            }

            else if(msg.command == 'start')
            {
               window.location.href = "/comicatomic/game/index.php"; 
            }
            
         };

      </script>
         <script type="application/javascript">

         document.getElementById("join").addEventListener("click", function () 
         {
            var sel = document.getElementById('gamelist').value;
            conn.send(JSON.stringify({command: "join", id: sel}));                 
         });

         document.getElementById("create").addEventListener("click", function () 
         {
            var button = document.getElementById('join');
            button.disabled = true;
            var button = document.getElementById('create');
            button.disabled = true;
            conn.send(JSON.stringify({command: "create", id: ''}));          
         });
      </script>
   </body>
</html>