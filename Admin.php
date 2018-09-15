<?php
namespace Nozomi\Plugin\Immersion;

use \Slim\PDO\Database;

class Admin {
  function Setup($config) {
        $s = $config['sqlhost'];
        $d = $config['sqldb'];
        $u = $config['sqluser'];
        $p = $config['sqlpass'];
        $conn = new Database("mysql:host=$s;dbname=$d", $u, $p);
        // set the PDO error mode to exception
        $conn->setAttribute(Database::ATTR_ERRMODE, Database::ERRMODE_EXCEPTION);
        $sql = "CREATE TABLE IF NOT EXISTS `immersion_guides`(`id` int(10) NOT NULL auto_increment, `guide_title` varchar(255), `guide_table` varchar(255), `guide_uri` varchar(255), PRIMARY KEY(`id`) );";
        $conn->exec($sql);
        $sql = "CREATE TABLE `immersion_ex`( `id` INT(10) NOT NULL AUTO_INCREMENT, `name` VARCHAR(260) NOT NULL, `title` VARCHAR(32) NOT NULL, `author` VARCHAR(50) NOT NULL, `content` TEXT NOT NULL, `template` VARCHAR(50) NOT NULL DEFAULT 'default.html', `last-modified` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, PRIMARY KEY (`id`), UNIQUE INDEX `name` (`name`)) COLLATE='utf8_general_ci' ENGINE=InnoDB;";
        $conn->exec($sql);
        $sql = "INSERT INTO `immersion_ex` (`name`, `title`, `author`, `content`) VALUES ('index', 'Home', 'nozomi', '<h1>Welcome to the Immersion plugin!</h1>');";
        $conn->exec($sql);
        $sql = "INSERT INTO `immersion_guides` (`id`, `guide_title`, `guide_table`, `guide_uri`) VALUES (NULL, 'Example Guide', 'immersion_ex', 'example');";
        $conn->exec($sql);
  }
}