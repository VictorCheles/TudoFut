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
- **Backend:** Laravel 11, PHP 8.2
- **Frontend:** Vue.js 3, Inertia.js, Bootstrap 5.3
- **Banco de Dados:** MySQL
- **Ambiente de Desenvolvimento:** Docker + Nginx

---

## ⚙️ **Instalação e Configuração**
### 🔹 **1. Clonar o Repositório**
```bash
git clone https://github.com/victorcheles/tudofut.git
cd tudofut

docker-compose build
docker-compose up -d

📌 O Docker automaticamente executará:
✅ Instalação do Composer e NPM
✅ Execução das migrations

Acompanhe os processos serem concluidos nos container NODE e LARAVEL

docker logs tudofut_app
docker logs vue_frontend

E após a conclusão de ambos, prossiga nos passos seguintes.


✅ Rodar a Importação de Dados Manualmente

> ⚠️ **IMPORTANTE:**  > ⚠️ **IMPORTANTE:**  > ⚠️ **IMPORTANTE:** 

docker exec -it tudofut_app php artisan importar:dados-fixos

✅ Limpar Cache e Configurações

docker exec -it tudofut_app php artisan optimize:clear

✅ Concluído ✅

http://localhost:8800
