<?php
    /**
     * File: Comment.php
     * @author Fausto Vanin <fnsvanin@yahoo.com.br>
     * Description: The post comment
     * Release: July/2009
     *
    **/
    require_once("Person.php");
    
    class Comment {
        /**
         * @property string content The comment itself
         * @property Person visitor The person who have post the comment
         * @property int id The comment database id
         * @property Database database The database to perform the operations
         * @todo Add score to the comment
        **/
        private $content;
        private $visitor;
        private $id;
        private $database;
        
        /**
         * @method <<constructor>> ___construct
         * @param string content The message content
         * @param Person visitor A Person object representing the visitor
        **/
        public function __construct($database, $content=NULL, $visitor=NULL){
            $this->database = $database;
            $this->content = htmlspecialchars(strip_tags($content)); //A HTML safe version
            $this->visitor = $visitor;
        }
        
        /**
         * @method string getContent
         * @return The message content
        **/
        public function getContent(){
            return $this->content;
        }
        /**
         * @method Person getVisitor
         * @return A Person object
        **/
        public function getVisitor(){
            return $this->visitor;
        }
        
        /**
         * @method void setId Defines a new id for the comment
         * @param int id The id to set
         * @param bool load If true the comment values will be obtained from the
         *  database, otherwise nothing will be done
        **/
        public function setId($id, $load=false){
            $this->id = $id;
            if($load){
                $this->database->connect();
                $rs = $this->database->executeQuery("SELECT * FROM comment WHERE comment_id = {$this->id}");
                $this->visitor = new Person($rs->getField("comment_name"), $rs->getField("comment_email"));
                $this->content = $rs->getField("comment_content");
                $this->database->disconnect();
            }
        }
        
        /**
         * @method int getId
         * @return The comment id
        **/
        public function getId(){
            return $this->id;
        }
        
        /**
         * @method Person[] getCommentList Return a list of comments for a given post
         * @param Database database The Database object that will execute the query
         * @param int postId The post to search
         * @param int from The start number of the subset. The default value is 0.
         *  This value will be used only if the <to> parameter were passed.
         * @param int to The end of the subset. The default value is NULL, wich
         *  means the whole available list
        **/
        public static function getCommentList($database, $postId, $from=0, $to=NULL){
            $commentList = array();
            $limit = isset($to) && $to > $from ? " LIMIT " . $from . ", " . $to : "";
            
            $database->connect();
            $rs = $database->executeQuery("SELECT * FROM comment WHERE post_id = {$postId} {$limit} ORDER BY post_id DESC");
            echo(mysql_error());
            while($rs->next()){
                $visitor = new Person( $rs->getField("comment_name"), $rs->getField("comment_email") );
                $content = $rs->getField("comment_content");
                
                $comment = new Comment($database, $content, $visitor);
                $comment->setId($rs->getField("comment_id"));
                
                $commentList[] = $comment;
                
                $rs->moveNext();
            }
            $database->disconnect();
            
            return $commentList;
        }
        
        /**
         * @method int insert Inserts the comment in the database
         * @param int postId The id of the posto to attach the comment
         * @return 1 if the data could be inserted and 0 otherwise
        **/
        public function insert($postId){
            $this->database->connect();
            $r = $this->database->execute("INSERT INTO comment VALUES(0, {$postId}, '{$this->visitor->getName()}', '{$this->visitor->getEmail()}', '{$this->content}')");
            $this->database->disconnect();
            return $r;
        }
        
        /**
         * @method int delete Removes a comment from the database
         * @return 1 if the value could be removed or 0 otherwise
        **/
        public function delete() {
            $this->database->connect();
            $r = $this->database->execute("DELETE FROM comment WHERE comment_id = {$this->id}");
            $this->database->disconnect();
            return $r;
        }
    }
?>