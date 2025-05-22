# 🛒 ProAimGear

**ProAimGear** é uma loja virtual completa desenvolvida com **PHP, MySQL, JavaScript, HTML e CSS**. O sistema possui uma interface de compra para os usuários e uma área administrativa robusta, permitindo o controle total dos produtos, categorias e mensagens recebidas.

---

## 🚀 Funcionalidades

### 🛍️ Área Pública (Cliente)
- Visualização de produtos com detalhes
- Carrinho de compras com adição e remoção de itens
- Formulário de contato para envio de mensagens aos administradores

### 🔐 Área Administrativa
- Login e logout de administrador
- Cadastro, edição e exclusão de produtos
- Gerenciamento de categorias (se implementado no back-end)
- Visualização de mensagens enviadas pelos usuários

---

## 💻 Tecnologias Utilizadas

- **PHP** – Lógica de servidor e rotinas do sistema
- **MySQL** – Banco de dados relacional
- **JavaScript** – Funcionalidades interativas (carrinho, mensagens, etc.)
- **HTML & CSS** – Estrutura e estilo das páginas
- **XAMPP/WAMP** – Ambiente de desenvolvimento local recomendado

---

## 📁 Estrutura do Projeto

proaimgear/
│
├── admin/ # Área administrativa
│ ├── admin-header.php
│ ├── admin-sidebar.php
│ ├── add-product.php
│ ├── delete-product.php
│ ├── edit-product.php
│ ├── index.php # Dashboard admin
│ ├── logout.php
│ ├── messages.php
│ └── products.php # Lista de produtos para administração
│
├── assets/ # Recursos estáticos
│ ├── css/
│ │ ├── admin.css
│ │ └── style.css
│ ├── images/ # Imagens do site
│ └── js/
│ ├── admin.js
│ ├── cart.js
│ ├── contact.js
│ ├── main.js
│ └── product-detail.js
│
├── database/
│ └── database.sql # Script do banco de dados
│
├── includes/ # Arquivos reutilizáveis
│ ├── db-connect.php
│ ├── footer.php
│ ├── functions.php
│ └── header.php
│
├── cart.php # Página do carrinho
├── contact.php # Página de contato com formulário
├── hash.php # Utilitário para hash de senhas dos admins
├── index.php # Página inicial da loja
├── login.php # Login administrativo
├── product-detail.php # Página de detalhes do produto
├── products.php # Lista de produtos (pública)
└── README.md # Documentação do projeto
