<?php
namespace Nozomi\Plugin\Immersion;

use \Nozomi\Core\Configuration;
use \Slim\PDO\Database;

class ContentManager {
  function GetPage($app, $rs, $guide, $page) {
    $conf = new Configuration();
    $config = $conf->GetConfig();
    //echo json_encode($config);
    $s = $config['sqlhost'];
    $d = $config['sqldb'];
    $u = $config['sqluser'];
    $p = $config['sqlpass'];
    $conn = new Database("mysql:host=$s;dbname=$d", $u, $p);
    // set the PDO error mode to exception
    $conn->setAttribute(Database::ATTR_ERRMODE, Database::ERRMODE_EXCEPTION);

    $stmt = $conn->prepare("SELECT * FROM `immersion_guides` WHERE `guide_uri` = ? LIMIT 1");
    $stmt->execute([$guide]);
    $guideRow = $stmt->fetch();
    $guideTable = $guideRow['guide_table'];
    
    $stmt = $conn->prepare("SELECT `template`,`content`,`title` FROM `{$guideTable}` WHERE `name` = ? LIMIT 1");
    $stmt->execute([$page]);
    $x = $stmt->fetch();

    $stmt = $conn->prepare("SELECT `template`,`content`,`title` FROM pages WHERE `name` = ? LIMIT 1");
    $stmt->execute(['404']);

    $notfound = $stmt->fetch();

    if($x) {
      $templateDir = 'themes/'.$config['theme'];
      $template = $templateDir.'/'.$x['template'];

      return $app->siteRenderer->render($rs, $template, $x);
    } else {
      $templateDir = 'themes/'.$config['theme'];
      $template = $templateDir.'/'.$notfound['template'];

      if ($notfound) return $app->siteRenderer->render($rs, $template, $notfound);
      else return $app->nozomiRenderer->render($rs, '404.html');
    }
  }
}