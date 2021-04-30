import java.io.*;
import java.net.*;

public class TCPServer {
    public static void main(String args[]) throws Exception {
        TCPPacket recieved = new TCPPacket();
        TCPPacket sent = new TCPPacket();
        ServerSocket welcomeSocket = new ServerSocket(6789);

        while(true) {
            Socket connectionSocket = welcomeSocket.accept();
            BufferedReader inFromClient = new BufferedReader(new InputStreamReader(connectionSocket.getInputStream()));
            DataOutputStream outToClient = new DataOutputStream(connectionSocket.getOutputStream());
            recieved.name = inFromClient.readLine();
            sent.name = recieved.name.toUpperCase() + '\n';
            outToClient.writeBytes(sent.name);

            
        }
    }
}