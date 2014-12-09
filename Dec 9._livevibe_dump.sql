-- phpMyAdmin SQL Dump
-- version 4.2.10
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 09, 2014 at 06:08 PM
-- Server version: 5.5.38
-- PHP Version: 5.6.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `livevibe`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `add_genre`(IN `uname` VARCHAR(20), IN `type` VARCHAR(10), IN `genre` VARCHAR(20))
    NO SQL
BEGIN
  IF type = "user" THEN
    INSERT INTO u_sub SET
        username = uname,
        sub = genre;
  ELSEIF type = "artist" THEN
    INSERT INTO a_sub SET
        artistname = uname,
        sub = genre;
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `artist_insert`(IN `aname` VARCHAR(30), IN `apwd` VARCHAR(20), IN `b` VARCHAR(300), IN `rt` DATETIME)
    NO SQL
BEGIN
    INSERT INTO artists
    SET artistname=aname, artpwd=apwd, bio = b, reg_time=rt, login_time=rt, lastaccess=rt;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `check_id`(IN `submitted_name` VARCHAR(20))
BEGIN
  CALL check_id_sub(submitted_name, @type);
    SELECT @type;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `check_id_sub`(IN `submitted_name` VARCHAR(20), OUT `usertype` VARCHAR(10))
BEGIN
  DECLARE urow INT;
    DECLARE arow INT;
    
  SELECT COUNT(distinct username) INTO urow
    FROM users
    WHERE username = submitted_name;
        
    IF urow != 0 THEN
    SET usertype = "user";
    ELSE
        SELECT COUNT(distinct artistname) INTO arow
        FROM artists
    WHERE artistname = submitted_name;
      
        IF arow != 0 THEN
      SET usertype = "artist";
      ELSE
      SET usertype = NULL;
      END IF;
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `check_pwd`(IN `submitted_name` VARCHAR(20), IN `submitted_pwd` VARCHAR(20), IN `usertype` VARCHAR(10))
BEGIN
    IF usertype = "user" THEN

          SELECT username
            FROM users
            WHERE username = submitted_name AND userpwd = submitted_pwd;
          
        ELSEIF usertype = "artist" THEN
          
          SELECT artistname
            FROM artists
            WHERE artistname = submitted_name AND artpwd = submitted_pwd;
          
        END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `list_genre`()
    NO SQL
BEGIN
  SELECT sub FROM genres;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `list_taste`(IN `uname` VARCHAR(20))
    NO SQL
BEGIN
  SELECT sub FROM u_sub WHERE username = uname;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `set_reg_time`(IN `submit_name` VARCHAR(20), IN `regT` DATETIME, IN `usertype` VARCHAR(10))
    NO SQL
BEGIN
  IF usertype = "user" THEN
    UPDATE users SET reg_time = regT
      WHERE username = submit_name;
  ELSEIF usertype = "artist" THEN
    UPDATE artists SET reg_time = regT
      WHERE artistname = submit_name;
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `update_LAT`(IN `submit_name` VARCHAR(20), IN `LAT` DATETIME, IN `usertype` VARCHAR(10))
BEGIN
  IF usertype = "user" THEN
    UPDATE users SET lastaccess = LAT
      WHERE username = submit_name;
  ELSEIF usertype = "artist" THEN
    UPDATE artists SET lastaccess = LAT
      WHERE artistname = submit_name;
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `update_login_time`(IN `submit_name` VARCHAR(20), IN `loginT` DATETIME, IN `usertype` VARCHAR(10))
    NO SQL
BEGIN
  IF usertype = "user" THEN
    UPDATE users SET login_time = loginT
      WHERE username = submit_name;
  ELSEIF usertype = "artist" THEN
    UPDATE artists SET login_time = loginT
      WHERE artistname = submit_name;
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `uprofile_insert`(IN `uname` VARCHAR(20), IN `pwd` VARCHAR(20), IN `realn` VARCHAR(30), IN `bir` DATETIME, IN `ct` VARCHAR(20), IN `st` VARCHAR(20), IN `zip` CHAR(5), IN `rt` DATETIME)
    NO SQL
