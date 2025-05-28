# 🛒 Loja ExpoTech

Loja ExpoTech é uma aplicação web desenvolvida em PHP e MySQL com funcionalidades completas de e-commerce simples.

## ✨ Funcionalidades

- Registo e login de utilizadores (cliente e admin)
- Autenticação com sessões PHP
- Diferenciação de perfis por `RoleID`
- Área administrativa para adicionar, editar e eliminar produtos
- Upload e exibição de imagens
- Envio de e-mails (via PHPMailer - excluído do repositório por segurança)
- Carrinho de compras com atualização de quantidades
- Pagamento via PayPal (modo sandbox)

## 🛠 Tecnologias Usadas

- PHP (PDO + MySQLi)
- MySQL
- Bootstrap 5
- PHPMailer
- PayPal JS SDK

## 🚀 Como Executar

1. Clone este repositório
2. Copie a pasta para o diretório `htdocs` do XAMPP
3. Inicie o servidor Apache e MySQL
4. Importe o ficheiro SQL para o MySQL com o nome de base de dados `24198_loja_teste`
5. Configure o `api/db.php` com as suas credenciais da base de dados
6. Crie um ficheiro `secrets.php` com suas credenciais de email:
   ```php
   <?php
   $EMAIL_SAPO = 'seu@email.com';
   $EMAIL_PASS = 'sua_senha';
   ?>

7. Acesso no navegador:
http://localhost/loja/

8. 👥 Credenciais de Teste

> Emails fictícios usados apenas como exemplo. Para testes reais com envio de mensagens, crie emails verdadeiros e atualize na base de dados.

**Admin**  
Username ou Email: `john11@gimail.com`  
Senha: `admin123`

**Cliente**  
Username ou Email: `novoteste@gimail.com`  
Senha: `cliente123`

📁 Estrutura do Projeto

LojaExpoTech/
│
├── api/
│   ├── auth.php
│   ├── db.php
│   ├── email.php
│   └── add_to_cart.php
│
├── views/
│   ├── login.php
│   ├── registo.php
│   ├── admin_produtos.php
│   ├── criar_produto.php
│   ├── editar_produto.php
│   ├── eliminar_produto.php
│   └── mostrar_imagem.php
│
├── img/
│   └── logo.png
│
├── index.php
├── carrinho.php
├── atualizar_carrinho.php
├── remover_item.php
├── final.php
└── logout.php


⚠️ Observações
O diretório PHPMailer e o ficheiro secrets.php foram excluídos do repositório por segurança.
Os emails utilizados nas credenciais são fictícios e não existem.

Este projeto foi desenvolvido por mim com o apoio e orientação do formador durante o curso.
