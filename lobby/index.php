<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap - Prebuilt Layout</title>
    <!-- <script src="https://cdn.socket.io/3.1.3/socket.io.min.js" integrity="sha384-cPwlPLvBTa3sKAgddT6krw0cJat7egBga3DJepJyrLl4Q9/5WLra3rrnMcyTyOnh" crossorigin="anonymous"></script> -->
    <!-- Bootstrap -->
    <link href="css/bootstrap-4.4.1.css" rel="stylesheet">

</head>
  <body>
   <div class="jumbotron">  </div>    
      <br>
      <hr>
      <br> 
      <div class ="row">
          <div class="col-md-6 text-center col-xl-4">
             <div class="card">
                <div class="card-body">
                   <h3>&nbsp;</h3>
                  
                   <p><button type="button" class="btn btn-success btn-md" id="create" onclick="create();">Create Game</button></p>
                   <button type="button" class="btn btn-success btn-md" id="join">&nbsp&nbsp Join Game&nbsp</button>
                </div>
             </div>
          </div>
		   
		   <div class="col-md-6 text-center col-xl-4">
             <div class="card">
				  <h3>Active Users&nbsp;</h3>
                <div class="card-body" id="users">
                   
                    
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
      <br>
      <hr>
      <div class="row">
          <div class="text-center col-lg-6 offset-lg-3">
             <h4>Footer </h4>
             <p>Copyright &copy; 2020 &middot; All Rights Reserved &middot; <a href="#" >My Website</a></p>
          </div>
      </div>
      <script> 

         //socket server
         var conn = new WebSocket('ws://172.16.216.210:1337');

         conn.onopen = function(e) 
         {
            console.log("connection established!");
         }
   
         conn.onmessage = function(e) 
         {
            var msg = JSON.parse(e.data);           
            
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
               console.log(msg.id);
               console.log(msg.id2);
               var item = document.getElementById(msg.id);

               var remove = document.getElementById("gamelist");
               remove.remove(remove.item);

               conn.send(JSON.stringify({command: "start", id1:msg.id1, id2:msg.id2}));

            }

            else if(msg.command == 'start')
            {
               window.location.href = "blank.html"; 
            }

            else if(msg.command == 'packet')
            
         };

      </script>

      <script type="application/javascript">

   document.getElementById("join").addEventListener("click", function () 
   {
      //var msg = "one";
      var sel = document.getElementById('gamelist').value;
      conn.send(JSON.stringify({command: "join", id: sel}));
      //conn.send(JSON.stringify(msg));
      //either manipulate html here or inside the socket onmessagereceived code                  
   });

   document.getElementById("create").addEventListener("click", function () 
   {
      //var msg = "one";  
      //need users own id here from variable in message
      conn.send(JSON.stringify({command: "create", id: ''}));
      //either manipulate html here or inside the socket onmessagereceived co             
   });

function myMethod( )
{
   var msg = "update";

   conn.send(JSON.stringify(msg));
}

 </script>
    
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) --> 
    <script src="jquery-3.4.1.min.js"></script>

    <!-- Include all compiled plugins (below), or include individual files as needed --> 
    
    <script src="jspopper.min.js"></script>
    <script src="bootstrap-4.4.1.js"></script>
  </body>
</html>