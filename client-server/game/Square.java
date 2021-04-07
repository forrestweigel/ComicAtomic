package game;

public class Square {
    public Card card;
    public int x;
    public int y;

    public Square(int x, int y, Card card)
    {
        this.setCard(card);
    }

    public void setCard(Card card)
    {
        this.card = card;
    }

    public void setX(int x)
    {
        this.x = x;
    }

    public void setY(int y)
    {
        this.y = y;
    }

    public Square returnSquare()
    {
        return this;
    }
}
