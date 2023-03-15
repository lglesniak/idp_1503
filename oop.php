<?php /* Template Name: OOP */


class Book
{
    private $title;
    private $author;
    private $year;
    private $price;

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function setAuthor($author)
    {
        $this->author = $author;
    }

    public function setYear($year)
    {
        $this->year = $year;
    }

    public function setPrice($price)
    {
        $this->price = $price;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getAuthor()
    {
        return $this->author;
    }

    public function getYear()
    {
        return $this->year;
    }

    public function getPrice()
    {
        return $this->price;
    }
}

class Library
{
    private $books = [];

    public function addBook(Book $book)
    {
        $this->books[] = $book;
    }

    public function removeBook(Book $book)
    {
        $index = array_search($book, $this->books);
        if ($index !== false) {
            unset($this->books[$index]);
        }
    }

    public function getBooks()
    {
        return $this->books;
    }

    public function sortBooksByYear()
    {
        usort($this->books, function ($a, $b) {
            return $b->getYear() - $a->getYear();
        });
    }
}

class BD
{
    private $serverName;
    private $username;
    private $password;
    private $dbName;
    private $connection;


    function __construct($server, $username, $password, $dbName)
    {
        $this->serverName = $server;
        $this->username = $username;
        $this->password = $password;
        $this->dbName = $dbName;

        $this->connection = new mysqli($server, $username, $password, $dbName);
        if ($this->connection->connect_error) {
            die("Failed: " . $this->connection->connect_error);
        }
    }

    public function query($query)
    {
        $result = $this->connection->query($query);
        if (!$result) die("Query failed" . $this->connection->connect_error);
        return $result;
    }

    public function escape($value)
    {
        return $this->connection->real_escape_string($value);
    }

    function __destruct()
    {
       $this->connection->close();
    }


}

$bd = new BD('localhost', 'root', 'root', 'books');
$result = $bd->query("SELECT * FROM `books`");
//var_dump($result);
while ($row = $result -> fetch_assoc()) {
    echo $row['Title'] . '<br>';
}


$title = $bd->escape("Title");
$author = $bd->escape("Author");
$year = $bd->escape(1257);
$price = $bd->escape(16.77);

$sql = "INSERT INTO `Books` (`Title`,`Author`,`Year`,`Price`) VALUES ('$title','$author',$year,$price)";
$bd->query($sql);
$bd->__destruct();

//$bd->query("CREATE TABLE Books (ID INT AUTO_INCREMENT primary key NOT NULL, Title varchar(255),Author varchar(255),Year int,Price DOUBLE)");
//$bd->query("INSERT INTO `Books` (`Title`,`Author`,`Year`,`Price`) VALUES ('The Great Gatsby','F. Scott Fitzgerald',1925,10.99)");
//$bd->query("INSERT INTO `Books` (`Title`,`Author`,`Year`,`Price`) VALUES ('To Kill a Mockingbird','Harper Lee',1960,8.99)");
//$bd->query("TRUNCATE TABLE Books");