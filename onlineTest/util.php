<?php
    namespace Quiz;
    class QuizInfo{
        private $title = "";
        private $answer = "";
        private $score = 0;
        private $optionList = array();
        public function _construct(){

        }

        public static function make(){
            echo __METHOD__;
        }
        /**
         * @param array $optionList
         */
        public function setOptionList(array $optionList): void
        {
            $this->optionList = $optionList;
        }

        /**
         * @return array
         */
        public function getOptionList(): array
        {
            return $this->optionList;
        }

        /**
         * @return string
         */
        public function getTitle(): string
        {
            return $this->title;
        }

        /**
         * @param string $title
         */
        public function setTitle(string $title): void
        {
            $this->title = $title;
        }

        /**
         * @return string
         */
        public function getAnswer(): string
        {
            return $this->answer;
        }

        /**
         * @param string $answer
         */
        public function setAnswer(string $answer): void
        {
            $this->answer = $answer;
        }

        /**
         * @return int
         */
        public function getScore(): int
        {
            return $this->score;
        }

        /**
         * @param int $score
         */
        public function setScore(int $score): void
        {
            $this->score = $score;
        }
    }
?>
