-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jun 28, 2017 at 01:37 PM
-- Server version: 10.1.16-MariaDB
-- PHP Version: 7.0.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `shop`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `ID` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Description` text NOT NULL,
  `parent` int(11) NOT NULL,
  `Ordering` int(11) DEFAULT NULL,
  `Visibility` tinyint(4) NOT NULL DEFAULT '0',
  `Allow_Comment` tinyint(4) NOT NULL DEFAULT '0',
  `Allow_Ads` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`ID`, `Name`, `Description`, `parent`, `Ordering`, `Visibility`, `Allow_Comment`, `Allow_Ads`) VALUES
(9, 'Hand Made', 'Hand Made Items', 0, 1, 1, 1, 0),
(10, 'Computers', 'Computers Item', 0, 2, 0, 0, 0),
(11, 'Cell Phones', 'Cell Phones Item', 0, 3, 0, 0, 0),
(12, 'Clothing', 'Clothing And Fashion', 0, 4, 0, 0, 0),
(13, 'Tools', 'Home Tools', 0, 5, 0, 0, 0),
(15, 'Infinix', 'The Model Is Nice', 13, 9, 0, 0, 0),
(17, 'Boxes', 'Description Boxes', 11, 1, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `c_id` int(11) NOT NULL,
  `comment` text NOT NULL,
  `status` tinyint(4) NOT NULL,
  `comment_date` date NOT NULL,
  `item_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`c_id`, `comment`, `status`, `comment_date`, `item_id`, `user_id`) VALUES
(2, 'This is Nice Phone', 1, '2017-06-20', 27, 15),
(3, 'This is Nice Phone This is Nice Phone This is Nice Phone This is Nice Phone This is Nice Phone This is Nice Phone This is Nice Phone This is Nice Phone This is Nice Phone This is Nice Phone This is Nice Phone This is Nice Phone This is Nice Phone This is Nice Phone This is Nice Phone This is Nice Phone This is Nice Phone This is Nice Phone This is Nice Phone This is Nice Phone This is Nice Phone This is Nice Phone This is Nice Phone This is Nice Phone ', 1, '2017-06-20', 27, 15);

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `Item_ID` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Description` text NOT NULL,
  `Price` varchar(255) NOT NULL,
  `Add_Date` date NOT NULL,
  `Country_Made` varchar(255) NOT NULL,
  `Image` varchar(255) NOT NULL,
  `Status` varchar(255) NOT NULL,
  `Rating` smallint(6) NOT NULL,
  `Approve` tinyint(4) NOT NULL DEFAULT '0',
  `Cat_ID` int(11) NOT NULL,
  `Member_ID` int(11) NOT NULL,
  `tags` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`Item_ID`, `Name`, `Description`, `Price`, `Add_Date`, `Country_Made`, `Image`, `Status`, `Rating`, `Approve`, `Cat_ID`, `Member_ID`, `tags`) VALUES
(27, 'Apple آيفون 7 - 32 جيجا بايت - فضي', 'المواصفات الأساسية شاشة لمس تكاثفية IPS LCD تبلغ 4.7 بوصة ذاكرة داخلية تبلغ 32 جيجا بايت كاميرا خلفية 12 ميجا بكسل وكاميرا أمامية 7 ميجا بكسل رام 2 جيجا بايت، وحدة معالجة مركزية رباعية النواة نظام التشغيل‏:‏ iOS 10 بطارية ليثيوم أيون 1960 مللي أمبير فى الساعة', '12.999', '2017-06-19', 'Apple', '', '1', 0, 1, 11, 1, 'Abdo, Game, Elzero'),
(28, 'TCL 32 Inch LED TV Black - 32D2900M ', 'Screen size: 32 Inch Resolution: 1366 x 768 (HD) Contrast: Mega contrast Brightness cd/m2: 240 Viewing angle: 178ْ Aspect ratio: 16:09 USB: Video - Photo - MP3 Broadcast:- DVB-T PVR Inputs & Outputs:- HDMI: 2 USB: 1 Video: 1 Component (DVD): 1 RGB (PC): 1 Speaker output: 5 W x ... ', '500.00', '2017-06-20', 'Egypt', '', '1', 0, 1, 10, 15, 'Abdo, Game, Elzero'),
(29, 'Screen size: 32 Inch Resolution: 1366 x 768 (HD)', 'Screen size: 32 Inch Resolution: 1366 x 768 (HD) Contrast: Mega contrast Brightness cd/m2: 240 Viewing angle: 178ْ Aspect ratio: 16:09 USB: Video - Photo - MP3 Broadcast:- DVB-T PVR Inputs & Outputs:- HDMI: 2 USB: 1 Video: 1 Component (DVD): 1 RGB (PC): 1 Speaker output: 5 W x ', '30', '2017-06-20', 'china', '', '2', 0, 1, 17, 15, 'Test, TV'),
(30, 'Item Title Must Be No More Than 35', 'Item Title Must Be No More Ahan 35 character', '35', '2017-06-20', 'Egypt', '', '1', 0, 1, 15, 15, 'Abdo, Game, Elzero'),
(31, 'Game ', 'Game Is Very Goood', '30', '2017-06-21', 'Egypt', '', '1', 0, 1, 9, 1, 'Abdo, Game, Elzero'),
(32, 'New Game', 'This Is New Game ', '80', '2017-06-24', 'Egypt', '', '1', 0, 1, 11, 1, 'RBG, Test');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `UserID` int(11) NOT NULL,
  `Username` varchar(255) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `FullName` varchar(255) NOT NULL,
  `GroupID` int(11) NOT NULL DEFAULT '0',
  `TrastStatus` int(11) NOT NULL DEFAULT '0',
  `RegStatus` int(11) NOT NULL DEFAULT '0',
  `Date` date NOT NULL,
  `user_profile` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`UserID`, `Username`, `Password`, `Email`, `FullName`, `GroupID`, `TrastStatus`, `RegStatus`, `Date`, `user_profile`) VALUES
(1, 'abdo', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'thebeststory0@gmail.com', 'Abdo Mohamed Alnefely', 1, 0, 1, '2017-05-31', ''),
(7, 'Mohamed', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'Mohamed@Gmail.com', 'Mohamed Ahmed Elsayed', 0, 0, 1, '2017-06-03', ''),
(8, 'Omar', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'omar@gamil.com', 'Omar Abdelate', 0, 0, 1, '2017-06-03', ''),
(9, 'Sayed ', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'Sayed@Yahoo.com', 'Sayed Mohamed', 0, 0, 1, '2017-06-03', ''),
(11, 'Magde', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', '', '', 1, 0, 1, '0000-00-00', ''),
(15, 'testt', '9b67d1d3496c9a95af7a46a2c5457d6469688522', 'thebeststory0@gmail.com', '', 0, 0, 1, '2017-06-09', ''),
(16, 'Gamal', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'Gamal@gmail.com1', 'Gamal alnefely', 0, 0, 1, '2017-06-28', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `Name` (`Name`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`c_id`),
  ADD KEY `item_id` (`item_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`Item_ID`),
  ADD KEY `Cat_ID` (`Cat_ID`),
  ADD KEY `Member_ID` (`Member_ID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`UserID`),
  ADD UNIQUE KEY `Username` (`Username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `c_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `Item_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comment_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`UserID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `items_comments` FOREIGN KEY (`item_id`) REFERENCES `items` (`Item_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `cat_1` FOREIGN KEY (`Cat_ID`) REFERENCES `categories` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `member_1` FOREIGN KEY (`Member_ID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
