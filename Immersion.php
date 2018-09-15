<?php

namespace Nozomi\Plugin\Immersion;

use \Nozomi\Core\NozomiPlugin;
use \Slim\PDO\Database;

class Immersion extends NozomiPlugin {
  public $sidebarHTML = '<ul class="nav nav-pills flex-column">
        <li class="nav-item">
            <a class="nav-link" href="/nozomi/immersion">Immersion</a>
        </li>
    </ul>';
  
  public function settings($container) {
    $container['immersionRenderer'] = function ($container) {
      $array = Array();
      $view = new \Slim\Views\Twig(__DIR__ . '/templates', [
        // 'cache' => __DIR__ . '/cache'
      ]);

      // Instantiate and add Slim specific extension
      $url = rtrim(str_ireplace('index.php', '', $container->get('request')->getUri()->getPath()), '/');

      $view->addExtension(new \Slim\Views\TwigExtension($container->get('router'), $url));
      return $view;
    };
  }
  
  public function registerRoutes() {
    $container = $this->app->getContainer();
    $this->settings($container);
    $this->app->get('/nozomi/immersion', function (\Slim\Http\Request $request, \Slim\Http\Response $response, array $args) {
      $configClass = new \Nozomi\Core\Configuration();
      $config = $configClass->GetConfig();
      if (isset($config['immersionEnabled'])) {
        return $this->immersionRenderer->render($response, 'home.html');
      } else {
        $admin = new Admin();
        $admin->Setup($config);
        $config['immersionEnabled'] = 'true';
        $configClass->WriteToConfig($config);
      }
    });
    
    $this->app->get('/immersion/assets/{name:.*}', function (Request $request, Response $response, array $args) {
      $path = $args['name'];
      $containingFolder = __DIR__ . '/';
      $filepath = $containingFolder . $path;
      $file = @file_get_contents($filepath);
      if ($file) {
        $finfo = new \Finfo(FILEINFO_MIME_TYPE);
        $response->write($file);
        $explosion = explode('.', $filepath);
        $ext = array_pop($explosion);
        if ($ext === 'svg') return $response->withHeader('Content-Type', 'image/svg+xml');
        //if ($ext === 'svg') return $response;
        else return $response->withHeader('Content-Type', $finfo->buffer($file));
      } else {
        $content = new Content();
        return $content->RenderPage($response, $this, '404');
      }
    });
    
    $this->app->group('/immersion/{guide}', function() {
      $this->get('', function ($request, $response, $args) {
        $mgr = new ContentManager();
        return $mgr->GetPage($this, $response, $args['guide'], 'index');
      });
      $this->get('/{page:.*}', function ($request, $response, $args) {
        $mgr = new ContentManager();
        return $mgr->GetPage($this, $response, $args['guide'], $args['page']);
      });
    });
  }
}