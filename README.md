#  CryptoTrade — CRUD con Laravel + Transacciones de Monedas Virtuales

Este es un proyecto Laravel que permite a múltiples usuarios registrados comprar, vender y transferir monedas virtuales entre sí.  
Está diseñado como base para una futura integración con blockchain (Ganache/Ethereum).
---
## REQUISITOS PREVIOS
Antes de clonar el proyecto asegúrate de tener instalado en tu sistema (Ubuntu/WSL/Mac):
- PHP >= 8.1
- Composer
- MySQL o MariaDB
- Laravel CLI (opcional)
- Ganache (opcional, para blockchain)
- Acceso a la terminal y permisos de ejecución
---
## INSTALACIÓN RÁPIDA
---
### 1. Clonar el repositorio
```bash
git clone git@github.com:SmartSiteCompany/POS_BLOCKCHAIN.git
cd POS_BLOCKCHAIN
```
---
### 2. Dar permisos de ejecución al script
```bash

chmod +x setup.sh
```

---
### 3. Ejecutar el script de instalación
```bash
./setup.sh
```
El script realiza lo siguiente:
•	Instala las dependencias PHP con Composer
•	Copia el archivo .env
•	Genera la clave de Laravel
•	Crea la base de datos local cryptotrade (si tienes acceso como root)
•	Ejecuta las migraciones
--
### 4. Configurar. env
Edita el archivo .env y coloca tu configuración de base de datos:
```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=cryptotrade
DB_USERNAME=root
DB_PASSWORD=
```
---
### 5. Levantar el servidor y abre en el navegador
```bash
php artisan serve
http://localhost:8000
```
