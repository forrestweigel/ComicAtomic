//const socket = io();

//const inboxPeople = document.querySelector(".inbox__people");

//let userName = "";



//const newUserConnected = (user) => {
//    userName = user || `User${Math.floor(Math.random() * 1000000)}`;
//    socket.emit("new user", userName);
//    addToUsersBox(userName);
//};

//const sendMessage = () => {

//    socket.emit('m', "worked")
//}

//const addToUsersBox = (userName) => {
//    if (!!document.querySelector(`.${userName}-userlist`)) {
//        return;
//    }

//    const userBox = `
//    <div class="chat_ib ${userName}-userlist">
//      <h5>${userName}</h5>
//    </div>
//  `;
//    inboxPeople.innerHTML += userBox;
//};

//// new user is created so we generate nickname and emit event
//newUserConnected();

//socket.on("new user", function (data) {
//    data.map((user) => addToUsersBox(user));
//});

//socket.on("user disconnected", function (userName) {
//    document.querySelector(`.${userName}-userlist`).remove();
//});

//const inputField = document.querySelector(".message_form__input");
//const messageForm = document.querySelector(".message_form");
//const messageBox = document.querySelector(".messages__history");

//const addNewMessage = ({ user, message }) => {
//    const time = new Date();
//    const formattedTime = time.toLocaleString("en-US", { hour: "numeric", minute: "numeric" });

//    const receivedMsg = `
//  <div class="incoming__message">
//    <div class="received__message">
//      <p>${message}</p>
//      <div class="message__info">
//        <span class="message__author">${user}</span>
//        <span class="time_date">${formattedTime}</span>
//      </div>
//    </div>
//  </div>`;

//    const myMsg = `
//  <div class="outgoing__message">
//    <div class="sent__message">
//      <p>${message}</p>
//      <div class="message__info">
//        <span class="time_date">${formattedTime}</span>
//      </div>
//    </div>
//  </div>`;

//    messageBox.innerHTML += user === userName ? myMsg : receivedMsg;
//};


//var x = document.getElementById("lol");

//document.getElementById("lol").addEventListener("click", function () {

//    lol.innerHTML += "hgello";
//    socket.emit("m", "worked");
//});

//x.addEventListener("onclick", function() =>{
    
//    socket.emit("m", "lerer") 

//    inputField.value = "";
//});





