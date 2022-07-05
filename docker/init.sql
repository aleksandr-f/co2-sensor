SET GLOBAL time_zone = 'UTC';

CREATE DATABASE IF NOT EXISTS `co2-sensor-test` CHARACTER SET utf8;
CREATE USER IF NOT EXISTS 'test'@'%' IDENTIFIED BY 'test';
GRANT ALL ON `co2-sensor-test`.* to 'test'@'%';

CREATE DATABASE IF NOT EXISTS `co2-sensor` CHARACTER SET utf8;
CREATE USER IF NOT EXISTS 'dev'@'%' IDENTIFIED BY 'dev';
GRANT ALL ON `co2-sensor`.* to 'dev'@'%';

FLUSH PRIVILEGES;