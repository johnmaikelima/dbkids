# DbKids - Plataforma de E-commerce

Plataforma de e-commerce para venda de produtos infantis com integração Mercado Pago e cálculo de frete pelos Correios.

## 📁 Estrutura do Projeto

```
DbKids/
├── app/
│   ├── config/
│   │   ├── database.php
│   │   └── router.php
│   ├── controllers/
│   ├── models/
│   ├── views/
│   ├── database/
│   │   └── schema.sql
│   └── helpers/
│       └── functions.php
├── public/
│   ├── css/
│   ├── js/
│   └── images/
├── database/
│   └── dbkids.db (será criado automaticamente)
├── index.php
├── .htaccess
└── README.md
```

## 🚀 Instalação

1. Coloque os arquivos em `C:\Users\ACER\Desktop\Projetos\DbKids`
2. Configure seu servidor web para apontar para este diretório
3. Acesse `http://localhost/DbKids`

## 📋 Próximos Passos

- [ ] Criar controllers
- [ ] Criar models
- [ ] Criar views
- [ ] Implementar autenticação
- [ ] Integrar Mercado Pago
- [ ] Integrar API Correios

## 🔐 Segurança

- Senhas com hash bcrypt
- Proteção CSRF
- Validação de inputs
- Prepared statements
