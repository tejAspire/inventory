SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE TABLE IF NOT EXISTS `login` (
  `login_id` int(11) unsigned NOT NULL PRIMARY KEY,
  `name` varchar(60) NOT NULL,
  `password` varchar(255) NOT NULL,
  `user_level` int(1) NOT NULL,
  `image` varchar(255) DEFAULT 'no_image.jpg',
  `email` varchar(50) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `warehouse` (
   `product_id` int(11) unsigned NOT NULL PRIMARY KEY,
   `product_name` varchar(50) NOT NULL,
   `product_disc` varchar(150) NOT NULL,
   `quantity` number(100000) DEFAULT NULL,
   `price` decimal(10,2) NOT NULL,
   `login_id` int(11) unsigned,
   FOREIGN KEY (`login_id`) REFERENCES `login`(`login_id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `orders` (
    `order_id` int(11) unsigned NOT NULL PRIMARY KEY,
    `product_id` int(11) unsigned,
    `buy_price` decimal(10,2) DEFAULT NULL,
    `login_id` int(11) unsigned,
    FOREIGN KEY (`product_id`) REFERENCES `warehouse`(`product_id`),
    FOREIGN KEY (`login_id`) REFERENCES `login`(`login_id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `sales` (
    `sales_id` int(11) unsigned NOT NULL PRIMARY KEY,
    `login_id` int(11) unsigned,
    `product_id` int(11) unsigned,
    `quantity` varchar(50) DEFAULT NULL,
    `buy_price` decimal(10,2) DEFAULT NULL,
    `date` datetime NOT NULL,
    FOREIGN KEY (`login_id`) REFERENCES `login`(`login_id`),
    FOREIGN KEY (`product_id`) REFERENCES `warehouse`(`product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `payment` (
    `payment_id` int(11) unsigned NOT NULL PRIMARY KEY,
    `login_id` int(11) unsigned,
    `product_id` int(11) unsigned,
    `quantity` varchar(50) DEFAULT NULL,
    `price` decimal(10,2) DEFAULT NULL,
    `date` datetime NOT NULL,
    FOREIGN KEY (`login_id`) REFERENCES `login`(`login_id`),
    FOREIGN KEY (`product_id`) REFERENCES `warehouse`(`product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;


INSERT INTO `login` (`login_id`, `name`, `password`, `user_level`, `email`) VALUES
(1, 'admin', 'admin', 1, 'john@example.com'),
(2, 'user', 'user', 2, 'jane@example.com');


INSERT INTO `warehouse` (`product_id`, `product_name`, `product_disc`, `quantity`, `price`, `login_id`) VALUES
(1, 'MacBook Pro', 'High-performance laptop by Apple', 10, 1999.00, 1),
(2, 'iPhone 13', 'Latest smartphone by Apple', 20, 999.00, 1),
(3, 'Samsung Galaxy S21', 'Flagship smartphone by Samsung', 15, 799.00, 2),
(4, 'Sony PlayStation 5', 'Next-gen gaming console by Sony', 8, 499.00, 2),
(5, 'Nike Air Jordan 1', 'Iconic basketball shoes by Nike', 30, 199.00, 3),
(6, 'Canon EOS R5', 'High-end mirrorless camera by Canon', 12, 3499.00, 3),
(7, 'Bose QuietComfort 45', 'Premium noise-canceling headphones by Bose', 25, 329.00, 4),
(8, 'LEGO Star Wars Millennium Falcon', 'Iconic LEGO set from the Star Wars series', 5, 159.99, 4),
(9, 'Instant Pot Duo Nova', 'Multi-functional pressure cooker', 18, 99.95, 5),
(10, 'Kindle Paperwhite', 'Waterproof e-reader by Amazon', 22, 129.99, 5);


INSERT INTO `sales` (`sales_id`, `login_id`, `product_id`, `quantity`, `buy_price`, `date`) VALUES
(1, 1, 1, '2', 50.00, NOW()),
(2, 2, 2, '1', 75.00, NOW());


INSERT INTO `payment` (`payment_id`, `login_id`, `product_id`, `quantity`, `price`, `date`) VALUES
(1, 1, 1, '2', 100.00, NOW()),
(2, 2, 2, '1', 75.00, NOW());
