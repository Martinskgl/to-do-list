# 📝 Todo App - Laravel + Vue.js

Uma aplicação completa de gerenciamento de tarefas (To-Do List) desenvolvida com **Laravel 11** no backend e **Vue.js 3** no frontend, implementando autenticação JWT, sistema de roles e CRUD completo.

## 🚀 Tecnologias Utilizadas

### Backend (Laravel)

- **Laravel 11** - Framework PHP
- **Laravel Sanctum** - Autenticação API via tokens
- **MySQL/SQLite** - Banco de dados
- **Eloquent ORM** - Mapeamento objeto-relacional
- **Laravel Mail** - Sistema de e-mails
- **Form Requests** - Validação de dados
- **Observers** - Eventos de modelo
- **Factories & Seeders** - Dados de teste
- **Job** - Job para export csv

### Frontend (Vue.js)

- **Vue.js 3** - Framework JavaScript reativo
- **Vue Router** - Roteamento SPA
- **Axios** - Cliente HTTP
- **Bootstrap 5** - Framework CSS
- **FontAwesome** - Ícones
- **Vue Select** - Componente de seleção

## ✨ Funcionalidades

### 🔐 Sistema de Autenticação

- Login/Logout com Sanctum
- Proteção de rotas por autenticação
- Interceptadores automáticos para requisições
- Redirecionamento em caso de token expirado

### 👥 Gerenciamento de Usuários (Admin)

- ✅ Criar novos usuários
- ✅ Editar dados de usuários
- ✅ Excluir usuários
- ✅ Atribuir roles (Admin/User)
- ✅ Validação completa de formulários

### 📋 Gerenciamento de Tarefas

- ✅ **Admin**: Visualiza todas as tarefas
- ✅ **User**: Visualiza apenas suas tarefas
- ✅ Criar/editar tarefas (apenas Admin)
- ✅ Atribuir responsáveis às tarefas
- ✅ Definir data de expiração
- ✅ Exportar as tarefas via CSV
- ✅ Alterar status das tarefas:
  - 🟡 Pendente
  - 🔵 Em Progresso
  - 🟢 Concluída
  - 🔴 Cancelada

### 🔍 Recursos Adicionais

- **Busca avançada** por título e descrição
- **Filtros por status** das tarefas
- **Sistema de notificações** toast
- **E-mails automáticos** para mudanças de status
- **Interface responsiva** com Bootstrap

## Comandos Úteris

- **Sail pint** formatador de código PHP padrão do Laravel
- **Sail npm run lint** - procura erros, más práticas e inconsistências de estilo no seu código
- **Sail npm run format** - Código para formatação do código (;, espaços...)
- **/vendor/bin/php-cs-fixer fix** - formatar automaticamente seu código PHP com base em um conjunto de regras predefinidas.
- **sail artisan format** - Executa os prettier do Laravel/PHP por command.

## 📁 Estrutura do Projeto

```
├── app/
│   ├── Enums/
│   │   └── TaskStatus.php                    # Enum para status das tarefas (pending, in_progress, completed, cancelled)
│   │
│   ├── Helpers/
│   │   └── EnumHelper.php                    # Helper para conversão de enums
│   │
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Api/
│   │   │   │   ├── AuthController.php        # Autenticação API (login, logout, me)
│   │   │   │   ├── TaskController.php        # CRUD de tarefas + updateStatus + getByUser
│   │   │   │   ├── UserController.php        # CRUD de usuários
│   │   │   │   └── RoleController.php        # Listagem de roles
│   │   │   │
│   │   │   ├── Auth/                         # Controllers de autenticação web
│   │   │   │   ├── LoginController.php
│   │   │   │   ├── RegisterController.php
│   │   │   │   ├── ForgotPasswordController.php
│   │   │   │   ├── ResetPasswordController.php
│   │   │   │   ├── ConfirmPasswordController.php
│   │   │   │   └── VerificationController.php
│   │   │   │
│   │   │   ├── Controller.php                # Controller base
│   │   │   └── RoleController.php            # (arquivo vazio)
│   │   │
│   │   ├── Middleware/
│   │   │   └── UserUnauthorized.php          # Middleware para verificar se usuário é admin
│   │   │
│   │   └── Requests/
│   │       ├── Task/
│   │       │   ├── StoreRequest.php          # Validação para criação de tarefas
│   │       │   └── UpdateRequest.php         # Validação para atualização de tarefas
│   │       │
│   │       └── User/
│   │           ├── StoreRequest.php          # Validação para criação de usuários
│   │           └── UpdateRequest.php         # Validação para atualização de usuários
│   │
│   ├── Jobs/
│   │   └── ExportTasksToCsv.php               # Job para exportar CSV
│   │
│   ├── Mail/
│   │   ├── TaskAssignMail.php                # E-mail de atribuição de tarefa
│   │   ├── TaskCancelledMail.php             # E-mail de tarefa cancelada
│   │   └── TaskCompletedMail.php             # E-mail de tarefa concluída
│   │
│   ├── Models/
│   │   ├── User.php                          # Modelo de usuário (com Sanctum)
│   │   ├── Role.php                          # Modelo de role/função
│   │   └── Task.php                          # Modelo de tarefa (com enum TaskStatus)
│   │
│   ├── Observers/
│   │   └── TaskObserver.php                  # Observer para eventos de Task (envio de e-mails)
│   │
│   ├── Policies/
│   │   ├── TaskPolicy.php                    # (arquivo vazio) - Policy para tarefas
│   │   └── UserPolicy.php                    # (arquivo vazio) - Policy para usuários
│   │
│   └── Providers/
│       └── AppServiceProvider.php            # Provider principal (registra observer e helper)
│
├── resources/
│   ├── views/
│   │   └── email/                            # Templates de e-mail
│   │       ├── assignTask.blade.php          # Template para tarefa atribuída
│   │       ├── cancelledTask.blade.php       # Template para tarefa cancelada
│   │       └── completedTask.blade.php       # Template para tarefa concluída
│   │
│   └── js/                                   # Frontend (assumido baseado na estrutura)
│       ├── Auth/                             # Sistema de autenticação
│       ├── pages/                            # Páginas Vue/React
│       └── components/                       # Componentes reutilizáveis
│
└── database/
    ├── migrations/                           # Migrações do banco
    │   ├── create_roles_table.php
    │   ├── create_users_table.php
    │   └── create_tasks_table.php
    │
    ├── seeders/                              # Dados de teste
    │   ├── RoleSeeder.php
    │   ├── UserSeeder.php
    │   └── TaskSeeder.php
    │
    └── factories/                            # Fábricas de modelos
        ├── UserFactory.php
        ├── RoleFactory.php
        └── TaskFactory.php
```

