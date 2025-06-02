# Sistema

## Sistema criado em PHP para agilizar tarefas cotidianas.

A situação atual da empresa é:
São 4 empresas, cada uma com sua Base Firebird, mas é 1 portal único, que utiliza o cadastro da empresa principal.
Como os itens devem ser criados e alterados na base principal a qual as outras empresas não tem acesso, o sistema foi criado para facilitar essas alterações.
O Portal e o WMS, embora fazem consultas ao Firebird, utilizam SQLServer.

Há uma consulta de produtos, que traz o cadastro em cada base, com estoque no firebird e no sistema WMS, com sua localização.

Também há algumas alterações e correções de falha dos sistemas de gestão, como:
- alteração de vendedores
- criação de paletes para o associado do Funchal
- inventários não integrados para cancelamento

Há um relatório de vendas cruzadas, que traz as compras feitas pelo cliente de uma empresa em outra, e cruza as faturas para conferência.

Um relatório de Código Tecdoc em mais um produto.


## Instalação

1.  PHP
2.  Firebird
3.	SQLServer
3.  MySql

## Uso

Este projeto não cria as tabelas, ele se conecta a diversas bases de dados diferentes, cada uma de um cliente, e faz alterações e consultas necessárias.
O MySql é utilizado para um controle de utilizadores bem simples, e os logs, essas opções podem ser desativadas, foram implementadas como estudo.
