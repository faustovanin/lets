<?php
    /**
     * File: BlogObserver.php
     * @author Fausto Vanin <fnsvanin@yahoo.com.br>
     * Description: A blog plug-in
     * Release: July/2009
     *
    **/
    
    class BlogObserver implements Observer {
        /**
         * @property string title The blog title
         * @property string description A short description
         * @property Post postLIst The list of posts of the blog
         * @property Database database The Database object that will execute the queries
         * @property Author author The logged author
         * @property int postsPerView The number of posts to show per view
         * @property LOGIN_ERROR A constant to map the login error
         * @property WRONG_ACCESS A constant to map invalid session access
         * @property DB_ERROR A constant to map database errors
        **/
        private $title;
        private $description;
        private $postList = array();
        private $database;
        private $author = NULL;
        private $postsPerView;
        const LOGIN_ERROR = 0;
        const WRONG_ACCESS = 1;
        const DB_ERROR = 2;
        
        /**
         * @method <<constructor>> __construct Cass constructor
         * @param string configFile The configuration XML file
        **/
        public function __construct(){
            /**
             * @todo Validate if the file exists
            **/
            $configFileName = "app/plugins/blog/config.xml";
            
            $configDocument = new DOMDocument();
            $configDocument->load($configFileName);
            
            //==================Database Section================================
            $databaseAccess = $configDocument->getElementsByTagName("database")->item(0);
            
            $host = $databaseAccess->getAttribute("host");
            $port = $databaseAccess->getAttribute("port");
            $user = $databaseAccess->getAttribute("user");
            $pass = $databaseAccess->getAttribute("password");
            $dbname = $databaseAccess->getAttribute("dbname");
            
            $this->database = new MySQLDB($dbname, $host, $port, $user, $pass);
            //==========================End=====================================
            
            //======================Descriptive Data============================
            $titleNode = $configDocument->getElementsByTagName("title")->item(0);
            $descriptionNode = $configDocument->getElementsByTagName("description")->item(0);
            $postsPerViewNode = $configDocument->getElementsByTagName("posts-per-view")->item(0);
            
            $this->title = $titleNode->textContent;
            $this->description = $descriptionNode->textContent;
            $this->postsPerView = $postsPerViewNode->textContent;
            //==========================End=====================================
        }
        
        /**
         * @method string getPreamble Implementation of Observer
         * @return The preamble of the blog
        **/
        public function getPreamble(){
            return "<script language='JavaScript' type='text/javascript' src='htm/rte/richtext.js'></script>
            
            <script language='JavaScript' type='text/javascript'>
                
                function submitForm() {
                        updateRTE('rte1');
                        return false;
                }
                initRTE('htm/rte/images/', 'htm/rte/', 'htm/rte/');
            </script>";
        }
        
        /**
         * @method string doAction Implementation of Observer
         * @param Message message The message for the action
        **/
        public function doAction($message){
            $request = $message->getRequest();
            $session = $message->getSession();
            $response = new Response();
            $authorId = $session->getAttribute("author_id");
            if($authorId){
                $this->author = new Author($this->database);
                $this->author->setId($authorId, true);
            }
            switch($request->getId()){
                case "login":
                    $response->append($this->getFormLogin());
                    break;
                case "logout":
                    $session->clear();
                    $response->append($this->getFormLogin());
                    break;
                case "do_login":
                    $this->author = Author::validate($this->database, $request->getParameter("email"), $request->getParameter("password"));
                    if(!$this->author){
                        $response->append($this->getErrorMessage(self::LOGIN_ERROR));
                        $response->append($this->getFormLogin($request->getParameter("email")));
                    }
                    else{
                        $this->postList = Post::getLastNPosts($this->database, $this->postsPerView);
                        $response->append( $this->getView() );
                        $session->setAttribute("author_id", $this->author->getId());
                    }
                    break;
                case "add_post":
                    if(!$this->author){
                        $response->append($this->getErrorMessage(self::WRONG_ACCESS));
                        $response->append($this->getFormLogin());
                    }
                    else {
                        $response->append($this->getRichPostForm());
                    }
                    break;
                case "do_post":
                    if(!$this->author){
                        $response->append($this->getErrorMessage(self::WRONG_ACCESS));
                        $response->append($this->getFormLogin());
                    }
                    else{
                        $post = new Post($this->database);
                        $post->setAuthor($this->author);
                        $post->setContent($request->getParameter("content"));
                        $post->setTitle($request->getParameter("title"));
                        if(!$post->insert()){
                            $response->append($this->getErrorMessage(self::DB_ERROR));
                        }
                        $this->postList = Post::getLastNPosts($this->database, $this->postsPerView);
                        $response->append($this->getView());
                    }
                    break;
                case "edit_post":
                    if(!$this->author){
                        $response->append($this->getErrorMessage(self::WRONG_ACCESS));
                        $response->append($this->getFormLogin());
                    }
                    else{
                        $post = new Post($this->database);
                        $post->setId($request->getParameter("post"), true);
                        $response->append( $this->getFormPost($post) );
                    }
                    break;
                case "do_edit_post":
                    if(!$this->author){
                        $response->append($this->getErrorMessage(self::WRONG_ACCESS));
                        $response->append($this->getFormLogin());
                    }
                    else{
                        $post = new Post($this->database);
                        $post->setId( $request->getParameter("post_id") );
                        $post->setTitle( $request->getParameter("title") );
                        $post->setContent( $request->getParameter("content") );
                        if( !$post->update() ){
                            $response->append($this->getErrorMessage(self::DB_ERROR));
                        }
                        $this->postList = Post::getLastNPosts($this->database, $this->postsPerView);
                        $response->append( $this->getView() );
                    }
                    break;
                case "remove_post":
                    if(!$this->author){
                        $response->append($this->getErrorMessage(self::WRONG_ACCESS));
                        $response->append($this->getFormLogin());
                    }
                    else{
                        $post = new Post($this->database);
                        $post->setId( $request->getParameter("post"), true );
                        if( !$post->delete() ){
                            $response->append($this->getErrorMessage(self::DB_ERROR));
                        }
                        $this->postList = Post::getLastNPosts($this->database, $this->postsPerView);
                        $response->append( $this->getView() );
                    }
                    break;
                case "comment_post":
                    $postId = $request->getParameter("post");
                    $post = new Post($this->database);
                    $post->setId($postId, true);
                    $response->append($this->getCommentForm($post));
                    break;
                case "do_comment":
                    $postId = $request->getParameter("post_id");
                    $visitor = new Person($request->getParameter("name"), $request->getParameter("email"));
                    $comment = new Comment($this->database, $request->getParameter("comment"), $visitor);
                    if(!$comment->insert($postId)){
                        $response->append($this->getErrorMessage(self::DB_ERROR));
                    }
                    $this->postList = Post::getLastNPosts($this->database, $this->postsPerView);
                    $response->append( $this->getView() );
                    break;
                case "comment_remove":
                    if(!$this->author){
                        $response->append($this->getErrorMessage(self::WRONG_ACCESS));
                        $response->append($this->getFormLogin());
                    }
                    else{
                        $commentId = $request->getParameter("comment_id");
                        $comment = new Comment($this->database);
                        $comment->setId($commentId, true);
                        if(!$comment->delete()){
                            $response->append($this->getErrorMessage(self::DB_ERROR));
                        }
                        $this->postList = Post::getLastNPosts($this->database, $this->postsPerView);
                        $response->append( $this->getView() );
                    }
                    break;
                default:
                    $this->postList = Post::getLastNPosts($this->database, $this->postsPerView);
                    $response->append( $this->getView() );
                    return $response;
            }
            return $response;
        }
        
        /**
         * @method string getView
         * @return The HTML version of the posts
        **/
        public function getView(){
            $html = $this->getAuthorMenu();
            
            foreach($this->postList as $post){
                $html .= "<div class='post'>";
                $author = $post->getAuthor();
                $html .= "<p class='post_title'>
                            {$post->getTitle()} ({$author->getName()}) |
                            <span class='post_date'>
                                {$post->getDateTime("d/m/Y - H:m:s")}
                            </span>
                        </p>";
                $html .= "<p class='post_content'>{$post->getContent()}</p>";
                $html .= "<div class='comment'>";
                foreach($post->getCommentList() as $comment){
                    $visitor = $comment->getVisitor();
                    $html .= "<p class='comment_name'>{$visitor->getName()}</p>";
                    $html .= "<p class='comment_content'>{$comment->getContent()}</p>";
                    $html .= $this->getCommentMenu($comment);
                }
                $html .= "</div>";
                $html .= $this->getPostMenu($post);
                $html .= "</div>";
            }
            
            return $html;
        }
        
        /**
         * @method string getFormLogin
         * @param string email If wrong data was provided it re-enter the email
         * @return The form login string
        **/
        public function getFormLogin($email=NULL){
            $html = $this->getAuthorMenu();
            $html .= "<form action='do_login' method='post'>";
            $html .= $email ? "Email <input type='text' name='email' value='{$email}'/>"
                            : "Email <input type='text' name='email'/>";
            $html .= "Password <input type='password' name='password' />
                            <input type='submit' />
                        </form>";
            return $html;
        }
        
        /**
         * @method string getErrorMessage
         * @param int errorCode The error code
         * @return A string containing the message for the error
        **/
        public function getErrorMessage($errorCode){
            switch($errorCode){
                case self::LOGIN_ERROR:
                    $msg ="Invalid data provided.";
                    break;
                case self::WRONG_ACCESS:
                    $msg = "Wrong access. Maybe your session expired or you don't have permission to view this content";
                    break;
                case self::DB_ERROR:
                    $msg = "A database error was encountered. Please call the administrator.";
                    break;
                default:
                    $msg = "An error occurred.";
            }
            return "<p class='error'>{$msg}</p>";
        }
        
        /**
         * @method string getFormPost
         * @param Post post Optional. A Post object for edit mode
         * @return A HTML form for the post
        **/
        public function getFormPost($post=NULL){
            $html = $this->getAuthorMenu();
            if($post){
                $html .= "<form action='do_edit_post' method='post'>
                        <input type='hidden' name='post_id' value='{$post->getId()}' />
                        Title<input type='text' name='title' value='{$post->getTitle()}' /><br/>
                        Content<br/><textarea name='content' cols='50' rows='15'>{$post->getContent()}</textarea><br/>
                        <input type='submit'/>
                    </form>";
            }
            else{
                $html .= "<form action='do_post' method='post'>
                            Title<input type='text' name='title'/><br/>
                            Content<br/><textarea name='content' cols='50' rows='15'>Content</textarea><br/>
                            <input type='submit'/>
                        </form>";
            }
            return $html;
        }
        
        /**
         * @method string getAuthorMenu
         * @return A HTML with the Author options
        **/
        public function getAuthorMenu(){
            $html .= "<p class='blog_title'><a href='home'>{$this->title}</a></p>";
            $html .= "<p class='blog_description'>{$this->description}</p>";
            $html .= "<div class='author_menu'>";            
            if(!$this->author){
                $html .= "<a href='login'>Login</a>";
            }
            else {
                $html .= "Author: {$this->author->getName()}";
                $html .= " | <a href='add_post'>Add Post</a>
                            | <a href='logout'>Logout</a>";
            }
            $html .= "</div>";
            return $html;
        }
        
        /**
         * @method string getPostMenu
         * @param Post post A post object
         * @return A HTML with the menu for each post
         **/
        public function getPostMenu($post){
            $html = "<div class='post_menu'>";
            $html .= "<a href='comment_post?post={$post->getId()}'>Comment ({$post->getCommentCount()})</a>";
            if($this->author){
                $html .= " | <a href='edit_post?post={$post->getId()}'>Edit</a>";
                $html .= " | <a href='remove_post?post={$post->getId()}'>Remove</a>";
            }
            $html .= "</div>";
            return $html;
        }
        
        /**
         * @method string getCommentForm
         * @param Post post A Post object to attach the comment
         * @return A HTML form to the comment
        **/
        public function getCommentForm($post){
            $html = $this->getAuthorMenu();
            $html .= "<p class='post_view'>{$post->getContent()}</p>";
            $html .= "<form action='do_comment' method='post'>
                        <input type='hidden' name='post_id' value='{$post->getId()}' />";
            if($this->author){
                $html .= "Name <input type='text' name='name' value='{$this->author->getName()}' readonly /><br/>
                        Email <input type='text' name='email' value='{$this->author->getEmail()}' readonly /><br/>";
            }
            else {
                $html .= "Name <input type='text' name='name' /><br/>
                        Email <input type='text' name='email' /><br/>";
            }
            $html .= "Comment<br/>
                        <textarea cols='80' rows='15' name='comment'>Comment</textarea>
                        <input type='submit' />
                    </form>";
            return $html;
        }
        
        /**
         * @method string getCommentMenu
         * @param Comment comment A Comment object to operate
         * @return the menu for each comment
        **/
        public function getCommentMenu($comment){
            $html = "";
            if($this->author){
                $html .= "<div class='comment_menu'>";
                $html .= "<a href='comment_remove?comment_id={$comment->getId()}'>Remove</a><br/>";
                $html .= "</div>";
            }
            return $html;
        }
        
        /**
         * @method string getRichPostForm
         * @return A form with rich interface
        **/
        public function getRichPostForm(){
            $html = $this->getAuthorMenu();
            $html .= "<form id='post_form' action='do_post' method='post' onsubmit='return submitForm();'>
                            Title<input type='text' name='title'/><br/>
            <div id='rteDiv'></div>
            <script language='JavaScript' type='text/javascript'>
                document.getElementById('rteDiv').innerHTML = getRichText('rte1', 'htm/rte/', 400, 200, true, false);
		enableDesignMode('rte1', 'htm/rte/', true);
            </script>
            <input type='submit' />
            </form>";
            
            return $html;
        }
    }
?>