## 🛠️ Instalação e Configuração

### Pré-requisitos

- **Docker** e **Docker Compose**
- **Git**

> 💡 Este projeto usa **Laravel Sail** para containerização.

### Instalação Rápida

1. **Clonar o repositório**

```bash
git clone <repository-url>
cd todo-app
```

2. **Instalar dependências do Laravel**

```bash
composer install
```

3. **Instalar dependências do frontend**

```bash
npm install
```

4. **Iniciar o projeto**

```bash
./vendor/bin/sail up -d
```

5. **Compilar assets (em terminal separado)**

```bash
./vendor/bin/sail npm run dev
```

> 📋 **Nota**: O arquivo `.env` já está configurado e será fornecido junto com o projeto - não é necessário configuração adicional.

A aplicação estará disponível em `http://localhost`

### Alias para facilitar uso (Opcional)

```bash
alias sail='./vendor/bin/sail'
```

## 👤 Usuários de Teste

O projeto já vem com usuários pré-configurados:

**Administrador:**

- Email: `admin@example.com`
- Senha: `password`

**Usuário comum:**

- Email: `user@example.com`
- Senha: `password`

## 🔗 Rotas da API

### Autenticação

```
POST   /api/login          # Login
POST   /api/logout         # Logout
GET    /api/me             # Dados do usuário logado
```

### Tarefas

```
GET    /api/tasks          # Listar todas (Admin)
GET    /api/tasks/user     # Tarefas do usuário logado
POST   /api/tasks          # Criar tarefa (Admin)
GET    /api/tasks/{id}     # Detalhes da tarefa
PUT    /api/tasks/{id}     # Atualizar tarefa (Admin)
DELETE /api/tasks/{id}     # Excluir tarefa
PATCH  /api/tasks/{id}/status # Atualizar status
```

### Usuários (Admin)

```
GET    /api/users          # Listar usuários
POST   /api/users          # Criar usuário
GET    /api/users/{id}     # Detalhes do usuário
PUT    /api/users/{id}     # Atualizar usuário
DELETE /api/users/{id}     # Excluir usuário
```

### Roles

```
GET    /api/roles          # Listar roles disponíveis
```

## 🎯 Funcionalidades por Tipo de Usuário

### 👑 Administrador

- ✅ Gerenciar todos os usuários
- ✅ Criar, editar e excluir tarefas
- ✅ Visualizar todas as tarefas do sistema
- ✅ Atribuir tarefas a usuários
- ✅ Alterar status de qualquer tarefa

### 👤 Usuário Comum

- ✅ Visualizar apenas suas tarefas
- ✅ Alterar status das próprias tarefas
- ✅ Filtrar e buscar suas tarefas
- ❌ Não pode criar/editar tarefas
- ❌ Não pode gerenciar usuários

## 📧 Sistema de E-mails

O sistema envia e-mails automáticos quando:

- ✉️ Tarefa é atribuída ao usuário
- ✉️ Status da tarefa muda para "Concluída"
- ✉️ Status da tarefa muda para "Cancelada"

## 🔧 Personalização

### Adicionar novos status

1. Editar `app/Enums/TaskStatus.php`
2. Atualizar validações nos Form Requests
3. Adicionar no frontend em `TaskList.vue`

### Customizar e-mails

Os templates estão em `resources/views/email/`:

- `assignTask.blade.php`
- `completedTask.blade.php`
- `cancelledTask.blade.php`

## 🚀 Deploy

### Desenvolvimento

```bash
# Iniciar ambiente completo
./vendor/bin/sail up -d

# Compilar assets em modo watch
./vendor/bin/sail npm run dev
```

## 📝 Licença

Este projeto está sob a licença MIT. Veja o arquivo `LICENSE` para mais detalhes.

## 👨‍💻 Desenvolvedor

Desenvolvido com ❤️ por JoãoMartins

---
