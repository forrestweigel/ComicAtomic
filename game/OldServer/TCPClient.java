import java.io.*;
import java.net.*;

public class TCPClient {
    public static void main(String argv[]) throws Exception {
        TCPPacket send = new TCPPacket();
        TCPPacket recieve = new TCPPacket();
        BufferedReader inFromUser = new BufferedReader(new InputStreamReader(System.in));

        Socket clientSocket = new Socket("localhost", 6789);
        DataOutputStream outToServer = new DataOutputStream(clientSocket.getOutputStream());
        BufferedReader inFromServer = new BufferedReader(new InputStreamReader(clientSocket.getInputStream()));

        //send.name = inFromUser.readLine();
        send.name = "bob";
        send.text = "He is a unit";
        outToServer.writeBytes(send.name + ", " + send.text + "\n");
        recieve.name = inFromServer.readLine();
        System.out.println(recieve.name);
        clientSocket.close();
    }
}