-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 19, 2024 at 11:56 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `it350-pz-5859`
--

-- --------------------------------------------------------

--
-- Table structure for table `application`
--

CREATE TABLE `application` (
  `ApplicationID` int(11) NOT NULL,
  `CandidateID` int(11) DEFAULT NULL,
  `JobID` int(11) DEFAULT NULL,
  `ApplicationDateTime` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `application`
--

INSERT INTO `application` (`ApplicationID`, `CandidateID`, `JobID`, `ApplicationDateTime`) VALUES
(1, 1, 2, '2024-01-15 08:30:00'),
(2, 2, 2, '2024-01-16 09:45:00'),
(3, 3, 2, '2024-01-17 10:15:00'),
(4, 4, 2, '2024-01-18 11:30:00'),
(5, 5, 2, '2024-01-19 13:00:00'),
(6, 6, 2, '2024-01-20 14:45:00'),
(7, 7, 2, '2024-01-21 16:00:00'),
(8, 8, 2, '2024-01-22 17:30:00'),
(9, 9, 2, '2024-01-23 18:15:00'),
(10, 10, 2, '2024-01-24 19:45:00'),
(11, 1, 6, '2020-05-25 09:30:00'),
(12, 1, 7, '2022-01-15 10:15:00'),
(13, 1, 8, '2022-01-15 11:00:00'),
(14, 1, 9, '2022-01-15 12:45:00'),
(15, 14, 7, '2024-01-09 02:30:15'),
(17, 13, 7, '2024-01-09 02:30:15'),
(18, 12, 7, '2024-01-09 02:30:15');

-- --------------------------------------------------------

--
-- Table structure for table `candidate`
--

CREATE TABLE `candidate` (
  `CandidateID` int(11) NOT NULL,
  `FirstName` varchar(50) NOT NULL,
  `LastName` varchar(50) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Username` varchar(50) NOT NULL,
  `HashedPassword` varchar(255) NOT NULL,
  `Gender` enum('Male','Female','Other') DEFAULT NULL,
  `YearOfBirth` int(11) DEFAULT NULL,
  `City` varchar(50) DEFAULT NULL,
  `Address` varchar(255) DEFAULT NULL,
  `ProfilePicture` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `candidate`
--

INSERT INTO `candidate` (`CandidateID`, `FirstName`, `LastName`, `Email`, `Username`, `HashedPassword`, `Gender`, `YearOfBirth`, `City`, `Address`, `ProfilePicture`) VALUES
(1, 'Galya', 'Galinova', 'galina@gmail.com', 'galina', '$2y$10$HoM7XXCcxV1wYSR4s/umoOkC3NDeI0mOH32wq4Bkc10FsY6oDkfCO', 'Female', 2002, 'Belgrade', 'Belgrade, Second St. 2', NULL),
(2, 'Ivan', 'Petrov', 'ivan.petrov@example.com', 'ivan123', '$2y$10$HoM7XXCcxV1wYSR4s/umoOkC3NDeI0mOH32wq4Bkc10FsY6oDkfCO', 'Male', 1990, 'Moscow', '123 Main Street', NULL),
(3, 'Elena', 'Sidorova', 'elena.sidorova@example.com', 'elena456', '$2y$10$HoM7XXCcxV1wYSR4s/umoOkC3NDeI0mOH32wq4Bkc10FsY6oDkfCO', 'Female', 1985, 'Saint Petersburg', '456 Oak Avenue', NULL),
(4, 'Dmitry', 'Ivanov', 'dmitry.ivanov@example.com', 'dmitry789', '$2y$10$HoM7XXCcxV1wYSR4s/umoOkC3NDeI0mOH32wq4Bkc10FsY6oDkfCO', 'Male', 1992, 'Kazan', '789 Pine Road', NULL),
(5, 'Maria', 'Smirnova', 'maria.smirnova@example.com', 'maria101', '$2y$10$HoM7XXCcxV1wYSR4s/umoOkC3NDeI0mOH32wq4Bkc10FsY6oDkfCO', 'Female', 1988, 'Yekaterinburg', '101 Birch Lane', NULL),
(6, 'Alexei', 'Kuznetsov', 'alexei.kuznetsov@example.com', 'alexei2021', '$2y$10$HoM7XXCcxV1wYSR4s/umoOkC3NDeI0mOH32wq4Bkc10FsY6oDkfCO', 'Male', 1995, 'Novosibirsk', '2021 Cedar Street', NULL),
(7, 'Anna', 'Popova', 'anna.popova@example.com', 'anna_34', '$2y$10$HoM7XXCcxV1wYSR4s/umoOkC3NDeI0mOH32wq4Bkc10FsY6oDkfCO', 'Female', 1982, 'Sochi', '34 Elm Place', NULL),
(8, 'Sergei', 'Mikhailov', 'sergei.mikhailov@example.com', 'sergei567', '$2y$10$HoM7XXCcxV1wYSR4s/umoOkC3NDeI0mOH32wq4Bkc10FsY6oDkfCO', 'Male', 1987, 'Samara', '567 Spruce Drive', NULL),
(9, 'Olga', 'Ilyina', 'olga.ilyina@example.com', 'olga_89', '$2y$10$HoM7XXCcxV1wYSR4s/umoOkC3NDeI0mOH32wq4Bkc10FsY6oDkfCO', 'Female', 1993, 'Nizhny Novgorod', '89 Willow Street', NULL),
(10, 'Pavel', 'Vasiliev', 'pavel.vasiliev@example.com', 'pavel999', '$2y$10$HoM7XXCcxV1wYSR4s/umoOkC3NDeI0mOH32wq4Bkc10FsY6oDkfCO', 'Male', 1980, 'Volgograd', '999 Pinecrest Avenue', NULL),
(11, 'Tatiana', 'Romanova', 'tatiana.romanova@example.com', 'tatiana_2000', '$2y$10$HoM7XXCcxV1wYSR4s/umoOkC3NDeI0mOH32wq4Bkc10FsY6oDkfCO', 'Female', 1983, 'Krasnoyarsk', '2000 Maple Lane', NULL),
(12, 'Mefody', 'Kuznetsov', 'mefody.kuznetsov@email.com', 'mefody_kuznetsov', '$2y$10$kgngQPhEMA0SZS9.eSUIPeJ/ktcnmzLUtc3zJcPYoZgRrT7zWacSe', 'Male', 1985, 'Vladivostok', '404 Oak St', 'mefody_profile.jpg'),
(13, 'Ludmila', 'Petrovna', 'ludmila.petrovna@email.com', 'ludmila_petrovna', '$2y$10$kgngQPhEMA0SZS9.eSUIPeJ/ktcnmzLUtc3zJcPYoZgRrT7zWacSe', 'Female', 1988, 'Krasnoyarsk', '505 Pine St', 'ludmila_profile.jpg'),
(14, 'Anatoliy', 'Golubev', 'anatoliy.golubev@email.com', 'anatoliy_golubev', '$2y$10$kgngQPhEMA0SZS9.eSUIPeJ/ktcnmzLUtc3zJcPYoZgRrT7zWacSe', 'Male', 1983, 'Novosibirsk', '606 Maple St', 'anatoliy_profile.jpg');

-- --------------------------------------------------------

--
-- Stand-in structure for view `candidateapplicationview`
-- (See below for the actual view)
--
CREATE TABLE `candidateapplicationview` (
`CandidateID` int(11)
,`FirstName` varchar(50)
,`LastName` varchar(50)
,`Email` varchar(100)
,`CandidateCity` varchar(50)
,`ApplicationID` int(11)
,`Position` varchar(100)
,`ApplicationDateTime` datetime
);

-- --------------------------------------------------------

--
-- Table structure for table `contact`
--

CREATE TABLE `contact` (
  `ID` int(11) NOT NULL,
  `RecruiterID` int(11) DEFAULT NULL,
  `FirstName` varchar(50) NOT NULL,
  `LastName` varchar(50) NOT NULL,
  `PhoneNumber` varchar(20) DEFAULT NULL,
  `Email` varchar(100) DEFAULT NULL,
  `Position` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact`
--

INSERT INTO `contact` (`ID`, `RecruiterID`, `FirstName`, `LastName`, `PhoneNumber`, `Email`, `Position`) VALUES
(2, 2, 'Boris', 'Ivanov', '+123456789', 'Boris_ivanovic@gmail.com', 'CEO'),
(3, 2, 'Nikolai', 'Fedorov', '987123654', 'nikolai.fedorov@example.com', 'Junior Recruiter'),
(4, 2, 'Svetlana', 'Morozova', '456321789', 'svetlana.morozova@example.com', 'Recruitment Manager'),
(5, 2, 'Vladimir', 'Sokolov', '321789654', 'vladimir.sokolov@example.com', 'HR Specialist'),
(6, 3, 'Yulia', 'Nikitina', '654987321', 'yulia.nikitina@example.com', 'Talent Acquisition Specialist'),
(7, 2, 'Andrei', 'Lebedev', '123789456', 'andrei.lebedev@example.com', 'Recruitment Coordinator'),
(8, 2, 'Eva', 'Vorontsova', '789654321', 'eva.vorontsova@example.com', 'Technical Recruiter'),
(9, 2, 'Mikhail', 'Alekseev', '456123789', 'mikhail.alekseev@example.com', 'HR Assistant'),
(10, 3, 'Tatiana', 'Smolina', '321987654', 'tatiana.smolina@example.com', 'Recruitment Consultant'),
(12, 3, 'Yekaterina', 'Pavlova', '654789321', 'yekaterina.pavlova@example.com', 'Senior Talent Acquisition Specialist'),
(15, 3, 'Dmitri', 'Volkov', '456987123', 'dmitri.volkov@example.com', 'Talent Acquisition Coordinator'),
(17, 3, 'Maria', 'Marilkina', '1234567879', 'marijko4@gmail.com', 'HR Manager'),
(18, 4, 'Alexey', 'Ivanov', '+74951234567', 'alexey@email.com', 'HR Manager'),
(19, 5, 'Ekaterina', 'Smirnova', '+74957654321', 'ekaterina@email.com', 'Recruitment Specialist'),
(20, 6, 'Jovana', 'Marković', '+381641112233', 'jovana@email.com', 'HR Assistant');

-- --------------------------------------------------------

--
-- Table structure for table `favorites`
--

CREATE TABLE `favorites` (
  `FavoriteID` int(11) NOT NULL,
  `CandidateID` int(11) DEFAULT NULL,
  `JobID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `favorites`
--

INSERT INTO `favorites` (`FavoriteID`, `CandidateID`, `JobID`) VALUES
(1, 1, 9),
(2, 1, 10),
(3, 1, 11);

-- --------------------------------------------------------

--
-- Table structure for table `interview`
--

CREATE TABLE `interview` (
  `InterviewID` int(11) NOT NULL,
  `CandidateID` int(11) DEFAULT NULL,
  `JobID` int(11) DEFAULT NULL,
  `InterviewDateTime` datetime DEFAULT NULL,
  `Location` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `interview`
--

INSERT INTO `interview` (`InterviewID`, `CandidateID`, `JobID`, `InterviewDateTime`, `Location`) VALUES
(1, 1, 2, '2024-01-16 15:58:00', 'zoom link'),
(2, 2, 2, '2024-01-25 15:12:00', 'zoom link'),
(3, 3, 2, '2024-01-22 15:17:00', 'zoom link'),
(4, 1, 6, '2020-05-27 11:00:00', 'zoom'),
(5, 1, 7, '2022-01-21 15:30:00', 'Office Building 4, room 3'),
(6, 1, 8, '2022-01-22 11:00:00', 'Coworking Metalurg, office 4');

-- --------------------------------------------------------

--
-- Table structure for table `job`
--

CREATE TABLE `job` (
  `JobID` int(11) NOT NULL,
  `RecruiterID` int(11) DEFAULT NULL,
  `Position` varchar(100) NOT NULL,
  `JobType` varchar(50) NOT NULL,
  `Salary` decimal(10,2) NOT NULL,
  `MinExperience` int(11) NOT NULL,
  `ContactID` int(11) DEFAULT NULL,
  `Photo` varchar(255) DEFAULT NULL,
  `Description` text DEFAULT NULL,
  `PostingDateTime` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `job`
--

INSERT INTO `job` (`JobID`, `RecruiterID`, `Position`, `JobType`, `Salary`, `MinExperience`, `ContactID`, `Photo`, `Description`, `PostingDateTime`) VALUES
(2, 2, 'Personal Assistant', 'Assistant', 70000.00, 2, 2, '2.png', 'Requirements:\n\nproven experience as a Personal Assistant or similar role,  organizational and multitasking abilities,\nstrong communication and interpersonal skills\n', '2024-01-14 13:45:26'),
(4, 3, 'HR Assistant', 'Assistant', 60000.00, 0, 17, '4.jpg', NULL, '2024-01-14 14:41:44'),
(6, 4, 'Software Engineer', 'Full-time', 80000.00, 2, 18, '6.jpeg', 'We are seeking a highly skilled Software Engneer to join our innovative team. The ideal candidate should have a strong background in software development.', '2020-05-11 12:00:00'),
(7, 2, 'Data Analyst', 'Part-time', 60000.00, 2, 2, '7.jpg', ' This part-time role offers a flexible schedule and is ideal for individuals with a passion for data analysis looking to contribute to impactful projects.', '2023-12-01 10:30:00'),
(8, 5, 'Marketing Specialist', 'Full-time', 70000.00, 3, 19, '8.jpg', 'The ideal candidate will have a strategic mindset, excellent communication skills, and a proven ability to create and implement successful marketing campaigns. Join our team and contribute to the growth of our organization.', '2023-12-11 14:45:00'),
(9, 5, 'Social Media Manager', 'Contract', 60000.00, 2, 19, '9.jpg', 'We are looking for a talented Social Media Manager to lead our social media efforts.', '2023-12-01 09:00:00'),
(10, 6, 'Financial Analyst', 'Full-time', 90000.00, 4, 20, '10.png', 'This full-time position offers a competitive salary and an opportunity to contribute to the financial success of our organization.', '2024-01-11 16:30:00'),
(11, 6, 'Investment Advisor', 'Full-time', 100000.00, 5, 20, '11.jpg', 'We are hiring a dedicated Investment Advisor to provide expert financial advice to our clients.', '2024-01-15 11:15:00'),
(12, 2, 'Software Developer', 'Junior', 60000.00, 2, 2, '12.png', 'Exciting opportunity for a Junior Software Developer to join our dynamic team.', '2024-01-15 01:32:39');

-- --------------------------------------------------------

--
-- Table structure for table `jobtypes`
--

CREATE TABLE `jobtypes` (
  `JobTypeID` int(11) NOT NULL,
  `JobType` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jobtypes`
--

INSERT INTO `jobtypes` (`JobTypeID`, `JobType`) VALUES
(1, 'Manager'),
(2, 'Junior'),
(3, 'Middle'),
(4, 'Senior'),
(5, 'Specialist'),
(6, 'Assistant'),
(7, 'Executive'),
(8, 'Coordinator'),
(9, 'Analyst'),
(10, 'Associate'),
(11, 'Consultant'),
(12, 'Lead'),
(13, 'Supervisor'),
(14, 'Technician'),
(15, 'Architect'),
(16, 'Designer'),
(17, 'Developer'),
(18, 'Engineer'),
(19, 'Administrator'),
(20, 'Coordinator'),
(21, 'Officer'),
(22, 'Representative'),
(23, 'Strategist'),
(24, 'Advisor');

-- --------------------------------------------------------

--
-- Table structure for table `recruiter`
--

CREATE TABLE `recruiter` (
  `RecruiterID` int(11) NOT NULL,
  `Username` varchar(50) NOT NULL,
  `HashedPassword` varchar(255) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Name` varchar(100) DEFAULT NULL,
  `City` varchar(50) DEFAULT NULL,
  `OwnerFirstName` varchar(50) DEFAULT NULL,
  `OwnerLastName` varchar(50) DEFAULT NULL,
  `OwnerAddress` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `recruiter`
--

INSERT INTO `recruiter` (`RecruiterID`, `Username`, `HashedPassword`, `Email`, `Name`, `City`, `OwnerFirstName`, `OwnerLastName`, `OwnerAddress`) VALUES
(2, 'borya_78', '$2y$10$kgngQPhEMA0SZS9.eSUIPeJ/ktcnmzLUtc3zJcPYoZgRrT7zWacSe', 'Boris_ivanovic@gmail.com', 'Borysko Inc.', 'Belgrade', 'Boris', 'Ivanov', 'Belgrade, Main St. 1'),
(3, 'Michailo', '$2y$10$WRV43jDMtddTuCLj3NpsuusKkasa47YIt24QkbefibF8PYOpYZTQ6', 'michailo@gmail.com', 'Michailkino Industries', 'Belgrade', 'Michail', 'Michailov', 'Belgrade, Main St. 21'),
(4, 'Oladushki', '$2y$10$kgngQPhEMA0SZS9.eSUIPeJ/ktcnmzLUtc3zJcPYoZgRrT7zWacSe', 'rusco1@email.com', 'Oladushki Co.', 'Moscow', 'Ivan', 'Petrov', 'Red Square 1'),
(5, 'Blinchiki', '$2y$10$kgngQPhEMA0SZS9.eSUIPeJ/ktcnmzLUtc3zJcPYoZgRrT7zWacSe', 'recruitrus@email.com', 'Blinchiki Co.', 'Moscow', 'Anna', 'Kovaleva', 'Arbat Street 23'),
(6, 'Cevapcici', '$2y$10$kgngQPhEMA0SZS9.eSUIPeJ/ktcnmzLUtc3zJcPYoZgRrT7zWacSe', 'cevapcici@email.com', 'Ćevapčići Industries Co.', 'Belgrade', 'Marko', 'Jovanović', 'Terazije 15'),
(7, 'vanilice', '$2y$10$kgngQPhEMA0SZS9.eSUIPeJ/ktcnmzLUtc3zJcPYoZgRrT7zWacSe', 'vanilice@email.com', 'Vanilice Stuffing Solutions Co.', 'Belgrade', 'Nina', 'Novak', 'Knez Mihailova 8');

-- --------------------------------------------------------

--
-- Structure for view `candidateapplicationview`
--
DROP TABLE IF EXISTS `candidateapplicationview`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `candidateapplicationview`  AS SELECT `c`.`CandidateID` AS `CandidateID`, `c`.`FirstName` AS `FirstName`, `c`.`LastName` AS `LastName`, `c`.`Email` AS `Email`, `c`.`City` AS `CandidateCity`, `a`.`ApplicationID` AS `ApplicationID`, `j`.`Position` AS `Position`, `a`.`ApplicationDateTime` AS `ApplicationDateTime` FROM ((`candidate` `c` left join `application` `a` on(`c`.`CandidateID` = `a`.`CandidateID`)) left join `job` `j` on(`a`.`JobID` = `j`.`JobID`)) ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `application`
--
ALTER TABLE `application`
  ADD PRIMARY KEY (`ApplicationID`),
  ADD KEY `CandidateID` (`CandidateID`),
  ADD KEY `JobID` (`JobID`);

--
-- Indexes for table `candidate`
--
ALTER TABLE `candidate`
  ADD PRIMARY KEY (`CandidateID`);

--
-- Indexes for table `contact`
--
ALTER TABLE `contact`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `RecruiterID` (`RecruiterID`);

--
-- Indexes for table `favorites`
--
ALTER TABLE `favorites`
  ADD PRIMARY KEY (`FavoriteID`),
  ADD KEY `CandidateID` (`CandidateID`),
  ADD KEY `JobID` (`JobID`);

--
-- Indexes for table `interview`
--
ALTER TABLE `interview`
  ADD PRIMARY KEY (`InterviewID`),
  ADD KEY `CandidateID` (`CandidateID`),
  ADD KEY `JobID` (`JobID`);

--
-- Indexes for table `job`
--
ALTER TABLE `job`
  ADD PRIMARY KEY (`JobID`),
  ADD KEY `RecruiterID` (`RecruiterID`),
  ADD KEY `ContactID` (`ContactID`);

--
-- Indexes for table `jobtypes`
--
ALTER TABLE `jobtypes`
  ADD PRIMARY KEY (`JobTypeID`);

--
-- Indexes for table `recruiter`
--
ALTER TABLE `recruiter`
  ADD PRIMARY KEY (`RecruiterID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `application`
--
ALTER TABLE `application`
  MODIFY `ApplicationID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `candidate`
--
ALTER TABLE `candidate`
  MODIFY `CandidateID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `contact`
--
ALTER TABLE `contact`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `favorites`
--
ALTER TABLE `favorites`
  MODIFY `FavoriteID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `interview`
--
ALTER TABLE `interview`
  MODIFY `InterviewID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `job`
--
ALTER TABLE `job`
  MODIFY `JobID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `jobtypes`
--
ALTER TABLE `jobtypes`
  MODIFY `JobTypeID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `recruiter`
--
ALTER TABLE `recruiter`
  MODIFY `RecruiterID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `application`
--
ALTER TABLE `application`
  ADD CONSTRAINT `application_ibfk_1` FOREIGN KEY (`CandidateID`) REFERENCES `candidate` (`CandidateID`),
  ADD CONSTRAINT `application_ibfk_2` FOREIGN KEY (`JobID`) REFERENCES `job` (`JobID`);

--
-- Constraints for table `contact`
--
ALTER TABLE `contact`
  ADD CONSTRAINT `contact_ibfk_1` FOREIGN KEY (`RecruiterID`) REFERENCES `recruiter` (`RecruiterID`);

--
-- Constraints for table `favorites`
--
ALTER TABLE `favorites`
  ADD CONSTRAINT `favorites_ibfk_1` FOREIGN KEY (`CandidateID`) REFERENCES `candidate` (`CandidateID`),
  ADD CONSTRAINT `favorites_ibfk_2` FOREIGN KEY (`JobID`) REFERENCES `job` (`JobID`);

--
-- Constraints for table `interview`
--
ALTER TABLE `interview`
  ADD CONSTRAINT `interview_ibfk_1` FOREIGN KEY (`CandidateID`) REFERENCES `candidate` (`CandidateID`),
  ADD CONSTRAINT `interview_ibfk_2` FOREIGN KEY (`JobID`) REFERENCES `job` (`JobID`);

--
-- Constraints for table `job`
--
ALTER TABLE `job`
  ADD CONSTRAINT `job_ibfk_1` FOREIGN KEY (`RecruiterID`) REFERENCES `recruiter` (`RecruiterID`),
  ADD CONSTRAINT `job_ibfk_2` FOREIGN KEY (`ContactID`) REFERENCES `contact` (`ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
