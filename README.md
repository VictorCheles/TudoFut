# ⚽ TudoFut - Sistema de Gerenciamento de Jogos de Futebol

<img src="public/images/logo_tudofut.svg" alt="TudoFut Logo" width="200">

O **TudoFut** é um sistema que permite consultar e gerenciar informações sobre campeonatos, times e partidas de futebol, utilizando dados da API **Football Data**. Com ele, usuários podem visualizar partidas, estatísticas e previsões de jogos.

---

## 📌 **Recursos do Sistema**
✅ Listagem de países e competições disponíveis.  
✅ Exibição de detalhes dos times, incluindo elenco e escudo.  
✅ Consulta de partidas passadas e futuras.  
✅ Filtros para encontrar jogos por time específico.  
✅ Integração com API externa para dados atualizados.  
✅ Backend otimizado para reduzir consumo da API.  
✅ Arquitetura baseada em **Laravel + Vue 3 + Inertia.js**.

---

## 🚀 **Tecnologias Utilizadas**
- **Backend:** Laravel 11, PHP 8.2
- **Frontend:** Vue.js 3, Inertia.js, Bootstrap 5.3
- **Banco de Dados:** MySQL
- **Ambiente de Desenvolvimento:** Docker + Nginx

---

## ⚙️ **Instalação e Configuração**
### 🔹 **1. Clonar o Repositório

git clone https://github.com/victorcheles/tudofut.git
cd tudofut

---
### 🔹 ** 2. Configuração do Ambiente

Antes de rodar o sistema, é necessário configurar as variáveis de ambiente:

1. Criar o arquivo .env

Copie o exemplo fornecido no repositório:
```bash
cp .env.example .env
```
2. Configurar Banco de Dados

Abra o arquivo .env e edite a variável DB_PASSWORD definindo uma senha para o banco de dados:

```bash
DB_PASSWORD=sua_senha_aqui
```
3. Configurar Token da API

O sistema utiliza a API Football Data para buscar informações sobre jogos e times.
Para isso, você precisa obter um token em football-data.org e adicioná-lo no .env:
```bash
API_FOOTBALL_TOKEN=seu_token_aqui
```
---

### 🔹 **3. 🚀 Iniciando o Ambiente com Docker

```bash
docker-compose build
docker-compose up -d
```


📌 O Docker automaticamente executará:
✅ Instalação do Composer e NPM
✅ Execução das migrations


> ⚠️ **IMPORTANTE:** 
✅ Rodar a Importação de Dados Manualmente

```bash
docker exec -it tudofut_app php artisan importar:dados-fixos
```

✅ Limpar Cache e Configurações

```bash
docker exec -it tudofut_app php artisan optimize:clear
```

Após a execução dos comandos, o sistema estará rodando em:
```bash
http://localhost:8800
```
⚠️ **Atenção:**  
Enquanto o sistema está subindo, pode ser exibido um erro **502 Bad Gateway**.  
Isso ocorre porque alguns serviços podem demorar um pouco para inicializar.  

🔄 **O que fazer?**  
Aguarde alguns instantes e tente acessar novamente em:  
👉 **http://localhost:8800**
