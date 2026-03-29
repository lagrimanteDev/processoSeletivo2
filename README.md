# Processo Seletivo - Dev I

Aplicação Laravel para gestão de operações, com:

- autenticação de usuários;
- importação de planilha (`.xlsx`, `.xls`, `.csv`) em background;
- listagem e detalhe de operações;
- transição de status com histórico.

## Requisitos

- PHP 8.2+
- Composer
- MySQL
- Node.js (opcional para front em modo dev)

## Instalação

1. Instalar dependências:

```bash
composer install
npm install
```

2. Configurar ambiente:

```bash
cp .env.example .env
php artisan key:generate
```

3. Ajustar conexão com banco no `.env`.

4. Rodar migrations:

```bash
php artisan migrate
```

5. Subir aplicação:

```bash
php artisan serve
```

6. Subir worker de fila (obrigatório para importação):

```bash
php artisan queue:work --queue=default --timeout=0 --tries=1
```

## Login

Credenciais de uso local:

- Email: `admin@gmail.com`
- Senha: `password`

## Rotas principais

- `GET /login`
- `GET /dashboard`
- `GET /operacoes`
- `GET /operacoes/{operacao}`
- `POST /operacoes/importar`
- `PATCH /operacoes/{operacao}/status`

## Importação de planilha

A importação é assíncrona (fila). Ao enviar o arquivo, um job é disparado.

### Cabeçalhos aceitos

Layout principal recebido:

- `valor_requerido`
- `valor_desembolso`
- `total_juros`
- `taxa_juros (%)`
- `taxa_multa`
- `taxa_mora`
- `status_id`
- `data_criacao`
- `data_pagamento`
- `produto`
- `conveniada_id`
- `quantidade_parcelas`
- `data_primeiro_vencimento`
- `valor_parcela`
- `quantidade_parcelas_pagas`
- `CPF`
- `nome`
- `dt_nasc`
- `sexo`
- `email`

Também há aliases para compatibilidade (ex.: `codigo`, `cliente_cpf`, etc.).

## Fluxo de status

Transições permitidas:

- `DIGITANDO -> PENDENTE` ou `CANCELADA`
- `PENDENTE -> APROVADA` ou `CANCELADA`
- `APROVADA -> PAGA` ou `CANCELADA`
- `PAGA` e `CANCELADA` sem transições

Toda mudança válida grava histórico em `historico_status`.

## Testes

Executar:

```bash
php artisan test
```

Inclui testes de:

- importação (disparo de job);
- transição de status válida/inválida com histórico.
