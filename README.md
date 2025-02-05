# ⚽ TudoFut - Sistema de Gerenciamento de Jogos de Futebol

![TudoFut Logo](public/build/logo_tudofut.svg)

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
- **Backend:** Laravel 11, PHP
- **Frontend:** Vue.js 3, Inertia.js, Bootstrap 5.3
- **Banco de Dados:** MySQL
- **Ambiente de Desenvolvimento:** Docker + Nginx

---

## ⚙️ **Instalação e Configuração**

### 🔹 **1. Clonar o Repositório**
```bash
git clone https://github.com/victorcheles/tudofut.git
cd tudofut

Ajustar TOKEN da API

API_FOOTBALL_URL=http://api.football-data.org/v4
API_FOOTBALL_TOKEN=<SEU_TOKEN_API>


### 🔹 **2. Construir e Iniciar os Serviços**
docker-compose build
docker-compose up -d

📌 O Docker automaticamente executará:
✅ Instalação do Composer e NPM
✅ Execução das migrations

Acompanhe os processos serem concluidos nos container NODE e LARAVEL

docker logs tudofut_app
docker logs vue_frontend

E após a conclusão de ambos, prossiga nos passos seguintes.

### 🔹 **3. Rodar a Importação de Dados Fixos**

> ⚠️ **IMPORTANTE:**  > ⚠️ **IMPORTANTE:**  > ⚠️ **IMPORTANTE:** 

docker exec -it tudofut_app php artisan importar:dados-fixos

### 🔹 **4. Limpar Cache e Configurações**

docker exec -it tudofut_app php artisan optimize:clear

### 🔹 **5. Acessar o Sistema**

http://localhost:8800
