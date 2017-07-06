<?php
use Symfony\Component\HttpFoundation\Request;
use MicroCMS\Domain\Comment;
use MicroCMS\Domain\Article;
use MicroCMS\Domain\User;
use MicroCMS\Form\Type\CommentType;
use MicroCMS\Form\Type\ArticleType;
use MicroCMS\Form\Type\UserType;


// Home page
$app->get('/', "MicroCMS\Controller\HomeController::indexAction")
    ->bind('home');

// About page
$app->get('/about', "MicroCMS\Controller\HomeController::aboutAction")
    ->bind('about');

// Mentions page
$app->get('/mentions', "MicroCMS\Controller\HomeController::mentionsAction")
    ->bind('mentions');

// Article with info
$app->match('/article/{id}', "MicroCMS\Controller\HomeController::articleAction")
    ->bind('article');

//add comment to a comment
$app->post('article/comment/{id}/comment', "MicroCMS\Controller\HomeController::commentCommentAction")
    ->bind('comment_comment');

// Login form
$app->get('/login', "MicroCMS\Controller\HomeController::loginAction")
    ->bind('login');

// Admin zone
$app->get('/admin', "MicroCMS\Controller\AdminController::indexAction")
    ->bind('admin');

// Add a new article
$app->match('/admin/article/add', "MicroCMS\Controller\AdminController::addArticleAction")
    ->bind('admin_article_add');

// Edit an existing article
$app->match('/admin/article/{id}/edit', "MicroCMS\Controller\AdminController::editArticleAction")
    ->bind('admin_article_edit');

// Remove an article
$app->get('/admin/article/{id}/delete', "MicroCMS\Controller\AdminController::deleteArticleAction")
    ->bind('admin_article_delete');

// Signal a comment
$app->get('/comment/{id}/signal', "MicroCMS\Controller\AdminController::signalCommentAction")
    ->bind('comment_signal');

// Valid a comment
$app->get('/admin/comment/{id}/valid', "MicroCMS\Controller\AdminController::validCommentAction")
    ->bind('admin_comment_valid');

// Edit an existing comment
$app->match('/admin/comment/{id}/edit', "MicroCMS\Controller\AdminController::editCommentAction")
    ->bind('admin_comment_edit');

// Remove a comment
$app->get('/admin/comment/{id}/delete', "MicroCMS\Controller\AdminController::deleteCommentAction")
    ->bind('admin_comment_delete');

// Add a user
$app->match('/admin/user/add', "MicroCMS\Controller\AdminController::addUserAction")
    ->bind('admin_user_add');

// Edit an existing user
$app->match('/admin/user/{id}/edit', "MicroCMS\Controller\AdminController::editUserAction")
    ->bind('admin_user_edit');

// Remove a user
$app->get('/admin/user/{id}/delete', "MicroCMS\Controller\AdminController::deleteUserAction")
    ->bind('admin_user_delete');