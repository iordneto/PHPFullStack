# Classificados PHP API v1.0

Esse projeto contém uma API HTTP para o controle de anúncios de classificados, anunciantes e controle financeiro referente a veiculação de anúncios na plataforma. Esse projeto utiliza o Composer para gerenciamento e injeção de dependências, Doctrine 2 para abstração da camada de Persistência, Slim 3 para construção da API HTTP. 

API hospedado na Plataforma de hospedagem em núvem [Heroku](https://www.heroku.com/): : https://iord-php-backend.herokuapp.com/v1/  


## Começando

### Pré-requisitos

Para instalar as dependências do projeto é necessário ter instalado o [composer](https://getcomposer.org/). E o XAMPP

### Instalação e configuração

O primeiro passo para a instalação do projeto é clonar o repositório.

```
$ git clone https://github.com/iordneto/PHPFullStack.git
```

Logo após, é necessário entrar no diretório do projeto no diretório onde está localizado o arquivo composer.json e executar o comando abaixo para baixar as dependências do projeto com a ajuda do composer.

```
$ cd PHPFullStack
$ composer install
```

## Iniciando o projeto

Para rodar o servidor interno do PHP e iniciar a API na porta 8000(pode ser qualquer outra porta, desde que não exista conflito):

```
$ php -S localhost:8000 
```

## Utilizando a API


Você pode utilizar a API de forma local, como explicado acima, ou pode testar diretamente através da versão hospedada na plataforma [Heroku](https://www.heroku.com/)


```
https://iord-php-backend.herokuapp.com/v1/
```

*O link acima é um equivalente à nossa rota "http://localhost:8000/v1".* 


### Requerindo um Token para autenticação

Consultando a rota de autenticação através de Basic Autentication obter um Token JWT para o resto da aplicação.


```
$ curl -u admin:admin -X GET http://localhost:8000/auth
```

O retorno deve ser algo parecido com 

```
{
  "auth-jwt": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyIjoiQGZpZGVsaXNzYXVybyIsInR3aXR0ZXIiOiJodHRwczpcL1wvdHdpdHRlci5jb21cL2ZpZGVsaXNzYXVybyIsImdpdGh1YiI6Imh0dHBzOlwvXC9naXRodWIuY29tXC9tc2ZpZGVsaXMifQ.5TSgJhrZnIDDnq9eXObFkDMGv8gw1yarErwAz9aZrwo"
}
```
Esse token deve ser utilizado para o resto dos endpoints da Aplicação como um HEADER da requisição, chamado *"X-Token"*:

```
curl -X GET -H "X-Token: seu-token-de-acesso" http://localhost:8000/v1/anunciantes -i
```

onde *"auth-token"* é o Token resultado da consulta 

```
$ curl -u admin:admin -X GET http://localhost:8000/auth
```

## Operações com Anunciante

Foram implementadas quatro operaçãoes HTTP para a entidade de Anunciante, são elas: *GET, POST, PUT e DELETE*

### Consultando todos os Anunciantes

```
$ curl -X GET -H "X-Token: seu-token-de-acesso" http://localhost:8000/v1/anunciantes
```

### Consultando um Anunciante

Exemplo: Consultando o Anunciante com identificador igual a 1.

```
$ curl -X GET -H "X-Token: seu-token-de-acesso" http://localhost:8000/v1/anunciantes/1
```

### Inserindo um novo Anunciante

```
$ curl -X POST http://localhost:8000/v1/anunciantes 
    --header "Content-type: application/json" 
    --header "X-Token: seu-token-de-acesso"
    --data '{"nome": "Novo Anunciante", "endereco": "Rua Indefinida - Cidade, UF", "telefone": "(00) 00000-00"}'
```

### Atualizando um Anunciante

Exemplo: Consultando o Anunciante com identificador igual a 1.

```
$ curl -X PUT http://localhost:8000/v1/anunciantes/1
    --header "Content-type: application/json" 
    --header "X-Token: seu-token-de-acesso"
    --data '{"nome": "Anunciante atualizado", "endereco": "Rua Nova - Outra Cidade, UF", "telefone": "(00) 00000-01"}'
```

### Deletando um Anunciante

Exemplo: Deletando o Anunciante com identificador igual a 1.

```
$ curl -X DELETE -H "X-Token: seu-token-de-acesso" http://localhost:8000/v1/anunciantes/1
```

## Operações com Anúncio

Foram implementadas quatro operaçãoes HTTP para a entidade de Anúncio, são elas: *GET, POST, PUT e DELETE*

### Consultando todos os Anúncios de um Anunciante

Exemplo: Consultando todos os anúncios do anunciante com identificador igual a 6:

```
$ curl -X GET -H "X-Token: seu-token-de-acesso" http://localhost:8000/v1/anunciantes/6/anuncios
```

### Consultando um Anúncio

Exemplo: Consultando o Anúncio de identificador igual a 3 do Anunciante com identificador igual a 8.

```
$ curl -X GET -H "X-Token: seu-token-de-acesso" http://localhost:8000/v1/anunciantes/8/anuncios/3
```

### Inserindo um novo Anúncio

*Ao inserir um novo anúncio ele é persistido no sistema como um anúncio ATIVO.*
Exemplo: Inserindo um novo Anúncio para o Anunciante com identificador igual a 8.

```
$ curl -X POST http://localhost:8000/v1/anunciantes/8/anuncios
    --header "Content-type: application/json" 
    --header "X-Token: seu-token-de-acesso"
    --data '{"descricao": "Aqui vai a descrição do anúncio"}'
```

### Atualizando um Anúncio

Exemplo: Atualizando o anúncio de identificador igual a 5 do Anunciante com identificador igual 2

```
$ curl -X POST http://localhost:8000/v1/anunciantes/2/anuncios/5
    --header "Content-type: application/json" 
    --header "X-Token: seu-token-de-acesso"
    --data '{"descricao": "Aqui vai a descrição atualizada"}'
```

### Deletando um Anúncio

Exemplo: Deletando o Anúncios com identificador igual a 14 do Anunciante com identificador igual a 3.

```
$ curl -X DELETE -H "X-Token: seu-token-de-acesso" http://localhost:8000/v1/anunciantes/3/anuncios/14
```


### Desativando um anúncio

Exemplo: Desativando Anúncio com identificador 2 do Anunciante com identificador 87

```
$ curl -X GET -H "X-Token: seu-token-de-acesso" http://localhost:8000/v1/anunciantes/97/anuncios/2/desativar
```

### Ativando um anúncio

Exemplo: Ativando Anúncio com identificador 2 do Anunciante com identificador 87

```
$ curl -X GET -H "X-Token: seu-token-de-acesso" http://localhost:8000/v1/anunciantes/97/anuncios/2/ativar
```


## Operações Especiais

### Lista de devedores

Baseado nos anúncios ativos de um usuário dado preço fixado no sistema igual a 10, esse <b>*Endpoint*</b> busca o nome e o valor devido de cada Anunciante ativo no sistema.

```
$ curl -X GET -H "X-Token: seu-token-de-acesso" http://localhost:8000/v1/anunciantes/devedores
```

## Construído com 

* [PHP V7.2.2](http://www.php.net/)
* [Slim](http://www.dropwizard.io/1.0.2/docs/) - O micro-framework utilizado para construir a API HTTP
* [composer](https://getcomposer.org/) - Gerenciamento de dependências
* [Doctrine](http://www.doctrine-project.org/) - Usado para abstrair a camada de persistência da aplicação

## Versionamento

[v1.0](https://github.com/iordneto/PHPFullStack.git) 

## Autores

* **Iord Neto** - *Trabalho Inicial* - [github](https://github.com/iordneto)

## Observações

* Basic Authentication - Pensando em proteger minimamente a URL geradora do Token de acesso devido a falta de controle de usuários.

* Utilizado SQLite para persistir as entidades juntamente ao Doctrine. Devido ao tamanho e finalidade do projeto, foi julgado que não era necessário nenhuma outra ferramenta mais poderosa.


