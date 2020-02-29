
--database structure
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
 ('tv', true), ('puzzles', true), ('movies', true),
('reading', true), ('cooking', true), ('playing cards', true),
('globe making', true), ('video games', true),
('swimming', false), ('running', false), ('hiking', false),
('metal detecting', false), ('collecting', false),
('horseback riding', false), ('pokemon go', false),
('bird watching', false);