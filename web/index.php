<?php

use Kilte\Silex\Pagination\PaginationServiceProvider;
use Silex\Application;
use Silex\Provider\TwigServiceProvider;

require_once __DIR__.'/../vendor/autoload.php';

$app = new Silex\Application();

require __DIR__.'/../app/config/dev.php';
require __DIR__.'/../app/app.php';
require __DIR__.'/../app/routes.php';
$app = new Application(array('debug' => true));

$app->register(new PaginationServiceProvider, array('pagination.per_page' => 2))
    ->register(new TwigServiceProvider, array('twig.path' => __DIR__ . '/views/'));
$app
    ->get(
        '/{page}',
        function ($page) use ($app) {
            /** @var \Kilte\Pagination\Pagination $pagination */
            $pagination = $app['pagination'](100, $page);
            $pages      = $pagination->build();
            /** @var $twig \Twig_Environment */
            $twig = $app['twig'];

            return $twig->render('pagination.twig', array('pages' => $pages, 'current' => $pagination->currentPage()));
        }
    )
    ->value('page', 1)
    ->convert(
        'page',
        function ($page) {
            return (int) $page;
        }
    );

$app->run();
