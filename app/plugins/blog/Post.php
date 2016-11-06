<?php
    /**
     * File: Post.php
     * @author Fausto Vanin <fnsvanin@yahoo.com.br>
     * Description: This class represents a blog post
     * Release: July/2009
     *
    **/
    require_once("Author.php");
    require_once("Comment.php");
    class Post {
        /**
         * @property dateTime
         * @property title
         * @property author
         * @property content
         * @property id
         * @property commentList
         * @property cathegoryList
         **/
        private $dateTime;
        private $title;
        private $author;
        private $content;
        private $id;
        private $database;
        private $commentList = array();
        private $cathegoryList = array();
        
        /**
         * @method <<constructor>> __construct Post constructor
         * @param Database database The database to execute operations
         **/
        public function __construct($database){
            $this->database = $database;
        }
        
        /**
         * @method void setDateTime
         * @param DateTime dateTime
        **/
        public function setDateTime($dateTime){
            $this->dateTime = $dateTime;
        }
        
        /**
         * @method DateTime getDateTime
         * @param string format If a format string is given, the post date
         *  will be formated according to it. Otherwise it will be returned
         *  with its current format
         * @return Return the post date and time
        **/
        public function getDateTime($format=NULL){
            if($format){
                $timestamp = strtotime($this->dateTime);
                return date($format, $timestamp);
            }
            return $this->dateTime;
        }
        
        /**
         * @method void setTitle
         * @param string title
        **/
        public function setTitle($title){
            $this->title = $title;
        }
        
        /**
         * @method string getTitle
         * @return The post title
        **/
        public function getTitle(){
            return $this->title;
        }
        
        /**
         * @method void setAuthor
         * @param Author author The post author
        **/
        public function setAuthor($author){
            $this->author = $author;
        }
        
        /**
         * @method Author getAuthor
         * @return The post author object
        **/
        public function getAuthor(){
            return $this->author;
        }
        
        /**
         * @method void setContent
         * @param string content
        **/
        public function setContent($content){
            $this->content = htmlspecialchars($content);
        }
        
        /**
         * @method string getContent
         * @return The post content
        **/
        public function getContent(){
            return $this->content;
        }
        
        /**
         * @method void setId Defines the id of the post
         * @param int id The post id
         * @param bool load Optional. If true the other attributes of the post
         *  will be obtained from the database
        **/
        public function setId($id, $load=false){
            $this->id = $id;
            if($load){
                $this->database->connect();
                $rs = $this->database->executeQuery("SELECT * FROM post WHERE post_id = " . $this->id);
                
                $this->title = $rs->getField("post_title");
                $this->setContent($rs->getField("post_content"));
                $this->dateTime = $rs->getField("post_date");
                
                $author = new Author($this->database);
                $author->setId($rs->getField("author_id"), true);
                $this->author = $author;
                
                $this->commentList = Comment::getCommentList($this->database, $this->id);
                
                $this->database->disconnect();
            }
        }
        
        /**
         * @method int getId
         * @return The post id
        **/
        public function getId(){
            return $this->id;
        }
        
        /**
         * @method string getLastNPosts
         * @param Database database The database to perform the query
         * @param int n The number of posts to return
         * @return the last N posts
        **/
        public static function getLastNPosts($database, $n){
            $postList = array();
            $database->connect();
            $rs = $database->executeQuery("SELECT post_id FROM post ORDER BY post_date DESC LIMIT 0, " . $n);
            while($rs->next()){
                $post = new Post($database);
                $post->setId( $rs->getField("post_id"), true );
                $postList[] = $post;
                $rs->moveNext();
            }
            $database->disconnect();
            
            return $postList;
        }
        
        /**
         * @method int insert Inserts a new post into the database
         * @return 1 if the value was inserted and 0 otherwise
        **/
        public function insert(){
            $this->database->connect();
            $r = $this->database->execute("INSERT INTO post VALUES(0, {$this->author->getId()}, NULL, '{$this->title}', '{$this->content}')");
            $this->database->disconnect(); 
            return $r;
        }
        
        /**
         * @method int update Updates the post data into the database
         * @return 1 if the value was updated and 0 otherwise
        **/
        public function update(){
            $this->database->connect();
            return $this->database->execute("UPDATE post SET post_title = '{$this->title}', post_content='{$this->content}' WHERE post_id = {$this->id}");
        }
        
        /**
         * @method int delete Removes a post from the database
         * @return 1 if the post was deleted and 0 otherwise
        **/
        public function delete(){
            foreach($this->commentList as $comment){
                $comment->delete();
            }
            $this->database->connect();
            return $this->database->execute("DELETE FROM post WHERE post_id = {$this->id}");
        }
        
        /**
         * @method Comment[] getCommentList
         * @return All the comments of a post
        **/
        public function getCommentList(){
            return $this->commentList;
        }
        
        /**
         * @method int getCommentCount
         * @return The number of comments of the post
        **/
        public function getCommentCount() {
            return count($this->commentList);
        }
    }
?>