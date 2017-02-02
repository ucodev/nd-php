-- MySQL dump 10.13  Distrib 5.6.28, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: ndphp_add
-- ------------------------------------------------------
-- Server version	5.6.28-0ubuntu0.15.10.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `_acl_rtcp`
--

DROP TABLE IF EXISTS `_acl_rtcp`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `_acl_rtcp` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `roles_id` int(11) DEFAULT NULL,
  `_table` varchar(64) NOT NULL,
  `_column` varchar(64) NOT NULL,
  `permissions` varchar(4) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `roles_id` (`roles_id`),
  CONSTRAINT `_acl_rtcp_ibfk_1` FOREIGN KEY (`roles_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13427 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `_acl_rtcp`
--

LOCK TABLES `_acl_rtcp` WRITE;
/*!40000 ALTER TABLE `_acl_rtcp` DISABLE KEYS */;
INSERT INTO `_acl_rtcp` VALUES (4589,4,'countries','id','RS'),(4590,4,'countries','country','RS'),(4591,4,'payment_types','id','R'),(4592,4,'payment_types','payment_type','R'),(4593,4,'payments','id','RS'),(4594,4,'payments','payment_types_id','RS'),(4595,4,'payments','amount','RS'),(4596,4,'payments','tax_rate','RS'),(4597,4,'payments','total_tax','RS'),(4598,4,'payments','payment_status_id','RS'),(4599,4,'payments','items_id','RS'),(4600,4,'payments','item_price','RS'),(4601,4,'payments','item_quantity','RS'),(4602,4,'payments','item_description','RS'),(4604,4,'subscription_types','id','RS'),(4605,4,'subscription_types','subscription_type','RS'),(4606,4,'subscription_types','description','RS'),(4607,4,'transaction_history','id','RS'),(4608,4,'transaction_history','transaction_date','RS'),(4609,4,'transaction_history','transaction_types_id','RS'),(4610,4,'transaction_history','ammount','RS'),(4611,4,'transaction_history','description','RS'),(4612,4,'transaction_types','id','R'),(4613,4,'transaction_types','transaction_type','R'),(4614,4,'transaction_types','description','R'),(4615,4,'users','id','R'),(4616,4,'users','username','R'),(4617,4,'users','password','RU'),(4618,4,'users','timezones_id','RU'),(4619,4,'users','subscription_types_id','R'),(4620,4,'users','subscription_change_date','R'),(4621,4,'users','subscription_renew_date','R'),(4622,4,'users','email','R'),(4623,4,'users','phone','RU'),(4624,4,'users','company','RU'),(4625,4,'users','address_line1','RU'),(4626,4,'users','address_line2','RU'),(4627,4,'users','city','RU'),(4628,4,'users','postcode','RU'),(4629,4,'users','vat','RU'),(4630,4,'users','last_login','R'),(4631,4,'users','countries_id','RU'),(4632,4,'users','first_name','RU'),(4633,4,'users','last_name','RU'),(4634,4,'users','credit','R'),(4635,4,'users','apikey','R'),(13134,1,'builder','id','CRUS'),(13135,1,'builder','build','CRUS'),(13136,1,'builder','created','CRUS'),(13137,1,'charts_config','id','CRUS'),(13138,1,'charts_config','title','CRUS'),(13139,1,'charts_config','controller','CRUS'),(13140,1,'charts_config','charts_types_id','CRUS'),(13141,1,'charts_config','charts_geometry_id','CRUS'),(13142,1,'charts_config','fields','CRUS'),(13143,1,'charts_config','abscissa','CRUS'),(13144,1,'charts_config','foreign_table','CRUS'),(13145,1,'charts_config','field','CRUS'),(13146,1,'charts_config','field_legend','CRUS'),(13147,1,'charts_config','field_total','CRUS'),(13148,1,'charts_config','import_ctrl','CRUS'),(13149,1,'charts_config','chartid','CRUS'),(13150,1,'charts_config','field_ts','CRUS'),(13151,1,'charts_config','start_ts','CRUS'),(13152,1,'charts_config','end_ts','CRUS'),(13153,1,'charts_geometry','id','RS'),(13154,1,'charts_geometry','chart_geometry','RS'),(13155,1,'charts_geometry','description','RS'),(13156,1,'charts_types','id','RS'),(13157,1,'charts_types','chart_type','RS'),(13158,1,'charts_types','description','RS'),(13159,1,'configuration','id','CRUS'),(13160,1,'configuration','configuration','CRUS'),(13161,1,'configuration','base_url','CRUS'),(13162,1,'configuration','page_rows','CRUS'),(13163,1,'configuration','temporary_directory','CRUS'),(13164,1,'configuration','themes_id','CRUS'),(13165,1,'configuration','timezones_id','CRUS'),(13166,1,'configuration','roles_id','CRUS'),(13167,1,'configuration','maintenance','CRUS'),(13168,1,'configuration','active','CRUS'),(13169,1,'configuration','_separator_project','CRU'),(13170,1,'configuration','project_name','CRUS'),(13171,1,'configuration','project_version','CRUS'),(13172,1,'configuration','project_date','CRUS'),(13173,1,'configuration','tagline','CRUS'),(13174,1,'configuration','description','CRUS'),(13175,1,'configuration','author','CRUS'),(13176,1,'configuration','_separator_smtp','CRU'),(13177,1,'configuration','smtp_username','CRUS'),(13178,1,'configuration','smtp_password','CRUS'),(13179,1,'configuration','smtp_server','CRUS'),(13180,1,'configuration','smtp_port','CRUS'),(13181,1,'configuration','smtp_ssl','CRUS'),(13182,1,'configuration','smtp_tls','CRUS'),(13183,1,'configuration','_separator_memcached','CRU'),(13184,1,'configuration','memcached_server','CRUS'),(13185,1,'configuration','memcached_port','CRUS'),(13186,1,'configuration','_separator_recaptcha','CRU'),(13187,1,'configuration','recaptcha_priv_key','CRUS'),(13188,1,'configuration','recaptcha_pub_key','CRUS'),(13189,1,'configuration','rel_configuration_features','CRUS'),(13190,1,'countries','id','CRUS'),(13191,1,'countries','country','CRUS'),(13192,1,'countries','code','CRUS'),(13193,1,'countries','prefix','CRUS'),(13194,1,'countries','eu_state','CRUS'),(13195,1,'countries','vat_rate','CRUS'),(13196,1,'dbms','id','CRUS'),(13197,1,'dbms','alias','CRUS'),(13198,1,'dbms','name','CRUS'),(13199,1,'dbms','host','CRUS'),(13200,1,'dbms','port','CRUS'),(13201,1,'dbms','username','CRUS'),(13202,1,'dbms','password','CRUS'),(13203,1,'dbms','charset','CRUS'),(13204,1,'dbms','persistent','CRUS'),(13205,1,'dbms','strict','CRUS'),(13206,1,'documentation','id','RS'),(13207,1,'documentation','revision','RS'),(13208,1,'documentation','changed','RS'),(13209,1,'documentation','description','RS'),(13210,1,'features','id','CRUS'),(13211,1,'features','feature','CRUS'),(13212,1,'features','description','CRUS'),(13213,1,'features','rel_configuration_features','CRUS'),(13214,1,'items','id','CRUS'),(13215,1,'items','item','CRUS'),(13216,1,'items','description','CRUS'),(13217,1,'items','price','CRUS'),(13218,1,'logging','id','RS'),(13219,1,'logging','operation','RS'),(13220,1,'logging','_table','RS'),(13221,1,'logging','_field','RS'),(13222,1,'logging','entryid','RS'),(13223,1,'logging','value_old','RS'),(13224,1,'logging','value_new','RS'),(13225,1,'logging','transaction','RS'),(13226,1,'logging','registered','RS'),(13227,1,'logging','sessions_id','RS'),(13228,1,'logging','users_id','RS'),(13229,1,'months','id','RUS'),(13230,1,'months','month','RUS'),(13231,1,'months','number','RS'),(13232,1,'notifications','id','CRUS'),(13233,1,'notifications','notification','CRUS'),(13234,1,'notifications','description','CRUS'),(13235,1,'notifications','url','CRUS'),(13236,1,'notifications','seen','CRUS'),(13237,1,'notifications','all','CRUS'),(13238,1,'notifications','users_id','CRUS'),(13239,1,'payment_actions','id','CRUS'),(13240,1,'payment_actions','payment_action','CRUS'),(13241,1,'payment_actions','description','CRUS'),(13242,1,'payment_status','id','CRUS'),(13243,1,'payment_status','payment_status','CRUS'),(13244,1,'payment_status','description','CRUS'),(13245,1,'payment_types','id','CRUS'),(13246,1,'payment_types','payment_type','CRUS'),(13247,1,'payment_types','transaction_fee_percentage','CRUS'),(13248,1,'payment_types','transaction_fee_fixed','CRUS'),(13249,1,'payment_types','transaction_min_amount','CRUS'),(13250,1,'payment_types','transaction_max_amount','CRUS'),(13251,1,'payment_types','description','CRUS'),(13252,1,'payments','id','CRUS'),(13253,1,'payments','txnid','CRUS'),(13254,1,'payments','payment_types_id','CRUS'),(13255,1,'payments','amount','CRUS'),(13256,1,'payments','tax_rate','CRUS'),(13257,1,'payments','payment_fee','CRUS'),(13258,1,'payments','total_tax','CRUS'),(13259,1,'payments','payment_status_id','CRUS'),(13260,1,'payments','status_desc','CRUS'),(13261,1,'payments','items_id','CRUS'),(13262,1,'payments','item_price','CRUS'),(13263,1,'payments','item_quantity','CRUS'),(13264,1,'payments','item_description','CRUS'),(13265,1,'payments','created','CRUS'),(13266,1,'payments','updated','CRUS'),(13267,1,'payments','users_id','CRUS'),(13268,1,'payments','payment_actions_id','CRUS'),(13269,1,'payments','payer_email','CRUS'),(13270,1,'payments','payer_first_name','CRUS'),(13271,1,'payments','payer_last_name','CRUS'),(13272,1,'payments','payer_address_name','CRUS'),(13273,1,'payments','payer_address_country','CRUS'),(13274,1,'payments','payer_address_city','CRUS'),(13275,1,'payments','payer_address_street','CRUS'),(13276,1,'payments','payer_address_zip','CRUS'),(13277,1,'payments','payer_address_state','CRUS'),(13278,1,'payments','payer_address_status','CRUS'),(13279,1,'payments','payer_status','CRUS'),(13280,1,'payments','payer_residence_country','CRUS'),(13281,1,'payments','payer_payment_date','CRUS'),(13282,1,'roles','id','CRUS'),(13283,1,'roles','role','CRUS'),(13284,1,'roles','description','CRUS'),(13285,1,'roles','rel_users_roles','CRUS'),(13286,1,'scheduler','id','CRUS'),(13287,1,'scheduler','entry_name','CRUS'),(13288,1,'scheduler','description','CRUS'),(13289,1,'scheduler','url','CRUS'),(13290,1,'scheduler','period','CRUS'),(13291,1,'scheduler','active','CRUS'),(13292,1,'scheduler','registered','RS'),(13293,1,'scheduler','last_run','RS'),(13294,1,'scheduler','next_run','CRUS'),(13295,1,'scheduler','output','RS'),(13296,1,'sessions','id','RS'),(13297,1,'sessions','session','RS'),(13298,1,'sessions','ip_address','RS'),(13299,1,'sessions','user_agent','RS'),(13300,1,'sessions','start_time','RS'),(13301,1,'sessions','last_login','RS'),(13302,1,'sessions','users_id','RS'),(13303,1,'subscription_types','id','CRUS'),(13304,1,'subscription_types','subscription_type','CRUS'),(13305,1,'subscription_types','description','CRUS'),(13306,1,'subscription_types','price','CRUS'),(13307,1,'subscription_types','api_extended','CRUS'),(13308,1,'themes','id','CRUS'),(13309,1,'themes','theme','CRUS'),(13310,1,'themes','description','CRUS'),(13311,1,'themes','animation_default_delay','CRUS'),(13312,1,'themes','animation_ordering_delay','CRUS'),(13313,1,'themes','themes_animations_default_id','CRUS'),(13314,1,'themes','themes_animations_ordering_id','CRUS'),(13315,1,'themes_animations_default','id','CRUS'),(13316,1,'themes_animations_default','animation','CRUS'),(13317,1,'themes_animations_default','description','CRUS'),(13318,1,'themes_animations_ordering','id','CRUS'),(13319,1,'themes_animations_ordering','animation','CRUS'),(13320,1,'themes_animations_ordering','description','CRUS'),(13321,1,'timezones','id','CRUS'),(13322,1,'timezones','timezone','CRUS'),(13323,1,'timezones','countries_id','CRUS'),(13324,1,'timezones','utc','CRUS'),(13325,1,'timezones','utc_dst','CRUS'),(13326,1,'timezones','coordinates','CRUS'),(13327,1,'transaction_history','id','CRUS'),(13328,1,'transaction_history','transaction_date','CRUS'),(13329,1,'transaction_history','transaction_types_id','CRUS'),(13330,1,'transaction_history','ammount','CRUS'),(13331,1,'transaction_history','description','CRUS'),(13332,1,'transaction_history','users_id','CRUS'),(13333,1,'transaction_types','id','CRUS'),(13334,1,'transaction_types','transaction_type','CRUS'),(13335,1,'transaction_types','description','CRUS'),(13336,1,'users','id','CRUS'),(13337,1,'users','username','CRUS'),(13338,1,'users','password','CRUS'),(13339,1,'users','_file_photo','CRU'),(13340,1,'users','email','CRUS'),(13341,1,'users','phone','CRUS'),(13342,1,'users','active','CRUS'),(13343,1,'users','locked','CRUS'),(13344,1,'users','_separator_subscription','CRU'),(13345,1,'users','subscription_types_id','CRUS'),(13346,1,'users','subscription_change_date','CRUS'),(13347,1,'users','subscription_renew_date','CRUS'),(13348,1,'users','_separator_personal','CRU'),(13349,1,'users','first_name','CRUS'),(13350,1,'users','last_name','CRUS'),(13351,1,'users','countries_id','CRUS'),(13352,1,'users','timezones_id','CRUS'),(13353,1,'users','company','CRUS'),(13354,1,'users','address_line1','CRUS'),(13355,1,'users','address_line2','CRUS'),(13356,1,'users','city','CRUS'),(13357,1,'users','postcode','CRUS'),(13358,1,'users','vat','CRUS'),(13359,1,'users','_separator_register','CRU'),(13360,1,'users','expire','CRUS'),(13361,1,'users','registered','CRUS'),(13362,1,'users','last_login','CRUS'),(13363,1,'users','confirm_email_hash','CRUS'),(13364,1,'users','confirm_phone_token','CRUS'),(13365,1,'users','email_confirmed','CRUS'),(13366,1,'users','phone_confirmed','CRUS'),(13367,1,'users','date_confirmed','CRUS'),(13368,1,'users','_separator_credit','CRU'),(13369,1,'users','credit','CRUS'),(13370,1,'users','allow_negative','CRUS'),(13371,1,'users','_separator_api','CRU'),(13372,1,'users','apikey','CRUS'),(13373,1,'users','_separator_accounting','R'),(13374,1,'users','acct_last_reset','R'),(13375,1,'users','acct_rest_list','R'),(13376,1,'users','acct_rest_result','R'),(13377,1,'users','acct_rest_view','R'),(13378,1,'users','acct_rest_delete','R'),(13379,1,'users','acct_rest_update','R'),(13380,1,'users','acct_rest_insert','R'),(13381,1,'users','_separator_sharding','CRU'),(13382,1,'users','dbms_id','CRUS'),(13383,1,'users','rel_users_roles','CRUS'),(13384,1,'weekdays','id','RUS'),(13385,1,'weekdays','weekday','RUS'),(13386,1,'weekdays','number','RS'),(13387,1,'configuration','support_email','CRUS'),(13388,1,'currencies','id','CRUS'),(13389,1,'currencies','currency','CRUS'),(13390,1,'currencies','code','CRUS'),(13391,1,'currencies','sign','CRUS'),(13392,1,'currencies','sign_position','CRUS'),(13393,1,'currencies','rate','CRUS'),(13394,1,'currencies','updated','CRUS'),(13395,1,'currencies','default','CRUS'),(13396,1,'genders','id','RUS'),(13397,1,'genders','gender','RUS'),(13398,1,'users','currencies_id','CRUS'),(13399,1,'users','genders_id','CRUS'),(13400,1,'users','birthdate','CRUS'),(13401,1,'users','brand','CRUS'),(13402,1,'users','website','CRUS'),(13403,1,'users','about','CRUS'),(13404,1,'countries','currencies_id','CRUS'),(13405,1,'users','_separator_generic','CRU'),(13406,1,'users','generic_counter_1','CRUS'),(13407,1,'users','generic_counter_2','CRUS'),(13408,1,'users','generic_counter_3','CRUS'),(13409,1,'users','generic_counter_4','CRUS'),(13410,1,'users','generic_text_1','CRUS'),(13411,1,'users','generic_text_2','CRUS'),(13412,1,'users','generic_text_3','CRUS'),(13413,1,'users','generic_text_4','CRUS'),(13414,1,'users','generic_datetime_1','CRUS'),(13415,1,'users','generic_datetime_2','CRUS'),(13416,1,'users','generic_datetime_3','CRUS'),(13417,1,'users','generic_datetime_4','CRUS'),(13418,1,'codes','id','CRUS'),(13419,1,'codes','code','CRUS'),(13420,1,'codes','remaining','CRUS'),(13421,1,'codes','valid_from','CRUS'),(13422,1,'codes','valid_to','CRUS'),(13423,1,'codes','roles_id','CRUS'),(13424,1,'codes_types','id','CRUS'),(13425,1,'codes_types','code_type','CRUS'),(13426,1,'codes_types','description','CRUS');
/*!40000 ALTER TABLE `_acl_rtcp` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `_acl_rtp`
--

DROP TABLE IF EXISTS `_acl_rtp`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `_acl_rtp` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `roles_id` int(11) DEFAULT NULL,
  `_table` varchar(64) NOT NULL,
  `permissions` varchar(4) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `roles_id` (`roles_id`),
  CONSTRAINT `_acl_rtp_ibfk_1` FOREIGN KEY (`roles_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2917 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `_acl_rtp`
--

LOCK TABLES `_acl_rtp` WRITE;
/*!40000 ALTER TABLE `_acl_rtp` DISABLE KEYS */;
INSERT INTO `_acl_rtp` VALUES (1049,4,'countries','R'),(1050,4,'payment_types','R'),(1051,4,'payments','R'),(1053,4,'subscription_types','R'),(1054,4,'transaction_history','R'),(1055,4,'transaction_types','R'),(1057,4,'users','RU'),(2884,1,'builder','CRUD'),(2885,1,'charts_config','CRUD'),(2886,1,'charts_geometry','R'),(2887,1,'charts_types','R'),(2888,1,'configuration','CRUD'),(2889,1,'countries','CRUD'),(2890,1,'dbms','CRUD'),(2891,1,'documentation','R'),(2892,1,'features','CRU'),(2893,1,'items','CRUD'),(2894,1,'logging','R'),(2895,1,'months','RU'),(2896,1,'notifications','CRUD'),(2897,1,'payment_actions','CRUD'),(2898,1,'payment_status','CRUD'),(2899,1,'payment_types','CRUD'),(2900,1,'payments','CRUD'),(2901,1,'roles','CRUD'),(2902,1,'scheduler','CRUD'),(2903,1,'sessions','R'),(2904,1,'subscription_types','CRUD'),(2905,1,'themes','CRUD'),(2906,1,'themes_animations_default','CRU'),(2907,1,'themes_animations_ordering','CRU'),(2908,1,'timezones','CRUD'),(2909,1,'transaction_history','CRUD'),(2910,1,'transaction_types','CRUD'),(2911,1,'users','CRUD'),(2912,1,'weekdays','RU'),(2913,1,'currencies','CRUD'),(2914,1,'genders','RU'),(2915,1,'codes','CRUD'),(2916,1,'codes_types','CRUD');
/*!40000 ALTER TABLE `_acl_rtp` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `_help_tfhd`
--

DROP TABLE IF EXISTS `_help_tfhd`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `_help_tfhd` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `table_name` varchar(64) NOT NULL,
  `field_name` varchar(64) DEFAULT NULL,
  `placeholder` varchar(128) DEFAULT NULL,
  `field_units` varchar(32) DEFAULT NULL,
  `units_on_left` tinyint(1) DEFAULT '0',
  `input_pattern` varchar(256) DEFAULT NULL,
  `help_description` varchar(4096) NOT NULL,
  `help_url` varchar(1024) NOT NULL DEFAULT '#',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `_help_tfhd`
--

LOCK TABLES `_help_tfhd` WRITE;
/*!40000 ALTER TABLE `_help_tfhd` DISABLE KEYS */;
/*!40000 ALTER TABLE `_help_tfhd` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `_saved_searches`
--

DROP TABLE IF EXISTS `_saved_searches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `_saved_searches` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `search_name` varchar(32) NOT NULL,
  `description` varchar(256) DEFAULT NULL,
  `controller` varchar(64) NOT NULL,
  `result_query` text NOT NULL,
  `users_id` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `users_id` (`users_id`),
  CONSTRAINT `_saved_searches_ibfk_1` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `_saved_searches`
--

LOCK TABLES `_saved_searches` WRITE;
/*!40000 ALTER TABLE `_saved_searches` DISABLE KEYS */;
/*!40000 ALTER TABLE `_saved_searches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `builder`
--

DROP TABLE IF EXISTS `builder`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `builder` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `build` int(11) NOT NULL DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `model` mediumtext,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `builder`
--

LOCK TABLES `builder` WRITE;
/*!40000 ALTER TABLE `builder` DISABLE KEYS */;
/*!40000 ALTER TABLE `builder` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `charts_config`
--

DROP TABLE IF EXISTS `charts_config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `charts_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(64) NOT NULL,
  `controller` varchar(128) NOT NULL,
  `charts_types_id` int(11) NOT NULL DEFAULT '1',
  `charts_geometry_id` int(11) NOT NULL DEFAULT '1',
  `fields` varchar(1024) DEFAULT NULL,
  `abscissa` varchar(128) DEFAULT NULL,
  `foreign_table` varchar(128) DEFAULT NULL,
  `field` varchar(128) DEFAULT NULL,
  `field_legend` varchar(128) DEFAULT NULL,
  `field_total` varchar(128) DEFAULT NULL,
  `import_ctrl` varchar(128) DEFAULT NULL,
  `chartid` int(11) DEFAULT NULL,
  `field_ts` varchar(128) DEFAULT NULL,
  `start_ts` timestamp NULL DEFAULT NULL,
  `end_ts` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `charts_types_id` (`charts_types_id`),
  KEY `charts_geometry_id` (`charts_geometry_id`),
  CONSTRAINT `charts_config_ibfk_1` FOREIGN KEY (`charts_types_id`) REFERENCES `charts_types` (`id`) ON DELETE CASCADE,
  CONSTRAINT `charts_config_ibfk_2` FOREIGN KEY (`charts_geometry_id`) REFERENCES `charts_geometry` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `charts_config`
--

LOCK TABLES `charts_config` WRITE;
/*!40000 ALTER TABLE `charts_config` DISABLE KEYS */;
/*!40000 ALTER TABLE `charts_config` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `charts_geometry`
--

DROP TABLE IF EXISTS `charts_geometry`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `charts_geometry` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `chart_geometry` varchar(32) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `chart_geometry` (`chart_geometry`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `charts_geometry`
--

LOCK TABLES `charts_geometry` WRITE;
/*!40000 ALTER TABLE `charts_geometry` DISABLE KEYS */;
INSERT INTO `charts_geometry` VALUES (1,'Line','Line Chart'),(2,'Spline','spLine Chart'),(3,'Area','Area Chart'),(4,'Stacked','Stacked Chart'),(5,'Pie','Pie Chart'),(6,'Bar','Bar Chart');
/*!40000 ALTER TABLE `charts_geometry` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `charts_types`
--

DROP TABLE IF EXISTS `charts_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `charts_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `chart_type` varchar(32) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `chart_type` (`chart_type`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `charts_types`
--

LOCK TABLES `charts_types` WRITE;
/*!40000 ALTER TABLE `charts_types` DISABLE KEYS */;
INSERT INTO `charts_types` VALUES (1,'TS','Time-Series - Listing/Result Chart'),(2,'REL','Relationship - Listing/Result Chart'),(3,'TOTALS','Totals - Listing/Result'),(4,'FOREIGN TS','Time-Series - View Entry Chart'),(5,'FOREIGN REL','Relationship - View Entry Chart'),(6,'FOREIGN TOTALS','Totals - View Entry Chat'),(7,'IMPORT','Imported Chart - Listing/Result Chart'),(8,'FOREIGN IMPORT','Imported Chart - View Entry Chart');
/*!40000 ALTER TABLE `charts_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `codes`
--

DROP TABLE IF EXISTS `codes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `codes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(32) NOT NULL,
  `codes_types_id` int(11) NOT NULL,
  `remaining` int(11) NOT NULL DEFAULT '0',
  `valid_from` datetime DEFAULT NULL,
  `valid_to` datetime DEFAULT NULL,
  `roles_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `codes_types_id` (`codes_types_id`),
  UNIQUE KEY `code` (`code`),
  CONSTRAINT `codes_ibfk_1` FOREIGN KEY (`codes_types_id`) REFERENCES `codes_types` (`id`) ON DELETE CASCADE,
  CONSTRAINT `codes_ibfk_2` FOREIGN KEY (`roles_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `codes`
--

LOCK TABLES `codes` WRITE;
/*!40000 ALTER TABLE `codes` DISABLE KEYS */;
/*!40000 ALTER TABLE `codes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `codes_types`
--

DROP TABLE IF EXISTS `codes_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `codes_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code_type` varchar(32) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code_type` (`code_type`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `codes_types`
--

LOCK TABLES `codes_types` WRITE;
/*!40000 ALTER TABLE `codes_types` DISABLE KEYS */;
INSERT INTO `codes_types` VALUES (1,'Registration','Registration Code');
/*!40000 ALTER TABLE `codes_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `configuration`
--

DROP TABLE IF EXISTS `configuration`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `configuration` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `configuration` varchar(32) NOT NULL DEFAULT 'default',
  `base_url` varchar(255) NOT NULL DEFAULT 'http://localhost/',
  `support_email` varchar(255) NOT NULL DEFAULT 'no-support@nd-php.org',
  `page_rows` int(11) DEFAULT NULL,
  `temporary_directory` varchar(255) DEFAULT NULL,
  `themes_id` int(11) NOT NULL DEFAULT '1',
  `timezones_id` int(11) NOT NULL DEFAULT '383',
  `roles_id` int(11) DEFAULT NULL,
  `maintenance` tinyint(1) DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL,
  `model` mediumtext,
  `_separator_project` tinyint(1) DEFAULT NULL,
  `project_name` varchar(32) NOT NULL DEFAULT 'ND php',
  `project_version` varchar(16) NOT NULL DEFAULT 'v0.01',
  `project_date` datetime NOT NULL DEFAULT '2016-01-01 00:00:00',
  `tagline` varchar(64) NOT NULL DEFAULT 'Framework',
  `description` varchar(512) NOT NULL DEFAULT 'An handy PHP Framework',
  `author` varchar(64) NOT NULL DEFAULT 'ND PHP Framework',
  `_separator_smtp` tinyint(1) DEFAULT NULL,
  `smtp_username` varchar(128) DEFAULT NULL,
  `smtp_password` varchar(128) DEFAULT NULL,
  `smtp_server` varchar(255) DEFAULT NULL,
  `smtp_port` int(11) DEFAULT '25',
  `smtp_ssl` tinyint(1) NOT NULL DEFAULT '0',
  `smtp_tls` tinyint(1) NOT NULL DEFAULT '0',
  `_separator_memcached` tinyint(1) DEFAULT NULL,
  `memcached_server` varchar(255) NOT NULL DEFAULT '127.0.0.1',
  `memcached_port` int(11) NOT NULL DEFAULT '11211',
  `_separator_recaptcha` tinyint(1) DEFAULT NULL,
  `recaptcha_priv_key` varchar(256) DEFAULT NULL,
  `recaptcha_pub_key` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `timezones_id` (`timezones_id`),
  KEY `themes_id` (`themes_id`),
  KEY `roles_id` (`roles_id`),
  CONSTRAINT `configuration_ibfk_1` FOREIGN KEY (`timezones_id`) REFERENCES `timezones` (`id`),
  CONSTRAINT `configuration_ibfk_2` FOREIGN KEY (`themes_id`) REFERENCES `themes` (`id`),
  CONSTRAINT `configuration_ibfk_3` FOREIGN KEY (`roles_id`) REFERENCES `roles` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `configuration`
--

LOCK TABLES `configuration` WRITE;
/*!40000 ALTER TABLE `configuration` DISABLE KEYS */;
/*!40000 ALTER TABLE `configuration` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `countries`
--

DROP TABLE IF EXISTS `countries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `countries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `country` varchar(36) NOT NULL,
  `code` varchar(3) NOT NULL,
  `prefix` varchar(8) NOT NULL,
  `eu_state` tinyint(1) NOT NULL DEFAULT '0',
  `vat_rate` decimal(10,6) NOT NULL DEFAULT '0.000000',
  `currencies_id` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `country` (`country`),
  UNIQUE KEY `code` (`code`),
  KEY `currencies_id` (`currencies_id`),
  CONSTRAINT `countries_ibfk_1` FOREIGN KEY (`currencies_id`) REFERENCES `currencies` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=243 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `countries`
--

LOCK TABLES `countries` WRITE;
/*!40000 ALTER TABLE `countries` DISABLE KEYS */;
INSERT INTO `countries` VALUES (1,'Portugal','PT','351',1,23.000000,1),(2,'United States of America','US','1',0,0.000000,1),(3,'Canada','CA','1',0,0.000000,1),(4,'Spain','ES','34',1,21.000000,1),(5,'France','FR','33',1,19.600000,1),(6,'Germany','DE','49',1,19.000000,1),(7,'United Kingdom','GB','44',1,20.000000,1),(8,'Netherlands','NL','31',1,21.000000,1),(9,'Switzerland','CH','41',0,0.000000,1),(10,'Italy','IT','39',1,21.000000,1),(11,'Brazil','BR','55',0,0.000000,1),(12,'Angola','AO','244',0,0.000000,1),(13,'United Arab Emirates','AE','971',0,0.000000,1),(14,'Mozambique','MZ','258',0,0.000000,1),(15,'Cape Verde','CV','238',0,0.000000,1),(16,'Croatia','HR','385',1,25.000000,1),(17,'Cyprus','CY','357',1,18.000000,1),(18,'Greece','GR','30',1,23.000000,1),(19,'Austria','AT','43',1,20.000000,1),(20,'Australia','AU','61',0,0.000000,1),(21,'Argentina','AR','54',0,0.000000,1),(22,'Algeria','DZ','213',0,0.000000,1),(23,'Egypt','EG','20',0,0.000000,1),(24,'Iceland','IS','354',0,0.000000,1),(25,'Japan','JP','81',0,0.000000,1),(26,'Russia','RU','7',0,0.000000,1),(27,'China','CN','86',0,0.000000,1),(28,'South Korea','KR','82',0,0.000000,1),(29,'Sri Lanka','LK','94',0,0.000000,1),(30,'Sweden','SE','46',1,25.000000,1),(31,'Norway','NO','47',0,0.000000,1),(32,'Finland','FI','358',1,24.000000,1),(33,'Ireland','IE','353',1,23.000000,1),(34,'Isle of Man','IM','441624',0,0.000000,1),(35,'Belgium','BE','32',1,21.000000,1),(36,'Czech Republic','CZ','420',1,21.000000,1),(37,'Denmark','DK','45',1,25.000000,1),(38,'Hungary','HU','36',1,27.000000,1),(39,'Luxembourg','LU','352',1,15.000000,1),(40,'Malta','MT','356',1,18.000000,1),(41,'Mexico','MX','52',0,0.000000,1),(42,'Morocco','MA','212',0,0.000000,1),(43,'New Zealand','NZ','64',0,0.000000,1),(44,'Nigeria','NG','234',0,0.000000,1),(45,'Poland','PL','48',1,23.000000,1),(46,'Taiwan','TW','886',0,0.000000,1),(47,'India','IN','91',0,0.000000,1),(48,'Indonesia','ID','62',0,0.000000,1),(49,'Bangladesh','BD','880',0,0.000000,1),(50,'Pakistan','PK','92',0,0.000000,1),(51,'Philippines','PH','63',0,0.000000,1),(52,'Vietnam','VN','84',0,0.000000,1),(53,'Ethiopia','ET','251',0,0.000000,1),(54,'Turkey','TR','90',0,0.000000,1),(56,'Democratic Republic of the Congo','CD','243',0,0.000000,1),(57,'Iran','IR','98',0,0.000000,1),(58,'Thailand','TH','66',0,0.000000,1),(59,'South Africa','ZA','27',0,0.000000,1),(60,'Burma','MM','95',0,0.000000,1),(61,'Ukraine','UA','380',0,0.000000,1),(62,'Colombia','CO','57',0,0.000000,1),(63,'Sudan','SD','249',0,0.000000,1),(64,'Tanzania','TZ','255',0,0.000000,1),(65,'Kenya','KE','254',0,0.000000,1),(66,'Uganda','UG','256',0,0.000000,1),(67,'Peru','PE','51',0,0.000000,1),(68,'Iraq','IQ','964',0,0.000000,1),(69,'Saudi Arabia','SA','966',0,0.000000,1),(70,'Nepal','NP','977',0,0.000000,1),(71,'Afghanistan','AF','93',0,0.000000,1),(72,'Uzbekistan','UZ','998',0,0.000000,1),(73,'Venezuela','VE','58',0,0.000000,1),(74,'Malaysia','MY','60',0,0.000000,1),(75,'Ghana','GH','233',0,0.000000,1),(76,'Yemen','YE','967',0,0.000000,1),(77,'North Korea','KP','850',0,0.000000,1),(78,'Romania','RO','40',1,24.000000,1),(79,'Madagascar','MG','261',0,0.000000,1),(80,'Ivory Coast','CI','225',0,0.000000,1),(81,'Syria','SY','963',0,0.000000,1),(82,'Cameroon','CM','237',0,0.000000,1),(83,'Chile','CL','56',0,0.000000,1),(84,'Burkina Faso','BF','226',0,0.000000,1),(85,'Kazakhstan','KZ','7',0,0.000000,1),(86,'Niger','NE','227',0,0.000000,1),(87,'Ecuador','EC','593',0,0.000000,1),(88,'Cambodia','KH','855',0,0.000000,1),(89,'Malawi','MW','265',0,0.000000,1),(90,'Senegal','SN','221',0,0.000000,1),(91,'Guatemala','GT','502',0,0.000000,1),(92,'Mali','ML','223',0,0.000000,1),(93,'Zambia','ZM','260',0,0.000000,1),(94,'Cuba','CU','53',0,0.000000,1),(95,'Zimbabwe','ZW','263',0,0.000000,1),(96,'Tunisia','TN','216',0,0.000000,1),(97,'Rwanda','RW','250',0,0.000000,1),(98,'Chad','TD','235',0,0.000000,1),(99,'Guinea','GN','224',0,0.000000,1),(100,'Somalia','SO','252',0,0.000000,1),(101,'Bolivia','BO','591',0,0.000000,1),(102,'Dominican Republic','DO','1809',0,0.000000,1),(103,'Belarus','BY','375',0,0.000000,1),(104,'Haiti','HT','509',0,0.000000,1),(105,'Burundi','BI','257',0,0.000000,1),(106,'Benin','BJ','229',0,0.000000,1),(107,'Azerbaijan','AZ','994',0,0.000000,1),(108,'Honduras','HN','504',0,0.000000,1),(109,'Serbia','RS','381',0,0.000000,1),(110,'Tajikistan','TJ','992',0,0.000000,1),(111,'Israel','IL','972',0,0.000000,1),(112,'Bulgaria','BG','359',1,20.000000,1),(113,'El Salvador','SV','503',0,0.000000,1),(114,'Hong Kong','HK','852',0,0.000000,1),(115,'Paraguay','PY','595',0,0.000000,1),(116,'Laos','LA','856',0,0.000000,1),(117,'Sierra Leone','SL','232',0,0.000000,1),(118,'Jordan','JO','962',0,0.000000,1),(119,'Libya','LY','218',0,0.000000,1),(120,'Papua New Guinea','PG','675',0,0.000000,1),(121,'Togo','TG','228',0,0.000000,1),(122,'Nicaragua','NI','505',0,0.000000,1),(123,'Eritrea','ER','291',0,0.000000,1),(124,'Slovakia','SK','421',1,20.000000,1),(125,'Kyrgyzstan','KG','996',0,0.000000,1),(126,'Turkmenistan','TM','993',0,0.000000,1),(127,'Singapore','SG','65',0,0.000000,1),(128,'Georgia','GE','995',0,0.000000,1),(129,'Bosnia Herzegovina','BA','387',0,0.000000,1),(130,'Central African Republic','CF','236',0,0.000000,1),(132,'Moldova','MD','373',0,0.000000,1),(133,'Costa Rica','CR','506',0,0.000000,1),(134,'Lebanon','LB','961',0,0.000000,1),(135,'Republic of the Congo','CG','242',0,0.000000,1),(136,'Puerto Rico','PR','1787',0,0.000000,1),(137,'Albania','AL','355',0,0.000000,1),(138,'Lithuania','LT','370',1,21.000000,1),(139,'Uruguay','UY','598',0,0.000000,1),(140,'Liberia','LR','231',0,0.000000,1),(141,'Oman','OM','968',0,0.000000,1),(142,'Panama','PA','507',0,0.000000,1),(143,'Mauritania','MR','222',0,0.000000,1),(144,'Mongolia','MN','976',0,0.000000,1),(145,'Armenia','AM','374',0,0.000000,1),(146,'Jamaica','JM','1876',0,0.000000,1),(147,'Kuwait','KW','965',0,0.000000,1),(149,'Latvia','LV','371',1,21.000000,1),(150,'Lesotho','LS','266',0,0.000000,1),(151,'Namibia','NA','264',0,0.000000,1),(152,'Macedonia','MK','389',0,0.000000,1),(153,'Slovenia','SI','386',1,22.000000,1),(154,'Botswana','BW','267',0,0.000000,1),(157,'Gambia','GM','220',0,0.000000,1),(158,'Gaza Strip','PS','970',0,0.000000,1),(159,'Guinea-Bissau','GW','245',0,0.000000,1),(160,'Gabon','GA','241',0,0.000000,1),(161,'Estonia','EE','372',1,20.000000,1),(162,'Mauritius','MU','230',0,0.000000,1),(163,'Trinidad and Tobago','TT','1868',0,0.000000,1),(164,'Timor-Leste','TL','670',0,0.000000,1),(165,'Swaziland','SZ','268',0,0.000000,1),(166,'Fiji','FJ','679',0,0.000000,1),(167,'Qatar','QA','974',0,0.000000,1),(169,'Guyana','GY','592',0,0.000000,1),(170,'Comoros','KM','269',0,0.000000,1),(171,'Bahrain','BH','973',0,0.000000,1),(172,'Bhutan','BT','975',0,0.000000,1),(173,'Montenegro','ME','382',0,0.000000,1),(174,'Equatorial Guinea','GQ','240',0,0.000000,1),(175,'Solomon Islands','SB','677',0,0.000000,1),(176,'Macau','MO','853',0,0.000000,1),(177,'Djibouti','DJ','253',0,0.000000,1),(178,'Suriname','SR','597',0,0.000000,1),(179,'Western Sahara','EH','212',0,0.000000,1),(180,'Maldives','MV','960',0,0.000000,1),(181,'Brunei','BN','673',0,0.000000,1),(182,'Bahamas','BS','1242',0,0.000000,1),(183,'Belize','BZ','501',0,0.000000,1),(184,'French Polynesia','PF','689',0,0.000000,1),(185,'Barbados','BB','1246',0,0.000000,1),(186,'New Caledonia','NC','687',0,0.000000,1),(187,'Netherlands Antilles','AN','599',0,0.000000,1),(188,'Mayotte','YT','262',0,0.000000,1),(189,'Samoa','WS','685',0,0.000000,1),(190,'Vanuatu','VU','678',0,0.000000,1),(191,'Sao Tome and Principe','ST','239',0,0.000000,1),(192,'Guam','GU','1671',0,0.000000,1),(193,'Saint Lucia','LC','1758',0,0.000000,1),(194,'Tonga','TO','676',0,0.000000,1),(195,'Kiribati','KI','686',0,0.000000,1),(196,'US Virgin Islands','VI','1340',0,0.000000,1),(197,'Micronesia','FM','691',0,0.000000,1),(198,'Saint Vincent and the Grenadines','VC','1784',0,0.000000,1),(199,'Aruba','AW','297',0,0.000000,1),(200,'Jersey','JE','441534',0,0.000000,1),(201,'Grenada','GD','1473',0,0.000000,1),(202,'Northern Mariana Islands','MP','1670',0,0.000000,1),(203,'Seychelles','SC','248',0,0.000000,1),(204,'Antigua and Barbuda','AG','1268',0,0.000000,1),(205,'Andorra','AD','376',0,0.000000,1),(207,'Dominica','DM','1767',0,0.000000,1),(208,'Bermuda','BM','1441',0,0.000000,1),(209,'American Samoa','AS','1684',0,0.000000,1),(210,'Marshall Islands','MH','692',0,0.000000,1),(211,'Greenland','GL','299',0,0.000000,1),(212,'Cayman Islands','KY','1345',0,0.000000,1),(213,'Faroe Islands','FO','298',0,0.000000,1),(214,'Saint Kitts and Nevis','KN','1869',0,0.000000,1),(215,'Liechtenstein','LI','423',0,0.000000,1),(216,'Monaco','MC','377',0,0.000000,1),(217,'San Marino','SM','378',0,0.000000,1),(218,'Saint Martin','MF','1599',0,0.000000,1),(219,'Gibraltar','GI','350',0,0.000000,1),(220,'British Virgin Islands','VG','1284',0,0.000000,1),(221,'Turks and Caicos Islands','TC','1649',0,0.000000,1),(222,'Palau','PW','680',0,0.000000,1),(223,'Wallis and Futuna','WF','681',0,0.000000,1),(224,'Anguilla','AI','1264',0,0.000000,1),(225,'Nauru','NR','674',0,0.000000,1),(226,'Tuvalu','TV','688',0,0.000000,1),(227,'Cook Islands','CK','682',0,0.000000,1),(228,'Saint Helena','SH','290',0,0.000000,1),(229,'Saint Barthelemy','BL','590',0,0.000000,1),(230,'Saint Pierre and Miquelon','PM','508',0,0.000000,1),(231,'Montserrat','MS','1664',0,0.000000,1),(232,'Falkland Islands','FK','500',0,0.000000,1),(234,'Norfolk Island','NF','6723',0,0.000000,1),(235,'Svalbard','SJ','47',0,0.000000,1),(236,'Tokelau','TK','690',0,0.000000,1),(237,'Christmas Island','CX','672',0,0.000000,1),(238,'Niue','NU','683',0,0.000000,1),(239,'Holy See','VA','39',0,0.000000,1),(240,'Cocos Islands','CC','672',0,0.000000,1),(241,'Pitcairn Islands','PN','870',0,0.000000,1),(242,'(None)','-','-',0,0.000000,1);
/*!40000 ALTER TABLE `countries` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `currencies`
--

DROP TABLE IF EXISTS `currencies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `currencies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `currency` varchar(128) NOT NULL,
  `code` varchar(3) NOT NULL,
  `sign` varchar(3) NOT NULL,
  `sign_position` varchar(6) NOT NULL DEFAULT 'R',
  `rate` decimal(16,6) NOT NULL,
  `updated` datetime NOT NULL,
  `default` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `currency` (`currency`),
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=159 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `currencies`
--

LOCK TABLES `currencies` WRITE;
/*!40000 ALTER TABLE `currencies` DISABLE KEYS */;
INSERT INTO `currencies` VALUES (1,'United States Dollar','USD','$','L',1.000000,'2016-01-01 00:00:00',1),(2,'Euro','EUR','€','R',1.000000,'2016-01-01 00:00:00',0),(3,'British Pound','GBP','£','L',1.000000,'2016-01-01 00:00:00',0),(4,'Singapore dollar','SGD','$','L',1.000000,'2016-01-01 00:00:00',0),(5,'Thai baht','THB','฿','L',1.000000,'2016-01-01 00:00:00',0),(6,'Chinese yuan','CNY','¥','L',1.000000,'2016-01-01 00:00:00',0),(7,'Vietnamese dong','VND','₫','R',1.000000,'2016-01-01 00:00:00',0),(8,'Australian Dollar','AUD','$','L',1.000000,'2016-01-01 00:00:00',0),(9,'Canadian Dollar','CAD','$','L',1.000000,'2016-01-01 00:00:00',0),(10,'Malaysian ringgit','MYR','RM','L',1.000000,'2016-01-01 00:00:00',0),(11,'Brunei Dollar','BND','$','L',1.000000,'2016-01-01 00:00:00',0),(12,'Hong Kong Dollar','HKD','$','L',1.000000,'2016-01-01 00:00:00',0),(13,'New Taiwan Dollar','TWD','$','L',1.000000,'2016-01-01 00:00:00',0),(14,'Philippine peso','PHP','₱','L',1.000000,'2016-01-01 00:00:00',0),(15,'Indonesian rupiah','IDR','Rp','L',1.000000,'2016-01-01 00:00:00',0),(16,'New Zealand Dollar','NZD','$','L',1.000000,'2016-01-01 00:00:00',0),(17,'Indian rupee','INR','₹','L',1.000000,'2016-01-01 00:00:00',0),(18,'Swiss franc','CHF','CHF','R',1.000000,'2016-01-01 00:00:00',0),(19,'Swedish krona','SEK','kr','R',1.000000,'2016-01-01 00:00:00',0),(20,'Brazilian real','BRL','R$','L',1.000000,'2016-01-01 00:00:00',0),(21,'United Arab Emirates dirham','AED','ﺩ.ﺇ','R',1.000000,'2016-01-01 00:00:00',0),(22,'Afghan afghani','AFN','؋','R',1.000000,'2016-01-01 00:00:00',0),(23,'Albanian lek','ALL','L','R',1.000000,'2016-01-01 00:00:00',0),(24,'Armenian dram','AMD','AMD','R',1.000000,'2016-01-01 00:00:00',0),(25,'Netherlands Antillean guilder','ANG','ƒ','L',1.000000,'2016-01-01 00:00:00',0),(26,'Angolan kwanza','AOA','Kz','R',1.000000,'2016-01-01 00:00:00',0),(27,'Argentine peso','ARS','$','L',1.000000,'2016-01-01 00:00:00',0),(28,'Aruban florin','AWG','ƒ','L',1.000000,'2016-01-01 00:00:00',0),(29,'Azerbaijani manat','AZN','AZN','R',1.000000,'2016-01-01 00:00:00',0),(30,'Bosnia and Herzegovina convertible mark','BAM','KM','R',1.000000,'2016-01-01 00:00:00',0),(31,'Barbadian Dollar','BBD','$','L',1.000000,'2016-01-01 00:00:00',0),(32,'Bangladeshi taka','BDT','৳','L',1.000000,'2016-01-01 00:00:00',0),(33,'Bulgarian lev','BGN','лв','R',1.000000,'2016-01-01 00:00:00',0),(34,'Bahraini dinar','BHD','BHD','R',1.000000,'2016-01-01 00:00:00',0),(35,'Burundian franc','BIF','Fr','R',1.000000,'2016-01-01 00:00:00',0),(36,'Bermudian dollar','BMD','$','L',1.000000,'2016-01-01 00:00:00',0),(37,'Bolivian boliviano','BOB','Bs.','L',1.000000,'2016-01-01 00:00:00',0),(38,'Bahamian Dollar','BSD','$','L',1.000000,'2016-01-01 00:00:00',0),(39,'Bhutanese ngultrum','BTN','Nu.','L',1.000000,'2016-01-01 00:00:00',0),(40,'Botswana pula','BWP','P','R',1.000000,'2016-01-01 00:00:00',0),(41,'Old Belarusian ruble','BYN','Br','R',1.000000,'2016-01-01 00:00:00',0),(42,'New Belarusian ruble','BYR','Br','R',1.000000,'2016-01-01 00:00:00',0),(43,'Belize dollar','BZD','$','L',1.000000,'2016-01-01 00:00:00',0),(44,'Congolese franc','CDF','Fr','R',1.000000,'2016-01-01 00:00:00',0),(45,'Chilean peso','CLP','$','L',1.000000,'2016-01-01 00:00:00',0),(46,'Colombian peso','COP','$','L',1.000000,'2016-01-01 00:00:00',0),(47,'Costa Rican colón','CRC','₡','L',1.000000,'2016-01-01 00:00:00',0),(48,'Cuban convertible peso','CUC','$','L',1.000000,'2016-01-01 00:00:00',0),(49,'Cuban peso','CUP','$','L',1.000000,'2016-01-01 00:00:00',0),(50,'Cape Verdean escudo','CVE','$','D',1.000000,'2016-01-01 00:00:00',0),(51,'Czech koruna','CZK','Kč','R',1.000000,'2016-01-01 00:00:00',0),(52,'Djiboutian franc','DJF','Fr','R',1.000000,'2016-01-01 00:00:00',0),(53,'Danish krone','DKK','kr','R',1.000000,'2016-01-01 00:00:00',0),(54,'Dominican peso','DOP','$','L',1.000000,'2016-01-01 00:00:00',0),(55,'Algerian dinar','DZD','ﺩ.ﺝ','R',1.000000,'2016-01-01 00:00:00',0),(56,'Egyptian pound','EGP','ﺝ.ﻡ','R',1.000000,'2016-01-01 00:00:00',0),(57,'Eritrean nakfa','ERN','Nfk','R',1.000000,'2016-01-01 00:00:00',0),(58,'Ethiopian birr','ETB','Br','R',1.000000,'2016-01-01 00:00:00',0),(59,'Fijian dollar','FJD','$','L',1.000000,'2016-01-01 00:00:00',0),(60,'Falkland Islands pound','FKP','£','L',1.000000,'2016-01-01 00:00:00',0),(61,'Georgian lari','GEL','₾','R',1.000000,'2016-01-01 00:00:00',0),(62,'Guernsey pound','GGP','£','L',1.000000,'2016-01-01 00:00:00',0),(63,'Ghanaian cedi','GHS','GH₵','L',1.000000,'2016-01-01 00:00:00',0),(64,'Gibraltar pound','GIP','£','L',1.000000,'2016-01-01 00:00:00',0),(65,'Gambian dalasi','GMD','D','R',1.000000,'2016-01-01 00:00:00',0),(66,'Guinean franc','GNF','Fr','R',1.000000,'2016-01-01 00:00:00',0),(67,'Guatemalan quetzal','GTQ','Q','R',1.000000,'2016-01-01 00:00:00',0),(68,'Guyanese dollar','GYD','$','L',1.000000,'2016-01-01 00:00:00',0),(69,'Honduran lempira','HNL','L','L',1.000000,'2016-01-01 00:00:00',0),(70,'Croatian kuna','HRK','kn','R',1.000000,'2016-01-01 00:00:00',0),(71,'Haitian gourde','HTG','G','R',1.000000,'2016-01-01 00:00:00',0),(72,'Hungarian forint','HUF','Ft','R',1.000000,'2016-01-01 00:00:00',0),(73,'Israeli new shekel','ILS','₪','L',1.000000,'2016-01-01 00:00:00',0),(74,'Manx pound','IMP','£','L',1.000000,'2016-01-01 00:00:00',0),(75,'Iraqi dinar','IQD','ﻉ.ﺩ','R',1.000000,'2016-01-01 00:00:00',0),(76,'Iranian rial','IRR','﷼','R',1.000000,'2016-01-01 00:00:00',0),(77,'Icelandic króna','ISK','kr','R',1.000000,'2016-01-01 00:00:00',0),(78,'Jersey pound','JEP','£','L',1.000000,'2016-01-01 00:00:00',0),(79,'Jamaican dollar','JMD','$','L',1.000000,'2016-01-01 00:00:00',0),(80,'Jordanian dinar','JOD','ﺩ.ﺍ','R',1.000000,'2016-01-01 00:00:00',0),(81,'Japanese yen','JPY','¥','R',1.000000,'2016-01-01 00:00:00',0),(82,'Kenyan shilling','KES','Sh','R',1.000000,'2016-01-01 00:00:00',0),(83,'Kyrgyzstani som','KGS','лв','R',1.000000,'2016-01-01 00:00:00',0),(84,'Cambodian riel','KHR','៛','R',1.000000,'2016-01-01 00:00:00',0),(85,'Comorian franc','KMF','Fr','R',1.000000,'2016-01-01 00:00:00',0),(86,'North Korean won','KPW','₩','L',1.000000,'2016-01-01 00:00:00',0),(87,'South Korean won','KRW','₩','L',1.000000,'2016-01-01 00:00:00',0),(88,'Kuwaiti dinar','KWD','ﺩ.ﻙ','R',1.000000,'2016-01-01 00:00:00',0),(89,'Cayman Islands dollar','KYD','$','L',1.000000,'2016-01-01 00:00:00',0),(90,'Kazakhstani tenge','KZT','KZT','R',1.000000,'2016-01-01 00:00:00',0),(91,'Lao kip','LAK','₭','R',1.000000,'2016-01-01 00:00:00',0),(92,'Lebanese pound','LBP','ﻝ.ﻝ','R',1.000000,'2016-01-01 00:00:00',0),(93,'Sri Lankan rupee','LKR','රු','L',1.000000,'2016-01-01 00:00:00',0),(94,'Liberian dollar','LRD','$','L',1.000000,'2016-01-01 00:00:00',0),(95,'Lesotho loti','LSL','L','L',1.000000,'2016-01-01 00:00:00',0),(96,'Libyan dinar','LYD','ﻝ.ﺩ','R',1.000000,'2016-01-01 00:00:00',0),(97,'Moroccan dirham','MAD','MAD','R',1.000000,'2016-01-01 00:00:00',0),(98,'Moldovan leu','MDL','L','R',1.000000,'2016-01-01 00:00:00',0),(99,'Malagasy ariary','MGA','Ar','L',1.000000,'2016-01-01 00:00:00',0),(100,'Macedonian denar','MKD','ден','R',1.000000,'2016-01-01 00:00:00',0),(101,'Burmese kyat','MMK','K','L',1.000000,'2016-01-01 00:00:00',0),(102,'Mongolian tögrög','MNT','₮','R',1.000000,'2016-01-01 00:00:00',0),(103,'Macanese pataca','MOP','MOP','L',1.000000,'2016-01-01 00:00:00',0),(104,'Mauritanian ouguiya','MRO','UM','R',1.000000,'2016-01-01 00:00:00',0),(105,'Mauritian rupee','MUR','Rs','L',1.000000,'2016-01-01 00:00:00',0),(106,'Maldivian rufiyaa','MVR','.ރ','L',1.000000,'2016-01-01 00:00:00',0),(107,'Malawian kwacha','MWK','MK','R',1.000000,'2016-01-01 00:00:00',0),(108,'Mexican peso','MXN','$','L',1.000000,'2016-01-01 00:00:00',0),(109,'Mozambican metical','MZN','MT','R',1.000000,'2016-01-01 00:00:00',0),(110,'Namibian dollar','NAD','$','L',1.000000,'2016-01-01 00:00:00',0),(111,'Nigerian naira','NGN','₦','L',1.000000,'2016-01-01 00:00:00',0),(112,'Nicaraguan córdoba','NIO','C$','L',1.000000,'2016-01-01 00:00:00',0),(113,'Norwegian krone','NOK','kr','R',1.000000,'2016-01-01 00:00:00',0),(114,'Nepalese rupee','NPR','रू','L',1.000000,'2016-01-01 00:00:00',0),(115,'Omani rial','OMR','OMR','R',1.000000,'2016-01-01 00:00:00',0),(116,'Panamanian balboa','PAB','B/.','R',1.000000,'2016-01-01 00:00:00',0),(117,'Peruvian sol','PEN','S/.','R',1.000000,'2016-01-01 00:00:00',0),(118,'Papua New Guinean kina','PGK','K','R',1.000000,'2016-01-01 00:00:00',0),(119,'Pakistani rupee','PKR','₨','L',1.000000,'2016-01-01 00:00:00',0),(120,'Polish złoty','PLN','zł','R',1.000000,'2016-01-01 00:00:00',0),(121,'Paraguayan guaraní','PYG','₲','R',1.000000,'2016-01-01 00:00:00',0),(122,'Qatari riyal','QAR','ﺭ.ﻕ','R',1.000000,'2016-01-01 00:00:00',0),(123,'Romanian leu','RON','lei','R',1.000000,'2016-01-01 00:00:00',0),(124,'Serbian dinar','RSD','дин','R',1.000000,'2016-01-01 00:00:00',0),(125,'Russian ruble','RUB','₽','R',1.000000,'2016-01-01 00:00:00',0),(126,'Rwandan franc','RWF','Fr','R',1.000000,'2016-01-01 00:00:00',0),(127,'Saudi riyal','SAR','ﺭ.ﺱ','R',1.000000,'2016-01-01 00:00:00',0),(128,'Solomon Islands dollar','SBD','$','L',1.000000,'2016-01-01 00:00:00',0),(129,'Seychellois rupee','SCR','SRe','R',1.000000,'2016-01-01 00:00:00',0),(130,'Sudanese pound','SDG','SDG','R',1.000000,'2016-01-01 00:00:00',0),(131,'Saint Helena pound','SHP','£','L',1.000000,'2016-01-01 00:00:00',0),(132,'Sierra Leonean leone','SLL','Le','R',1.000000,'2016-01-01 00:00:00',0),(133,'Somali shilling','SOS','Sh','R',1.000000,'2016-01-01 00:00:00',0),(134,'Surinamese dollar','SRD','$','L',1.000000,'2016-01-01 00:00:00',0),(135,'São Tomé and Príncipe dobra','STD','Db','R',1.000000,'2016-01-01 00:00:00',0),(136,'Syrian pound','SYP','£','L',1.000000,'2016-01-01 00:00:00',0),(137,'Swazi lilangeni','SZL','E','L',1.000000,'2016-01-01 00:00:00',0),(138,'Tajikistani somoni','TJS','SM','R',1.000000,'2016-01-01 00:00:00',0),(139,'Turkmenistan manat','TMT','m','R',1.000000,'2016-01-01 00:00:00',0),(140,'Tunisian dinar','TND','ﺩ.ﺕ','R',1.000000,'2016-01-01 00:00:00',0),(141,'Tongan paʻanga','TOP','T$','L',1.000000,'2016-01-01 00:00:00',0),(142,'Turkish lira','TRY','₺','L',1.000000,'2016-01-01 00:00:00',0),(143,'Trinidad and Tobago dollar','TTD','$','L',1.000000,'2016-01-01 00:00:00',0),(144,'Tanzanian shilling','TZS','TSh','R',1.000000,'2016-01-01 00:00:00',0),(145,'Ukrainian hryvnia','UAH','₴','L',1.000000,'2016-01-01 00:00:00',0),(146,'Ugandan shilling','UGX','Sh','R',1.000000,'2016-01-01 00:00:00',0),(147,'Uruguayan peso','UYU','$','L',1.000000,'2016-01-01 00:00:00',0),(148,'Uzbekistani som','UZS','UZS','R',1.000000,'2016-01-01 00:00:00',0),(149,'Venezuelan bolívar','VEF','VEF','R',1.000000,'2016-01-01 00:00:00',0),(150,'Vanuatu vatu','VUV','VT','R',1.000000,'2016-01-01 00:00:00',0),(151,'Samoan tālā','WST','WS$','L',1.000000,'2016-01-01 00:00:00',0),(152,'Central African CFA franc','XAF','Fr','R',1.000000,'2016-01-01 00:00:00',0),(153,'East Caribbean dollar','XCD','$','L',1.000000,'2016-01-01 00:00:00',0),(154,'West African CFA franc','XOF','Fr','R',1.000000,'2016-01-01 00:00:00',0),(155,'CFP franc','XPF','Fr','R',1.000000,'2016-01-01 00:00:00',0),(156,'Yemeni rial','YER','﷼','R',1.000000,'2016-01-01 00:00:00',0),(157,'South African rand','ZAR','R','L',1.000000,'2016-01-01 00:00:00',0),(158,'Zambian kwacha','ZMW','ZK','R',1.000000,'2016-01-01 00:00:00',0);
/*!40000 ALTER TABLE `currencies` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dbms`
--

DROP TABLE IF EXISTS `dbms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dbms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `alias` varchar(64) NOT NULL,
  `name` varchar(64) NOT NULL,
  `host` varchar(255) NOT NULL DEFAULT '127.0.0.1',
  `port` int(11) NOT NULL DEFAULT '3306',
  `username` varchar(128) NOT NULL,
  `password` varchar(128) NOT NULL,
  `charset` varchar(32) DEFAULT 'utf8',
  `persistent` tinyint(1) DEFAULT '1',
  `strict` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `alias` (`alias`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dbms`
--

LOCK TABLES `dbms` WRITE;
/*!40000 ALTER TABLE `dbms` DISABLE KEYS */;
INSERT INTO `dbms` VALUES (1,'default','ndphp','127.0.0.1',3306,'ndphp_username','ndphp_password','utf8',1,1);
/*!40000 ALTER TABLE `dbms` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `documentation`
--

DROP TABLE IF EXISTS `documentation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `documentation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `revision` varchar(32) NOT NULL,
  `changed` datetime DEFAULT NULL,
  `description` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `revision` (`revision`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `documentation`
--

LOCK TABLES `documentation` WRITE;
/*!40000 ALTER TABLE `documentation` DISABLE KEYS */;
INSERT INTO `documentation` VALUES (1,'rev-0.1','2016-04-09 01:21:23',NULL);
/*!40000 ALTER TABLE `documentation` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `features`
--

DROP TABLE IF EXISTS `features`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `features` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `feature` varchar(64) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `features`
--

LOCK TABLES `features` WRITE;
/*!40000 ALTER TABLE `features` DISABLE KEYS */;
INSERT INTO `features` VALUES (1,'FEATURE_ACCESSIBILITY','Enable accessibility support.'),(2,'FEATURE_MULTI_USER','Multi-user support.'),(3,'FEATURE_USER_SUBSCRIPTIONS','Enable user subscription types.'),(4,'FEATURE_USER_CREDIT','Enable user credit control.'),(5,'FEATURE_USER_NOTIFICATIONS','Enable user notifications support.'),(6,'FEATURE_USER_REGISTRATION','Enable user registration support.'),(7,'FEATURE_USER_RECOVERY','Enable user credential recovery support.'),(8,'FEATURE_REGISTER_CONFIRM_VAT_EU','Confirm EU VAT on user registration.'),(9,'FEATURE_REGISTER_RECAPTCHA','Enable reCAPTCHA support on user registration.'),(10,'FEATURE_REGISTER_CONFIRM_EMAIL','Confirm email address on user registration (sends a confirmation email).'),(11,'FEATURE_REGISTER_CONFIRM_PHONE','Confirm the phone number on user registration (sends a confirmation sms).'),(12,'FEATURE_SYSTEM_MEMCACHED','Enable support for memcached.'),(13,'FEATURE_SYSTEM_SHARDING','Enable database sharding support.');
/*!40000 ALTER TABLE `features` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `genders`
--

DROP TABLE IF EXISTS `genders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `genders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gender` varchar(24) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `gender` (`gender`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `genders`
--

LOCK TABLES `genders` WRITE;
/*!40000 ALTER TABLE `genders` DISABLE KEYS */;
INSERT INTO `genders` VALUES (1,'Female'),(2,'Male'),(3,'Unspecified');
/*!40000 ALTER TABLE `genders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `items`
--

DROP TABLE IF EXISTS `items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item` varchar(30) NOT NULL,
  `description` varchar(60) NOT NULL,
  `price` decimal(7,4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `items`
--

LOCK TABLES `items` WRITE;
/*!40000 ALTER TABLE `items` DISABLE KEYS */;
INSERT INTO `items` VALUES (1,'Paypal Credit','Paypal Credit',1.0000);
/*!40000 ALTER TABLE `items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `logging`
--

DROP TABLE IF EXISTS `logging`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `logging` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `operation` varchar(16) NOT NULL,
  `_table` varchar(64) NOT NULL,
  `_field` varchar(64) DEFAULT NULL,
  `entryid` varchar(64) DEFAULT NULL,
  `value_old` text DEFAULT NULL,
  `value_new` text DEFAULT NULL,
  `transaction` varchar(40) NOT NULL,
  `registered` datetime NOT NULL,
  `rolled_back` tinyint(1) DEFAULT '0',
  `sessions_id` int(11) DEFAULT NULL,
  `users_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `users_id` (`users_id`),
  CONSTRAINT `logging_ibfk_1` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `logging`
--

LOCK TABLES `logging` WRITE;
/*!40000 ALTER TABLE `logging` DISABLE KEYS */;
/*!40000 ALTER TABLE `logging` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `model_objects`
--

DROP TABLE IF EXISTS `model_objects`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `model_objects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object` varchar(255) NOT NULL,
  `db_table` varchar(64) DEFAULT NULL,
  `db_table_field` varchar(64) DEFAULT NULL,
  `is_table` tinyint(1) DEFAULT '0',
  `is_field` tinyint(1) DEFAULT '0',
  `type` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `object` (`object`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `model_objects`
--

LOCK TABLES `model_objects` WRITE;
/*!40000 ALTER TABLE `model_objects` DISABLE KEYS */;
/*!40000 ALTER TABLE `model_objects` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `months`
--

DROP TABLE IF EXISTS `months`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `months` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `month` varchar(32) NOT NULL,
  `number` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `months`
--

LOCK TABLES `months` WRITE;
/*!40000 ALTER TABLE `months` DISABLE KEYS */;
INSERT INTO `months` VALUES (1,'January',1),(2,'February',2),(3,'March',3),(4,'April',4),(5,'May',5),(6,'June',6),(7,'July',7),(8,'August',8),(9,'September',9),(10,'October',10),(11,'November',11),(12,'December',12);
/*!40000 ALTER TABLE `months` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `months`
--

DROP TABLE IF EXISTS `notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `notifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `notification` varchar(255) NOT NULL,
  `description` varchar(512) NOT NULL,
  `url` varchar(2048) DEFAULT NULL,
  `seen` tinyint(1) DEFAULT '0',
  `all` tinyint(1) DEFAULT '0',
  `when` datetime NOT NULL,
  `users_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `users_id` (`users_id`),
  CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notifications`
--

LOCK TABLES `notifications` WRITE;
/*!40000 ALTER TABLE `notifications` DISABLE KEYS */;
/*!40000 ALTER TABLE `notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payment_actions`
--

DROP TABLE IF EXISTS `payment_actions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `payment_actions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `payment_action` varchar(16) NOT NULL,
  `description` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payment_actions`
--

LOCK TABLES `payment_actions` WRITE;
/*!40000 ALTER TABLE `payment_actions` DISABLE KEYS */;
INSERT INTO `payment_actions` VALUES (1,'Pending','Post-payment action pending'),(2,'Processed','Post-payment action processed'),(3,'Failed','Post-payment action failed');
/*!40000 ALTER TABLE `payment_actions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payment_status`
--

DROP TABLE IF EXISTS `payment_status`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `payment_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `payment_status` varchar(16) NOT NULL,
  `description` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payment_status`
--

LOCK TABLES `payment_status` WRITE;
/*!40000 ALTER TABLE `payment_status` DISABLE KEYS */;
INSERT INTO `payment_status` VALUES (1,'Pending','Payment pending'),(2,'Success','Payment successful'),(3,'Failed','Payment failed'),(4,'Fraudulent','Fraudulent payment'),(5,'Invalid','Invalid payment');
/*!40000 ALTER TABLE `payment_status` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payment_types`
--

DROP TABLE IF EXISTS `payment_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `payment_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `payment_type` varchar(32) NOT NULL,
  `transaction_fee_percentage` decimal(10,6) NOT NULL DEFAULT '0.000000',
  `transaction_fee_fixed` decimal(10,6) NOT NULL DEFAULT '0.000000',
  `transaction_min_amount` decimal(10,6) NOT NULL DEFAULT '10.000000',
  `transaction_max_amount` decimal(10,6) NOT NULL DEFAULT '0.000000',
  `description` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `payment_type` (`payment_type`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payment_types`
--

LOCK TABLES `payment_types` WRITE;
/*!40000 ALTER TABLE `payment_types` DISABLE KEYS */;
INSERT INTO `payment_types` VALUES (1,'Paypal',3.400000,0.350000,10.000000,2500.000000,'Paypal Payment');
/*!40000 ALTER TABLE `payment_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payments`
--

DROP TABLE IF EXISTS `payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `payments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `txnid` varchar(20) NOT NULL,
  `payment_types_id` int(11) NOT NULL,
  `amount` decimal(10,6) NOT NULL,
  `tax_rate` decimal(10,6) DEFAULT '0.000000',
  `payment_fee` decimal(10,6) DEFAULT '0.000000',
  `total_tax` decimal(10,6) DEFAULT '0.000000',
  `payment_status_id` int(11) NOT NULL DEFAULT '1',
  `status_desc` varchar(30) DEFAULT NULL,
  `items_id` int(11) NOT NULL,
  `item_price` decimal(10,6) DEFAULT '0.000000',
  `item_quantity` int(11) NOT NULL DEFAULT '1',
  `item_description` varchar(60) DEFAULT NULL,
  `created` datetime NOT NULL,
  `updated` datetime DEFAULT NULL,
  `users_id` int(11) NOT NULL,
  `payment_actions_id` int(11) NOT NULL DEFAULT '1',
  `payer_email` varchar(256) DEFAULT NULL,
  `payer_first_name` varchar(64) DEFAULT NULL,
  `payer_last_name` varchar(64) DEFAULT NULL,
  `payer_address_name` varchar(128) DEFAULT NULL,
  `payer_address_country` varchar(32) DEFAULT NULL,
  `payer_address_city` varchar(64) DEFAULT NULL,
  `payer_address_street` varchar(256) DEFAULT NULL,
  `payer_address_zip` varchar(32) DEFAULT NULL,
  `payer_address_state` varchar(32) DEFAULT NULL,
  `payer_address_status` varchar(16) DEFAULT NULL,
  `payer_status` varchar(16) DEFAULT NULL,
  `payer_residence_country` varchar(32) DEFAULT NULL,
  `payer_payment_date` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `payment_types_id` (`payment_types_id`),
  KEY `payment_status_id` (`payment_status_id`),
  KEY `payment_actions_id` (`payment_actions_id`),
  KEY `users_id` (`users_id`),
  CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`payment_types_id`) REFERENCES `payment_types` (`id`) ON DELETE CASCADE,
  CONSTRAINT `payments_ibfk_2` FOREIGN KEY (`payment_status_id`) REFERENCES `payment_status` (`id`) ON DELETE CASCADE,
  CONSTRAINT `payments_ibfk_3` FOREIGN KEY (`payment_actions_id`) REFERENCES `payment_actions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `payments_ibfk_4` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payments`
--

LOCK TABLES `payments` WRITE;
/*!40000 ALTER TABLE `payments` DISABLE KEYS */;
/*!40000 ALTER TABLE `payments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rel_configuration_features`
--

DROP TABLE IF EXISTS `rel_configuration_features`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rel_configuration_features` (
  `configuration_id` int(11) NOT NULL,
  `features_id` int(11) NOT NULL,
  KEY `configuration_id` (`configuration_id`),
  KEY `features_id` (`features_id`),
  CONSTRAINT `rel_configuration_features_ibfk_1` FOREIGN KEY (`configuration_id`) REFERENCES `configuration` (`id`) ON DELETE CASCADE,
  CONSTRAINT `rel_configuration_features_ibfk_2` FOREIGN KEY (`features_id`) REFERENCES `features` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rel_configuration_features`
--

LOCK TABLES `rel_configuration_features` WRITE;
/*!40000 ALTER TABLE `rel_configuration_features` DISABLE KEYS */;
/*!40000 ALTER TABLE `rel_configuration_features` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rel_users_roles`
--

DROP TABLE IF EXISTS `rel_users_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rel_users_roles` (
  `users_id` int(11) NOT NULL,
  `roles_id` int(11) NOT NULL,
  KEY `users_id` (`users_id`),
  KEY `roles_id` (`roles_id`),
  CONSTRAINT `rel_users_roles_ibfk_1` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `rel_users_roles_ibfk_2` FOREIGN KEY (`roles_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rel_users_roles`
--

LOCK TABLES `rel_users_roles` WRITE;
/*!40000 ALTER TABLE `rel_users_roles` DISABLE KEYS */;
INSERT INTO `rel_users_roles` VALUES (1,1);
/*!40000 ALTER TABLE `rel_users_roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role` varchar(32) NOT NULL,
  `description` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'ROLE_ADMIN','Administration Role'),(2,'ROLE_USER_RO','Read-Only'),(3,'ROLE_CUSTOM','Custom Role'),(4,'ROLE_REGULAR','Regular User Role');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `scheduler`
--

DROP TABLE IF EXISTS `scheduler`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `scheduler` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `entry_name` varchar(32) NOT NULL,
  `description` varchar(128) DEFAULT NULL,
  `url` varchar(2048) NOT NULL,
  `period` int(11) NOT NULL DEFAULT '86400',
  `active` tinyint(1) DEFAULT '1',
  `registered` datetime DEFAULT NULL,
  `last_run` datetime DEFAULT NULL,
  `next_run` datetime DEFAULT NULL,
  `output` varchar(1024) DEFAULT NULL,
  `queued` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `entry_name` (`entry_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `scheduler`
--

LOCK TABLES `scheduler` WRITE;
/*!40000 ALTER TABLE `scheduler` DISABLE KEYS */;
/*!40000 ALTER TABLE `scheduler` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sessions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `session` varchar(40) NOT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` varchar(1024) DEFAULT NULL,
  `start_time` datetime NOT NULL,
  `change_time` datetime NOT NULL,
  `end_time` datetime DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `valid` tinyint(1) DEFAULT 0,
  `data` varchar(4096) DEFAULT NULL,
  `users_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `session` (`session`),
  KEY `sessions_ibfk_1` (`users_id`),
  CONSTRAINT `sessions_ibfk_1` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES (0,'REST','-','-','2016-01-01 00:00:00','2016-01-01 00:00:00','2016-01-01 00:00:00','2016-01-01 00:00:00',0,'-',1);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `subscription_types`
--

DROP TABLE IF EXISTS `subscription_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `subscription_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `subscription_type` varchar(16) NOT NULL,
  `description` varchar(1024) DEFAULT NULL,
  `price` decimal(10,6) NOT NULL,
  `api_extended` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `subscription_types`
--

LOCK TABLES `subscription_types` WRITE;
/*!40000 ALTER TABLE `subscription_types` DISABLE KEYS */;
INSERT INTO `subscription_types` VALUES (1,'Standard','The Standard Subscription Plan - Free',0.000000,0),(2,'Plus','The Plus Subscription Plan - From 9.90 EUR/month',9.000000,0),(3,'Business','The Business Subscription Plan - From 29.90 EUR/month',29.000000,1),(4,'Enterprise','The Enterprise Subscription Plan - From 99.90 EUR/month',99.000000,1);
/*!40000 ALTER TABLE `subscription_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `themes`
--

DROP TABLE IF EXISTS `themes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `themes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `theme` varchar(32) DEFAULT 'Theme Name',
  `description` varchar(255) NOT NULL DEFAULT 'Theme description...',
  `animation_default_delay` int(11) NOT NULL DEFAULT '800',
  `animation_ordering_delay` int(11) NOT NULL DEFAULT '600',
  `themes_animations_default_id` int(11) NOT NULL,
  `themes_animations_ordering_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `themes`
--

LOCK TABLES `themes` WRITE;
/*!40000 ALTER TABLE `themes` DISABLE KEYS */;
INSERT INTO `themes` VALUES (1,'Blueish','A blueish theme',800,600,1,1);
/*!40000 ALTER TABLE `themes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `themes_animations_default`
--

DROP TABLE IF EXISTS `themes_animations_default`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `themes_animations_default` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `animation` varchar(16) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `animation` (`animation`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `themes_animations_default`
--

LOCK TABLES `themes_animations_default` WRITE;
/*!40000 ALTER TABLE `themes_animations_default` DISABLE KEYS */;
INSERT INTO `themes_animations_default` VALUES (1,'None','No animation.'),(2,'Slide','Slide Up and Down.'),(3,'Fade','Fade In and Out.');
/*!40000 ALTER TABLE `themes_animations_default` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `themes_animations_ordering`
--

DROP TABLE IF EXISTS `themes_animations_ordering`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `themes_animations_ordering` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `animation` varchar(16) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `animation` (`animation`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `themes_animations_ordering`
--

LOCK TABLES `themes_animations_ordering` WRITE;
/*!40000 ALTER TABLE `themes_animations_ordering` DISABLE KEYS */;
INSERT INTO `themes_animations_ordering` VALUES (1,'None','No animation.'),(2,'Slide','Slide Up and Down.'),(3,'Fade','Fade In and Out.');
/*!40000 ALTER TABLE `themes_animations_ordering` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `timezones`
--

DROP TABLE IF EXISTS `timezones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `timezones` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timezone` varchar(64) NOT NULL,
  `countries_id` int(11) NOT NULL DEFAULT '242',
  `utc` varchar(6) NOT NULL,
  `utc_dst` varchar(6) NOT NULL,
  `coordinates` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `countries_id` (`countries_id`),
  CONSTRAINT `timezones_ibfk_1` FOREIGN KEY (`countries_id`) REFERENCES `countries` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=550 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `timezones`
--

LOCK TABLES `timezones` WRITE;
/*!40000 ALTER TABLE `timezones` DISABLE KEYS */;
INSERT INTO `timezones` VALUES (1,'Africa/Abidjan',80,'+00:00','+00:00','+0519-00402'),(2,'Africa/Accra',75,'+00:00','+00:00','+0533-00013'),(3,'Africa/Addis_Ababa',53,'+03:00','+03:00','+0902+03842'),(4,'Africa/Algiers',22,'+01:00','+01:00','+3647+00303'),(5,'Africa/Asmara',123,'+03:00','+03:00','+1520+03853'),(6,'Africa/Asmera',123,'+03:00','+03:00',''),(7,'Africa/Bamako',92,'+00:00','+00:00','+1239-00800'),(8,'Africa/Bangui',130,'+01:00','+01:00','+0422+01835'),(9,'Africa/Banjul',157,'+00:00','+00:00','+1328-01639'),(10,'Africa/Bissau',159,'+00:00','+00:00','+1151-01535'),(11,'Africa/Blantyre',89,'+02:00','+02:00','-1547+03500'),(12,'Africa/Brazzaville',135,'+01:00','+01:00','-0416+01517'),(13,'Africa/Bujumbura',105,'+02:00','+02:00','-0323+02922'),(14,'Africa/Cairo',23,'+02:00','+02:00','+3003+03115'),(15,'Africa/Casablanca',42,'+00:00','+01:00','+3339-00735'),(16,'Africa/Ceuta',4,'+01:00','+02:00','+3553-00519'),(17,'Africa/Conakry',99,'+00:00','+00:00','+0931-01343'),(18,'Africa/Dakar',90,'+00:00','+00:00','+1440-01726'),(19,'Africa/Dar_es_Salaam',64,'+03:00','+03:00','-0648+03917'),(20,'Africa/Djibouti',177,'+03:00','+03:00','+1136+04309'),(21,'Africa/Douala',82,'+01:00','+01:00','+0403+00942'),(22,'Africa/El_Aaiun',179,'+00:00','+00:00','+2709-01312'),(23,'Africa/Freetown',117,'+00:00','+00:00','+0830-01315'),(24,'Africa/Gaborone',154,'+02:00','+02:00','-2439+02555'),(25,'Africa/Harare',95,'+02:00','+02:00','-1750+03103'),(26,'Africa/Johannesburg',59,'+02:00','+02:00','-2615+02800'),(27,'Africa/Juba',156,'+03:00','+03:00','+0451+03136'),(28,'Africa/Kampala',66,'+03:00','+03:00','+0019+03225'),(29,'Africa/Khartoum',63,'+03:00','+03:00','+1536+03232'),(30,'Africa/Kigali',97,'+02:00','+02:00','-0157+03004'),(31,'Africa/Kinshasa',56,'+01:00','+01:00','-0418+01518'),(32,'Africa/Lagos',44,'+01:00','+01:00','+0627+00324'),(33,'Africa/Libreville',160,'+01:00','+01:00','+0023+00927'),(34,'Africa/Lome',121,'+00:00','+00:00','+0608+00113'),(35,'Africa/Luanda',12,'+01:00','+01:00','-0848+01314'),(36,'Africa/Lubumbashi',56,'+02:00','+02:00','-1140+02728'),(37,'Africa/Lusaka',93,'+02:00','+02:00','-1525+02817'),(38,'Africa/Malabo',174,'+01:00','+01:00','+0345+00847'),(39,'Africa/Maputo',14,'+02:00','+02:00','-2558+03235'),(40,'Africa/Maseru',150,'+02:00','+02:00','-2928+02730'),(41,'Africa/Mbabane',165,'+02:00','+02:00','-2618+03106'),(42,'Africa/Mogadishu',100,'+03:00','+03:00','+0204+04522'),(43,'Africa/Monrovia',140,'+00:00','+00:00','+0618-01047'),(44,'Africa/Nairobi',65,'+03:00','+03:00','-0117+03649'),(45,'Africa/Ndjamena',98,'+01:00','+01:00','+1207+01503'),(46,'Africa/Niamey',86,'+01:00','+01:00','+1331+00207'),(47,'Africa/Nouakchott',143,'+00:00','+00:00','+1806-01557'),(48,'Africa/Ouagadougou',84,'+00:00','+00:00','+1222-00131'),(49,'Africa/Porto-Novo',106,'+01:00','+01:00','+0629+00237'),(50,'Africa/Sao_Tome',191,'+00:00','+00:00','+0020+00644'),(51,'Africa/Timbuktu',92,'+00:00','+00:00',''),(52,'Africa/Tripoli',119,'+02:00','+02:00','+3254+01311'),(53,'Africa/Tunis',96,'+01:00','+01:00','+3648+01011'),(54,'Africa/Windhoek',151,'+01:00','+02:00','-2234+01706'),(55,'AKST9AKDT',242,'-09:00','-08:00',''),(56,'America/Adak',2,'-10:00','-09:00','+515248-1763929'),(57,'America/Anchorage',2,'-09:00','-08:00','+611305-1495401'),(58,'America/Anguilla',224,'-04:00','-04:00','+1812-06304'),(59,'America/Antigua',204,'-04:00','-04:00','+1703-06148'),(60,'America/Araguaina',11,'-03:00','-03:00','-0712-04812'),(61,'America/Argentina/Buenos_Aires',21,'-03:00','-03:00','-3436-05827'),(62,'America/Argentina/Catamarca',21,'-03:00','-03:00','-2828-06547'),(63,'America/Argentina/ComodRivadavia',21,'-03:00','-03:00',''),(64,'America/Argentina/Cordoba',21,'-03:00','-03:00','-3124-06411'),(65,'America/Argentina/Jujuy',21,'-03:00','-03:00','-2411-06518'),(66,'America/Argentina/La_Rioja',21,'-03:00','-03:00','-2926-06651'),(67,'America/Argentina/Mendoza',21,'-03:00','-03:00','-3253-06849'),(68,'America/Argentina/Rio_Gallegos',21,'-03:00','-03:00','-5138-06913'),(69,'America/Argentina/Salta',21,'-03:00','-03:00','-2447-06525'),(70,'America/Argentina/San_Juan',21,'-03:00','-03:00','-3132-06831'),(71,'America/Argentina/San_Luis',21,'-03:00','-03:00','-3319-06621'),(72,'America/Argentina/Tucuman',21,'-03:00','-03:00','-2649-06513'),(73,'America/Argentina/Ushuaia',21,'-03:00','-03:00','-5448-06818'),(74,'America/Aruba',199,'-04:00','-04:00','+1230-06958'),(75,'America/Asuncion',115,'-04:00','-03:00','-2516-05740'),(76,'America/Atikokan',3,'-05:00','-05:00','+484531-0913718'),(77,'America/Atka',2,'-10:00','-09:00',''),(78,'America/Bahia',11,'-03:00','-02:00','-1259-03831'),(79,'America/Bahia_Banderas',41,'-06:00','-05:00','+2048-10515'),(80,'America/Barbados',185,'-04:00','-04:00','+1306-05937'),(81,'America/Belem',11,'-03:00','-03:00','-0127-04829'),(82,'America/Belize',183,'-06:00','-06:00','+1730-08812'),(83,'America/Blanc-Sablon',3,'-04:00','-04:00','+5125-05707'),(84,'America/Boa_Vista',11,'-04:00','-04:00','+0249-06040'),(85,'America/Bogota',62,'-05:00','-05:00','+0436-07405'),(86,'America/Boise',2,'-07:00','-06:00','+433649-1161209'),(87,'America/Buenos_Aires',21,'-03:00','-03:00',''),(88,'America/Cambridge_Bay',3,'-07:00','-06:00','+690650-1050310'),(89,'America/Campo_Grande',11,'-04:00','-03:00','-2027-05437'),(90,'America/Cancun',41,'-06:00','-05:00','+2105-08646'),(91,'America/Caracas',73,'-04:30','-04:30','+1030-06656'),(92,'America/Catamarca',21,'-03:00','-03:00',''),(93,'America/Cayenne',155,'-03:00','-03:00','+0456-05220'),(94,'America/Cayman',212,'-05:00','-05:00','+1918-08123'),(95,'America/Chicago',2,'-06:00','-05:00','+415100-0873900'),(96,'America/Chihuahua',41,'-07:00','-06:00','+2838-10605'),(97,'America/Coral_Harbour',3,'-05:00','-05:00',''),(98,'America/Cordoba',21,'-03:00','-03:00',''),(99,'America/Costa_Rica',133,'-06:00','-06:00','+0956-08405'),(100,'America/Creston',3,'-07:00','-07:00','+4906-11631'),(101,'America/Cuiaba',11,'-04:00','-03:00','-1535-05605'),(102,'America/Curacao',168,'-04:00','-04:00','+1211-06900'),(103,'America/Danmarkshavn',211,'+00:00','+00:00','+7646-01840'),(104,'America/Dawson',3,'-08:00','-07:00','+6404-13925'),(105,'America/Dawson_Creek',3,'-07:00','-07:00','+5946-12014'),(106,'America/Denver',2,'-07:00','-06:00','+394421-1045903'),(107,'America/Detroit',2,'-05:00','-04:00','+421953-0830245'),(108,'America/Dominica',207,'-04:00','-04:00','+1518-06124'),(109,'America/Edmonton',3,'-07:00','-06:00','+5333-11328'),(110,'America/Eirunepe',11,'-04:00','-04:00','-0640-06952'),(111,'America/El_Salvador',113,'-06:00','-06:00','+1342-08912'),(112,'America/Ensenada',41,'-08:00','-07:00',''),(113,'America/Fort_Wayne',2,'-05:00','-04:00',''),(114,'America/Fortaleza',11,'-03:00','-03:00','-0343-03830'),(115,'America/Glace_Bay',3,'-04:00','-03:00','+4612-05957'),(116,'America/Godthab',211,'-03:00','-02:00','+6411-05144'),(117,'America/Goose_Bay',3,'-04:00','-03:00','+5320-06025'),(118,'America/Grand_Turk',221,'-05:00','-04:00','+2128-07108'),(119,'America/Grenada',201,'-04:00','-04:00','+1203-06145'),(120,'America/Guadeloupe',148,'-04:00','-04:00','+1614-06132'),(121,'America/Guatemala',91,'-06:00','-06:00','+1438-09031'),(122,'America/Guayaquil',87,'-05:00','-05:00','-0210-07950'),(123,'America/Guyana',169,'-04:00','-04:00','+0648-05810'),(124,'America/Halifax',3,'-04:00','-03:00','+4439-06336'),(125,'America/Havana',94,'-05:00','-04:00','+2308-08222'),(126,'America/Hermosillo',41,'-07:00','-07:00','+2904-11058'),(127,'America/Indiana/Indianapolis',2,'-05:00','-04:00','+394606-0860929'),(128,'America/Indiana/Knox',2,'-06:00','-05:00','+411745-0863730'),(129,'America/Indiana/Marengo',2,'-05:00','-04:00','+382232-0862041'),(130,'America/Indiana/Petersburg',2,'-05:00','-04:00','+382931-0871643'),(131,'America/Indiana/Tell_City',2,'-06:00','-05:00','+375711-0864541'),(132,'America/Indiana/Vevay',2,'-05:00','-04:00','+384452-0850402'),(133,'America/Indiana/Vincennes',2,'-05:00','-04:00','+384038-0873143'),(134,'America/Indiana/Winamac',2,'-05:00','-04:00','+410305-0863611'),(135,'America/Indianapolis',2,'-05:00','-04:00',''),(136,'America/Inuvik',3,'-07:00','-06:00','+682059-1334300'),(137,'America/Iqaluit',3,'-05:00','-04:00','+6344-06828'),(138,'America/Jamaica',146,'-05:00','-05:00','+1800-07648'),(139,'America/Jujuy',21,'-03:00','-03:00',''),(140,'America/Juneau',2,'-09:00','-08:00','+581807-1342511'),(141,'America/Kentucky/Louisville',2,'-05:00','-04:00','+381515-0854534'),(142,'America/Kentucky/Monticello',2,'-05:00','-04:00','+364947-0845057'),(143,'America/Knox_IN',2,'-06:00','-05:00',''),(144,'America/Kralendijk',131,'-04:00','-04:00','+120903-0681636'),(145,'America/La_Paz',101,'-04:00','-04:00','-1630-06809'),(146,'America/Lima',67,'-05:00','-05:00','-1203-07703'),(147,'America/Los_Angeles',2,'-08:00','-07:00','+340308-1181434'),(148,'America/Louisville',2,'-05:00','-04:00',''),(149,'America/Lower_Princes',55,'-04:00','-04:00','+180305-0630250'),(150,'America/Maceio',11,'-03:00','-03:00','-0940-03543'),(151,'America/Managua',122,'-06:00','-06:00','+1209-08617'),(152,'America/Manaus',11,'-04:00','-04:00','-0308-06001'),(153,'America/Marigot',218,'-04:00','-04:00','+1804-06305'),(154,'America/Martinique',206,'-04:00','-04:00','+1436-06105'),(155,'America/Matamoros',41,'-06:00','-05:00','+2550-09730'),(156,'America/Mazatlan',41,'-07:00','-06:00','+2313-10625'),(157,'America/Mendoza',21,'-03:00','-03:00',''),(158,'America/Menominee',2,'-06:00','-05:00','+450628-0873651'),(159,'America/Merida',41,'-06:00','-05:00','+2058-08937'),(160,'America/Metlakatla',2,'-08:00','-08:00','+550737-1313435'),(161,'America/Mexico_City',41,'-06:00','-05:00','+1924-09909'),(162,'America/Miquelon',230,'-03:00','-02:00','+4703-05620'),(163,'America/Moncton',3,'-04:00','-03:00','+4606-06447'),(164,'America/Monterrey',41,'-06:00','-05:00','+2540-10019'),(165,'America/Montevideo',139,'-03:00','-02:00','-3453-05611'),(166,'America/Montreal',3,'-05:00','-04:00','+4531-07334'),(167,'America/Montserrat',231,'-04:00','-04:00','+1643-06213'),(168,'America/Nassau',182,'-05:00','-04:00','+2505-07721'),(169,'America/New_York',2,'-05:00','-04:00','+404251-0740023'),(170,'America/Nipigon',3,'-05:00','-04:00','+4901-08816'),(171,'America/Nome',2,'-09:00','-08:00','+643004-1652423'),(172,'America/Noronha',11,'-02:00','-02:00','-0351-03225'),(173,'America/North_Dakota/Beulah',2,'-06:00','-05:00','+471551-1014640'),(174,'America/North_Dakota/Center',2,'-06:00','-05:00','+470659-1011757'),(175,'America/North_Dakota/New_Salem',2,'-06:00','-05:00','+465042-1012439'),(176,'America/Ojinaga',41,'-07:00','-06:00','+2934-10425'),(177,'America/Panama',142,'-05:00','-05:00','+0858-07932'),(178,'America/Pangnirtung',3,'-05:00','-04:00','+6608-06544'),(179,'America/Paramaribo',178,'-03:00','-03:00','+0550-05510'),(180,'America/Phoenix',2,'-07:00','-07:00','+332654-1120424'),(181,'America/Port_of_Spain',163,'-04:00','-04:00','+1039-06131'),(182,'America/Port-au-Prince',104,'-05:00','-04:00','+1832-07220'),(183,'America/Porto_Acre',11,'-05:00','',''),(184,'America/Porto_Velho',11,'-04:00','-04:00','-0846-06354'),(185,'America/Puerto_Rico',136,'-04:00','-04:00','+182806-0660622'),(186,'America/Rainy_River',3,'-06:00','-05:00','+4843-09434'),(187,'America/Rankin_Inlet',3,'-06:00','-05:00','+624900-0920459'),(188,'America/Recife',11,'-03:00','-03:00','-0803-03454'),(189,'America/Regina',3,'-06:00','-06:00','+5024-10439'),(190,'America/Resolute',3,'-06:00','-05:00','+744144-0944945'),(191,'America/Rio_Branco',11,'-05:00','','-0958-06748'),(192,'America/Rosario',21,'-03:00','-03:00',''),(193,'America/Santa_Isabel',41,'-08:00','-07:00','+3018-11452'),(194,'America/Santarem',11,'-03:00','-03:00','-0226-05452'),(195,'America/Santiago',83,'-04:00','-03:00','-3327-07040'),(196,'America/Santo_Domingo',102,'-04:00','-04:00','+1828-06954'),(197,'America/Sao_Paulo',11,'-03:00','-02:00','-2332-04637'),(198,'America/Scoresbysund',211,'-01:00','+00:00','+7029-02158'),(199,'America/Shiprock',2,'-07:00','-06:00','+364708-1084111'),(200,'America/Sitka',2,'-09:00','-08:00','+571035-1351807'),(201,'America/St_Barthelemy',229,'-04:00','-04:00','+1753-06251'),(202,'America/St_Johns',3,'-03:30','-02:30','+4734-05243'),(203,'America/St_Kitts',214,'-04:00','-04:00','+1718-06243'),(204,'America/St_Lucia',193,'-04:00','-04:00','+1401-06100'),(205,'America/St_Thomas',196,'-04:00','-04:00','+1821-06456'),(206,'America/St_Vincent',198,'-04:00','-04:00','+1309-06114'),(207,'America/Swift_Current',3,'-06:00','-06:00','+5017-10750'),(208,'America/Tegucigalpa',108,'-06:00','-06:00','+1406-08713'),(209,'America/Thule',211,'-04:00','-03:00','+7634-06847'),(210,'America/Thunder_Bay',3,'-05:00','-04:00','+4823-08915'),(211,'America/Tijuana',41,'-08:00','-07:00','+3232-11701'),(212,'America/Toronto',3,'-05:00','-04:00','+4339-07923'),(213,'America/Tortola',220,'-04:00','-04:00','+1827-06437'),(214,'America/Vancouver',3,'-08:00','-07:00','+4916-12307'),(215,'America/Virgin',196,'-04:00','-04:00',''),(216,'America/Whitehorse',3,'-08:00','-07:00','+6043-13503'),(217,'America/Winnipeg',3,'-06:00','-05:00','+4953-09709'),(218,'America/Yakutat',2,'-09:00','-08:00','+593249-1394338'),(219,'America/Yellowknife',3,'-07:00','-06:00','+6227-11421'),(220,'Antarctica/Casey',243,'+11:00','+08:00','-6617+11031'),(221,'Antarctica/Davis',243,'+05:00','+07:00','-6835+07758'),(222,'Antarctica/DumontDUrville',243,'+10:00','+10:00','-6640+14001'),(223,'Antarctica/Macquarie',243,'+11:00','+11:00','-5430+15857'),(224,'Antarctica/Mawson',243,'+05:00','+05:00','-6736+06253'),(225,'Antarctica/McMurdo',243,'+12:00','+13:00','-7750+16636'),(226,'Antarctica/Palmer',243,'-04:00','-03:00','-6448-06406'),(227,'Antarctica/Rothera',243,'-03:00','-03:00','-6734-06808'),(228,'Antarctica/South_Pole',243,'+12:00','+13:00','-9000+00000'),(229,'Antarctica/Syowa',243,'+03:00','+03:00','-690022+0393524'),(230,'Antarctica/Vostok',243,'+06:00','+06:00','-7824+10654'),(231,'Arctic/Longyearbyen',235,'+01:00','+02:00','+7800+01600'),(232,'Asia/Aden',76,'+03:00','+03:00','+1245+04512'),(233,'Asia/Almaty',85,'+06:00','+06:00','+4315+07657'),(234,'Asia/Amman',118,'+02:00','+03:00','+3157+03556'),(235,'Asia/Anadyr',26,'+12:00','+12:00','+6445+17729'),(236,'Asia/Aqtau',85,'+05:00','+05:00','+4431+05016'),(237,'Asia/Aqtobe',85,'+05:00','+05:00','+5017+05710'),(238,'Asia/Ashgabat',126,'+05:00','+05:00','+3757+05823'),(239,'Asia/Ashkhabad',126,'+05:00','+05:00',''),(240,'Asia/Baghdad',68,'+03:00','+03:00','+3321+04425'),(241,'Asia/Bahrain',171,'+03:00','+03:00','+2623+05035'),(242,'Asia/Baku',107,'+04:00','+05:00','+4023+04951'),(243,'Asia/Bangkok',58,'+07:00','+07:00','+1345+10031'),(244,'Asia/Beirut',134,'+02:00','+03:00','+3353+03530'),(245,'Asia/Bishkek',125,'+06:00','+06:00','+4254+07436'),(246,'Asia/Brunei',181,'+08:00','+08:00','+0456+11455'),(247,'Asia/Calcutta',47,'+05:30','+05:30',''),(248,'Asia/Choibalsan',144,'+08:00','+08:00','+4804+11430'),(249,'Asia/Chongqing',27,'+08:00','+08:00','+2934+10635'),(250,'Asia/Chungking',27,'+08:00','+08:00',''),(251,'Asia/Colombo',29,'+05:30','+05:30','+0656+07951'),(252,'Asia/Dacca',49,'+06:00','+06:00',''),(253,'Asia/Damascus',81,'+02:00','+03:00','+3330+03618'),(254,'Asia/Dhaka',49,'+06:00','+06:00','+2343+09025'),(255,'Asia/Dili',164,'+09:00','+09:00','-0833+12535'),(256,'Asia/Dubai',13,'+04:00','+04:00','+2518+05518'),(257,'Asia/Dushanbe',110,'+05:00','+05:00','+3835+06848'),(258,'Asia/Gaza',158,'+02:00','+03:00','+3130+03428'),(259,'Asia/Harbin',27,'+08:00','+08:00','+4545+12641'),(260,'Asia/Hebron',158,'+02:00','+03:00','+313200+0350542'),(261,'Asia/Ho_Chi_Minh',52,'+07:00','+07:00','+1045+10640'),(262,'Asia/Hong_Kong',114,'+08:00','+08:00','+2217+11409'),(263,'Asia/Hovd',144,'+07:00','+07:00','+4801+09139'),(264,'Asia/Irkutsk',26,'+09:00','+09:00','+5216+10420'),(265,'Asia/Istanbul',54,'+02:00','+03:00',''),(266,'Asia/Jakarta',48,'+07:00','+07:00','-0610+10648'),(267,'Asia/Jayapura',48,'+09:00','+09:00','-0232+14042'),(268,'Asia/Jerusalem',111,'+02:00','+03:00','+3146+03514'),(269,'Asia/Kabul',71,'+04:30','+04:30','+3431+06912'),(270,'Asia/Kamchatka',26,'+12:00','+12:00','+5301+15839'),(271,'Asia/Karachi',50,'+05:00','+05:00','+2452+06703'),(272,'Asia/Kashgar',27,'+08:00','+08:00','+3929+07559'),(273,'Asia/Kathmandu',70,'+05:45','+05:45','+2743+08519'),(274,'Asia/Katmandu',70,'+05:45','+05:45',''),(275,'Asia/Kolkata',47,'+05:30','+05:30','+2232+08822'),(276,'Asia/Krasnoyarsk',26,'+08:00','+08:00','+5601+09250'),(277,'Asia/Kuala_Lumpur',74,'+08:00','+08:00','+0310+10142'),(278,'Asia/Kuching',74,'+08:00','+08:00','+0133+11020'),(279,'Asia/Kuwait',147,'+03:00','+03:00','+2920+04759'),(280,'Asia/Macao',176,'+08:00','+08:00',''),(281,'Asia/Macau',176,'+08:00','+08:00','+2214+11335'),(282,'Asia/Magadan',26,'+12:00','+12:00','+5934+15048'),(283,'Asia/Makassar',48,'+08:00','+08:00','-0507+11924'),(284,'Asia/Manila',51,'+08:00','+08:00','+1435+12100'),(285,'Asia/Muscat',141,'+04:00','+04:00','+2336+05835'),(286,'Asia/Nicosia',17,'+02:00','+03:00','+3510+03322'),(287,'Asia/Novokuznetsk',26,'+07:00','+07:00','+5345+08707'),(288,'Asia/Novosibirsk',26,'+07:00','+07:00','+5502+08255'),(289,'Asia/Omsk',26,'+07:00','+07:00','+5500+07324'),(290,'Asia/Oral',85,'+05:00','+05:00','+5113+05121'),(291,'Asia/Phnom_Penh',88,'+07:00','+07:00','+1133+10455'),(292,'Asia/Pontianak',48,'+07:00','+07:00','-0002+10920'),(293,'Asia/Pyongyang',77,'+09:00','+09:00','+3901+12545'),(294,'Asia/Qatar',167,'+03:00','+03:00','+2517+05132'),(295,'Asia/Qyzylorda',85,'+06:00','+06:00','+4448+06528'),(296,'Asia/Rangoon',60,'+06:30','+06:30','+1647+09610'),(297,'Asia/Riyadh',69,'+03:00','+03:00','+2438+04643'),(298,'Asia/Saigon',52,'+07:00','+07:00',''),(299,'Asia/Sakhalin',26,'+11:00','+11:00','+4658+14242'),(300,'Asia/Samarkand',72,'+05:00','+05:00','+3940+06648'),(301,'Asia/Seoul',28,'+09:00','+09:00','+3733+12658'),(302,'Asia/Shanghai',27,'+08:00','+08:00','+3114+12128'),(303,'Asia/Singapore',127,'+08:00','+08:00','+0117+10351'),(304,'Asia/Taipei',46,'+08:00','+08:00','+2503+12130'),(305,'Asia/Tashkent',72,'+05:00','+05:00','+4120+06918'),(306,'Asia/Tbilisi',128,'+04:00','+04:00','+4143+04449'),(307,'Asia/Tehran',57,'+03:30','+04:30','+3540+05126'),(308,'Asia/Tel_Aviv',111,'+02:00','+03:00',''),(309,'Asia/Thimbu',172,'+06:00','+06:00',''),(310,'Asia/Thimphu',172,'+06:00','+06:00','+2728+08939'),(311,'Asia/Tokyo',25,'+09:00','+09:00','+353916+1394441'),(312,'Asia/Ujung_Pandang',48,'+08:00','+08:00',''),(313,'Asia/Ulaanbaatar',144,'+08:00','+08:00','+4755+10653'),(314,'Asia/Ulan_Bator',144,'+08:00','+08:00',''),(315,'Asia/Urumqi',27,'+08:00','+08:00','+4348+08735'),(316,'Asia/Vientiane',116,'+07:00','+07:00','+1758+10236'),(317,'Asia/Vladivostok',26,'+11:00','+11:00','+4310+13156'),(318,'Asia/Yakutsk',26,'+10:00','+10:00','+6200+12940'),(319,'Asia/Yekaterinburg',26,'+06:00','+06:00','+5651+06036'),(320,'Asia/Yerevan',145,'+04:00','+04:00','+4011+04430'),(321,'Atlantic/Azores',1,'-01:00','+00:00','+3744-02540'),(322,'Atlantic/Bermuda',208,'-04:00','-03:00','+3217-06446'),(323,'Atlantic/Canary',4,'+00:00','+01:00','+2806-01524'),(324,'Atlantic/Cape_Verde',15,'-01:00','-01:00','+1455-02331'),(325,'Atlantic/Faeroe',213,'+00:00','+01:00',''),(326,'Atlantic/Faroe',213,'+00:00','+01:00','+6201-00646'),(327,'Atlantic/Jan_Mayen',31,'+01:00','+02:00',''),(328,'Atlantic/Madeira',1,'+00:00','+01:00','+3238-01654'),(329,'Atlantic/Reykjavik',24,'+00:00','+00:00','+6409-02151'),(330,'Atlantic/South_Georgia',244,'-02:00','-02:00','-5416-03632'),(331,'Atlantic/St_Helena',228,'+00:00','+00:00','-1555-00542'),(332,'Atlantic/Stanley',232,'-03:00','-03:00','-5142-05751'),(333,'Australia/ACT',20,'+10:00','+11:00',''),(334,'Australia/Adelaide',20,'+09:30','+10:30','-3455+13835'),(335,'Australia/Brisbane',20,'+10:00','+10:00','-2728+15302'),(336,'Australia/Broken_Hill',20,'+09:30','+10:30','-3157+14127'),(337,'Australia/Canberra',20,'+10:00','+11:00',''),(338,'Australia/Currie',20,'+10:00','+11:00','-3956+14352'),(339,'Australia/Darwin',20,'+09:30','+09:30','-1228+13050'),(340,'Australia/Eucla',20,'+08:45','+08:45','-3143+12852'),(341,'Australia/Hobart',20,'+10:00','+11:00','-4253+14719'),(342,'Australia/LHI',20,'+10:30','+11:00',''),(343,'Australia/Lindeman',20,'+10:00','+10:00','-2016+14900'),(344,'Australia/Lord_Howe',20,'+10:30','+11:00','-3133+15905'),(345,'Australia/Melbourne',20,'+10:00','+11:00','-3749+14458'),(346,'Australia/North',20,'+09:30','+09:30',''),(347,'Australia/NSW',20,'+10:00','+11:00',''),(348,'Australia/Perth',20,'+08:00','+08:00','-3157+11551'),(349,'Australia/Queensland',20,'+10:00','+10:00',''),(350,'Australia/South',20,'+09:30','+10:30',''),(351,'Australia/Sydney',20,'+10:00','+11:00','-3352+15113'),(352,'Australia/Tasmania',20,'+10:00','+11:00',''),(353,'Australia/Victoria',20,'+10:00','+11:00',''),(354,'Australia/West',20,'+08:00','+08:00',''),(355,'Australia/Yancowinna',20,'+09:30','+10:30',''),(356,'Brazil/Acre',11,'-05:00','',''),(357,'Brazil/DeNoronha',11,'-02:00','-02:00',''),(358,'Brazil/East',11,'-03:00','-02:00',''),(359,'Brazil/West',11,'-04:00','-04:00',''),(360,'Canada/Atlantic',3,'-04:00','-03:00',''),(361,'Canada/Central',3,'-06:00','-05:00',''),(362,'Canada/Eastern',3,'-05:00','-04:00',''),(363,'Canada/East-Saskatchewan',3,'-06:00','-06:00',''),(364,'Canada/Mountain',3,'-07:00','-06:00',''),(365,'Canada/Newfoundland',3,'-03:30','-02:30',''),(366,'Canada/Pacific',3,'-08:00','-07:00',''),(367,'Canada/Saskatchewan',3,'-06:00','-06:00',''),(368,'Canada/Yukon',3,'-08:00','-07:00',''),(369,'CET',242,'+01:00','+02:00',''),(370,'Chile/Continental',83,'-04:00','-03:00',''),(371,'Chile/EasterIsland',83,'-06:00','-05:00',''),(372,'CST6CDT',242,'-06:00','-05:00',''),(373,'Cuba',94,'-05:00','-04:00',''),(374,'EET',242,'+02:00','+03:00',''),(375,'Egypt',23,'+02:00','+02:00',''),(376,'Eire',33,'+00:00','+01:00',''),(377,'EST',242,'-05:00','-05:00',''),(378,'EST5EDT',242,'-05:00','-04:00',''),(379,'Etc/GMT',242,'+00:00','+00:00',''),(380,'Etc/GMT+0',242,'+00:00','+00:00',''),(381,'Etc/UCT',242,'+00:00','+00:00',''),(382,'Etc/Universal',242,'+00:00','+00:00',''),(383,'Etc/UTC',242,'+00:00','+00:00',''),(384,'Etc/Zulu',242,'+00:00','+00:00',''),(385,'Europe/Amsterdam',8,'+01:00','+02:00','+5222+00454'),(386,'Europe/Andorra',205,'+01:00','+02:00','+4230+00131'),(387,'Europe/Athens',18,'+02:00','+03:00','+3758+02343'),(388,'Europe/Belfast',33,'+00:00','+01:00',''),(389,'Europe/Belgrade',109,'+01:00','+02:00','+4450+02030'),(390,'Europe/Berlin',6,'+01:00','+02:00','+5230+01322'),(391,'Europe/Bratislava',124,'+01:00','+02:00','+4809+01707'),(392,'Europe/Brussels',35,'+01:00','+02:00','+5050+00420'),(393,'Europe/Bucharest',78,'+02:00','+03:00','+4426+02606'),(394,'Europe/Budapest',38,'+01:00','+02:00','+4730+01905'),(395,'Europe/Chisinau',132,'+02:00','+03:00','+4700+02850'),(396,'Europe/Copenhagen',37,'+01:00','+02:00','+5540+01235'),(397,'Europe/Dublin',33,'+00:00','+01:00','+5320-00615'),(398,'Europe/Gibraltar',219,'+01:00','+02:00','+3608-00521'),(399,'Europe/Guernsey',245,'+00:00','+01:00','+4927-00232'),(400,'Europe/Helsinki',32,'+02:00','+03:00','+6010+02458'),(401,'Europe/Isle_of_Man',34,'+00:00','+01:00','+5409-00428'),(402,'Europe/Istanbul',54,'+02:00','+03:00','+4101+02858'),(403,'Europe/Jersey',200,'+00:00','+01:00','+4912-00207'),(404,'Europe/Kaliningrad',26,'+03:00','+03:00','+5443+02030'),(405,'Europe/Kiev',61,'+02:00','+03:00','+5026+03031'),(406,'Europe/Lisbon',1,'+00:00','+01:00','+3843-00908'),(407,'Europe/Ljubljana',153,'+01:00','+02:00','+4603+01431'),(408,'Europe/London',7,'+00:00','+01:00','+513030-0000731'),(409,'Europe/Luxembourg',39,'+01:00','+02:00','+4936+00609'),(410,'Europe/Madrid',4,'+01:00','+02:00','+4024-00341'),(411,'Europe/Malta',40,'+01:00','+02:00','+3554+01431'),(412,'Europe/Mariehamn',246,'+02:00','+03:00','+6006+01957'),(413,'Europe/Minsk',103,'+03:00','+03:00','+5354+02734'),(414,'Europe/Monaco',216,'+01:00','+02:00','+4342+00723'),(415,'Europe/Moscow',26,'+04:00','+04:00','+5545+03735'),(416,'Europe/Nicosia',17,'+02:00','+03:00',''),(417,'Europe/Oslo',31,'+01:00','+02:00','+5955+01045'),(418,'Europe/Paris',5,'+01:00','+02:00','+4852+00220'),(419,'Europe/Podgorica',173,'+01:00','+02:00','+4226+01916'),(420,'Europe/Prague',36,'+01:00','+02:00','+5005+01426'),(421,'Europe/Riga',149,'+02:00','+03:00','+5657+02406'),(422,'Europe/Rome',10,'+01:00','+02:00','+4154+01229'),(423,'Europe/Samara',26,'+04:00','+04:00','+5312+05009'),(424,'Europe/San_Marino',217,'+01:00','+02:00','+4355+01228'),(425,'Europe/Sarajevo',129,'+01:00','+02:00','+4352+01825'),(426,'Europe/Simferopol',61,'+02:00','+03:00','+4457+03406'),(427,'Europe/Skopje',152,'+01:00','+02:00','+4159+02126'),(428,'Europe/Sofia',112,'+02:00','+03:00','+4241+02319'),(429,'Europe/Stockholm',30,'+01:00','+02:00','+5920+01803'),(430,'Europe/Tallinn',161,'+02:00','+03:00','+5925+02445'),(431,'Europe/Tirane',137,'+01:00','+02:00','+4120+01950'),(432,'Europe/Tiraspol',132,'+02:00','+03:00',''),(433,'Europe/Uzhgorod',61,'+02:00','+03:00','+4837+02218'),(434,'Europe/Vaduz',215,'+01:00','+02:00','+4709+00931'),(435,'Europe/Vatican',239,'+01:00','+02:00','+415408+0122711'),(436,'Europe/Vienna',19,'+01:00','+02:00','+4813+01620'),(437,'Europe/Vilnius',138,'+02:00','+03:00','+5441+02519'),(438,'Europe/Volgograd',26,'+04:00','+04:00','+4844+04425'),(439,'Europe/Warsaw',45,'+01:00','+02:00','+5215+02100'),(440,'Europe/Zagreb',16,'+01:00','+02:00','+4548+01558'),(441,'Europe/Zaporozhye',61,'+02:00','+03:00','+4750+03510'),(442,'Europe/Zurich',9,'+01:00','+02:00','+4723+00832'),(443,'GB',7,'+00:00','+01:00',''),(444,'GB-Eire',242,'+00:00','+01:00',''),(445,'GMT',242,'+00:00','+00:00',''),(446,'GMT+0',242,'+00:00','+00:00',''),(447,'GMT0',242,'+00:00','+00:00',''),(448,'GMT-0',242,'+00:00','+00:00',''),(449,'Greenwich',7,'+00:00','+00:00',''),(450,'Hongkong',114,'+08:00','+08:00',''),(451,'HST',242,'-10:00','-10:00',''),(452,'Iceland',24,'+00:00','+00:00',''),(453,'Indian/Antananarivo',79,'+03:00','+03:00','-1855+04731'),(454,'Indian/Chagos',247,'+06:00','+06:00','-0720+07225'),(455,'Indian/Christmas',237,'+07:00','+07:00','-1025+10543'),(456,'Indian/Cocos',240,'+06:30','+06:30','-1210+09655'),(457,'Indian/Comoro',170,'+03:00','+03:00','-1141+04316'),(458,'Indian/Kerguelen',248,'+05:00','+05:00','-492110+0701303'),(459,'Indian/Mahe',203,'+04:00','+04:00','-0440+05528'),(460,'Indian/Maldives',180,'+05:00','+05:00','+0410+07330'),(461,'Indian/Mauritius',162,'+04:00','+04:00','-2010+05730'),(462,'Indian/Mayotte',188,'+03:00','+03:00','-1247+04514'),(463,'Indian/Reunion',249,'+04:00','+04:00','-2052+05528'),(464,'Iran',57,'+03:30','+04:30',''),(465,'Israel',111,'+02:00','+03:00',''),(466,'Jamaica',146,'-05:00','-05:00',''),(467,'Japan',25,'+09:00','+09:00',''),(468,'JST-9',242,'+09:00','+09:00',''),(469,'Kwajalein',210,'+12:00','+12:00',''),(470,'Libya',119,'+02:00','+02:00',''),(471,'MET',242,'+01:00','+02:00',''),(472,'Mexico/BajaNorte',41,'-08:00','-07:00',''),(473,'Mexico/BajaSur',41,'-07:00','-06:00',''),(474,'Mexico/General',41,'-06:00','-05:00',''),(475,'MST',242,'-07:00','-07:00',''),(476,'MST7MDT',242,'-07:00','-06:00',''),(477,'Navajo',2,'-07:00','-06:00',''),(478,'NZ',43,'+12:00','+13:00',''),(479,'NZ-CHAT',43,'+12:45','+13:45',''),(480,'Pacific/Apia',189,'+13:00','+14:00','-1350-17144'),(481,'Pacific/Auckland',43,'+12:00','+13:00','-3652+17446'),(482,'Pacific/Chatham',43,'+12:45','+13:45','-4357-17633'),(483,'Pacific/Chuuk',197,'+10:00','+10:00','+0725+15147'),(484,'Pacific/Easter',83,'-06:00','-05:00','-2709-10926'),(485,'Pacific/Efate',190,'+11:00','+11:00','-1740+16825'),(486,'Pacific/Enderbury',195,'+13:00','+13:00','-0308-17105'),(487,'Pacific/Fakaofo',236,'+13:00','+13:00','-0922-17114'),(488,'Pacific/Fiji',166,'+12:00','+13:00','-1808+17825'),(489,'Pacific/Funafuti',226,'+12:00','+12:00','-0831+17913'),(490,'Pacific/Galapagos',87,'-06:00','-06:00','-0054-08936'),(491,'Pacific/Gambier',184,'-09:00','-09:00','-2308-13457'),(492,'Pacific/Guadalcanal',175,'+11:00','+11:00','-0932+16012'),(493,'Pacific/Guam',192,'+10:00','+10:00','+1328+14445'),(494,'Pacific/Honolulu',2,'-10:00','-10:00','+211825-1575130'),(495,'Pacific/Johnston',250,'-10:00','-10:00','+1645-16931'),(496,'Pacific/Kiritimati',195,'+14:00','+14:00','+0152-15720'),(497,'Pacific/Kosrae',197,'+11:00','+11:00','+0519+16259'),(498,'Pacific/Kwajalein',210,'+12:00','+12:00','+0905+16720'),(499,'Pacific/Majuro',210,'+12:00','+12:00','+0709+17112'),(500,'Pacific/Marquesas',184,'-09:30','-09:30','-0900-13930'),(501,'Pacific/Midway',250,'-11:00','-11:00','+2813-17722'),(502,'Pacific/Nauru',225,'+12:00','+12:00','-0031+16655'),(503,'Pacific/Niue',238,'-11:00','-11:00','-1901-16955'),(504,'Pacific/Norfolk',234,'+11:30','+11:30','-2903+16758'),(505,'Pacific/Noumea',186,'+11:00','+11:00','-2216+16627'),(506,'Pacific/Pago_Pago',209,'-11:00','-11:00','-1416-17042'),(507,'Pacific/Palau',222,'+09:00','+09:00','+0720+13429'),(508,'Pacific/Pitcairn',241,'-08:00','-08:00','-2504-13005'),(509,'Pacific/Pohnpei',197,'+11:00','+11:00','+0658+15813'),(510,'Pacific/Ponape',197,'+11:00','+11:00',''),(511,'Pacific/Port_Moresby',120,'+10:00','+10:00','-0930+14710'),(512,'Pacific/Rarotonga',227,'-10:00','-10:00','-2114-15946'),(513,'Pacific/Saipan',202,'+10:00','+10:00','+1512+14545'),(514,'Pacific/Samoa',189,'-11:00','-11:00',''),(515,'Pacific/Tahiti',184,'-10:00','-10:00','-1732-14934'),(516,'Pacific/Tarawa',195,'+12:00','+12:00','+0125+17300'),(517,'Pacific/Tongatapu',194,'+13:00','+13:00','-2110-17510'),(518,'Pacific/Truk',197,'+10:00','+10:00',''),(519,'Pacific/Wake',250,'+12:00','+12:00','+1917+16637'),(520,'Pacific/Wallis',223,'+12:00','+12:00','-1318-17610'),(521,'Pacific/Yap',197,'+10:00','+10:00',''),(522,'Poland',45,'+01:00','+02:00',''),(523,'Portugal',1,'+00:00','+01:00',''),(524,'PRC',242,'+08:00','+08:00',''),(525,'PST8PDT',242,'-08:00','-07:00',''),(526,'ROC',242,'+08:00','+08:00',''),(527,'ROK',242,'+09:00','+09:00',''),(528,'Singapore',127,'+08:00','+08:00',''),(529,'Turkey',54,'+02:00','+03:00',''),(530,'UCT',242,'+00:00','+00:00',''),(531,'Universal',242,'+00:00','+00:00',''),(532,'US/Alaska',2,'-09:00','-08:00',''),(533,'US/Aleutian',2,'-10:00','-09:00',''),(534,'US/Arizona',2,'-07:00','-07:00',''),(535,'US/Central',2,'-06:00','-05:00',''),(536,'US/Eastern',2,'-05:00','-04:00',''),(537,'US/East-Indiana',2,'-05:00','-04:00',''),(538,'US/Hawaii',2,'-10:00','-10:00',''),(539,'US/Indiana-Starke',2,'-06:00','-05:00',''),(540,'US/Michigan',2,'-05:00','-04:00',''),(541,'US/Mountain',2,'-07:00','-06:00',''),(542,'US/Pacific',2,'-08:00','-07:00',''),(543,'US/Pacific-New',2,'-08:00','-07:00',''),(544,'US/Samoa',189,'-11:00','-11:00',''),(545,'UTC',242,'+00:00','+00:00',''),(546,'WET',242,'+00:00','+01:00',''),(547,'W-SU',242,'+04:00','+04:00',''),(548,'Zulu',242,'+00:00','+00:00',''),(549,'Europe/Busingen',6,'+01:00','+02:00',NULL);
/*!40000 ALTER TABLE `timezones` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transaction_history`
--

DROP TABLE IF EXISTS `transaction_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `transaction_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `transaction_date` datetime NOT NULL,
  `transaction_types_id` int(11) NOT NULL DEFAULT '1',
  `ammount` decimal(10,6) NOT NULL DEFAULT '1000.000000',
  `description` varchar(64) NOT NULL,
  `users_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `transaction_types_id` (`transaction_types_id`),
  KEY `users_id` (`users_id`),
  CONSTRAINT `transaction_history_ibfk_1` FOREIGN KEY (`transaction_types_id`) REFERENCES `transaction_types` (`id`) ON DELETE CASCADE,
  CONSTRAINT `transaction_history_ibfk_2` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transaction_history`
--

LOCK TABLES `transaction_history` WRITE;
/*!40000 ALTER TABLE `transaction_history` DISABLE KEYS */;
/*!40000 ALTER TABLE `transaction_history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transaction_types`
--

DROP TABLE IF EXISTS `transaction_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `transaction_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `transaction_type` varchar(32) NOT NULL,
  `description` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transaction_types`
--

LOCK TABLES `transaction_types` WRITE;
/*!40000 ALTER TABLE `transaction_types` DISABLE KEYS */;
INSERT INTO `transaction_types` VALUES (1,'Debit','The transaction was a debit'),(2,'Credit','The transaction was a credit');
/*!40000 ALTER TABLE `transaction_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(64) NOT NULL,
  `password` varchar(512) NOT NULL,
  `_file_photo` varchar(2048) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(16) DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '0',
  `locked` tinyint(1) NOT NULL DEFAULT '1',
  `_separator_subscription` tinyint(1) DEFAULT NULL,
  `subscription_types_id` int(11) NOT NULL DEFAULT '1',
  `subscription_change_date` datetime DEFAULT NULL,
  `subscription_renew_date` date DEFAULT NULL,
  `_separator_personal` tinyint(1) DEFAULT NULL,
  `first_name` varchar(32) DEFAULT NULL,
  `last_name` varchar(32) DEFAULT NULL,
  `genders_id` int(11) NOT NULL DEFAULT '3',
  `birthdate` date DEFAULT NULL,
  `countries_id` int(11) NOT NULL DEFAULT '1',
  `currencies_id` int(11) NOT NULL DEFAULT '1',
  `timezones_id` int(11) NOT NULL DEFAULT '383',
  `company` varchar(64) DEFAULT NULL,
  `brand` varchar(64) DEFAULT NULL,
  `address_line1` varchar(64) DEFAULT NULL,
  `address_line2` varchar(64) DEFAULT NULL,
  `city` varchar(32) DEFAULT NULL,
  `postcode` varchar(16) DEFAULT NULL,
  `vat` varchar(24) DEFAULT NULL,
  `website` varchar(128) DEFAULT NULL,
  `about` text DEFAULT NULL,
  `_separator_register` tinyint(1) DEFAULT NULL,
  `expire` datetime DEFAULT NULL,
  `registered` datetime DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `confirm_email_hash` varchar(40) DEFAULT NULL,
  `confirm_phone_token` varchar(8) DEFAULT NULL,
  `email_confirmed` tinyint(1) NOT NULL DEFAULT '0',
  `phone_confirmed` tinyint(1) NOT NULL DEFAULT '0',
  `date_confirmed` datetime DEFAULT NULL,
  `_separator_credit` tinyint(1) DEFAULT NULL,
  `credit` decimal(10,6) NOT NULL DEFAULT '0.000000',
  `allow_negative` tinyint(1) NOT NULL DEFAULT '0',
  `_separator_api` tinyint(1) DEFAULT NULL,
  `apikey` varchar(40) DEFAULT NULL,
  `_separator_accounting` tinyint(1) DEFAULT NULL,
  `acct_last_reset` datetime DEFAULT NULL,
  `acct_rest_list` int(11) DEFAULT '0',
  `acct_rest_result` int(11) DEFAULT '0',
  `acct_rest_view` int(11) DEFAULT '0',
  `acct_rest_delete` int(11) DEFAULT '0',
  `acct_rest_update` int(11) DEFAULT '0',
  `acct_rest_insert` int(11) DEFAULT '0',
  `_separator_sharding` tinyint(1) DEFAULT NULL,
  `dbms_id` int(11) NOT NULL DEFAULT '1',
  `_separator_generic` tinyint(1) DEFAULT NULL,
  `generic_counter_1` int(11) DEFAULT '0',
  `generic_counter_2` int(11) DEFAULT '0',
  `generic_counter_3` int(11) DEFAULT '0',
  `generic_counter_4` int(11) DEFAULT '0',
  `generic_text_1` text DEFAULT NULL,
  `generic_text_2` text DEFAULT NULL,
  `generic_text_3` text DEFAULT NULL,
  `generic_text_4` text DEFAULT NULL,
  `generic_datetime_1` datetime DEFAULT NULL,
  `generic_datetime_2` datetime DEFAULT NULL,
  `generic_datetime_3` datetime DEFAULT NULL,
  `generic_datetime_4` datetime DEFAULT NULL,
  `registration_code` varchar(32) DEFAULT NULL,
  `registration_code_status` tinyint(1) DEFAULT '0',
  `privenckey` varbinary(512) DEFAULT NULL,
  `users_id` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `phone` (`phone`),
  KEY `subscription_types_id` (`subscription_types_id`),
  KEY `genders_id` (`genders_id`),
  KEY `countries_id` (`countries_id`),
  KEY `currencies_id` (`currencies_id`),
  KEY `timezones_id` (`timezones_id`),
  KEY `dbms_id` (`dbms_id`),
  CONSTRAINT `users_ibfk_1` FOREIGN KEY (`subscription_types_id`) REFERENCES `subscription_types` (`id`) ON DELETE CASCADE,
  CONSTRAINT `users_ibfk_2` FOREIGN KEY (`genders_id`) REFERENCES `genders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `users_ibfk_3` FOREIGN KEY (`countries_id`) REFERENCES `countries` (`id`) ON DELETE CASCADE,
  CONSTRAINT `users_ibfk_4` FOREIGN KEY (`currencies_id`) REFERENCES `currencies` (`id`) ON DELETE CASCADE,
  CONSTRAINT `users_ibfk_5` FOREIGN KEY (`timezones_id`) REFERENCES `timezones` (`id`) ON DELETE CASCADE,
  CONSTRAINT `users_ibfk_6` FOREIGN KEY (`dbms_id`) REFERENCES `dbms` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'admin','_',NULL,'your@email.address','',1,0,NULL,1,'2016-05-11 16:05:01','2030-12-31',NULL,'Admin','Admin',3,'2016-01-01',1,1,383,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2030-12-31 23:59:59','2016-05-11 16:05:01','2016-05-11 16:34:46',NULL,NULL,1,1,'2016-05-11 16:05:01',NULL,0.000000,1,NULL,'_',NULL,'2016-05-11 16:01:54',0,0,0,0,0,0,NULL,1,NULL,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,'_',1);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `weekdays`
--

DROP TABLE IF EXISTS `weekdays`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `weekdays` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weekday` varchar(32) NOT NULL,
  `number` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `weekdays`
--

LOCK TABLES `weekdays` WRITE;
/*!40000 ALTER TABLE `weekdays` DISABLE KEYS */;
INSERT INTO `weekdays` VALUES (1,'Sunday',0),(2,'Monday',1),(3,'Tuesday',2),(4,'Wednesday',3),(5,'Thursday',4),(6,'Friday',5),(7,'Saturday',6);
/*!40000 ALTER TABLE `weekdays` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-05-11 17:05:03
