<?php

namespace MicroCMS\Domain;

class Comment
{
    /**
     * Comment id.
     *
     * @var integer
     */
    private $id;

    /**
     * Comment author.
     *
     * @var string
     */
    private $author;

    /**
     * Comment Date.
     *
     * @var date
     */
    private $date;


    /**
     * Comment content.
     *
     * @var integer
     */
    private $content;


    /**
     * Associated article.
     *
     * @var \MicroCMS\Domain\Article
     */
    private $article;


    /**
     * Associated Comment.
     *
     * @var integer
     */
    private $comParent;


    /**
     * Comment Moderation statut.
     *
     * @var string : yes or NULL
     */
    private $moderation;



    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    public function getAuthor() {
        return $this->author;
    }

    public function setAuthor($author) {
        $this->author = $author;
        return $this;
    }

    public function getDate() {
        return $this->date;
    }

    public function setDate($date) {
        $this->date = $date;
        return $this;
    }

    public function getContent() {
        return $this->content;
    }

    public function setContent($content) {
        $this->content = $content;
        return $this;
    }

    public function getArticle() {
        return $this->article;
    }

    public function setArticle(Article $article) {
        $this->article = $article;
        return $this;
    }

    public function getComParent() {
        return $this->comParent;
    }

    public function setComParent($comParent) {
        $this->comParent = $comParent;
        return $this;
    }

    public function getModeration() {
        return $this->moderation;
    }

    public function setModeration($moderation) {
        $this->moderation = $moderation;
        return $this;
    }

}
