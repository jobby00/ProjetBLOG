<?php

namespace MicroCMS\DAO;

use MicroCMS\Domain\Comment;

class CommentDAO extends DAO
{
    /**
     * @var \MicroCMS\DAO\ArticleDAO
     */
    private $articleDAO;

    public function setArticleDAO(ArticleDAO $articleDAO) {
        $this->articleDAO = $articleDAO;
    }


    /**
     * Return a list of all comments for an article, sorted by date (most recent last).
     *
     * @param integer $articleId The article id.
     *
     * @return array A list of all comments for the article.
     */
    public function findAllByArticle($articleId) {
        // The associated article is retrieved only once
        $article = $this->articleDAO->find($articleId);
        // art_id is not selected by the SQL query
        // The article won't be retrieved during domain objet construction
        $sql = "select com_id, com_author, com_date, com_content, com_parent_id, com_moderation from t_comment where art_id=? order by com_date";
        $result = $this->getDb()->fetchAll($sql, array($articleId));
        // Convert query result to an array of domain objects
        $comments = array();
        foreach ($result as $row) {
            $comId = $row['com_id'];
            $comment = $this->buildDomainObject($row);
            // The associated article is defined for the constructed comment
            $comment->setArticle($article);
            $comments[$comId] = $comment;
        }
        return $comments;
    }

    /**
     * Creates an Comment object based on a DB row.
     *
     * @param array $row The DB row containing Comment data.
     * @return \MicroCMS\Domain\Comment
     */
    protected function buildDomainObject(array $row) {
        $comment = new Comment();
        $comment->setId($row['com_id']);
        $comment->setAuthor($row['com_author']);
        $comment->setDate($row['com_date']);
        $comment->setContent($row['com_content']);
        $comment->setModeration($row['com_moderation']);
        $comment->setComParent($row['com_parent_id']);
        if (array_key_exists('art_id', $row)) {
            // Find and set the associated article
            $articleId = $row['art_id'];
            $article = $this->articleDAO->find($articleId);
            $comment->setArticle($article);
        }
        return $comment;
    }

    /**
     * Returns a comment matching the supplied id.
     *
     * @param integer $id
     *
     * @return \MicroCMS\Domain\Comment|throws an exception if no matching article is found
     */
    public function find($id) {
        $sql = "select * from t_comment where com_id=?";
        $row = $this->getDb()->fetchAssoc($sql, array($id));

        if ($row)
            return $this->buildDomainObject($row);
        else
            throw new \Exception("No comment matching id " . $id);
    }


    /**
     * Saves a comment into the database.
     *
     * @param \MicroCMS\Domain\Comment $comment The comment to save
     */
    public function save(Comment $comment) {
        $commentData = array(
            'art_id' => $comment->getArticle()->getId(),
            'com_author' => $comment->getAuthor(),
            'com_date' => $comment->getDate(),
            'com_content' => $comment->getContent(),
            'com_parent_id' => $comment->getcomParent(),
            'com_moderation' => $comment->getModeration(),
        );

        if ($comment->getId()) {
            // The comment has already been saved : update it
            $this->getDb()->update('t_comment', $commentData, array('com_id' => $comment->getId()));
        } else {
            // The comment has never been saved : insert it
            $this->getDb()->insert('t_comment', $commentData);
            // Get the id of the newly created comment and set it on the entity.
            $id = $this->getDb()->lastInsertId();
            $comment->setId($id);
        }
    }

    /**
     * Returns a list of all comments, sorted by date (most recent first).
     *
     * @return array A list of all comments.
     */
    public function findAll() {
        $sql = "select * from t_comment order by com_id desc";
        $result = $this->getDb()->fetchAll($sql);

        // Convert query result to an array of domain objects
        $entities = array();
        foreach ($result as $row) {
            $id = $row['com_id'];
            $entities[$id] = $this->buildDomainObject($row);
        }
        return $entities;
    }

    /**
     * Removes all comments for an article
     *
     * @param $articleId The id of the article
     */
    public function deleteAllByArticle($articleId) {
        $this->getDb()->delete('t_comment', array('art_id' => $articleId));
    }

    /**
     * Removes a comment from the database.
     *
     * @param @param integer $id The comment id
     */
    public function delete($id) {
        // Find the child of comment
        $sql = "select * from t_comment where com_parent_id=?";
        $child = $this->getDb()->fetchAssoc($sql, array($id));
        if ($child) {
            $IDChild = $child['com_id'];
            // Find the child of the child comment
            $sql = "select * from t_comment where com_parent_id=?";
            $childChild = $this->getDb()->fetchAssoc($sql, array($IDChild));
            if ($childChild) {
                $IDChildChild = $childChild['com_id'];
                $this->getDb()->delete('t_comment', array('com_id' => $IDChildChild));
            }
            $this->getDb()->delete('t_comment', array('com_parent_id' => $id));
        }


        $this->getDb()->delete('t_comment', array('com_id' => $id));
    }

}
