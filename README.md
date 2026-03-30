# Processo Seletivo - Dev I

Aplicação Laravel para gestão de operações financeiras com importação otimizada de planilhas.

## 🛠️ Tecnologias Utilizadas

| Componente | Tecnologia |
|-----------|-----------|
| **Backend** | Laravel 8.2+ (PHP 8.2+) |
| **Frontend** | Blade Templates + Tailwind CSS + Vite |
| **Autenticação** | Laravel Breeze |
| **Banco de Dados** | MySQL 5.7+ ou SQLite |
| **Queue/Cache** | Database Queue + Database Cache |
| **Importação** | Maatwebsite/Excel (PhpOffice) |
| **Build** | npm + Vite.js |

## ✨ Funcionalidades

- ✅ Autenticação de usuários
- ✅ Importação otimizada em batch
- ✅ Processamento background com fila em banco (`database`)
- ✅ Listagem, detalhe e atualização de status de operações
- ✅ Histórico de mudança de status
- ✅ Relatório com cálculo de valor presente
- ✅ Filtros avançados por código, cliente, produto, conveniada e status
- ✅ Cancelamento de importação em tempo real

---

## 📋 Pré-requisitos

### Obrigatório

- **PHP 8.2+** com extensões: `openssl`, `pdo`, `pdo_mysql` (ou `pdo_sqlite`)
- **Composer** — [Download](https://getcomposer.org/)
- **Node.js 18+** e **npm** — [Download](https://nodejs.org/)
- **Banco de dados:** MySQL 5.7+ **OU** SQLite

### Opcional

- **Git** (para versionamento)

---

## ⚡ Setup Rápido (5 minutos)

### Windows (PowerShell)

```powershell
cd processoSeletivo
composer install && npm install
Copy-Item .env.example .env
php artisan key:generate

# Criar banco MySQL (XAMPP porta 3307)
mysql -u root -P 3307 -e "CREATE DATABASE processo_seletivo CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# Garantir fila/cache em banco
php artisan queue:table
php artisan queue:failed-table
php artisan cache:table
php artisan migrate
php artisan db:seed
npm run build

composer run dev
```

### Linux/macOS

```bash
cd processoSeletivo
composer install && npm install
cp .env.example .env
php artisan key:generate
mysql -u root -e "CREATE DATABASE processo_seletivo CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
php artisan queue:table
php artisan queue:failed-table
php artisan cache:table
php artisan migrate && php artisan db:seed && npm run build
composer run dev
```

**Acesse:** `http://127.0.0.1:8000`  
**Login:** `test@example.com` / `password`

---

## 🔧 Configuração de Fila

Este projeto está configurado para usar fila em banco de dados.

Use no `.env`:

```dotenv
QUEUE_CONNECTION=database
CACHE_STORE=database
SESSION_DRIVER=database
```

Depois execute:

```bash
php artisan queue:table
php artisan queue:failed-table
php artisan cache:table
php artisan migrate
php artisan config:clear
```

---

## 📊 Rodar Aplicação

### Comando Único (Recomendado)

```bash
composer run dev
```

Inicia automaticamente:
- Servidor Laravel (porta 8000)
- Worker da fila
- Logs em tempo real
- Vite (frontend assets)

### Manual (3 Terminais)

**Terminal 1:**
```bash
php artisan serve
```

**Terminal 2:**
```bash
php artisan queue:work --tries=1 --timeout=0
```

**Terminal 3 (opcional):**
```bash
npm run dev
```

### 🚨 Worker (Extremamente Importante)

O worker é **extremamente importante** para o funcionamento do projeto.
Sem worker ativo, a importação fica em **"carregando"** e os jobs não são processados.

#### Windows PowerShell (recomendado)

```powershell
cd C:\Users\Administrador\Desktop\processoSeletivo
.\workers.bat
```

#### Git Bash

```bash
cd /c/Users/Administrador/Desktop/processoSeletivo
./workers.bat
```

> No PowerShell, use `./workers.bat` ou `.\workers.bat` (não apenas `workers.bat`).

---

## 📝 Usar a Aplicação

### Importar Arquivo

1. Acesse `/operacoes`
2. Escolha arquivo Excel (.xlsx, .xls, .csv)
3. Clique "Importar"
4. Acompanhe o progresso
5. (Opcional) Clique "Cancelar importação"

### Colunas Obrigatórias

- `cpf` ou `cliente_cpf` — CPF do cliente
- `valor_requerido` — valor solicitado

### Colunas Opcionais

- `nome_cliente` — nome do cliente
- `email` / `cliente_email` — email
- `data_nascimento` — data de nascimento
- `valor_desembolso` — valor desembolsado
- `total_juros` — total de juros
- `taxa_juros` — taxa em %
- `taxa_multa` — taxa de multa
- `taxa_mora` — taxa de mora
- `status` — status inicial (1=DIGITANDO, 2=PRÉ-ANÁLISE, etc)
- `data_criacao` — data de criação
- `data_pagamento` — data de pagamento
- `produto` — CONSIGNADO ou NAO_CONSIGNADO
- `conveniada_id` ou `codigo_conveniada` — código da conveniada
- `quantidade_parcelas` — qtd de parcelas
- `data_primeiro_vencimento` — vencimento 1ª parcela
- `valor_parcela` — valor de cada parcela
- `quantidade_parcelas_pagas` — parcelas já pagas

### Regras Importantes

- **CPF preservado** do arquivo (sem normalização)
- **Mesmo CPF, dados diferentes** = permitido (múltiplas operações)
- **Linha completa idêntica** = pula (evita duplicata exata)
- **Produto CONSIGNADO** → conveniada obrigatória
- **Produto NAO_CONSIGNADO** → conveniada opcional

---

## 🔑 Rotas Principais

| Descrição | URL |
|-----------|-----|
| Login | `/login` |
| Dashboard | `/dashboard` |
| Operações | `/operacoes` |
| Importar | `POST /operacoes/importar` |
| Cancelar importação | `POST /operacoes/importar/cancelar` |
| Detalhe | `/operacoes/{id}` |
| Alterar status | `PATCH /operacoes/{id}/status` |
| Relatório | `/operacoes/relatorio` |

---

## 📊 Status das Operações

| Status | Descrição |
|--------|-----------|
| `DIGITANDO` | Preenchimento em andamento |
| `PRÉ-ANÁLISE` | Aguardando análise |
| `EM ANÁLISE` | Sendo analisada |
| `PARA ASSINATURA` | Pronta para assinatura |
| `ASSINATURA CONCLUÍDA` | Assinada ✓ |
| `APROVADA` | Aprovada |
| `PAGO AO CLIENTE` | Pagamento realizado |
| `CANCELADA` | Cancelada (final) |

**Regras:**
- `PAGO AO CLIENTE` apenas se status = `APROVADA`
- `CANCELADA` não pode ser alterada
- Cada mudança gera log em `historico_status`

---

## 🛠️ Comandos Úteis

```bash
php artisan migrate              # Rodar migrations
php artisan migrate:fresh --seed # Resetar banco + seed
php artisan queue:failed         # Ver jobs falhados
php artisan queue:retry all      # Reprocessar falhados
php artisan optimize:clear       # Limpar caches
php artisan test                 # Executar testes
```

---

## 🐛 Troubleshooting

### Importação não progride

```bash
# Verifique worker
composer run dev

# Veja logs
tail -f storage/logs/laravel.log

# Reset jobs
php artisan queue:flush
php artisan db:seed
```

### Erro de banco de dados

```bash
php artisan migrate:refresh --seed
```

### npm error

```bash
npm cache clean --force
npm install
npm run dev
```

### CORS error

- Acesse `http://127.0.0.1:8000` (não localhost)
- Verifique `.env` tem `APP_URL=http://localhost`

---

## 📂 Estrutura de Pastas

```
app/
  ├── Http/Controllers/OperacaoController.php   (listagem, filtros, importação)
  ├── Imports/
  │   ├── OperacoesImport.php                  (validação e persistência)
  │   └── OperacoesDispatchRowsImport.php      (batch dispatcher)
  ├── Jobs/
  │   ├── ImportOperacoesBatchJob.php          (batch 500 linhas)
  │   ├── ImportOperacaoLinhaJob.php
  │   └── ImportOperacoesJob.php
  └── Models/

resources/views/operacoes/
  ├── index.blade.php    (listagem + importar)
  ├── show.blade.php     (detalhe + histórico)
  └── edit.blade.php     (alterar status)

database/
  ├── migrations/        (schemas)
  └── seeders/           (dados iniciais)
```

---

## 📚 Documentação Adicional

- **OTIMIZACOES.md** — Detalhes técnicos do batching v1.1
- **Laravel Docs** — https://laravel.com/docs
- **Maatwebsite/Excel** — https://docs.laravel-excel.com/

---

**Data:** 30 de Março de 2026  
**Versão:** 1.1 (com Batching otimizado)  
**Status:** Pronto para Deploy ✅
