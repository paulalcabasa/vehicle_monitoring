/*
SQLyog Ultimate v10.00 Beta1
MySQL - 5.6.17 : Database - sys_insurance_and_registration
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`sys_insurance_and_registration` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `sys_insurance_and_registration`;

/*Table structure for table `iar_company_car_change` */

DROP TABLE IF EXISTS `iar_company_car_change`;

CREATE TABLE `iar_company_car_change` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_car_id` int(11) NOT NULL,
  `classification` varchar(15) NOT NULL,
  `color` varchar(30) NOT NULL,
  `assignee` varchar(50) NOT NULL,
  `remarks` varchar(100) NOT NULL,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Data for the table `iar_company_car_change` */

/*Table structure for table `iar_company_car_claim` */

DROP TABLE IF EXISTS `iar_company_car_claim`;

CREATE TABLE `iar_company_car_claim` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_car_id` int(11) NOT NULL,
  `accident_date` date NOT NULL,
  `nature_of_accident` varchar(250) NOT NULL,
  `damage_parts` varchar(250) NOT NULL,
  `insurance_required` varchar(250) NOT NULL,
  `status` varchar(10) NOT NULL,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Data for the table `iar_company_car_claim` */

/*Table structure for table `iar_company_car_classification` */

DROP TABLE IF EXISTS `iar_company_car_classification`;

CREATE TABLE `iar_company_car_classification` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `classification` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;

/*Data for the table `iar_company_car_classification` */

insert  into `iar_company_car_classification`(`id`,`classification`) values (1,'CSS Service Unit'),(2,'Carplan'),(3,'Media Unit'),(4,'Executive Service Unit'),(5,'Executive Family Unit'),(6,'CRT Loaner'),(7,'Development Unit');

/*Table structure for table `iar_company_car_ctpl` */

DROP TABLE IF EXISTS `iar_company_car_ctpl`;

CREATE TABLE `iar_company_car_ctpl` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_car_id` int(11) NOT NULL,
  `ctpl_company_id` int(11) NOT NULL,
  `insurance_from` date NOT NULL,
  `insurance_to` date NOT NULL,
  `billing_date` date NOT NULL,
  `billing_ref` varchar(30) NOT NULL,
  `policy_no` varchar(30) NOT NULL,
  `limit_` decimal(10,2) NOT NULL,
  `payment_reference` varchar(30) NOT NULL,
  `insurance_policy` varchar(50) NOT NULL,
  `insurance_amount` decimal(10,2) NOT NULL,
  `remarks` varchar(100) NOT NULL,
  `premium` decimal(8,2) NOT NULL,
  `ctpl` decimal(8,2) NOT NULL,
  `mvppap` decimal(8,2) NOT NULL,
  `vat` decimal(8,2) NOT NULL,
  `local_tax` decimal(8,2) NOT NULL,
  `others` decimal(8,2) NOT NULL,
  `doc_stamps` decimal(10,2) NOT NULL,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Data for the table `iar_company_car_ctpl` */

/*Table structure for table `iar_company_car_insurance` */

DROP TABLE IF EXISTS `iar_company_car_insurance`;

CREATE TABLE `iar_company_car_insurance` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_car_id` int(11) NOT NULL,
  `insurance_company_id` int(11) NOT NULL,
  `insurance_from` date NOT NULL,
  `insurance_to` date NOT NULL,
  `billing_date` date NOT NULL,
  `billing_ref` varchar(30) NOT NULL,
  `policy_no` varchar(30) NOT NULL,
  `limit_` decimal(10,2) NOT NULL,
  `payment_reference` varchar(30) NOT NULL,
  `insurance_policy` varchar(50) NOT NULL,
  `insurance_amount` decimal(10,2) NOT NULL,
  `remarks` varchar(100) NOT NULL,
  `premium` decimal(8,2) NOT NULL,
  `ctpl` decimal(8,2) NOT NULL,
  `mvppap` decimal(8,2) NOT NULL,
  `vat` decimal(8,2) NOT NULL,
  `local_tax` decimal(8,2) NOT NULL,
  `others` decimal(8,2) NOT NULL,
  `doc_stamps` decimal(10,2) NOT NULL,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Data for the table `iar_company_car_insurance` */

/*Table structure for table `iar_company_car_orcr` */

DROP TABLE IF EXISTS `iar_company_car_orcr`;

