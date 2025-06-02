# Sistema

## Sistema criado em PHP para agilizar tarefas cotidianas.

A situa��o atual da empresa �:
S�o 4 empresas, cada uma com sua Base Firebird, mas � 1 portal �nico, que utiliza o cadastro da empresa principal.
Como os itens devem ser criados e alterados na base principal a qual as outras empresas n�o tem acesso, o sistema foi criado para facilitar essas altera��es.
O Portal e o WMS, embora fazem consultas ao Firebird, utilizam SQLServer.

H� uma consulta de produtos, que traz o cadastro em cada base, com estoque no firebird e no sistema WMS, com sua localiza��o.

Tamb�m h� algumas altera��es e corre��es de falha dos sistemas de gest�o, como:
- altera��o de vendedores
- cria��o de paletes para o associado do Funchal
- invent�rios n�o integrados para cancelamento

H� um relat�rio de vendas cruzadas, que traz as compras feitas pelo cliente de uma empresa em outra, e cruza as faturas para confer�ncia.

Um relat�rio de C�digo Tecdoc em mais um produto.


## Instala��o

1.  PHP
2.  Firebird
3.	SQLServer
3.  MySql

## Uso

Este projeto n�o cria as tabelas, ele se conecta a diversas bases de dados diferentes, cada uma de um cliente, e faz altera��es e consultas necess�rias.
O MySql � utilizado para um controle de utilizadores bem simples, e os logs, essas op��es podem ser desativadas, foram implementadas como estudo.
