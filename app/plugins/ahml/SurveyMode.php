<?php
    /**
     * File: 
     * @author Fausto Vanin <fnsvanin@yahoo.com.br> and
     * Caio Cesar Ferreira <kumppler@gmail.com>
     * Description: This module was developed as part the reaserch "Seleção
     *  Automática de Amostras em Pesquisa Digital de Opinião" of the Tuiuti
     *  University of Paraná.
     *  Implementation of the Mode interface for whole survey answer mode
     * Release: November/2009
     **/
    
    require_once("Mode.php");
    
    class SuveyMode extends Mode{
        public function processMessage($message){
            parent::processMessage($message);
            switch($this->request->getId()){
                case "start":
                    $surveyName = $this->session->getAttribute("survey_name");
                    $answerList = new AnswerList($this->surveyList[$surveyName], $this->session->getId());
                    $survey = new Survey($surveyName, $answerList);
                    $question = $survey->getQuestion();
                    $this->response->append($question->getView());
                    $this->session->setAttribute("actual_question", $question->getId());
                    break;
                case "finish":
                    break;
                case "answer_question":
                    $surveyName = $this->session->getAttribute("survey_name");
                    $answerList = new AnswerList($this->surveyList[$surveyName], $this->session->getId());
                    
                    $survey = new Survey($surveyName, $answerList);
                    $priorQuestionId = $this->session->getAttribute("actual_question");
                    $priorQuestion = $survey->getQuestionById($priorQuestionId);
                    
                    for($i=0; $i<$priorQuestion->getInputCount(); $i++) {
                        $input = $priorQuestion->getInput($i);
                        $answerContent = $this->request->getParameter($input->getId());
                        $answerList->setAnswer($priorQuestionId, $input->getId(), $answerContent);
                        //$input->setAnswer(new Answer($priorQuestion->getType(), $answerContent));
                    }
                    $answerList->save();
                    $answerList = new AnswerList($this->surveyList[$surveyName], $this->session->getId());
                    $survey = new Survey($surveyName, $answerList);
                    $priorQuestion = $survey->getQuestionById($priorQuestionId);
                    $question = $survey->getQuestion($priorQuestion);
                    if($question){
                        $this->response->append($question->getView());
                        $this->session->setAttribute("actual_question", $question->getId());
                    }
                    else {
                        $this->finish($survey);
                    }
                    break;
                default:
                    $oldSession = $this->cookie->getAttribute("id");
                    try {
                        if(!$oldSession) {
                            $this->cookie->setAttribute("id", $this->session->getId(), Time::years(1));
                            $survey = $this->getSurvey();
                            $surveyList = array();
                            $surveyList[] = $survey->getFileName();
                            $this->cookie->setAttribute("survey_list", $surveyList, Time::years(1));
                        }
                        else {
                            $this->cookie->setAttribute("id", $oldSession, Time::years(1));
                            $surveyList = $this->cookie->getAttribute("survey_list");
                            if(!$surveyList)
                                $survey = $this->getSurvey();
                            else $survey = $this->getSurvey($surveyList);
                        }
                        $this->session->setAttribute("survey_name", $survey->getFileName());
                        $this->response->append($this->viewDisclaimer($survey));
                    }
                    catch(Exception $e) {
                        foreach($this->surveyList as $surveyName => $surveyAnswer) {
                            $answerList = new AnswerList($surveyAnswer, $this->session->getId());
                            $survey = new Survey($surveyName, $answerList);
                            $li .= "<li>{$survey->getTitle()} <em>({$survey->getAuthor()->getName()})</em></li>";
                        }
                        $message = "<h1>You have already responded:</h1>";
                        $message .= "<ul>{$li}</ul>";
                        $this->response->append($message);
                    }
            }
            return $this->response;
        }
        
        /**
         * @method String viewDisclaimer
         * @param Survey survey The survey object to extract the disclaimer
         * @return The survey presentation and disclaimer message with a button
         *  to start
        **/
        public function viewDisclaimer($survey) {
            $html .= "<h1 class='title'>{$survey->getTitle()}</h1>";
            $html .= "<h2 class='authors'>{$survey->getAuthor()->getName()}</h2>";
            $html .= "<h3 class='description'>{$survey->getDescription()}</h3>";
            $html .= "<p class='disclaimer'>{$survey->getDisclaimer()}</p>";
            
            $html .= "<form action='start' method='post'>
                        <input type='submit' value='Iniciar' />
                    </form>";
            return $html;
        }
        
        /**
         * @method Survey getSurvey
         * @param Array blackList The list of responded surveys
         * @return A not responded survey
        **/
        public function getSurvey($blackList=array()) {
            //foreach($this->surveyList as $survey) {
            $list = $this->surveyList;
            while( count($list) ) {
                $survey = array_rand($list);
                $answer = $list[$survey];
                $inList = false;
                foreach($blackList as $blSurvey) {
                    if($survey == $blSurvey) {
                        $inList = true;
                    }
                }
                if(!$inList) {
                    $answerList = new AnswerList($answer, $this->session->getId());
                    $answerList->save();
                    return new Survey($survey, $answerList);
                }
                unset($list[$survey]);
            }
            throw new Exception("There are no survyes left.");
        }
        
        /**
         * @method void finish
        **/
        protected function finish(Survey $survey) {
            $surveyList = $this->cookie->getAttribute("survey_list");
            $surveyName = $this->session->getAttribute("survey_name");
            $surveyList[] = $surveyName;
            $this->cookie->setAttribute("survey_list", $surveyList, Time::years(1));
            $this->response->append("<h1>Final</h1>");
            $this->response->append($survey->getClosing());
        }
    }
?>