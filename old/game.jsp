<html>
    <head>    
        <style>
            .card
            {
                position: absolute;
                width:119px;
                height:78px;
                background:white;
                cursor: move;
                left: 1;
                top:45;
                transition-duration: .5s;
                transition-timing-function: ease-in-out;
            }

            .board
            {
                display:block;
                margin-left: auto;
                margin-right: auto;
                width: 720;
                height: 720;       
                background: url("/ComicAtomic/img/board.png");
                background-size: cover;
                position: relative;
            }
        </style>
    </head>

    <body>
        
        <div id = board class = "board"></div>
        <button onClick = "newCard()"> Ship </button>
    </body>

    <script>

        var deckNum = 0;
        var cardNum = 0;
        var selectedCard = null;
        
        function newCard()
        {
            card = document.createElement("div");
            var cardID;
            card.classList.add("card")
            cardID = "card" + cardNum.toString();
            if(deck[cardNum].health == 5){ card.background = "red"}
            cardNum++;
            card.id = cardID;
            card.setAttribute("data-degree", "0");
            card.onclick = function() {setSelect(this.id);};
            document.getElementById("board").appendChild(card);
            dragElement(document.getElementById(card.id));
        }

        function setSelect(card)
        {
            selectedCard = card;
        }

        function dragElement(elmnt) //needs fixing
        {
            var pos1 = 0, pos2 = 0, pos3 = 0, pos4 = 0;
            elmnt.onmousedown = dragMouseDown;
            
            function dragMouseDown(e) 
            {           
                document.getElementById(selectedCard).style.transitionDuration = "0s";
                var board = document.getElementById("board");
                e = e || window.event;
                e.preventDefault();
                pos3 = e.clientX;
                pos4 = e.clientY;
                document.onmouseup = closeDragElement;
                document.onmousemove = elementDrag;
            }

            function elementDrag(e) 
            {
                e = e || window.event;
                e.preventDefault();
                pos1 = (pos3 - e.clientX);
                pos2 = (pos4 - e.clientY );
                pos3 = e.clientX; 
                pos4 = e.clientY;
                elmnt.style.top = ((elmnt.offsetTop - pos2)) + "px";
                elmnt.style.left = ((elmnt.offsetLeft - pos1)) + "px";
            }

            function closeDragElement(e) 
            {
                e = e || window.event;
                e.preventDefault();
                pos1 = 120*(Math.round((elmnt.offsetLeft) / 120));
                pos2 = 79*(Math.floor((elmnt.offsetTop) / 78));
                pos2 += 45;
                pos1 += 1;
                if(pos2 > 598){pos2 = 598}   
                if(pos1 > 601){pos2 = 601}
                elmnt.style.top = pos2+ "px";
                elmnt.style.left = pos1 + "px";
                document.onmouseup = null;
                document.onmousemove = null;
                selectedCard = null;
              }          
        }
    </script>
</html>