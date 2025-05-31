-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 31, 2025 at 11:16 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bakery`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `IDA` int(5) NOT NULL,
  `username` varchar(20) NOT NULL,
  `IDP` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`IDA`, `username`, `IDP`) VALUES
(1, 'vixie', 8);

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `IDC` int(5) NOT NULL,
  `username` varchar(20) NOT NULL,
  `IDP` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`IDC`, `username`, `IDP`) VALUES
(12359, '', 4),
(12362, '', 9),
(12364, 'user', 11);

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `IDT` int(5) NOT NULL,
  `name` varchar(50) NOT NULL,
  `price` decimal(10,0) NOT NULL,
  `description` text NOT NULL,
  `imgpath` varchar(150) NOT NULL,
  `IDS` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`IDT`, `name`, `price`, `description`, `imgpath`, `IDS`) VALUES
(67892, 'Croissant duo cocoa', 40, 'A croissant, yes, but not only that! The puff pastry has a zebra pattern on top and is filled with cocoa.', '../menu/image.jpg', 12325),
(67893, 'Brownies', 70, 'The authentic brownie, as soft as it is rich in chocolate!', '../menu/dc56bb617643234a5fe4b6e0aff0f22f.jpg', 12325),
(67894, 'Chocolate chip cookies', 50, 'Classic buttery cookies loaded with gooey chocolate chips', '../menu/f21ec7494c8134e3ba921c39e136cab9.jpg', 12325),
(67895, 'Triple chocolate cookies', 50, '3 times more chocolate for 3 times more deliciousness!', '../menu/55d6ebfa1e0897d1a37c9f4fea037bf3.jpg', 12325),
(67896, 'Muffins', 80, 'Soft, fluffy muffins with a golden crust and rich flavor.', '../menu/8b93cb5855abb9898da82d785dbd7f40.jpg', 12325),
(67897, 'Chocolate muffins', 100, 'Moist chocolate muffins bursting with rich cocoa flavor.', '../menu/a1b8ac0bbc69170dc251b8c3ca16d5bc.jpg', 12325),
(67899, 'Sable', 50, ' Buttery and crumbly shortbread cookies that melt in your mouth. Often filled with jam or dipped in chocolate, they’re delicately sweet and perfect with tea or coffee.', '../menu/4587891b9b6bb157e3399d6eaf6383ff.jpg', 12326),
(67900, 'Makroud', 70, 'Semolina dough filled with dates or almonds, fried then soaked in honey.', '../menu/183535cc7a7a7c9a841a492e8243f14f.jpg', 12326),
(67901, 'Baklawa', 70, 'Layers of pastry filled with almonds or walnuts, flavored with orange blossom water.', '../menu/9569c2e1efc61a1fe359e07a6ef948cb.jpg', 12326),
(67902, 'Mchewek', 70, 'Soft almond cookie coated in crushed nuts, topped with a cherry or almond. ', '../menu/0eaba99dfa2cfe9d00435f446dd4663a.jpg', 12326),
(67903, 'Macarons', 80, 'Light, crisp and chewy, our colorful french macaroons are filled with rich ganache , creamy buttercream, or fruity jams. A perfect bite of sweetness!', '../uploads/1748276811_d815ffdc094a3d6ed62987bccdccb50e.jpg', 12325),
(67911, 'Croissant', 20, 'The famous croissant is an all-butter recipe made from puff pastry.', '../uploads/1748450228_18c190c26ed4011b258849e2f9418819.jpg', 12325),
(67912, 'Pain au chocolat', 20, 'The traditional pain au chocolat is made with all-butter puff pastry.', '../uploads/1748450283_252de5452b88e294c1b1b5517f6c80d3.jpg', 12325),
(67915, 'Red Velvet cupcakes', 80, 'Moist red velvet cupcakes topped with smooth cream cheese frosting.', '../uploads/1748475523_3b220661dbcf9a85c9f15b85ad326324.jpg', 12325),
(67916, 'Custard Cread Donuts', 80, 'Soft, fluffy donuts filled with rich, creamy vanilla custard.', '../uploads/1748475567_bba33602155546d59181e8a7853aaaeb.jpg', 12325),
(67917, 'Apple pie', 150, 'Classic apple pie with spiced apple filling and a golden, buttery crust.', '../uploads/1748475599_624e4c2b540a31451a79167c5c4d0212.jpg', 12325),
(67918, 'Chocalate cupcakes', 80, 'Decadent chocolate cupcakes with a rich, fudgy flavor and chocolate frosting.', '../uploads/1748475637_027d8bb0d6b6cde8347c9ac1280e79f7.jpg', 12325),
(67919, 'Cinnamon rolls', 150, 'Soft, swirled rolls with a cinnamon-sugar filling and a sweet glaze on top.', '../uploads/1748475943_50867f465d6d774c2aa70890b9697641.jpg', 12325),
(67920, 'Éclairs', 150, 'Light choux pastry filled with vanilla cream and topped with rich chocolate icing.', '../uploads/1748476004_547078892e008c1167f2383898e65b6a.jpg', 12325),
(67921, 'Crème Brûlée', 150, 'Silky vanilla custard topped with a crisp, caramelized sugar crust.', '../uploads/1748476143_ba4211b0d04b9a013dbef4e143667a8f.jpg', 12325),
(67922, 'Tiramisu', 150, 'Layers of coffee-soaked sponge and mascarpone cream, dusted with cocoa powder.', '../uploads/1748476286_f12a80e6dcdc69f4277cf8b6b09c1d37.jpg', 12325),
(67923, 'Millefeuille', 100, 'Flaky puff pastry layers with smooth vanilla cream and a sweet glaze.', '../uploads/1748476361_31305af6ba1b21da27bda0de52e2620d.jpg', 12325),
(67924, 'Swiss rolls', 150, ' Light sponge cake rolled with sweet cream and jam, soft and delicious in every bite', '../uploads/1748476697_f5c2a8d9fc332bdc0bbcb462c9dfd2a5.jpg', 12325),
(67925, 'Griwech', 70, 'Crispy, honey-coated pastry twists with a hint of orange blossom.', '../uploads/1748476838_42613b03e95c12b49337f02d8cd84eb3.jpg', 12326),
(67926, 'Mhancha', 80, 'Coiled pastry filled with almond paste, flavored with cinnamon and orange blossom.', '../uploads/1748476978_db2174ed0635d970e6599030bb476281.jpg', 12326),
(67927, 'Ghraibiya', 80, 'Crumbly, melt-in-the-mouth cookies made with flour, sugar, and butter or oil.', '../uploads/1748477329_e0d473d67418321d17346ec213608e2d.jpg', 12326),
(67928, 'Kalb el Louz', 70, 'Semolina cake with orange blossom syrup and a heart of almond paste.', '../uploads/1748477456_57e94ac0ae039ef9b0d6db1b7a46a36e.jpg', 12326),
(67929, 'Dziriette', 80, 'Almond-filled tartlets topped with glazed icing or syrup.', '../uploads/1748477532_be395864cbb9c55e28f29ac0f1b1f1b2.jpg', 12326),
(67930, 'Kaak Nakache', 70, 'Ring-shaped cookies filled with dates and decorated with a tweezer pattern', '../uploads/1748477594_37e289978cdd7ef50a5eb3e23836ab15.jpg', 12326),
(67931, 'Tcharek', 80, 'Crescent-shaped pastries stuffed with dates or almonds, dusted with sugar.', '../uploads/1748477768_cb507784cdec8ea97c6a39bff29e980e.jpg', 12326),
(67932, 'Arayech', 80, 'Star-shaped almond cookies with a honey glaze and intricate decorations.', '../uploads/1748477892_07973c060a4ef8f85931c7cd5e355af5.jpg', 12326);

-- --------------------------------------------------------

--
-- Table structure for table `order`
--

CREATE TABLE `order` (
  `IDO` int(5) NOT NULL,
  `items` varchar(50) NOT NULL,
  `total` decimal(10,0) NOT NULL,
  `status` varchar(20) DEFAULT 'pending',
  `state` varchar(10) DEFAULT 'pending',
  `IDC` int(5) NOT NULL,
  `style` varchar(50) DEFAULT NULL,
  `size` varchar(50) DEFAULT NULL,
  `occasion` varchar(50) DEFAULT NULL,
  `flavour` varchar(50) DEFAULT NULL,
  `diet` varchar(50) DEFAULT NULL,
  `topping` varchar(30) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ordereditem`
