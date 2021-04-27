package game;

public abstract class Card {

    public int health;
    public int dice;
    public int cost;
    public String name;
    public String desc;
    public String player;

    public Card(int _health, int _dice, int _cost, String _name, String _desc)
    {
        health = _health;
        dice = _dice;
        cost = _cost;
        name = _name;
        desc = _desc;
    }
    
    public Card returnCard()
    {
        return this;
    }

    public boolean canMove(Board board, Square start, Square end)
    {
        if(end.card == null)
        {
            if((Math.abs(start.x - end.x) + Math.abs(start.y - end.y)) <= 2)
            {
                int x = end.x - start.x;
                int y = end.y - start.y;
                boolean moved = false;

                while(x != 0 || y != 0)
                {
                    if(x != 0 && board.returnSquare(start.x + x, start.y) == null)
                    {
                        if(x < 0) x++;
                        else x--;

                        moved = true;
                    }

                    if(y != 0 && board.returnSquare(start.x, start.y + y) == null)
                    { 
                        if(y < 0) y++;
                        else y--;

                        moved = true;
                    }

                    if(!moved)
                    {
                        return false;
                    }
                }

                return true;
            }

            else return false;
        }

        else return false;
    }
}
