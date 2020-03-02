<?php
/**
 * Created in PhpStorm
 * @author Brian Kiehn
 * @date 2/28/2020
 * @version 1.0
 * database.php
 * GreenRiverDev
 * @link https://github.com/medioxumate/dating4.git
 */

require("/home/bkiehngr/re-dbconfig.php");

//--database structure
/*
CREATE TABLE member (
member_id int NOT NULL AUTO_INCREMENT,
fname varchar(32) NOT NULL,
lname varchar(32) NOT NULL,
age int NOT NULL DEFAULT 18,
gender varchar(24) NOT NULL,
phone varchar(16) NOT NULL,
email varchar(254) NOT NULL,
`state` varchar(24) NOT NULL,
seeking varchar(24) NOT NULL,
bio MEDIUMTEXT,
premium boolean NOT NULL DEFAULT false,
PRIMARY KEY (member_id));

CREATE TABLE interest (
interest_id int NOT NULL AUTO_INCREMENT,
interest varchar(32) NOT NULL,
`type` boolean NOT NULL,
PRIMARY KEY (interest_id));

CREATE TABLE `member-interest` (
member_id int NOT NULL,
interest_id int NOT NULL,
PRIMARY KEY (member_id, interest_id),
FOREIGN KEY (member_id) REFERENCES member(member_id),
FOREIGN KEY (interest_id) REFERENCES interest(interest_id));

--true is 'indoor', false is 'outdoor'
 INSERT INTO `interest` (`interest`,`type`) VALUES
('tv', true),
('puzzles', true),
('movies', true),
('reading', true),
('cooking', true),
('playing cards', true),
('globe making', true),
('video games', true),
('swimming', false),
('running', false),
('hiking', false),
('metal detecting', false),
('collecting', false),
('horseback riding', false),
('pokemon go', false),
('bird watching', false);
 */
class database
{
    private $dbh;

    function __construct()
    {
        $this->connect();
    }

    function connect()
    {
        try {
            //Instantiate a database object
            $this->dbh = new PDO(DB_DSN, DB_USERNAME,
                DB_PASSWORD);
            //echo "Connected to database!!!";
            return $this->dbh;
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

    function insertMember($fn, $ln, $age, $g, $ph, $em, $st, $bio, $member)
    {
        //query
        $sql = "INSERT INTO member (fname, lname, age, gender, phone, email, `state`, seeking, bio, premium)
            VALUES (:fn, :ln, :age, :g, :ph, :em, :st, :g, :bio, :member);";

        $id = "SELECT LAST_INSERT_ID();";

        //statement
        $statement = $this->dbh->prepare($sql . $id);

        //bind
        $statement->bindParam(':fn', $fn, PDO::PARAM_STR);
        $statement->bindParam(':ln', $ln, PDO::PARAM_STR);
        $statement->bindParam(':age', $age, PDO::PARAM_INT);
        $statement->bindParam(':g', $g, PDO::PARAM_STR);
        $statement->bindParam(':ph', $ph, PDO::PARAM_STR);
        $statement->bindParam(':em', $em, PDO::PARAM_STR);
        $statement->bindParam(':st', $st, PDO::PARAM_STR);
        $statement->bindParam(':bio', $bio, PDO::PARAM_STR);
        $statement->bindParam(':member', $member, PDO::PARAM_BOOL);

        //exe
        $statement->execute();

        return $this->dbh->lastInsertId();
    }

    function insertInterest($member_id, $interest)
    {
        $id = database::getInterestId($interest);

        $sql = "INSERT INTO `member-interest`(`member_id`, `interest_id`) VALUES (:member, :id)";

        $statement = $this->dbh->prepare($sql);

        //bind
        $statement->bindParam(':member', $member_id, PDO::PARAM_INT);
        $statement->bindParam(':id', $id, PDO::PARAM_INT);

        //exe and result
        return $statement->execute();
    }

    function getInterestId($interest)
    {
        $sql = "SELECT `interest_id` FROM interest WHERE `interest`= :interest";

        //statement
        $statement = $this->dbh->prepare($sql);

        $statement->bindParam(':interest', $interest, PDO::PARAM_STR);

        //exe
        $statement->execute();

        $query = $statement->fetch();

        return $query['interest_id'];
    }

    function getInterests($member_id)
    {
        $sql = "SELECT `interest`.interest FROM `interest` RIGHT JOIN `member-interest` ON 
            `interest`.interest_id = `member-interest`.interest_id AND `member-interest`.`member_id` = :id";

        //statement
        $statement = $this->dbh->prepare($sql);

        $statement->bindParam(':id', $member_id, PDO::PARAM_INT);

        //exe
        $statement->execute();

        //result
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    function getMembers()
    {
        $sql = "SELECT * FROM `member` ORDER BY `member`.`lname` ASC";

        //statement
        $statement = $this->dbh->prepare($sql);

        //exe
        $statement->execute();

        //result
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

}