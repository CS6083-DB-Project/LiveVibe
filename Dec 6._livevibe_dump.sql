-- phpMyAdmin SQL Dump
-- version 4.2.10
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 06, 2014 at 11:19 PM
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
CREATE DEFINER=`root`@`localhost` PROCEDURE `check_id`(IN `submitted_username` VARCHAR(20), OUT `result` INT)
BEGIN
        SELECT COUNT(*) INTO result
        FROM users
        WHERE username = submitted_username;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `check_pwd`(IN `submitted_username` VARCHAR(20), IN `submitted_userpwd` VARCHAR(20), OUT `valid` INT)
BEGIN
        SELECT COUNT(*) INTO valid
        FROM users
        WHERE username = submitted_username AND
              userpwd  = submitted_userpwd;
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
('Bob Dylan', 'abc123', 'Sounds old.', '2011-05-22 16:44:34', '2014-10-25 13:22:48', '2014-11-25 13:54:26'),
('Damien Rice', 'abc123', 'Sounds good.', '2010-04-22 16:44:34', '2014-11-15 13:22:48', '2014-11-25 16:32:54'),
('Interpol', 'abc123', 'Sounds gay.', '2009-07-22 16:44:34', '2014-08-25 13:22:48', '2014-11-25 10:23:32'),
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
('Indie folk', 'Folk'),
('Indie pop', 'Pop'),
('Indie rock', 'Rock'),
('Pop rock', 'Rock');

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
('mchotdog', 'Barack Obama', '1961-08-04 23:44:34', 'Washington', 'DC', '20500');

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
('johndoe', 'abc123', '2011-05-12 13:44:34', '2014-09-25 13:22:48', '2014-11-12 21:14:32'),
('magicmike', 'abc123', '2014-01-04 12:34:34', '2014-11-23 13:22:48', '2014-11-25 16:42:53'),
('mchotdog', 'abc123', '2008-09-23 23:44:34', '2014-11-25 06:22:48', '2014-11-25 14:12:13');

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
('johndoe', 'Indie rock');

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