CREATE TABLE `iar_company_car_orcr` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_car_id` int(11) NOT NULL,
  `cr_no` varchar(30) NOT NULL,
  `cr_date` date NOT NULL,
  `district_office_code` varchar(30) NOT NULL,
  `owner` varchar(30) NOT NULL,
  `or_no` varchar(30) NOT NULL,
  `or_date` date NOT NULL,
  `expiry_date` date NOT NULL,
  `amount_paid` decimal(10,2) NOT NULL,
  `smoke_emission` varchar(30) NOT NULL,
  `or_attachment` varchar(40) NOT NULL,
  `cr_attachment` varchar(40) NOT NULL,
  `other_attachment` varchar(40) NOT NULL,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Data for the table `iar_company_car_orcr` */

/*Table structure for table `iar_company_car_remarks` */

DROP TABLE IF EXISTS `iar_company_car_remarks`;

CREATE TABLE `iar_company_car_remarks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_car_id` int(11) NOT NULL,
  `remarks_date` date NOT NULL,
  `remarks` varchar(255) NOT NULL,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Data for the table `iar_company_car_remarks` */

/*Table structure for table `iar_company_car_units` */

DROP TABLE IF EXISTS `iar_company_car_units`;

CREATE TABLE `iar_company_car_units` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cs_no` varchar(6) NOT NULL,
  `plate_no` varchar(10) NOT NULL,
  `classification` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `assignee` varchar(50) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `employee_no` varchar(10) NOT NULL,
  `department` varchar(50) NOT NULL,
  `section` varchar(50) NOT NULL,
  `disposal_date` date NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `disposal_category` int(11) NOT NULL,
  `ppaf` varchar(50) NOT NULL,
  `deed_of_sale` varchar(50) NOT NULL,
  `secretary_certificate` varchar(50) NOT NULL,
  `carplan_application` varchar(50) NOT NULL,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `cs_no` (`cs_no`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

/*Data for the table `iar_company_car_units` */

insert  into `iar_company_car_units`(`id`,`cs_no`,`plate_no`,`classification`,`status`,`assignee`,`employee_id`,`employee_no`,`department`,`section`,`disposal_date`,`amount`,`disposal_category`,`ppaf`,`deed_of_sale`,`secretary_certificate`,`carplan_application`,`last_update`) values (1,'CG2019','VEG513',1,1,'SALES/PARTS & SERVICE',0,'0','SALES/PARTS & SERVICE','CUSTOMER SERVICE','0000-00-00','0.00',0,'','','','','2016-01-08 07:43:25'),(2,'CH0261','NQD103',1,1,'ADMIN/GEN. ADMIN',0,'0','CSS','CSS','0000-00-00','0.00',0,'','','','','2016-01-08 07:45:39'),(3,'CH2463','NAI239',1,1,'SALES PLANNING',0,'0','DND/MARKETING & SALES','SALES PLANNING & BUSINESS DEVELOPMENT','0000-00-00','0.00',0,'','','','','2016-01-08 07:47:28'),(4,'CN2238','UAO130',2,1,'Alcones, Eric B',412,'041001','Finance & Accounting','Treasury','0000-00-00','0.00',0,'','','','','2016-01-08 07:52:42'),(5,'CL7714','QOQ888',2,1,'Quijano, Daisylyn B',152,'973533','General Admin','Management Info Systems','0000-00-00','0.00',0,'','','','','2016-01-08 07:54:19'),(6,'CM1349','ZPY351',2,1,'Macabenta, Ariel A',483,'140701','General Admin','Corporate Service','0000-00-00','0.00',0,'','','','','2016-01-08 07:56:02'),(7,'CL5349','UQL292',2,1,'Yandoc  Jr., David L',184,'973901','Administration','Administration','0000-00-00','0.00',0,'','','','','2016-01-08 07:57:25'),(8,'CJ7261','ZSP212',1,1,'010909',0,'0','MD/SERVICE & PARTS DEPARTMENT','SERVICE','0000-00-00','0.00',0,'','','','','2016-01-08 13:41:58');

/*Table structure for table `iar_insurance_company` */

DROP TABLE IF EXISTS `iar_insurance_company`;

CREATE TABLE `iar_insurance_company` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=35 DEFAULT CHARSET=latin1;

/*Data for the table `iar_insurance_company` */

insert  into `iar_insurance_company`(`id`,`company_name`) values (2,'INSULAR & HIHGEN'),(3,'TOKYO MARINE MALAYAN INSURANCE'),(4,'LAND TRANSPORTATION OFFICE'),(5,'FORTUNE & INSURANCE CORPORATION'),(6,'BPI/MS INSURANCE CORPORATION'),(7,'SECURITY PACIFIC ASSURANCE CORPORATION'),(8,'DEVELOPMENT INSURANCE AND SURETY CORPORATION'),(9,'MEGA PACIFIC INSURANCE'),(10,'THE PREMIER INSURANCE AND SURETY CORPORATION'),(11,'MONARCH INSURANCE COMPANY, INC.'),(12,'GREAT DOMESTIC INSURANCE CO. OF THE PHILS., INC.YU'),(13,'CIBELES INSURANCE CORPORATION'),(14,'COMMONWEALTH INSURANCE COMPANY'),(15,'GREAT DOMESTIC INSURANCE CO. OF THE PHIL.'),(17,'RICO GENERAL INSURANCE CORPORATION'),(18,'PLARIDEL SURETY & INSURANCE COMPANY'),(19,'MAPFRE INSULAR INSURANCE CORPORATION'),(20,'PACIFIC UNION INSURANCE COMPANY'),(21,'BF GENERAL INSURANCE CO., INC.'),(22,'STANDARD INSURANCE CO., INC.'),(23,'SOUTH SEA SURETY & INSURANCE CO., INC'),(24,'EASTERN ASSURANCE & SURETY CORPORATION'),(25,'ALGEN INSURANCE CORPORATION (FORMERLY ACROPOLIS)'),(26,'FIRST INTEGRATED BONDING & INSURANCE COMPANY, INC.'),(27,'INDUSTRIAL INSURANCE COMPANY, INC.'),(28,'FAR EASTERN SURETY & INSURANCE CO., INC.'),(29,'PIONEER INSURANCE & SURETYCORPORATION');

/*Table structure for table `iar_vehicle_master` */

DROP TABLE IF EXISTS `iar_vehicle_master`;

CREATE TABLE `iar_vehicle_master` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cs_no` varchar(6) NOT NULL,
  `model` varchar(30) NOT NULL,
  `vin` varchar(30) NOT NULL,
  `body_color` varchar(30) NOT NULL,
  `engine_no` varchar(10) NOT NULL,
  `invoice_no` varchar(20) NOT NULL,
  `invoice_date` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=196 DEFAULT CHARSET=latin1;

/*Data for the table `iar_vehicle_master` */

insert  into `iar_vehicle_master`(`id`,`cs_no`,`model`,`vin`,`body_color`,`engine_no`,`invoice_no`,`invoice_date`) values (1,'CG2019','09 ALTERRA MT','MPAUCR85H9T100538','OMEGA WHITE','GR0527','131648','2010-03-31'),(2,'CG2020','09 ALTERRA MT','MPAUCR85H9T100558','OMEGA WHITE','GR8812','131650','2010-03-31'),(3,'CG2026','09 ALTERRA MT','MPAUCR85H9T100559','OMEGA WHITE','GR8821','131651','2010-03-31'),(4,'CG0695','09 ALTERRA MT','MPAUCR85H9T100566','STARRY BLACK/STERLING SILVER','GR8824','131652','2010-03-31'),(5,'CG3715','2010 ALTERRA AT','MPAUCR85HAT100024','NEBULA GRAY MICA/STERLING SILV','GT4275','129506','2010-01-19'),(6,'CG2692','010NHR-FLEXITRUCK 80A','PABNHR55ELA000007','ARC WHITE','856463P','129459','2010-01-14'),(7,'CG2694','010NHR-FLEXITRUCK 80A','PABNHR55ELA000009','ARC WHITE','856466P','129752','2010-01-27'),(8,'CG3741','2010 ALTERRA MT','MPAUCR85HAT100036','STARRY BLACK','GT8523','129423','2010-01-13'),(9,'CG3801','2010 SPORTIVO AT','PABTBR54F92054614','EBONY BLACK MONO','M22162','129625','2010-01-21'),(10,'CG2693','010NHR-FLEXITRUCK 80A','PABNHR55ELA000008','ARC WHITE','856464P','131487','2010-03-26'),(11,'CG6341','2010 XT','PABTBR54FA2055900','GRANITE GRAY','M23316','131981','2010-04-15'),(12,'CG4119','010NHR-FLEXITRUCK 80A','PABNHR55ELA000016','ARC WHITE','858647P','131625','2010-03-31'),(13,'CG2017','09 ALTERRA MT','MPAUCR85H9T100557','OMEGA WHITE','GR8814','131649','2010-03-31'),(14,'CG7479','2010 XUV M/T L.E.','PABTBR54FA2056711','CANYON RED/GRANITE GRAY','M24268','133594','2010-05-31'),(15,'CG7713','2010 D-MAX 4X2 AT LS','PABTFR85HA0004795','GLACIAL WHITE','HE1083','135233','2010-07-23'),(16,'CG8598','2010 SUV M/T J/S','PABTBR54FA2057286','TUNDRA GREEN','M24750','134399','2010-06-29'),(17,'CG8458','2010 XUV A/T','PABTBR54FA2057226','GRANITE GRAY/LIGHT SILVER','M24654','134274','2010-06-24'),(18,'CG8904','2010 DMAX 4X4 LT MT','MPATFS85HAH536279','STERLING SILVER','HF3024','134257','2010-06-24'),(19,'CH0261','2010 SPORTIVO M/T','PABTBR54FA2058052','CANYON RED','M25546','135681','2010-08-04'),(20,'CH0077','2010 SPORTIVO AT','PABTBR54FA2057986','EBONY BLACK MONO','M25449','139379','2010-11-30'),(21,'CG7715','2010 D-MAX 4X2 LS AT R.E.','PABTFR85HA0004792','OYSTER PEARL WHITE','HE1080','134635','2010-07-06'),(22,'CH0794','2010 SPORTIVO M/T','PABTBR54FA2058317','CANYON RED','M25811','136671','2010-09-07'),(23,'CH0771','2010 SPORTIVO AT','PABTBR54FA2058475','MOROCCAN GOLD','M25959','136775','2010-09-09'),(24,'CH2463','2010 ALTERRA MT S.E.','MPAUCR85HAT100692','STERLING SILVER','HL5746','980009460','2011-06-29'),(25,'CH0990','2010 XUV A/T','PABTBR54FA2058524','GLACIAL WHITE/LIGHT SILVER','M25901','980013340','2011-08-10'),(26,'CH0441','2010 ALTERRA AT S.E.','MPAUCR85HAT100546','STARRY BLACK','HH5554','139712','2010-12-14'),(27,'CH0518','2010 SPORTIVO AT','PABTBR54FA2058172','EBONY BLACK MONO','M25690','135759','2010-08-06'),(28,'CH4940','2011 ALTERRA AT U.C. Edition','MPAUCR85HBT100201','STARRY BLACK','HP8128','139218','2010-11-30'),(29,'CH5506','2011 D-MAX 4X2 MT LS','PABTFR85HB0006199','RICH RED','HS9788','980001453','2011-03-23'),(30,'CH4931','2011 ALTERRA MT U.C. Edition','MPAUCR85HBT100182','NAUTILUS BLUE','HP9618','140276','2011-01-06'),(31,'CH4920','2011 ALTERRA MT U.C. Edition','MPAUCR85HBT100183','NAUTILUS BLUE','HP9617','140377','2011-01-12'),(32,'CH3648','2011 SPORTIVO AT','PABTBR54FA2059909','RICH RED','M27433','980001433','2011-03-23'),(33,'CH2641','2011 SPORTIVO M/T','PABTBR54FA2059374','MIDNIGHT BLUE','M26812','980001505','2011-03-23'),(34,'CH4946','2011 ALTERRA AT U.C. Edition','MPAUCR85HBT100207','STARRY BLACK','HP8135','139219','2010-11-30'),(35,'CH2611','2011 XUV M/T','PABTBR54FA2059273','CYANINE GREEN/LIGHT SILVER','M26857','980001504','2011-03-23'),(36,'CH4407','2011 ALTERRA AT U.C. Edition','MPAUCR85HBT100065','NAUTILUS BLUE MICA/STERLING SI','HN5416','138771','2010-11-17'),(37,'CH5057','011NHR-MB 80A','PABNHR55ELA000772','ARC WHITE','982924P','980006494','2011-05-20'),(38,'CH3680','2011 XUV A/T','PABTBR54FA2059467','MOROCCAN GOLD/LIGHT SILVER','M26985','980001431','2011-03-23'),(39,'CH5221','2011 SPORTIVO M/T','PABTBR54FB2061006','EBONY BLACK MONO','M28338','980001432','2011-03-23'),(40,'CH5688','2010 D-MAX 4X2 LS MT X-MAX','PABTFR85HA0006161','STERLING SILVER','HU7146','140977','2011-01-31'),(41,'CH4456','010NHR-FLEXITRUCK 80A','PABNHR55ELA000625','ARC WHITE','962718P','138770','2010-11-17'),(42,'CH5507','2011 D-MAX 4X2 MT LS','PABTFR85HB0006202','TITANIUM SILVER','HS9791','980001467','2011-03-23'),(43,'CH5655','2010 D-MAX 4X2 LS MT X-MAX','PABTFR85HA0006163','STERLING SILVER','HU7148','140976','2011-01-31'),(44,'CH4951','2011 ALTERRA AT U.C. Edition','MPAUCR85HBT100132','MINERAL GRAY','HP4361','140376','2011-01-12'),(45,'CH5492','2010 SPORTIVO M/T X-MAX','PABTBR54FA2060931','GLACIAL WHITE','M28485','980001249','2011-03-21'),(46,'CH5280','2011 XUV M/T','PABTBR54FB2061049','CYANINE GREEN/LIGHT SILVER','M28387','980002868','2011-04-07'),(47,'CH6751','2011 SPORTIVO AT','PABTBR54FB2061364','RICH RED','M28887','980001434','2011-03-23'),(48,'CH8151','ALTERRA MT ZEN S.E.','MPAUCR85HAT100467','OMEGA WHITE','HG9854','980013503','2011-08-11'),(49,'CH7629','2011 XL','PABTBR54FB2061836','GLACIAL WHITE','M29264','980007951','2011-06-13'),(50,'CH6370','2011 XL','PABTBR54FB2061258','GLACIAL WHITE','M28602','980007365','2011-06-01'),(51,'CI1282','2012 SPORTIVO AT S.E.','PABTBR54FB2063283','SATIN SILVER MET','M31002','980032423','2012-03-27'),(52,'CI3419','020NPR71PL5JXYJ','PABNPR71PLB000235','ARC WHITE','937520P','980031646','2012-03-19'),(53,'CI2403','2012 D-MAX 4X2 LS MT','PABTFR85HB0007103','TITANIUM SILVER','JE6477','980019050','2011-10-17'),(54,'CI3500','020NHR-FLEXITRUCK 80A','PABNHR55ELC001500','ARC WHITE','169137P','980026481','2012-01-17'),(55,'CI3129','2012 SPORTIVO M/T S.E.','PABTBR54FB2064560','EBONY BLACK MONO','M31852','980032093','2012-03-22'),(56,'CI1099','2011 ALTERRA AT U.C. Edition','MPAUCR85HBT100929','SILKY PEARL WHITE','JE3283','980019327','2011-10-20'),(57,'CI2595','2011 ALTERRA AT U.C. Edition','MPAUCR85HBT100919','SILKY PEARL WHITE','JE3273','554144','2011-12-28'),(58,'CI2644','2011 XUV M/T','PABTBR54FB2064455','GLACIAL WHITE/LIGHT SILVER','M31895','544421','2011-11-09'),(59,'CI3522','2012 D-MAX 4X2 LS MT S.E.','PABTFR85HB0007287','SATIN SILVER MET','JG3688','980034538','2012-04-23'),(60,'CI3956','2011 ALTERRA 4X4  AT U.C.','MPAUCS85HBT100150','SILKY PEARL WHITE','JF6876','980043411','2012-07-24'),(61,'CI4717','2012 DMAX 4X4 LS AT S.E.','PABTFS85HB0002012','GLACIAL WHITE','JG4938','980035784','2012-05-03'),(62,'CI3880','2012 XUV A/T','PABTBR54FB2065101','MIDNIGHT BLUE/LIGHT SILVER','M32565','980032091','2012-03-22'),(63,'CJ0280','2013 ALTERRA AT U.C.X. Edition','MPAUCR85HCT100283','SILKY PEARL WHITE','JP0562','980043398','2012-07-24'),(64,'CI8686','2013 ALTERRA MT U.C.X. Edition','MPAUCR85HCT100245','STARRY BLACK','JM9136','980048682','2012-09-19'),(65,'CI8687','2013 ALTERRA AT U.C.X. Edition','MPAUCR85HCT100348','MINERAL GRAY','JR5126','980042331','2012-07-11'),(66,'CI8685','2013 ALTERRA 4X4  AT U.C.X.','MPAUCS85HCT000003','SILKY PEARL WHITE','JR2833','980048685','2012-09-19'),(67,'CI9403','2012 XUV A/T','PABTBR54FC2067654','MIDNIGHT BLUE/LIGHT SILVER','M35099','980037699','2012-05-24'),(68,'CI8491','2012 SPORTIVO AT S.E.','PABTBR54FC2067862','SATIN SILVER METALLIC','M35605','980035956','2012-05-07'),(69,'CJ2989','2013 ALTERRA AT U.C.X. Edition','MPAUCR85HCT100425','MINERAL GRAY','JT7474','980046992','2012-08-31'),(70,'CJ1429','2013 ALTERRA AT U.C.X. Edition','MPAUCR85HCT100392','SILKY PEARL WHITE','JS7798','980044592','2012-08-02'),(71,'CG9591','2010 D-MAX 4X2 LS AT R.E.','PABTFR85HA0005238','EBONY BLACK MONO','HH8788','139048','2010-11-25'),(72,'CI7765','2011 ALTERRA AT U.C. Edition','MPAUCR85HCT100128','MINERAL GRAY','JJ1788','980037603','2012-05-24'),(73,'CI1408','2012 XUV M/T','PABTBR54FB2063579','TITANIUM SILVER/LIGHT SILVER','M31028','980032090','2012-03-22'),(74,'CJ3973','2013 ALTERRA MT U.C.X. Edition','MPAUCR85HCT100452','STARRY BLACK','JV7284','980050428','2012-10-05'),(75,'CJ4460','2013 ALTERRA MT U.C.X. Edition','MPAUCR85HCT100455','STARRY BLACK','JV7288','980050432','2012-10-05'),(76,'CJ2668','2013 SPORTIVO X A/T','PABTBR54FC2069929','EBONY BLACK MONO','M37442','980046863','2012-08-31'),(77,'CJ3962','2013 ALTERRA AT U.C.X. Edition','MPAUCR85HCT100437','SILKY PEARL WHITE','JU8830','980049537','2012-09-27'),(78,'CJ4470','2013 ALTERRA MT U.C.X. Edition','MPAUCR85HCT100451','STARRY BLACK','JV7276','980050427','2012-10-05'),(79,'CJ3956','2013 ALTERRA MT U.C.X. Edition','MPAUCR85HCT100453','STARRY BLACK','JV7278','980050429','2012-10-05'),(80,'CJ3954','2013 ALTERRA MT U.C.X. Edition','MPAUCR85HCT100454','STARRY BLACK','JV7286','980050431','2012-10-05'),(81,'CJ8363','ALTERRA MT ZEN','MPAUCR85HCT100939','STARRY BLACK','KH0029','980090533','2013-11-27'),(82,'CJ7272','2013 ALTERRA 4X4  AT U.C.X.','MPAUCS85HCT000045','STARRY BLACK','JV3165','980106560','2014-05-12'),(83,'CJ6679','2013 XT','PABTBR54FC2071730','RICH RED','M39189','980057512','2012-12-21'),(84,'CJ5957','2013 ALTERRA AT U.C.X. Edition','MPAUCR85HCT100682','SILKY PEARL WHITE','KB2815','980055964','2012-12-03'),(85,'CJ5971','2013 ALTERRA MT U.C.X. Edition','MPAUCR85HCT100589','STARRY BLACK','JZ3707','980063890','2013-03-07'),(86,'CJ6993','2013 ALTERRA AT U.C.X. Edition','MPAUCR85HCT100704','STARRY BLACK','KB5720','980070247','2013-05-14'),(87,'CJ5976','2013 ALTERRA AT U.C.X. Edition','MPAUCR85HCT100578','STARRY BLACK','JY9441','980057200','2012-12-19'),(88,'CJ6958','2013 ALTERRA AT U.C.X. Edition','MPAUCR85HCT100812','SILKY PEARL WHITE','KE5544','980057337','2012-12-20'),(89,'CJ7261','2013 ALTERRA AT U.C.X. Edition','MPAUCR85HCT100823','STARRY BLACK','KE5543','980083938','2013-09-23'),(90,'CJ7118','2013 SPORTIVO X MT','PABTBR54FC2072055','RICH RED','M39522','980057665','2012-12-28'),(91,'CJ7213','2013 ALTERRA MT U.C.X. Edition','MPAUCR85HCT100879','SILKY PEARL WHITE','KF9870','980062372','2013-02-21'),(92,'CJ8360','ALTERRA MT ZEN','MPAUCR85HCT100931','SILKY PEARL WHITE','KH0037','980103133','2014-04-04'),(93,'CJ8314','2013 ALTERRA AT U.C.X. Edition','MPAUCR85HCT100843','MINERAL GRAY','KF1096','980074792','2013-06-26'),(94,'CJ7240','2013 ALTERRA AT U.C.X. Edition','MPAUCR85HCT100847','MINERAL GRAY','KF1097','980064260','2013-03-12'),(95,'CJ8312','2013 ALTERRA AT U.C.X. Edition','MPAUCR85HCT100851','MINERAL GRAY','KF1086','980070246','2013-05-14'),(96,'CJ9735','2012 D-MAX 4X2 LS AT S.E.','PABTFR85HD0009384','RICH RED','KR2330','980066902','2013-04-11'),(97,'CK1514','2013 SPORTIVO X A/T','PABTBR54FD2074864','RICH RED','M42372','980074930','2013-06-27'),(98,'CK2004','2014 D-MAX-Cab Chassis','MPATFR86JDT001655','SPLASH WHITE','KY0388','980075929','2013-07-05'),(99,'CJ9722','2012 D-MAX 4X2 LS MT S.E.','PABTFR85HD0009281','TITANIUM SILVER','KH9001','980069164','2013-04-30'),(100,'CK1982','2013 XT L.E.','PABTBR54FD2075266','EBONY BLACK MONO','M42707','980073175','2013-06-11'),(101,'CK1182','2013 ALTERRA AT U.C.X. Edition','MPAUCR85HCT101084','SILKY PEARL WHITE','KP1188','980072993','2013-06-07'),(102,'CK2005','2014 D-MAX 4X2 LS MT','MPATFR85JDT001434','COSMIC BLACK MICA','KV4682','980074446','2013-06-25'),(103,'CK2006','2014 D-MAX 4X2 LS AT','MPATFR85JDT001435','TITANIUM SILVER','KR5267','980075590','2013-07-02'),(104,'CK2007','2014 DMAX 4X4 LS AT','MPATFS85JDT005302','GARNET RED','KW1769','980074445','2013-06-25'),(105,'CK1466','2013 ALTERRA MT U.C.X. Edition','MPAUCR85HCT101031','STARRY BLACK','KL8546','980076036','2013-07-08'),(106,'CK1529','2014 SPORTIVO X A/T','PABTBR54FD2074873','COSMIC BLACK MICA','M42361','980093504','2014-01-08'),(107,'CK1557','2014 XL','PABTBR54FD2075001','SPLASH WHITE','M42329','980093502','2014-01-08'),(108,'CK1709','2014 XT','PABTBR54FD2074969','NAUTILUS BLUE','M42442','980095143','2014-01-23'),(109,'CK6078','2013 ALTERRA 4X4  AT U.C.X.','MPAUCS85HCT000042','STARRY BLACK','JV3155','980116197','2014-08-04'),(110,'CK4742','140 DMAX 4X4 LS AT','MPATFS85JDT006633','GARNET RED','LE1464','980084461','2013-09-26'),(111,'CK4680','140 DMAX 4X4 LS MT','MPATFS85JDT006601','TITANIUM SILVER','LE1517','980084009','2013-09-23'),(112,'CK6186','140 D-MAX 4X2 LS AT','MPATFR85JDT002647','COSMIC BLACK MICA','LH2609','980085968','2013-10-10'),(113,'CK4661','140 D-MAX 4X2 MT LT','MPATFR86JDT001881','SPLASH WHITE','LD9230','980084246','2013-09-25'),(114,'CK7161','140 ALTERRA AT U.C.X.','MPAUCR85HCT101171','SILKY PEARL WHITE','LB6972','980091426','2013-12-06'),(115,'CK8371','140 D-MAX 4X2 LS AT','MPATFR85JDT003240','COSMIC BLACK MICA','LN0044','980093312','2014-01-06'),(116,'CK5803','140D-MAX 4X2 LS MT','PABTFR85HD2000017','COSMIC BLACK MICA','LF8452','980093076','2013-12-27'),(117,'CK5201','140D-MAX 4X2 LS AT','PABTFR85HD2000002','GARNET RED','LF4233','980085005','2013-09-30'),(118,'CK9774','140D-MAX Single Cab&Chassis','PABTFR86HD2000022','SPLASH WHITE','LG3976','980096371','2014-02-04'),(119,'CK9775','140D-MAX Single Cab&Chassis','PABTFR86HD2000026','SPLASH WHITE','LG3980','980096524','2014-02-05'),(120,'CK9121','140 ALTERRA MT U.C.X.','MPAUCR85HCT101103','MINERAL GRAY','LB2941','980095141','2014-01-23'),(121,'CL2914','140 ALTERRA AT U.C.X.','MPAUCR85HCT101284','SILKY PEARL WHITE','LC3762','980104887','2014-04-24'),(122,'CL2913','140 ALTERRA AT U.C.X.','MPAUCR85HCT101286','SILKY PEARL WHITE','LC3773','980105143','2014-04-28'),(123,'CL0969','140 ALTERRA AT U.C.X.','MPAUCR85HCT101344','SILKY PEARL WHITE','LC6414','980103490','2014-04-10'),(124,'CL1136','020NKR71EL1EX','PABNKR71ELE001784','ARC WHITE','194150A','980102644','2014-03-31'),(125,'CK8998','140 D-MAX 4X2 LS AT','MPATFR85JDT003640','COSMIC BLACK MICA','LN3131','980093505','2014-01-08'),(126,'CK9204','140 D-MAX 4X2 LS AT','MPATFR85JDT003671','GARNET RED','LN4074','980095142','2014-01-23'),(127,'CK1737','2014 SPORTIVO X MT','PABTBR54FD2074055','ASH BEIGE MET.','M41512','980095144','2014-01-23'),(128,'CL1135','020NKR71EL1EX','PABNKR71ELE001783','ARC WHITE','194147A','980102643','2014-03-31'),(129,'CL1129','020NKR71EL1EX','PABNKR71ELE001777','ARC WHITE','194162A','980102646','2014-03-31'),(130,'CL7707','mu-X 4x2 LS-A AT','MPAUCR86GET000273','SPLASH WHITE','MF7039','980125008','2014-10-23'),(131,'CL1108','020NHR55EL-TILT','PABNHR55ELE003730','ARC WHITE','1E4214A','980102641','2014-03-31'),(132,'CL7766','mu-X 4x2 LS-A AT','MPAUCR86GET000115','SILKY PEARL WHITE','ME9378','980121485','2014-09-25'),(133,'CL7114','140 D-MAX 4X2 LS MT X-Series','MPATFR85JET002179','VENETIAN RED MICA','MF7402','980123847','2014-10-15'),(134,'CL7720','mu-X 4x4 LS-A AT','MPAUCS86GET000069','OUTBACK BROWN','MF4918','980119154','2014-09-02'),(135,'CL5349','mu-X 4x2 LS-M MT','MPAUCR86GET000007','FJORD BLUE','LY2355','980123227','2014-10-09'),(136,'CL5350','mu-X 4x4 LS-A AT','MPAUCS86GET000006','ULURU BROWN','LY2357','980123226','2014-10-09'),(137,'CL7715','mu-X 4x2 LS MT','MPAUCR86GET000070','SPLASH WHITE','ME8569','980119150','2014-09-02'),(138,'CL7714','mu-X 4x2 LS-M MT','MPAUCR86GET000085','TITANIUM SILVER','ME8566','980119151','2014-09-02'),(139,'CM1349','mu-X 4x2 LS-A AT','MPAUCR86GET002011','AQUA BLUE','MN2923','980130573','2014-12-02'),(140,'CL7733','mu-X 4x2 LS-A AT','MPAUCR86GET000120','OUTBACK BROWN','ME9388','980121486','2014-09-25'),(141,'CM1437','150 XUV AT','PABTBR54FE2082411','GARNET RED','M49785','980141393','2015-03-03'),(142,'CM0402','150 XT','PABTBR54FE2082261','DARK SILVER','M49725','980141391','2015-03-03'),(143,'CM4574','mu-X 4x2 LS-M MT','MPAUCR86GET003026','SILKY PEARL WHITE','MT7643','980137170','2015-02-03'),(144,'CM2315','2014 XT L.E.','PABTBR54FE2082590','ASH BEIGE MET.','M50054','980132241','2014-12-17'),(145,'CM3312','mu-X 4x2 LS-A AT','MPAUCR86GET002583','SILKY PEARL WHITE','MS5108','980141395','2015-03-03'),(146,'CM0401','150 SPORTIVO X MT','PABTBR54FE2081511','HUNTER GREEN','M48975','980141394','2015-03-03'),(147,'CM3804','mu-X 4x2 LS-A AT','MPAUCR86GET002362','COSMIC BLACK MICA','MR8157','980144081','2015-03-23'),(148,'CM2077','150 XL','PABTBR54FE2082379','SPLASH WHITE','M49813','980141390','2015-03-03'),(149,'CM6149','020NQR71RL-5NVXYJ','PABN1R71RLE001085','ARC WHITE','308337A','980142377','2015-03-10'),(150,'CM6468','150 SPORTIVO X AT','PABTBR54FF2083508','HUNTER GREEN','M51002','980145574','2015-04-07'),(151,'CM5781','020NKR71EL1EX','PABNKR71ELF002547','ARC WHITE','320502A','980141719','2015-03-05'),(152,'CM6825','120FVM34UL-TN','PABFVM34ULE000072','ARC WHITE','663864','980144230','2015-03-24'),(153,'CM5779','020NKR71EL1EX','PABNKR71ELF002545','ARC WHITE','320495A','980141718','2015-03-05'),(154,'CM5166','150 DMAX 4X4 LS MT','PABTFS86DF2000013','COBALT BLUE','MW4140','980153314','2015-06-02'),(155,'CM6008','020NPR71PL5JXYJ','PABNPR71PLF001376','ARC WHITE','327405A','980141717','2015-03-05'),(156,'CM5185','150 D-MAX 4X2 LS MT','PABTFR86DF2000002','GARNET RED','MW2395','980148524','2015-04-29'),(157,'CM9395','mu-X 4x2 LS-A AT','MPAUCR86GFT003478','SPLASH WHITE','NA9396','980158814','2015-07-09'),(158,'CN3252','150 SPORTIVO X MT JS','PABTBR54FF2084422','ASH BEIGE MET.','M51826','980155266','2015-06-17'),(159,'CN2608','mu-X 4x2 LS-A AT','MPAUCR86GFT005134','SILKY PEARL WHITE','ND7589','980154192','2015-06-10'),(160,'CM6803','150 D-MAX 4X2 LS AT','PABTFR86DF2000046','COSMIC BLACK MICA','MW4113','980150352','2015-05-13'),(161,'CN2639','mu-X 4x2 LS-A AT','MPAUCR86GFT005069','SPLASH WHITE','ND7500','980158243','2015-07-07'),(162,'CN2633','mu-X 4x2 LS-A AT','MPAUCR86GFT005061','SPLASH WHITE','ND6872','980154194','2015-06-10'),(163,'CM7172','150 DMAX 4X4 LS AT','PABTFS86DF2000051','COBALT BLUE','MW8698','980148527','2015-04-29'),(164,'CN2238','mu-X 4x2 LS-A AT','MPAUCR86GFT004408','SILKY PEARL WHITE','NC6989','980154191','2015-06-10'),(165,'CN2649','mu-X 4x2 LS-A AT','MPAUCR86GFT005081','TITANIUM SILVER','ND7533','980154189','2015-06-10'),(166,'CN2663','mu-X 4x2 LS-A AT','MPAUCR86GFT005095','TITANIUM SILVER','ND7551','980154190','2015-06-10'),(167,'CN2401','mu-X 4x2 LS-A AT','MPAUCR86GFT004607','OUTBACK BROWN','ND1147','980154187','2015-06-10'),(168,'CN2388','mu-X 4x2 LS-A AT','MPAUCR86GFT004769','COSMIC BLACK MICA','ND3774','980154193','2015-06-10'),(169,'CN8277','mu-X 4x2 LS MT','MPAUCR86GFT008139','SILKY PEARL WHITE','NJ5037','980170299','2015-09-29'),(170,'CN4316','mu-X 4x2 LS-A AT','MPAUCR86GFT006289','SPLASH WHITE','NF8387','980158270','2015-07-07'),(171,'CN6493','mu-X 4x2 LS-A AT','MPAUCR86GFT006450','TITANIUM SILVER','NF9418','980160736','2015-07-22'),(172,'CN4384','mu-X 4x2 LS-A AT','MPAUCR86GFT006428','TITANIUM SILVER','NF9405','980158529','2015-07-08'),(173,'CN3826','mu-X 4x2 LS-A AT','MPAUCR86GFT005702','AQUA BLUE','NE7991','980158267','2015-07-07'),(174,'CN6454','mu-X 4x2 LS-A AT','MPAUCR86GFT006410','SPLASH WHITE','NF9385','980159711','2015-07-15'),(175,'CN4310','mu-X 4x2 LS-A AT','MPAUCR86GFT006283','SPLASH WHITE','NF8369','980158271','2015-07-07'),(176,'CN5996','020NKR71EL1EX','PABNKR71ELF002995','ARC WHITE','373940A','980160735','2015-07-22'),(177,'CN6513','mu-X 4x2 LS-A AT','MPAUCR86GFT006470','TITANIUM SILVER','NG0094','980159378','2015-07-13'),(178,'CN7559','mu-X 4x2 LS-A AT','MPAUCR86GFT007431','TITANIUM SILVER','NH1325','980162077','2015-07-31'),(179,'CN7457','mu-X 4x2 LS-A AT','MPAUCR86GFT007371','AQUA BLUE','NH1179','980164506','2015-08-19'),(180,'CJ8369','ALTERRA MT ZEN','MPAUCR85HCT100933','SILKY PEARL WHITE','KH0038','980073257','2013-06-11'),(181,'CM3405','mu-X 4x2 LS-M MT','MPAUCR86GET002443','OUTBACK BROWN','MS5131','980137169','2015-02-03'),(182,'CN4301','mu-X 4x2 LS-A AT','MPAUCR86GFT006274','SPLASH WHITE','NF8357','980158268','2015-07-07'),(183,'CN6446','mu-X 4x2 LS-A AT','MPAUCR86GFT006401','SPLASH WHITE','NF9381','980159712','2015-07-15'),(184,'CN4644','150 XT','PABTBR54FF2085673','SPLASH WHITE','M53047','980171232','2015-10-05'),(185,'CO2210','mu-X 4x2 LS-A AT','MPAUCR86GFT009728','SPLASH WHITE','NM2982','980171236','2015-10-05'),(186,'CN9742','mu-X 4x2 LS-A AT','MPAUCR86GFT008695','COSMIC BLACK MICA','NK3608','980167251','2015-09-08'),(187,'CO2883','mu-X 4x2 LS-A AT 3.0','MPAUCR85GFT001162','COSMIC BLACK MICA','NP1900','980176071','2015-11-11'),(188,'CO5311','150 XT L.E.','PABTBR54FF2086421','COSMIC BLACK MICA','M53867','980180179','2015-12-14'),(189,'CO3849','160 DMAX 4X4 LS AT','MPATFS85JFT018210','VENETIAN RED MICA','NP3083','980176283','2015-11-12'),(190,'CO5419','mu-X 4x2 LS-A AT 3.0','MPAUCR85GFT001309','COSMIC BLACK MICA','NP5064','980180002','2015-12-11'),(191,'CO3848','mu-X 4x4 LS-A AT 3.0','MPAUCS85GFT006818','SILKY PEARL WHITE','NP3769','885745','2016-01-07'),(192,'CO5896','mu-X 4x2 LS-A AT 3.0','MPAUCR85GFT001578','SPLASH WHITE','NP7136','980176074','2015-11-11'),(193,'CO3854','mu-X 4x4 LS-A AT 3.0','MPAUCS85GFT006749','COSMIC BLACK MICA','NP0287','980176284','2015-11-12'),(194,'CO4517','mu-X 4x2 LS-A AT 3.0','MPAUCR85GFT002096','TITANIUM SILVER','NR0744','980180001','2015-12-10'),(195,'CO7114','020NHR-FLEXITRUCK 80A','PABNHR55ELF006823','ARC WHITE','2G6760A','980180234','2015-12-14');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;