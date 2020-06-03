CREATE TABLE `extcal_cat` (
  `cat_id`     INT(11)      NOT NULL AUTO_INCREMENT,
  `cat_name`   VARCHAR(255) NOT NULL,
  `cat_desc`   TEXT         NULL,
  `cat_color`  VARCHAR(6)   NOT NULL,
  `cat_weight` INT          NOT NULL DEFAULT '0',
  `cat_icone`  VARCHAR(50)  NOT NULL,
  PRIMARY KEY (`cat_id`)
)
  COMMENT = 'eXtCal By Zoullou';

CREATE TABLE `extcal_event` (
  `event_id`            INT(11)      NOT NULL AUTO_INCREMENT,
  `cat_id`              INT(11)      NOT NULL DEFAULT '0',
  `event_title`         VARCHAR(255) NOT NULL DEFAULT '',
  `event_desc`          TEXT         NULL,
  `event_organisateur`  VARCHAR(255) NOT NULL DEFAULT '',
  `event_contact`       VARCHAR(255) NOT NULL DEFAULT '',
  `event_url`           VARCHAR(255) NOT NULL DEFAULT '',
  `event_email`         VARCHAR(255) NOT NULL DEFAULT '',
  `event_address`       TEXT         NULL,
  `event_approved`      TINYINT(1)   NOT NULL DEFAULT '0',
  `event_start`         INT(11)      NOT NULL DEFAULT '0',
  `event_end`           INT(11)      NOT NULL DEFAULT '0',
  `event_submitter`     INT(11)      NOT NULL DEFAULT '0',
  `event_submitdate`    INT(11)      NOT NULL DEFAULT '0',
  `event_nbmember`      TINYINT(4)   NOT NULL DEFAULT '0',
  `event_isrecur`       TINYINT(1)   NOT NULL,
  `event_recur_rules`   VARCHAR(255) NOT NULL,
  `event_recur_start`   INT(11)      NOT NULL,
  `event_recur_end`     INT(11)      NOT NULL,
  `event_picture1`      VARCHAR(255) NOT NULL,
  `event_picture2`      VARCHAR(255) NOT NULL,
  `event_price`         VARCHAR(255) NOT NULL DEFAULT '',
  `event_location` INT(5)       NOT NULL DEFAULT '0',
  `dohtml`              TINYINT(1)   NOT NULL DEFAULT '0',
  `event_icone`         VARCHAR(50)  NOT NULL,
  PRIMARY KEY (`event_id`)
)
  COMMENT = 'eXtCal By Zoullou';

CREATE TABLE `extcal_eventmember` (
  `eventmember_id` INT(11) NOT NULL AUTO_INCREMENT,
  `event_id`       INT(11) NOT NULL DEFAULT '0',
  `uid`            INT(11) NOT NULL DEFAULT '0',
  `status`         INT     NOT NULL DEFAULT '0',
  PRIMARY KEY (`eventmember_id`),
  UNIQUE KEY `eventmember` (`event_id`, `uid`)
)
  COMMENT = 'eXtCal By Zoullou';

CREATE TABLE `extcal_eventnotmember` (
  `eventnotmember_id` INT(11) NOT NULL AUTO_INCREMENT,
  `event_id`          INT(11) NOT NULL DEFAULT '0',
  `uid`               INT(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`eventnotmember_id`),
  UNIQUE KEY `eventnotmember` (`event_id`, `uid`)
)
  COMMENT = 'eXtCal By Zoullou';

CREATE TABLE `extcal_file` (
  `file_id`       INT(11)      NOT NULL AUTO_INCREMENT,
  `file_name`     VARCHAR(255) NOT NULL,
  `file_nicename` VARCHAR(255) NOT NULL,
  `file_mimetype` VARCHAR(255) NOT NULL,
  `file_size`     INT(11)      NOT NULL,
  `file_download` INT(11)      NOT NULL,
  `file_date`     INT(11)      NOT NULL,
  `file_approved` TINYINT(1)   NOT NULL,
  `event_id`      INT(11)      NOT NULL,
  `uid`           INT(11)      NOT NULL,
  PRIMARY KEY (`file_id`)
)
  COMMENT = 'eXtCal By Zoullou';

CREATE TABLE `extcal_location` (
  `id`           INT(5)       NOT NULL AUTO_INCREMENT,
  `nom`          VARCHAR(255) NOT NULL,
  `description`  TEXT         NULL,
  `logo`         VARCHAR(255) NOT NULL,
  `categorie`    VARCHAR(255) NOT NULL,
  `adresse`      VARCHAR(255) NOT NULL,
  `adresse2`     VARCHAR(255) NOT NULL,
  `cp`           VARCHAR(10)  NOT NULL,
  `ville`        VARCHAR(50)  NOT NULL,
  `tel_fixe`     VARCHAR(20)  NOT NULL,
  `tel_portable` VARCHAR(20)  NOT NULL,
  `mail`         VARCHAR(255) NOT NULL,
  `site`         VARCHAR(255) NOT NULL,
  `horaires`     TEXT         NULL,
  `divers`       TEXT         NULL,
  `tarifs`       TEXT         NULL,
  `map`          TEXT         NULL,

  PRIMARY KEY (`id`)
)


