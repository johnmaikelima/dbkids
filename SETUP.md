# DbKids - Guia de Instalação e Execução

## 📋 Requisitos

- **PHP 7.4+** instalado e acessível via linha de comando
- **SQLite** (já vem com PHP)
- Um navegador web moderno

## 🚀 Opção 1: Usando PHP Built-in Server (Recomendado)

### Windows

1. **Abra o PowerShell ou CMD** na pasta do projeto
2. **Execute:**
   ```powershell
   php -S localhost:8000
   ```
3. **Acesse no navegador:**
   ```
   http://localhost:8000
   ```

### Linux/Mac

1. **Abra o Terminal** na pasta do projeto
2. **Execute:**
   ```bash
   php -S localhost:8000
   ```
3. **Acesse no navegador:**
   ```
   http://localhost:8000
   ```

### Usando o Script (Mais Fácil)

**Windows:**
- Duplo-clique em `start-server.bat`

**Linux/Mac:**
- Execute no terminal:
  ```bash
  chmod +x start-server.sh
  ./start-server.sh
  ```

---

## 🔧 Opção 2: Usando Laragon

1. Copie a pasta `DbKids` para `C:\laragon\www\`
2. Abra o Laragon e clique em "Start All"
3. Acesse `http://dbkids.test`

---

## 📝 Configuração Inicial

### 1. Criar arquivo .env

Copie `.env.example` para `.env`:

```bash
cp .env.example .env
```

### 2. Configurar Variáveis (Opcional)

Edite `.env` com suas configurações:

```
APP_NAME=DbKids
APP_URL=http://localhost:8000
MERCADO_PAGO_TOKEN=seu_token_aqui
MERCADO_PAGO_PUBLIC_KEY=sua_chave_publica
```

### 3. Banco de Dados

O banco de dados SQLite será criado automaticamente na primeira execução em:
```
database/dbkids.db
```

---

## 👤 Primeiro Acesso

### Criar Usuário Admin

Para criar o primeiro usuário admin, execute no PHP:

```php
<?php
require_once 'app/config/database.php';
require_once 'app/helpers/functions.php';

$user = new User();
$user->create([
    'name' => 'Admin',
    'email' => 'admin@dbkids.com',
    'password' => 'senha123',
    'role' => 'admin'
]);

echo "Usuário criado com sucesso!";
?>
```

Ou acesse:
```
http://localhost:8000/admin/login
```

**Credenciais padrão:**
- Email: `admin@dbkids.com`
- Senha: `senha123`

---

## 🌐 Acessar o Projeto

- **Frontend:** `http://localhost:8000`
- **Admin:** `http://localhost:8000/admin/login`
- **Dashboard:** `http://localhost:8000/admin/dashboard`

---

## 📂 Estrutura de Pastas

```
DbKids/
├── app/                 # Código da aplicação
│   ├── config/         # Configurações
│   ├── controllers/     # Controllers
│   ├── models/         # Models
│   ├── views/          # Templates HTML
│   ├── database/       # Schema SQL
│   └── helpers/        # Funções auxiliares
├── public/             # Arquivos públicos
│   ├── css/           # Estilos
│   ├── js/            # Scripts
│   └── uploads/       # Uploads de usuários
├── database/          # Banco de dados SQLite
├── index.php          # Ponto de entrada
├── .env               # Variáveis de ambiente
└── README.md          # Documentação
```

---

## 🔐 Segurança

- Senhas são hasheadas com bcrypt
- Variáveis sensíveis em `.env` (não commitadas)
- Proteção contra SQL injection com prepared statements
- Validação de inputs
- Escape de HTML para XSS

---

## 🐛 Troubleshooting

### PHP não encontrado

**Solução:** Adicione PHP ao PATH do Windows

1. Abra "Variáveis de Ambiente"
2. Clique em "Variáveis de Ambiente"
3. Adicione o caminho do PHP (ex: `C:\php`)
4. Reinicie o terminal

### Banco de dados não criado

O banco é criado automaticamente. Se houver erro:

1. Verifique permissões da pasta `database/`
2. Certifique-se que a pasta existe
3. Tente criar manualmente: `mkdir database`

### Porta 8000 já em uso

Use outra porta:
```bash
php -S localhost:8001
```

---

## 📚 Próximos Passos

1. Criar categorias de produtos
2. Cadastrar produtos
3. Testar carrinho de compras
4. Configurar Mercado Pago
5. Implementar cálculo de frete

---

## 💡 Dicas

- Use `http://localhost:8000` para testes locais
- Não commite `.env` no Git
- Faça backups do banco de dados regularmente
- Teste em diferentes navegadores

---

## 📞 Suporte

Para dúvidas ou problemas, verifique:
- README.md
- Logs do servidor
- Console do navegador (F12)

