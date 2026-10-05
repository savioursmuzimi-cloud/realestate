SET SQL_MODE='NO_AUTO_VALUE_ON_ZERO';
START TRANSACTION;
SET time_zone='+00:00';
SET NAMES utf8mb4;

CREATE DATABASE IF NOT EXISTS homelink_ke CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE homelink_ke;

CREATE TABLE IF NOT EXISTS admins (
 id INT AUTO_INCREMENT PRIMARY KEY,
 username VARCHAR(50) NOT NULL UNIQUE,
 password VARCHAR(255) NOT NULL,
 full_name VARCHAR(100) DEFAULT NULL,
 role ENUM('super_admin','admin') NOT NULL DEFAULT 'admin',
 active TINYINT(1) NOT NULL DEFAULT 1,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS audit_logs (
 id INT AUTO_INCREMENT PRIMARY KEY,
 actor VARCHAR(100) NOT NULL,
 action TEXT NOT NULL,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 INDEX(created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS properties (
 id INT AUTO_INCREMENT PRIMARY KEY,
 title VARCHAR(255) NOT NULL,
 location VARCHAR(255) NOT NULL,
 price DECIMAL(10,2) NOT NULL,
 status ENUM('available','rented','sold','reserved') DEFAULT 'available',
 description TEXT,
 image_url VARCHAR(255) DEFAULT NULL,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 deposit_info VARCHAR(255) DEFAULT NULL,
 bedrooms INT DEFAULT NULL,
 furnished_status ENUM('Furnished','Unfurnished','Semi-Furnished') DEFAULT 'Unfurnished',
 amenities TEXT,
 category VARCHAR(100) DEFAULT NULL,
 is_verified TINYINT(1) DEFAULT 0,
 is_hidden TINYINT(1) DEFAULT 0,
 landlord_name VARCHAR(255) DEFAULT NULL,
 landlord_phone VARCHAR(50) DEFAULT NULL,
 landlord_email VARCHAR(255) DEFAULT NULL,
 landlord_notes TEXT,
 video_path VARCHAR(255) DEFAULT NULL,
 availability VARCHAR(50) NOT NULL DEFAULT 'Available',
 updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 INDEX(category), INDEX(location), INDEX(is_verified,is_hidden)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS property_photos (
 id INT AUTO_INCREMENT PRIMARY KEY,
 property_id INT NOT NULL,
 photo_path VARCHAR(255) NOT NULL,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 FOREIGN KEY(property_id) REFERENCES properties(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS viewing_requests (
 id INT AUTO_INCREMENT PRIMARY KEY,
 property_id INT DEFAULT NULL,
 full_name VARCHAR(100) NOT NULL,
 email VARCHAR(100) DEFAULT NULL,
 phone VARCHAR(30) NOT NULL,
 view_date DATE NOT NULL,
 notes TEXT,
 status ENUM('pending','confirmed','completed','cancelled') NOT NULL DEFAULT 'pending',
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 FOREIGN KEY(property_id) REFERENCES properties(id) ON DELETE SET NULL,
 INDEX(status,created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS bnb_requests (
 id INT AUTO_INCREMENT PRIMARY KEY,
 property_id INT DEFAULT NULL,
 full_name VARCHAR(100) NOT NULL,
 email VARCHAR(100) DEFAULT NULL,
 phone VARCHAR(30) NOT NULL,
 check_in DATE NOT NULL,
 check_out DATE NOT NULL,
 guests INT NOT NULL DEFAULT 1,
 notes TEXT,
 status ENUM('pending','confirmed','checked_in','completed','cancelled') NOT NULL DEFAULT 'pending',
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 FOREIGN KEY(property_id) REFERENCES properties(id) ON DELETE SET NULL,
 INDEX(status,check_in,check_out)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS inquiries (
 id INT AUTO_INCREMENT PRIMARY KEY,
 property_id INT DEFAULT NULL,
 name VARCHAR(100) NOT NULL,
 email VARCHAR(100) NOT NULL,
 subject VARCHAR(255) DEFAULT NULL,
 message TEXT NOT NULL,
 status ENUM('new','read','closed') NOT NULL DEFAULT 'new',
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 FOREIGN KEY(property_id) REFERENCES properties(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS complaints (
 id INT AUTO_INCREMENT PRIMARY KEY,
 full_name VARCHAR(100) NOT NULL,
 phone VARCHAR(30) NOT NULL,
 email VARCHAR(100) DEFAULT NULL,
 details TEXT NOT NULL,
 status ENUM('open','in_progress','resolved','closed') NOT NULL DEFAULT 'open',
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS landlord_requests (
 id INT AUTO_INCREMENT PRIMARY KEY,
 landlord_name VARCHAR(100) NOT NULL,
 phone VARCHAR(30) NOT NULL,
 email VARCHAR(100) DEFAULT NULL,
 property_type VARCHAR(50) DEFAULT NULL,
 location VARCHAR(255) NOT NULL,
 details TEXT,
 status ENUM('new','contacted','listed','closed') NOT NULL DEFAULT 'new',
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS service_fees (
 id INT AUTO_INCREMENT PRIMARY KEY,
 property_type VARCHAR(100) NOT NULL UNIQUE,
 fee_type ENUM('fixed','percentage') NOT NULL DEFAULT 'fixed',
 amount DECIMAL(10,2) NOT NULL DEFAULT 0,
 active TINYINT(1) NOT NULL DEFAULT 1,
 updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS site_settings (
 id INT PRIMARY KEY DEFAULT 1,
 site_name VARCHAR(150) NOT NULL DEFAULT 'HomeLink-KE',
 site_tagline VARCHAR(100) DEFAULT '-KE',
 site_logo VARCHAR(255) DEFAULT '',
 contact_email VARCHAR(100) NOT NULL DEFAULT 'info@homelink.co.ke',
 contact_phone VARCHAR(50) NOT NULL DEFAULT '+254 712 345 678',
 office_location VARCHAR(255) NOT NULL DEFAULT 'Kisii CBD, Kenya',
 contact_location VARCHAR(255) DEFAULT 'Kisii CBD, Kenya',
 mpesa_paybill VARCHAR(50) DEFAULT '',
 mpesa_account VARCHAR(100) DEFAULT '',
 viewing_fee DECIMAL(10,2) DEFAULT 1000,
 maintenance_mode TINYINT(1) DEFAULT 0,
 footer_text VARCHAR(255) DEFAULT '© 2026 HomeLink-KE. All rights reserved.',
 facebook_link VARCHAR(255) DEFAULT '',
 twitter_link VARCHAR(255) DEFAULT '',
 whatsapp_number VARCHAR(50) DEFAULT '',
 instagram_link VARCHAR(255) DEFAULT '',
 updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS users (
 id INT AUTO_INCREMENT PRIMARY KEY,
 name VARCHAR(100) NOT NULL,
 email VARCHAR(100) NOT NULL UNIQUE,
 password VARCHAR(255) NOT NULL,
 role VARCHAR(20) DEFAULT 'user',
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO admins(id,username,password,full_name,role,active) VALUES (1,'admin','$2y$12$xJ/FlZMOB0KN/go5rE7Q7etB2.je0Qg9nY985E6MdF3FxDTT6SkH2','HomeLink Administrator','super_admin',1)
ON DUPLICATE KEY UPDATE username=VALUES(username), role='super_admin', active=1;
INSERT INTO site_settings(id,site_name,site_tagline,contact_phone,contact_email,office_location,contact_location,whatsapp_number,viewing_fee,footer_text) VALUES(1,'HomeLink-KE','-KE','+254 712 345 678','info@homelink.co.ke','Kisii CBD, Kenya','Kisii CBD, Kenya','0712345678',1000,'Connecting Kisii to quality housing and stays.') ON DUPLICATE KEY UPDATE id=1;
INSERT INTO service_fees(property_type,fee_type,amount,active) VALUES
('Single Rooms','fixed',500,1),('Bedsitters','fixed',750,1),('1 Bedroom','fixed',1000,1),('2 Bedroom','fixed',1500,1),('Commercial','fixed',2000,1),('BNB','fixed',500,1)
ON DUPLICATE KEY UPDATE property_type=VALUES(property_type);

INSERT INTO properties(id,title,location,price,status,description,image_url,deposit_info,bedrooms,furnished_status,amenities,category,is_verified,is_hidden,landlord_name,landlord_phone,landlord_email,landlord_notes,availability) VALUES
(1,'Modern 1-Bedroom Apartment','Nyanchwa, Kisii Town',12000,'available','Spacious 1-bedroom unit with modern finishes, 24/7 security, and reliable water supply. 5-minute drive to Kisii CBD.',NULL,NULL,1,'Unfurnished','Security, water','1 Bedroom',1,0,NULL,NULL,NULL,NULL,'Available'),
(2,'Executive Student Bedsitter','Near Kisii University Main Gate',6666,'available','Clean and secure bedsitter with tiled floors, inside sink, and high-speed Wi-Fi access. Ideal for Kisii University students.',NULL,'',1,'Unfurnished','WiFi','Bedsitters',1,0,'Property owner','0712345678',NULL,NULL,'Available'),
(3,'Commercial Shop / Office Space','Kisii Town CBD (Hospital Road)',25000,'available','Prime commercial space on the 1st floor along busy street. Great foot traffic and suitable for boutique, salon, or office.',NULL,NULL,NULL,'Unfurnished','High foot traffic','Commercial',1,0,NULL,NULL,NULL,NULL,'Available'),
(4,'2-Bedroom Master Ensuite','Mosocho / Kisii-Kisumu Highway',18000,'available','Secure perimeter wall, ample parking space, constant water flow, and modern kitchen cabinets.',NULL,NULL,2,'Unfurnished','Parking, water, security','2 Bedroom',1,0,NULL,NULL,NULL,NULL,'Available'),
(5,'Single Room','Mwembe',4500,'available','','uploads/photos/1790427134_121_123.jpg','1500',NULL,'Unfurnished','WiFi','Single Rooms',1,0,'Property owner','0712345678',NULL,NULL,'Available'),
(6,'Single Room','Mwembe',4500,'available','','uploads/photos/1790427216_171_123.jpg','1500',NULL,'Unfurnished','WiFi','Single Rooms',1,0,'Property owner','0712345678',NULL,NULL,'Available'),
(8,'Single Rooms','Mwembe',4500,'available','','uploads/photos/1790427431_506_123.jpg','1500',NULL,'Unfurnished','WiFi','Single Rooms',1,0,'Property owner','0712345678',NULL,NULL,'Available'),
(9,'Bedsitter','Mwembe',7600,'available','','uploads/photos/1790541261_609_IMG_17750240742403833.jpg','1500',NULL,'Unfurnished','WiFi','Bedsitters',1,0,'Property owner','0712345678',NULL,NULL,'Available')
ON DUPLICATE KEY UPDATE id=VALUES(id);
INSERT INTO property_photos(property_id,photo_path) VALUES
(5,'uploads/photos/1790427134_121_123.jpg'),(6,'uploads/photos/1790427216_171_123.jpg'),(8,'uploads/photos/1790427431_506_123.jpg'),
(9,'uploads/photos/1790541261_609_IMG_17750240742403833.jpg'),(9,'uploads/photos/1790541261_732_IMG_17750252460960083.jpg'),(9,'uploads/photos/1790541261_862_IMG_17750253321153812.jpg');

COMMIT;
