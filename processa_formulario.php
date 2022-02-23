<?php
    //print_r($_POST);

    class Mensagem{
        private $para = null;
        private $assunto = null;
        private $mensagem = null;

        public function __get($atributo){
            return $this->$valor;
        }

        public function __set($atributo, $valor){
            $this->$atributo = $valor;
        }

        public function mensagemValida(){
            if (empty($this->para) || empty($this->assunto) || empty($this->mensagem)) {
                return false;
            }

            return true;

        }
    }

    $mensagem = new Mensagem();

    $mensagem->__set('para', filter_input(INPUT_POST, 'para', FILTER_SANITIZE_EMAIL));
    $mensagem->__set('assunto', filter_input(INPUT_POST, 'assunto', FILTER_SANITIZE_STRING));
    $mensagem->__set('mensagem', filter_input(INPUT_POST, 'assunto', FILTER_SANITIZE_STRING));

    //print_r($mensagem);

    if($mensagem->mensagemValida()){
        echo 'mensagem é válida!';
    } else{
        echo 'mensagem não é válida!';
    }
?>