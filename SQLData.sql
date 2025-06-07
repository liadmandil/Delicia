-- MySQL dump 10.13  Distrib 8.0.42, for Win64 (x86_64)
--
-- Host: localhost    Database: delicia_db
-- ------------------------------------------------------
-- Server version	8.0.40

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `cart`
--

DROP TABLE IF EXISTS `cart`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cart` (
  `order_id` int NOT NULL,
  `menu_item_id` int NOT NULL,
  `quantity` int DEFAULT '1',
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`order_id`,`menu_item_id`),
  KEY `menu_item_id` (`menu_item_id`),
  CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`menu_item_id`) REFERENCES `menu` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cart`
--

LOCK TABLES `cart` WRITE;
/*!40000 ALTER TABLE `cart` DISABLE KEYS */;
INSERT INTO `cart` VALUES (6,7,1,'2025-06-07 16:59:44'),(6,8,1,'2025-06-07 16:58:15'),(6,9,1,'2025-06-07 16:58:15'),(6,10,1,'2025-06-07 16:58:15'),(6,11,1,'2025-06-07 16:58:15'),(6,12,1,'2025-06-07 16:58:15'),(6,13,1,'2025-06-07 16:58:15'),(6,14,1,'2025-06-07 16:58:15'),(6,15,1,'2025-06-07 16:58:15'),(6,16,1,'2025-06-07 16:58:15'),(21,17,1,'2025-06-07 16:58:15'),(21,18,1,'2025-06-07 16:58:15'),(21,19,1,'2025-06-07 16:58:15'),(21,20,1,'2025-06-07 16:58:15'),(21,21,1,'2025-06-07 16:58:15'),(21,22,1,'2025-06-07 16:58:15'),(21,23,1,'2025-06-07 16:58:15'),(21,24,1,'2025-06-07 16:58:15'),(21,25,1,'2025-06-07 16:58:15'),(21,26,1,'2025-06-07 16:58:15'),(22,27,1,'2025-06-07 16:58:15'),(22,28,1,'2025-06-07 16:58:15'),(22,29,1,'2025-06-07 16:58:15'),(22,30,1,'2025-06-07 16:58:15'),(22,31,1,'2025-06-07 16:58:15'),(22,32,1,'2025-06-07 16:58:15'),(22,33,1,'2025-06-07 16:58:15'),(22,34,1,'2025-06-07 16:58:15'),(22,35,1,'2025-06-07 16:58:15'),(22,36,1,'2025-06-07 16:58:15'),(23,37,1,'2025-06-07 16:58:15'),(23,38,1,'2025-06-07 16:58:15'),(23,39,1,'2025-06-07 16:58:15'),(23,40,1,'2025-06-07 16:58:15'),(23,41,1,'2025-06-07 16:58:15'),(23,42,1,'2025-06-07 16:58:15'),(23,43,1,'2025-06-07 16:58:15'),(23,44,1,'2025-06-07 16:58:15'),(23,45,1,'2025-06-07 16:58:15'),(23,46,1,'2025-06-07 16:58:15');
/*!40000 ALTER TABLE `cart` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `menu`
--

DROP TABLE IF EXISTS `menu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `menu` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text,
  `price` decimal(10,2) NOT NULL,
  `available` tinyint(1) DEFAULT '1',
  `image_url` varchar(255) DEFAULT NULL,
  `category` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=129 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `menu`
--

LOCK TABLES `menu` WRITE;
/*!40000 ALTER TABLE `menu` DISABLE KEYS */;
INSERT INTO `menu` VALUES (7,'מים מינרליים','בקבוק מים קרים',8.00,1,'images/water.jpg','שתייה'),(8,'קוקה קולה','פחית קולה קרה',10.00,1,'images/coke.jpg','שתייה'),(9,'קולה זירו','פחית קולה זירו',10.00,1,'images/zero.jpg','שתייה'),(10,'ספרייט','פחית ספרייט מוגזת',10.00,1,'images/sprite.jpg','שתייה'),(11,'פיוז טי אפרסק','תה קר בטעם אפרסק',11.00,1,'images/peach_tea.jpg','שתייה'),(12,'פיוז טי לימון','תה קר בטעם לימון',11.00,1,'images/lemon_tea.jpg','שתייה'),(13,'סודה','בקבוק סודה מוגזת',9.00,1,'images/soda.jpg','שתייה'),(14,'בירה אסאהי','בירה יפנית קלה',22.00,1,'images/asahi.jpg','שתייה'),(15,'בירה קירין','בירה יפנית ייחודית',22.00,1,'images/kirin.jpg','שתייה'),(16,'סאקה קר','משקה אורז יפני',28.00,1,'images/sake.jpg','שתייה'),(17,'גלידת מאצ׳ה','גלידה בטעם תה ירוק יפני',18.00,1,'images/matcha_ice.jpg','קינוחים'),(18,'מוצ׳י גלידה וניל','כדורי אורז ממולאים בגלידת וניל',20.00,1,'images/mochi_vanilla.jpg','קינוחים'),(19,'מוצ׳י גלידה תות','כדורי אורז ממולאים בגלידת תות',20.00,1,'images/mochi_strawberry.jpg','קינוחים'),(20,'בננה מטוגנת','בננה מצופה בטמפורה ומטוגנת, מוגשת עם רוטב שוקולד',22.00,1,'images/fried_banana.jpg','קינוחים'),(21,'פנקוטה קוקוס','קינוח איטלקי ברוטב מנגו יפני',23.00,1,'images/panna_cotta.jpg','קינוחים'),(22,'גלידת וניל','שתי כדורי גלידת וניל קלאסית',16.00,1,'images/vanilla_ice.jpg','קינוחים'),(23,'עוגת שוקולד חמה','עוגת שוקולד עם ליבת שוקולד נוזלי',26.00,1,'images/chocolate_lava.jpg','קינוחים'),(24,'מאפין תה ירוק','מאפין אוורירי בטעם מאצ׳ה',18.00,1,'images/matcha_muffin.jpg','קינוחים'),(25,'כדור שוקולד','כדור שוקולד מוקשה שמוגש עם רוטב חם',28.00,1,'images/choco_ball.jpg','קינוחים'),(26,'טפיוקה קרה','קינוח פנינים בטעם קוקוס',21.00,1,'images/tapioca.jpg','קינוחים'),(27,'פאד תאי','אטריות אורז מוקפצות עם ירקות ובוטנים',44.00,1,'images/pad_thai.jpg','מנות ווק'),(28,'שיפודי יאקיטורי','שיפודי עוף בטריאקי על אורז',49.00,1,'images/yakitori.jpg','מנות ווק'),(29,'ראמן עוף','מרק ראמן עם עוף וביצה',52.00,1,'images/ramen_chicken.jpg','מנות ווק'),(30,'ראמן טופו','מרק ראמן עם טופו ואצות',49.00,1,'images/ramen_tofu.jpg','מנות ווק'),(31,'יאקי סובה','אטריות סובה מוקפצות עם ירקות',46.00,1,'images/yakisoba.jpg','מנות ווק'),(32,'אורז מטוגן','אורז מוקפץ עם ירקות וביצה',39.00,1,'images/fried_rice.jpg','מנות ווק'),(33,'צ׳יקן ווק חריף','עוף חריף מוקפץ עם נבטים',48.00,1,'images/spicy_chicken.jpg','מנות ווק'),(34,'ווק סלמון','נתחי סלמון מוקפצים עם ברוקולי',54.00,1,'images/salmon_wok.jpg','מנות ווק'),(35,'ווק ירקות','ירקות מוקפצים עם טופו',42.00,1,'images/vegan_wok.jpg','מנות ווק'),(36,'ווק בקר','נתחי בקר מוקפצים בסויה ושום',56.00,1,'images/beef_wok.jpg','מנות ווק'),(37,'אדממה','פולי סויה מאודים עם מלח ים',18.00,1,'images/edamame.jpg','מנות פתיחה'),(38,'גיוזה','כיסוני בצק מאודים במילוי ירקות',28.00,1,'images/gyoza.jpg','מנות פתיחה'),(39,'אצות ים','סלט אצות עם שומשום וחומץ אורז',24.00,1,'images/seaweed.jpg','מנות פתיחה'),(40,'מרק מיסו','מרק מיסו עם טופו ואצות',19.00,1,'images/miso.jpg','מנות פתיחה'),(41,'סלט וואקמה','אצות וואקמה עם גזר ורוטב',26.00,1,'images/wakame.jpg','מנות פתיחה'),(42,'קראנצ׳ רולס','רול מטוגן פריך עם ירקות',32.00,1,'images/crunch_rolls.jpg','מנות פתיחה'),(43,'טטאקי טונה','טונה צרובה קלות עם רוטב יוזו',44.00,1,'images/tataki.jpg','מנות פתיחה'),(44,'קאראגה עוף','עוף מטוגן בסגנון יפני',36.00,1,'images/karaage.jpg','מנות פתיחה'),(45,'טמפורה ירקות','ירקות בטמפורה פריכה',33.00,1,'images/tempura.jpg','מנות פתיחה'),(46,'אוניגירי','כדור אורז ממולא עטוף באצה',22.00,1,'images/onigiri.jpg','מנות פתיחה'),(47,'רול סלמון','רול סושי עם סלמון, אבוקדו ומלפפון',42.00,1,'images/salmon_roll.jpg','סושי'),(48,'רול ריינבו','רול צבעוני עם סלמון, טונה ואבוקדו',54.00,1,'images/rainbow_roll.jpg','סושי'),(49,'פוטומאקי ירקות','רול עבה עם ירקות',36.00,1,'images/veggie_futomaki.jpg','סושי'),(50,'רול טמפורה שרימפס','סושי חם עם שרימפס בטמפורה',48.00,1,'images/tempura_shrimp.jpg','סושי'),(51,'סשימי סלמון','פרוסות דקות של סלמון נא',52.00,1,'images/salmon_sashimi.jpg','סושי'),(52,'רול טונה חריף','טונה נא עם ספייסי מאיו',47.00,1,'images/spicy_tuna.jpg','סושי'),(53,'רול קליפורניה','סרימיס עם אבוקדו ומלפפון',41.00,1,'images/california.jpg','סושי'),(54,'ניגירי סלמון','כדור אורז עם סלמון מעל',22.00,1,'images/nigiri_salmon.jpg','סושי'),(55,'ניגירי טונה','כדור אורז עם טונה מעל',24.00,1,'images/nigiri_tuna.jpg','סושי'),(56,'רול דאבל סלמון','רול סלמון כפול עם ספייסי מאיו',55.00,1,'images/double_salmon.jpg','סושי');
/*!40000 ALTER TABLE `menu` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `orders` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `total_price` decimal(10,2) DEFAULT NULL,
  `status` enum('InCart','Pending','Paid','Cancelled','Delivered') DEFAULT 'InCart',
  `delivery_address` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` VALUES (6,1,'2025-06-07 15:02:44',135.00,'InCart',NULL),(21,2,'2025-06-07 16:51:38',218.00,'InCart',NULL),(22,3,'2025-06-07 16:58:06',364.00,'InCart',NULL),(23,4,'2025-06-07 16:58:06',409.00,'InCart',NULL);
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `city` varchar(100) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `role` varchar(20) DEFAULT 'user',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'דני כהן','תל אביב','רחוב דיזנגוף 100','0501234567','dani@example.com','customer','2025-06-07 14:39:38'),(2,'נועה לוי','חיפה','רחוב הרצל 10','0521111111','noa@example.com','customer','2025-06-07 14:43:54'),(3,'יוסי בן חיים','ירושלים','דרך חברון 55','0532222222','yossi@example.com','customer','2025-06-07 14:43:54'),(4,'שירה כהן','רמת גן','רחוב ביאליק 8','0543333333','shira@example.com','customer','2025-06-07 14:43:54');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-06-07 17:07:07