--

CREATE TABLE `ordereditem` (
  `IDT` int(11) NOT NULL,
  `IDO` int(11) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `person`
--

CREATE TABLE `person` (
  `IDP` int(5) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(30) NOT NULL,
  `birth` date NOT NULL,
  `adress` varchar(50) NOT NULL,
  `phone` varchar(13) NOT NULL,
  `email` varchar(40) NOT NULL,
  `role` enum('customer','admin') NOT NULL DEFAULT 'customer'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `person`
--

INSERT INTO `person` (`IDP`, `username`, `password`, `birth`, `adress`, `phone`, `email`, `role`) VALUES
(3, 'mamamia', 'italiano180', '2025-05-12', 'italia.roma', '0764534177', 'lamiacara@gmail.com', 'customer'),
(4, 'earthling67', 'alienbabe51', '2001-10-10', 'area51,us', '0724523973', 'alienlivesmatter@gmail.yahoo', 'customer'),
(8, 'vixie', 'a1n4n0a00', '2004-02-11', 'Valmascort,Annaba', '0723453278', 'afnane.ayache140@gmail.com', 'admin'),
(9, 'afnane', 'anna14000', '1999-12-23', 'Valmascort,Annaba', '05535732', 'lamiacara@gmail.com', 'customer'),
(11, 'user', 'user1999', '1999-11-11', 'street,city', '0777777777', 'userEmail@example.com', 'customer');

-- --------------------------------------------------------

--
-- Table structure for table `section`
--

CREATE TABLE `section` (
  `IDS` int(5) NOT NULL,
  `name` varchar(20) NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `section`
--

INSERT INTO `section` (`IDS`, `name`, `description`) VALUES
(12325, 'Baked Goods', 'A delightful assortment of fresh , oven-backed treats made daily. From buttery croissants and flaky pastries to wholesome breads and savory snacks, This section offers comforting classics perfect for any time of day.'),
(12326, 'Traditional Sweets', 'Celebrate heritage and flavor with time-honored confections passed down through generations. This section features rich, Authentic desserts rooted in cultural tradition, Crafted with care and nostalgia.'),
(12327, 'Wedding Cakes', 'Elegant, custom-designed cakes tailored for your special day. Each creations is blend of beauty and taste-layered, decorated, and personalized to match your wedding theme and dreams.');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`IDA`),
  ADD KEY `FK_(ADMIN_PERSON)` (`IDP`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`IDC`),
  ADD KEY `FK_(CUSTOMER_PERSON)` (`IDP`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`IDT`),
  ADD KEY `FK_(ITEMS_SECTION)` (`IDS`);

--
-- Indexes for table `order`
--
ALTER TABLE `order`
  ADD PRIMARY KEY (`IDO`),
  ADD KEY `FK_(ORDER_CUSTOMER)` (`IDC`);

--
-- Indexes for table `ordereditem`
--
ALTER TABLE `ordereditem`
  ADD PRIMARY KEY (`IDT`,`IDO`),
  ADD KEY `FK_(ORDEREDITEM_ORDER)` (`IDO`);

--
-- Indexes for table `person`
--
ALTER TABLE `person`
  ADD PRIMARY KEY (`IDP`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `section`
--
ALTER TABLE `section`
  ADD PRIMARY KEY (`IDS`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `IDA` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `IDC` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12365;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `IDT` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67935;

--
-- AUTO_INCREMENT for table `order`
--
ALTER TABLE `order`
  MODIFY `IDO` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `person`
--
ALTER TABLE `person`
  MODIFY `IDP` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `section`
--
ALTER TABLE `section`
  MODIFY `IDS` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12330;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin`
--
ALTER TABLE `admin`
  ADD CONSTRAINT `FK_(ADMIN_PERSON)` FOREIGN KEY (`IDP`) REFERENCES `person` (`IDP`);

--
-- Constraints for table `customer`
--
ALTER TABLE `customer`
  ADD CONSTRAINT `FK_(CUSTOMER_PERSON)` FOREIGN KEY (`IDP`) REFERENCES `person` (`IDP`);

--
-- Constraints for table `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `FK_(ITEMS_SECTION)` FOREIGN KEY (`IDS`) REFERENCES `section` (`IDS`);

--
-- Constraints for table `order`
--
ALTER TABLE `order`
  ADD CONSTRAINT `FK_(ORDER_CUSTOMER)` FOREIGN KEY (`IDC`) REFERENCES `customer` (`IDC`);

--
-- Constraints for table `ordereditem`
--
ALTER TABLE `ordereditem`
  ADD CONSTRAINT `FK_(ORDEREDITEM_ORDER)` FOREIGN KEY (`IDO`) REFERENCES `order` (`IDO`),
  ADD CONSTRAINT `FK_ORDEREDITEM_ITEMS` FOREIGN KEY (`IDT`) REFERENCES `items` (`IDT`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
