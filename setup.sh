#!/bin/bash
# filepath: ./setup.sh

echo "1️⃣ Instalando dependências PHP (composer install)..."
composer install

echo "2️⃣ Instalando dependências JS (npm install)..."
npm install

echo "3️⃣ Iniciando o Laravel Sail..."
./vendor/bin/sail up -d

echo "⏳ Aguardando containers subirem..."
sleep 10

echo "4️⃣ Rodando migrations e seeders..."
./vendor/bin/sail artisan migrate:fresh --seed

echo "5️⃣ Iniciando o frontend (npm run dev)..."
./vendor/bin/sail npm run dev

echo "✅ Projeto pronto! Lembre-se de configurar seu arquivo .env antes de iniciar."