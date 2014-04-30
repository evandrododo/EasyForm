<?php
/*
** Classe de formulário para facilitar implementação, 
desde a montagem (não precisar digitar o HTML) 
até a geração de conteúdo HTML (pra enviar por email).

** @author      Evandro Carreira
** @email       evandro.carreira@gmail.com
** @date(m/Y)   04/2014


[ Configuração ]
Texto em formato JSON , objeto form com as seguintes propriedades:

--- action
Ação do formulário, URL para onde os dados serão enviados

--- method
método de envio: [post|get]
default: post

--- id
auto explicativo, rs

--- class
Classe(s) que serão adicionadas ao form, utilizado para poder modificar o estilo

--- fields
Descrição de todos os campos que estarão contidos no form

--- --- name
--- Nome por onde vai ser identificado o campo

--- --- type
--- tipo do campo: [ text | email | textarea | image | submit ]
--- default: text

--- --- placeholder
--- Caso seja declarado coloca como placeholder

--- --- label
--- Caso seja declarado insere o label com o for=$name_do_form

--- --- required
--- Campo de preenchimento obrigatório

--- --- src
--- [type=image] source da imagem utilizada (como submit)

--- --- alt
--- [type=image] Texto alternativo para a imagem caso ela não carregue

--- --- value
--- [type=submit] Valor (texto) que o campo terá





[Utilização]

// Configurando
$form_json_config = '{
    "form" : {
    "id": "form_vazio"
    }
}

// Criando o objeto
$MeuFormTeste = new EasyForm($form_json_config)

// Pega os elementos em HTML (para preenchimento)
$MeuFormTeste->getHTMLForm();

// Caso queira um único elemento convertido, a classe pode ser utilizada para gerar o HTML desse elemento:
$json_campo = {
    "type" : "textarea",
    "label" : "Mensagem",
    "name" : "msg",
    "required" : true
};

$campo_obj = json_decode($json_campo); //$campo precisa estar decodificado (em formato de objeto)
$MeuFormTeste->getHTML($campo_obj); //O formulário não precisa ter configuração definida




[Todo]

--- $EasyForm->printValues(fields = { "name_campo1" , "name_campo2", "etc" } ) //retorna HTML com os valores (faz um search pelo name)
--- $EasyForm->printValue($field) //retorna o html do $field para exibição

*/


$form_json_exemplo = '{
    "form" : {
        "action" : "FaleConosco",
        "method" : "post",
        "id"    : "form_fale_conosco",
        "class"    : "",
        "fields" : [
        {
            "name": "nome",
            "placeholder" : "Nome"
        },
        {
            "name" : "email",
            "type" : "email",
            "label" : "E-mail",
            "required" : true
        },
        {
            "name" : "msg",
            "type" : "textarea",
            "label" : "Mensagem",
            "required" : true
        },
        {
            "type" : "image",
            "src" : "/images/enviar.png",
            "alt" : "Envia esse form"
        },
        {
            "type" : "submit",
            "value" : "Enviar"
        }
        ]
    }
}';

class EasyForm
{
    public $config;

    function __construct($json_config) {
        $this->config = json_decode($json_config);
    }


    //Gera o html de um único campo
    function getHTML($field) {
        $input_types = array ("text","email","image","submit");
        $plain_text = "";

        //trata erros/exceções
        if($field->label && !$field->name)
            return "Para utilizar um label é necessário especificar um name!";

        if(!$field->type) $field->type = "text";

        if($field->label){
            $plain_text .= "<label for='".$field->name."'";
            if($field->label_class) { $plain_text .= " class='".$field->label_class."'";  }
            $plain_text .= ">".$field->label."</label>";
        }

        if(in_array($field->type, $input_types)) {
            $plain_text .= "<input type='".$field->type."' ";
            
            //seta os atributos
            if($field->src) {  $plain_text .= " src='".$field->src."'"; }
            if($field->alt) {   $plain_text .= " alt='".$field->alt."'"; }
            if($field->value) {   $plain_text .= " value='".$field->value."'"; }

            if($field->name) {  $plain_text .= " name='".$field->name."'"; }
            if($field->placeholder) {   $plain_text .= " placeholder='".$field->placeholder."'"; }
            if($field->class) { $plain_text .= " class='".$field->class."'";  }
            if($field->required) { $plain_text .= " required";  }

            $plain_text .= "/>";
        }
        if($field->type == "textarea") {
            $plain_text .= "<textarea ";
            //seta os atributos do textarea (maldito campo 'especial')
            if($field->name) {  $plain_text .= " name='".$field->name."'"; }
            if($field->placeholder) {   $plain_text .= " placeholder='".$field->placeholder."'"; }
            if($field->class) { $plain_text .= " class='".$field->class."'";  }
            if($field->required) { $plain_text .= " required";  }

            $plain_text .= "></textarea>";
        }
        return $plain_text;
    }

    //pega o HTML de vários campos (pode ser redefinida para acertar o layout)
    function getInnerForm($fields) {
        $plain_text = "";
        if(is_array($fields) )
        foreach($fields as $Field)
        {
            $plain_text .= $this->getHTML($Field);
        }
        return $plain_text;
    }

    //retorna o HTML do form com os campos (pode receber os campos como parametro caso queira modificar)
    function getHTMLForm($plain_text_fields = "") {

        $form = $this->config->form;
        if(!$form->method) $form->method = "post";
        if(!$form->action) $form->action = "#";

        $plain_text = "<form ";
        
        //atributos
        $plain_text .= " action='".$form->action."'";
        $plain_text .= " method='".$form->method."'"; 
        if($form->class) {  $plain_text .= " class='".$form->class."'"; }
        if($form->id) {  $plain_text .= " id='".$form->id."'"; }

        $plain_text .= ">";

        if($plain_text_fields){
            $plain_text .= $plain_text_fields;
        }
        else{
            $plain_text .= $this->getInnerForm($form->fields);
        }
        $plain_text .= "</form>";
        return $plain_text;
    }
}
?>