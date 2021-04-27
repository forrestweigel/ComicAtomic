package game;

public class Board{
    Square[][] spaces;

    public Board()
    {
        for(int x = 0; x < 7; x++)
        {
            for(int y = 0; y < 7; y++)
            {
                spaces[x][y] = new Square(x,y,null);
            }
        }
    }

    public Square returnSquare(int x, int y)
    {
        return spaces[x][y];
    }
}