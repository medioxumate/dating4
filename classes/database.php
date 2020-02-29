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

require("/home/bkiehngr/re_dbconfig.php");

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
    private $array = array('tv', 'puzzles', 'movies', 'reading', 'cooking', 'playing cards',
        'globe making', 'video games', 'swimming', 'running', 'hiking', 'metal detecting',
        'collecting', 'horseback riding', 'pokemon go', 'bird watching');

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
        }
        catch (PDOException $e) {
            //echo $e->getMessage();
            return $e->getMessage();
        }
    }

    function insertMember($fn, $ln, $age, $g, $ph, $em, $st, $bio, $member){
        //query
        $sql = "INSERT INTO member (fname, lname, age, gender, phone, email, `state`, seeking, bio, premium)
            VALUES (:fn, :ln, :age, :g, :ph, :em, :st, :g, :bio, :member)";

        //statement
        $statement = $this->dbh->prepare($sql);

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

        //exe and result
        return $statement->execute();
    }

    function insertInterests($member_id, array $indoor, array $outdoor)
    {
        if (in_array("Not Given", $indoor)) {
            $sql = "INSERT INTO `member-interest`(`member_id`, `interest_id`) VALUES ";
            foreach ($indoor as $in) {
                $inId = "SELECT `interest_id` FROM `interest` WHERE `interest` =" . $in;
                $sql .= "(" . $member_id . "," . $inId . ")";
            }
            foreach ($outdoor as $out) {
                $outId = "SELECT `interest_id` FROM `interest` WHERE `interest` =" . $out;
                $sql .= "(" . $member_id . "," . $outId . ")";
            }


            //statement
            $statement = $this->dbh->prepare($sql);

            //exe and result
            return $statement->execute();
        }
        else{
            return false;
        }
    }

    function getMembers(){
        $sql = "SELECT * FROM member";

        //statement
        $statement = $this->dbh->prepare($sql);

        //exe
        $statement->execute();

        //result
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    function getMemberID($fn, $ln, $age, $g, $ph, $em, $st, $bio, $member){
        //query
        $sql = "SELECT member_id FROM member
            WHERE fname = :fn AND :ln = lname AND age = :age AND gender = :g AND 
            phone = :ph AND email = :em AND `state`= :st AND premium = :member";

        //statement
        $statement = $this->dbh->prepare($sql);

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

        //exe and result
        return $statement->execute();
    }

    function getMember($member_id){
        $sql = "SELECT fname, lname, age, gender, phone, email, `state`,
            seeking, bio, premium FROM member WHERE member_id = "."$member_id";

        //statement
        $statement = $this->dbh->prepare($sql);

        //exe
        $statement->execute();

        //result
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    function getInterests($member_id){
        $sql = "SELECT interest.interest FROM interest RIGHT JOIN member-interest 
            ON interest.interest_id=member-interest.interest_id
            WHERE member_id="."$member_id";

        //statement
        $statement = $this->dbh->prepare($sql);

        //exe
        $statement->execute();

        //result
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
}