BEGIN
    INSERT INTO users
    SET username=uname, userpwd=pwd, reg_time=rt, login_time=rt, lastaccess=rt;
    INSERT INTO uprofile
    SET username=uname, realname = realn, birth=bir, city=ct, state=st, zipcode=zip;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `up_info`(IN `uname` VARCHAR(20))
    NO SQL
BEGIN
  SELECT usr_geo.username, usr_geo.city, usr_geo.state, usr_follower.flwer_num, usr_following.flw_num, usr_review.review_num
    FROM usr_geo JOIN usr_follower JOIN usr_following JOIN usr_review
    WHERE usr_geo.username = uname AND usr_follower.to_usr = uname    
          AND usr_following.from_usr = uname
          AND usr_review.username = uname;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `anew`
--

CREATE TABLE `anew` (
  `artistname` varchar(30) NOT NULL DEFAULT '',
  `cid` char(10) NOT NULL DEFAULT '',
  `new_time` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `artists`
--

CREATE TABLE `artists` (
  `artistname` varchar(30) NOT NULL DEFAULT '',
  `artpwd` varchar(20) DEFAULT NULL,
  `bio` varchar(300) DEFAULT NULL,
  `reg_time` datetime DEFAULT NULL,
  `login_time` datetime DEFAULT NULL,
  `lastaccess` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `artists`
--

INSERT INTO `artists` (`artistname`, `artpwd`, `bio`, `reg_time`, `login_time`, `lastaccess`) VALUES
('Belle & Sebastian', 'abc123', 'Sounds childish.', '2010-04-22 16:44:34', '2014-11-25 13:22:48', '2014-11-23 15:23:32'),
('Billy Joel', 'abc123', 'Sounds old.', '2013-11-22 16:44:34', '2014-09-25 13:22:48', '2014-11-24 09:42:23'),
('Bob Dylan', 'abc123', 'Sounds old.', '2011-05-22 16:44:34', '2014-12-07 22:12:59', '2014-12-07 22:13:00'),
('Damien Rice', 'abc123', 'Sounds good.', '2010-04-22 16:44:34', '2014-11-15 13:22:48', '2014-11-25 16:32:54'),
('Interpol', 'abc123', 'Sounds gay.', '2009-06-22 02:36:12', '2014-08-25 13:22:48', '2014-11-25 10:23:32'),
('Justin Timberlake', 'abc123', 'Sounds girly.', '2009-04-22 16:44:34', '2014-04-25 13:22:48', '2014-11-22 17:14:24'),
('Linkin Park', 'abc123', 'Sounds rude.', '2010-04-22 16:44:34', '2014-05-25 13:22:48', '2014-11-25 14:54:52'),
('Maroon 5', 'abc123', 'Sounds pop.', '2013-12-21 16:44:34', '2014-10-12 13:22:48', '2014-11-24 18:15:23'),
('OneRepublic', 'abc123', 'Sounds pop.', '2011-08-22 16:44:34', '2014-06-25 13:22:48', '2014-11-22 20:14:54'),
('Snapline', 'abc123', 'Sounds experimental.', '2010-08-13 16:44:34', '2014-05-23 23:22:48', '2014-11-25 21:15:23');

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `username` varchar(20) NOT NULL DEFAULT '',
  `cid` char(10) NOT NULL DEFAULT '',
  `rating` int(2) DEFAULT NULL,
  `review` varchar(300) DEFAULT NULL,
  `rv_time` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`username`, `cid`, `rating`, `review`, `rv_time`) VALUES
('johndoe', '5500000634', NULL, NULL, NULL),
('johndoe', '5500000953', 8, 'Review: A SPECIAL Guest came to concert last night!', '2014-01-26 10:33:35'),
('mchotdog', '5500000432', 8, 'Review: My girlfriend got crazy last night.', '2014-11-25 13:34:14'),
('mchotdog', '5500000945', 7, 'Review: A little bit disappointed.', '2014-11-30 11:37:48');

-- --------------------------------------------------------

--
-- Table structure for table `a_sub`
--

CREATE TABLE `a_sub` (
  `artistname` varchar(30) NOT NULL DEFAULT '',
  `sub` varchar(20) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `a_sub`
--

INSERT INTO `a_sub` (`artistname`, `sub`) VALUES
('Bob Dylan', 'Alternative rock'),
('Belle & Sebastian', 'Indie rock'),
('Interpol', 'Indie rock'),
('Snapline', 'Indie rock');

-- --------------------------------------------------------

--
-- Table structure for table `concerts`
--

CREATE TABLE `concerts` (
  `cid` char(10) NOT NULL DEFAULT '',
  `vid` char(10) DEFAULT NULL,
  `artistname` char(30) DEFAULT NULL,
  `start_time` datetime DEFAULT NULL,
  `link` varchar(40) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `concerts`
--

INSERT INTO `concerts` (`cid`, `vid`, `artistname`, `start_time`, `link`) VALUES
('5500000189', '8800000843', 'OneRepublic', '2015-04-14 20:00:00', 'http://www.bandsintown.com'),
('5500000231', '8800000678', 'Bob Dylan', '2015-07-01 20:00:00', 'http://www.bandsintown.com'),
('5500000343', '8800000678', 'Linkin Park', '2014-12-12 19:00:00', 'http://www.bandsintown.com'),
('5500000432', '8800000999', 'Damien Rice', '2014-11-24 19:00:00', 'http://www.bandsintown.com'),
('5500000513', '8800000333', 'Belle & Sebastian', '2015-06-10 19:00:00', 'http://www.bandsintown.com'),
('5500000634', '8800000678', 'Snapline', '2015-03-05 19:00:00', 'http://www.bandsintown.com'),
('5500000791', '8800000111', 'Billy Joel', '2014-12-14 20:00:00', 'http://www.bandsintown.com'),
('5500000945', '8800000843', 'Interpol', '2014-11-28 20:00:00', 'http://www.bandsintown.com'),
('5500000953', '8800000111', 'Justin Timberlake', '2014-01-25 19:00:00', 'http://www.bandsintown.com');

-- --------------------------------------------------------

--
-- Table structure for table `fans`
--

CREATE TABLE `fans` (
  `username` char(10) NOT NULL DEFAULT '',
  `artistname` varchar(30) NOT NULL DEFAULT '',
  `fan_time` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `follow`
--

CREATE TABLE `follow` (
  `from_usr` char(10) NOT NULL DEFAULT '',
  `to_usr` char(10) NOT NULL DEFAULT '',
  `f_time` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `follow`
--

INSERT INTO `follow` (`from_usr`, `to_usr`, `f_time`) VALUES
('johndoe', 'mchotdog', '2012-10-08 07:28:33'),
('magicmike', 'johndoe', '2014-11-12 08:04:21'),
('magicmike', 'mchotdog', '2014-11-27 10:26:35'),
('test_user', 'johndoe', '2014-10-07 14:24:29'),
('test_user', 'mchotdog', '2014-12-02 18:37:00');

-- --------------------------------------------------------

--
-- Table structure for table `genres`
--

CREATE TABLE `genres` (
  `sub` varchar(20) NOT NULL DEFAULT '',
  `main` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `genres`
--

INSERT INTO `genres` (`sub`, `main`) VALUES
('Alternative rock', 'Rock'),
('Classic Country', 'Country'),
('Indie folk', 'Folk'),
('Indie pop', 'Pop'),
('Indie rock', 'Rock'),
('New School Hip-Hop', 'Hip-Hop'),
('Old School Hip-Hop', 'Hip-Hop'),
('Pop rock', 'Rock'),
('Texas Country', 'Country');

-- --------------------------------------------------------

--
-- Table structure for table `recommend`
--

CREATE TABLE `recommend` (
  `username` char(10) NOT NULL DEFAULT '',
  `cid` char(10) NOT NULL DEFAULT '',
  `listname` varchar(30) NOT NULL DEFAULT '',
  `rm_time` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `recommend`
--

INSERT INTO `recommend` (`username`, `cid`, `listname`, `rm_time`) VALUES
('johndoe', '5500000189', 'Where to go this winter', '2014-11-25 15:12:13'),
('johndoe', '5500000432', 'Where to go this winter', '2014-11-25 15:12:13'),
('johndoe', '5500000513', 'Where to go this winter', '2014-11-25 15:12:13');

-- --------------------------------------------------------

--
-- Table structure for table `ucomments`
--

CREATE TABLE `ucomments` (
  `username` varchar(20) NOT NULL DEFAULT '',
  `cid` char(10) NOT NULL DEFAULT '',
  `c_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `unew`
--

CREATE TABLE `unew` (
  `username` char(10) NOT NULL DEFAULT '',
  `cid` char(10) NOT NULL DEFAULT '',
  `new_time` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `uprofile`
--

CREATE TABLE `uprofile` (
  `username` varchar(20) NOT NULL DEFAULT '',
  `realname` varchar(30) DEFAULT NULL,
  `birth` datetime DEFAULT NULL,
  `city` varchar(20) DEFAULT NULL,
  `state` varchar(20) DEFAULT NULL,
  `zipcode` char(5) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `uprofile`
--

INSERT INTO `uprofile` (`username`, `realname`, `birth`, `city`, `state`, `zipcode`) VALUES
('johndoe', 'John Doe', '1985-05-12 13:44:34', 'New York', 'NY', '10012'),
('magicmike', 'Mike Fassbender', '1977-04-02 00:00:00', 'Los Angeles', 'CA', '90001'),
('mchotdog', 'Barack Obama', '1961-08-04 23:44:34', 'Washington', 'DC', '20500'),
('test_user', 'Test User', '1995-05-20 15:15:00', 'New York', 'NY', '10007');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `username` varchar(20) NOT NULL DEFAULT '',
  `userpwd` varchar(20) DEFAULT NULL,
  `reg_time` datetime DEFAULT NULL,
  `login_time` datetime DEFAULT NULL,
  `lastaccess` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`username`, `userpwd`, `reg_time`, `login_time`, `lastaccess`) VALUES
('johndoe', 'abc123', '2011-05-12 13:44:34', '2014-12-09 12:06:06', '2014-12-09 12:06:06'),
('magicmike', 'abc123', '2014-01-04 12:34:34', '2014-11-23 13:22:48', '2014-11-25 16:42:53'),
('mchotdog', 'abc123', '2008-09-23 23:44:34', '2014-12-09 12:06:39', '2014-12-09 12:06:39'),
('test_user', 'abc123', '2014-12-08 09:45:37', '2014-12-09 12:06:26', '2014-12-09 12:06:26');

-- --------------------------------------------------------

--
-- Stand-in structure for view `usr_follower`
--
CREATE TABLE `usr_follower` (
`to_usr` varchar(20)
,`flwer_num` bigint(21)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `usr_following`
--
CREATE TABLE `usr_following` (
`from_usr` varchar(20)
,`flw_num` bigint(21)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `usr_geo`
--
CREATE TABLE `usr_geo` (
`username` varchar(20)
,`city` varchar(20)
,`state` varchar(20)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `usr_review`
--
CREATE TABLE `usr_review` (
`username` varchar(20)
,`review_num` bigint(21)
);
-- --------------------------------------------------------

--
-- Table structure for table `u_sub`
--

CREATE TABLE `u_sub` (
  `username` varchar(20) NOT NULL DEFAULT '',
  `sub` varchar(20) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `u_sub`
--

INSERT INTO `u_sub` (`username`, `sub`) VALUES
('johndoe', 'Alternative rock'),
('mchotdog', 'Alternative rock'),
('magicmike', 'Indie folk'),
('test_user', 'Indie folk'),
('johndoe', 'Indie rock'),
('mchotdog', 'Indie rock'),
('mchotdog', 'New School Hip-Hop'),
('test_user', 'Old School Hip-Hop'),
('magicmike', 'Pop rock'),
('test_user', 'Texas Country');

-- --------------------------------------------------------

--
-- Table structure for table `venues`
--

CREATE TABLE `venues` (
  `vid` char(10) NOT NULL DEFAULT '',
  `vname` varchar(40) DEFAULT NULL,
  `street` varchar(40) DEFAULT NULL,
  `city` varchar(20) DEFAULT NULL,
  `state` varchar(20) DEFAULT NULL,
  `zipcode` char(5) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `venues`
--

INSERT INTO `venues` (`vid`, `vname`, `street`, `city`, `state`, `zipcode`) VALUES
('8800000111', 'Barclays Center', '620 Atlantic Ave', 'Brooklyn', 'NY', '11217'),
('8800000256', 'The Bowery Ballroom', '6 Delancey St', 'New York', 'NY', '10002'),
('8800000333', 'Radio City Music Hall', '1260 6th Avenue', 'New York', 'NY', '10020'),
('8800000381', 'Mercury Lounge', '217 East Houston St.', 'New York', 'NY', '10002'),
('8800000532', 'Hammerstein Ballroom', '311 W 34th St.', 'New York', 'NY', '10001'),
('8800000678', 'Madison Square Garden', '4 Pennsylvania Plaza', 'New York', 'NY', '10001'),
('8800000765', 'Music Hall of Williamsburg', '66 North 6th St.', 'Brooklyn', 'NY', '11211'),
('8800000843', 'Beacon Theatre', '2124 Broadway', 'New York', 'NY', '10023'),
('8800000999', 'Terminal 5', '610 W 56th St', 'New York', 'NY', '10019');

-- --------------------------------------------------------

--
-- Structure for view `usr_follower`
--
DROP TABLE IF EXISTS `usr_follower`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `usr_follower` AS select `users`.`username` AS `to_usr`,count(distinct `follow`.`from_usr`) AS `flwer_num` from (`users` left join `follow` on((`users`.`username` = `follow`.`to_usr`))) group by `users`.`username`;

-- --------------------------------------------------------

--
-- Structure for view `usr_following`
--
DROP TABLE IF EXISTS `usr_following`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `usr_following` AS select `users`.`username` AS `from_usr`,count(distinct `follow`.`to_usr`) AS `flw_num` from (`users` left join `follow` on((`users`.`username` = `follow`.`from_usr`))) group by `users`.`username`;

-- --------------------------------------------------------

--
-- Structure for view `usr_geo`
--
DROP TABLE IF EXISTS `usr_geo`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `usr_geo` AS select `uprofile`.`username` AS `username`,`uprofile`.`city` AS `city`,`uprofile`.`state` AS `state` from `uprofile` group by `uprofile`.`username`,`uprofile`.`city`,`uprofile`.`state`;

-- --------------------------------------------------------

--
-- Structure for view `usr_review`
--
DROP TABLE IF EXISTS `usr_review`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `usr_review` AS select `users`.`username` AS `username`,count(distinct `attendance`.`review`) AS `review_num` from (`users` left join `attendance` on((`users`.`username` = `attendance`.`username`))) group by `users`.`username`;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `anew`
--
ALTER TABLE `anew`
 ADD PRIMARY KEY (`artistname`,`cid`), ADD KEY `cid` (`cid`);

--
-- Indexes for table `artists`
--
ALTER TABLE `artists`
 ADD PRIMARY KEY (`artistname`);

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
 ADD PRIMARY KEY (`username`,`cid`), ADD KEY `cid` (`cid`);

--
-- Indexes for table `a_sub`
--
ALTER TABLE `a_sub`
 ADD PRIMARY KEY (`artistname`,`sub`), ADD KEY `sub` (`sub`);

--
-- Indexes for table `concerts`
--
ALTER TABLE `concerts`
 ADD PRIMARY KEY (`cid`), ADD KEY `vid` (`vid`), ADD KEY `artistname` (`artistname`);

--
-- Indexes for table `fans`
--
ALTER TABLE `fans`
 ADD PRIMARY KEY (`username`,`artistname`), ADD KEY `artistname` (`artistname`);

--
-- Indexes for table `follow`
--
ALTER TABLE `follow`
 ADD PRIMARY KEY (`from_usr`,`to_usr`), ADD KEY `to_usr` (`to_usr`);

--
-- Indexes for table `genres`
--
ALTER TABLE `genres`
 ADD PRIMARY KEY (`sub`);

--
-- Indexes for table `recommend`
--
ALTER TABLE `recommend`
 ADD PRIMARY KEY (`username`,`cid`,`listname`), ADD KEY `cid` (`cid`);

--
-- Indexes for table `ucomments`
--
ALTER TABLE `ucomments`
 ADD PRIMARY KEY (`username`,`cid`,`c_time`), ADD KEY `cid` (`cid`);

--
-- Indexes for table `unew`
--
ALTER TABLE `unew`
 ADD PRIMARY KEY (`username`,`cid`), ADD KEY `cid` (`cid`);

--
-- Indexes for table `uprofile`
--
ALTER TABLE `uprofile`
 ADD PRIMARY KEY (`username`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
 ADD PRIMARY KEY (`username`);

--
-- Indexes for table `u_sub`
--
ALTER TABLE `u_sub`
 ADD PRIMARY KEY (`username`,`sub`), ADD KEY `sub` (`sub`);

--
-- Indexes for table `venues`
--
ALTER TABLE `venues`
 ADD PRIMARY KEY (`vid`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `anew`
--
ALTER TABLE `anew`
ADD CONSTRAINT `anew_ibfk_1` FOREIGN KEY (`artistname`) REFERENCES `artists` (`artistname`),
ADD CONSTRAINT `anew_ibfk_2` FOREIGN KEY (`cid`) REFERENCES `concerts` (`cid`);

--
-- Constraints for table `attendance`
--
ALTER TABLE `attendance`
ADD CONSTRAINT `attendance_ibfk_1` FOREIGN KEY (`username`) REFERENCES `users` (`username`),
ADD CONSTRAINT `attendance_ibfk_2` FOREIGN KEY (`cid`) REFERENCES `concerts` (`cid`);

--
-- Constraints for table `a_sub`
--
ALTER TABLE `a_sub`
ADD CONSTRAINT `a_sub_ibfk_1` FOREIGN KEY (`artistname`) REFERENCES `artists` (`artistname`),
ADD CONSTRAINT `a_sub_ibfk_2` FOREIGN KEY (`sub`) REFERENCES `genres` (`sub`);

--
-- Constraints for table `concerts`
--
ALTER TABLE `concerts`
ADD CONSTRAINT `concerts_ibfk_1` FOREIGN KEY (`vid`) REFERENCES `venues` (`vid`),
ADD CONSTRAINT `concerts_ibfk_2` FOREIGN KEY (`artistname`) REFERENCES `artists` (`artistname`);

--
-- Constraints for table `fans`
--
ALTER TABLE `fans`
ADD CONSTRAINT `fans_ibfk_1` FOREIGN KEY (`username`) REFERENCES `users` (`username`),
ADD CONSTRAINT `fans_ibfk_2` FOREIGN KEY (`artistname`) REFERENCES `artists` (`artistname`);

--
-- Constraints for table `follow`
--
ALTER TABLE `follow`
ADD CONSTRAINT `follow_ibfk_1` FOREIGN KEY (`from_usr`) REFERENCES `users` (`username`),
ADD CONSTRAINT `follow_ibfk_2` FOREIGN KEY (`to_usr`) REFERENCES `users` (`username`);

--
-- Constraints for table `recommend`
--
ALTER TABLE `recommend`
ADD CONSTRAINT `recommend_ibfk_1` FOREIGN KEY (`username`) REFERENCES `users` (`username`),
ADD CONSTRAINT `recommend_ibfk_2` FOREIGN KEY (`cid`) REFERENCES `concerts` (`cid`);

--
-- Constraints for table `ucomments`
--
ALTER TABLE `ucomments`
ADD CONSTRAINT `ucomments_ibfk_1` FOREIGN KEY (`username`) REFERENCES `users` (`username`),
ADD CONSTRAINT `ucomments_ibfk_2` FOREIGN KEY (`cid`) REFERENCES `concerts` (`cid`);

--
-- Constraints for table `unew`
--
ALTER TABLE `unew`
ADD CONSTRAINT `unew_ibfk_1` FOREIGN KEY (`username`) REFERENCES `users` (`username`),
ADD CONSTRAINT `unew_ibfk_2` FOREIGN KEY (`cid`) REFERENCES `concerts` (`cid`);

--
-- Constraints for table `uprofile`
--
ALTER TABLE `uprofile`
ADD CONSTRAINT `uprofile_ibfk_1` FOREIGN KEY (`username`) REFERENCES `users` (`username`);

--
-- Constraints for table `u_sub`
--
ALTER TABLE `u_sub`
ADD CONSTRAINT `u_sub_ibfk_1` FOREIGN KEY (`username`) REFERENCES `users` (`username`),
ADD CONSTRAINT `u_sub_ibfk_2` FOREIGN KEY (`sub`) REFERENCES `genres` (`sub`);
