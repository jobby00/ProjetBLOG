<?php

namespace MicroCMS\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use MicroCMS\Domain\Comment;
use MicroCMS\DAO\CommentDAO;
use MicroCMS\Form\Type\CommentType;

class HomeController {

    /**
     * Home page controller.
     *
     * @param Application $app Silex application
     */
    public function indexAction(Application $app) {
        $articles = $app['dao.article']->findAll();
        return $app['twig']->render('index.html.twig', array('articles' => $articles));
    }

    /**
     * All articles page controller.
     *
     * @param Application $app Silex application
     */
    public function allarticlesAction(Application $app) {
        $articles = $app['dao.article']->findAll();
        return $app['twig']->render('allarticles.html.twig', array('articles' => $articles));
    }

    /**
     * About page controller.
     *
     * @param Application $app Silex application
     */
    public function aboutAction(Application $app) {
        return $app['twig']->render('about.html.twig');

    }

    /**
     * Mentions page controller.
     *
     * @param Application $app Silex application
     */
    public function mentionsAction(Application $app) {
        return $app['twig']->render('mentions.html.twig');
    }

    /**
     * Article details with comment controller.
     *
     * @param integer $id Article id
     * @param Request $request Incoming request
     * @param Application $app Silex application
     */
    public function articleAction($id, Request $request, Application $app) {
        $article = $app['dao.article']->find($id);
        $commentFormView = null;
        $comment = new Comment();
        $comment->setArticle($article);
        $dateNow = date('Y-m-d');
        $comment->setDate($dateNow);
        $commentForm = $app['form.factory']->create(CommentType::class, $comment );
        $commentForm->handleRequest($request);
        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            $app['dao.comment']->save($comment);
            $app['session']->getFlashBag()->add('success', 'Votre commentaire a bien été ajouté.');
        }
        $commentFormView = $commentForm->createView();
        $comments = $app['dao.comment']->findAllByArticle($id);
        return $app['twig']->render('article.html.twig', array(
            'article' => $article,
            'comments' => $comments,
            'commentForm' => $commentFormView));
    }

    /**
     * Comment a comment.
     *
     * @param Request $request
     * @param Application $app
     */
    public function commentCommentAction($id, Request $request, Application $app){
        $dateNow = date('Y-m-d');
        $moderationNULL = NULL;
        $comment = new Comment();
        $comment->setAuthor($request->get("authorMessage"));
        $comment->setDate($dateNow);
        $comment->setContent($request->get("commentMessage"));
        $comment->setModeration($moderationNULL);
        $comment->setComParent($request->get("commentaireParent"));
        $article = $app['dao.article']->find($id);
        $comment->setArticle($article);
        $app['dao.comment']->save($comment);
        $app['session']->getFlashBag()->add('success', 'Votre commentaire a bien été ajouté.');
        return $app->redirect($app['url_generator']->generate('article' ,array(
            'id' => $id)));
    }

    /**
     * User login controller.
     *
     * @param Request $request Incoming request
     * @param Application $app Silex application
     */
    public function loginAction(Request $request, Application $app) {
        return $app['twig']->render('login.html.twig', array(
            'error'         => $app['security.last_error']($request),
            'last_username' => $app['session']->get('_security.last_username'),
        ));
    }
}