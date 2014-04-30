EasyForm
========
Classe de formulário para facilitar implementação, 
desde a montagem (não precisar digitar o HTML) 
até a geração de conteúdo HTML (pra enviar por email).

** @author      Evandro Carreira
** @email       evandro.carreira@gmail.com
** @date(m/Y)   04/2014


# Configuração
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





# Utilização
`
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
`



# Todo

--- $EasyForm->printValues(fields = { "name_campo1" , "name_campo2", "etc" } ) //retorna HTML com os valores (faz um search pelo name)
--- $EasyForm->printValue($field) //retorna o html do $field para exibição
