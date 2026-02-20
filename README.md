# 💰 Tu Gestor Financiero Personal (Honduras)

Una aplicación web moderna construida con **Laravel 11** y **Bootstrap 5**, diseñada específicamente para la realidad financiera en Honduras. Permite administrar cuentas en Lempiras (HNL) y Dólares (USD), realizar proyecciones a futuro y mantener un control estricto de los límites de las tarjetas de crédito bimonetarias.

## ✨ Características Principales

*   **💳 Tarjetas de Crédito Duales:**
    *   Soporte nativo para tarjetas que manejan saldos independientes en HNL y USD bajo un mismo límite de crédito unificado.
    *   Interfaz con apariencia de "Estado de Cuenta" real (separando compras en lempiras y dólares).
*   **💱 Soporte Multimoneda Inteligente:**
    *   Uso de la API de *exchangerate-api.com* para conversiones USD a HNL en tiempo real (con un tipo de cambio de respaldo).
    *   Al registrar compras en dólares en una cuenta de lempiras, el sistema convierte automáticamente el monto y lo deduce correctamente.
*   **📈 Proyecciones de Saldo a Futuro:**
    *   Algoritmo matemático que proyecta cuánto dinero tendrás a final de mes basado en tus ingresos regulares (efectivo/débito) y tus deudas sumadas (tarjetas).
    *   Integración elegante de gráficos (vía Chart.js) para ver tu salud financiera mensual.
*   **🔄 Gestión Fácil de Transacciones:**
    *   Posibilidad de editar o eliminar cualquier transacción. El sistema revierte automáticamente la matemática y ajusta tus saldos precisos.
*   **🛡️ Respaldos de Base de Datos Integrados:**
    *   Comandos *Artisan* personalizados para crear copias de seguridad de todas tus finanzas y restaurarlas con un solo comando.
*   **🎨 Diseño Premium:**
    *   Interfaz "Dark Mode / Crystal Blue" utilizando componentes limpios, sombras suaves, y diseño responsivo nativo mediante Bootstrap 5.

---

## 🚀 Requisitos Previos

Antes de instalar este proyecto, asegúrate de tener en tu servidor / entorno local:

-   **PHP** 8.2 o superior
-   **Composer**
-   **MySQL** o MariaDB (recomendado a través de Laragon, XAMPP, etc.)
-   **Node.js** y **NPM** (para compilar los assets si modificas CSS/JS)

---

## 🛠️ Instalación Paso a Paso

1.  **Clonar / Descargar el repositorio:**
    Coloca los archivos del proyecto dentro de la carpeta de tu servidor web (ej: `C:\laragon\www\finance-app`).

2.  **Instalar las dependencias de PHP:**
    Abre tu terminal en la carpeta del proyecto y ejecuta:
    ```bash
    composer install
    ```

3.  **Configurar las variables de entorno:**
    Copia el archivo `.env.example` y renómbralo a `.env`:
    ```bash
    cp .env.example .env
    ```
    *(Abre el `.env` y asegúrate de que tus credenciales de base de datos (`DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`) coincidan con tu servidor MySQL local).*

4.  **Generar la clave de la aplicación:**
    ```bash
    php artisan key:generate
    ```

5.  **Crear la base de datos y migrar las tablas:**
    Si no has creado la base de datos `finance_app` en MySQL, puedes correr nuestro comando personalizado para que la cree y cargue las tablas:
    ```bash
    php artisan db:restore
    ```
    O si ya tienes la base de datos vacía, simplemente corre:
    ```bash
    php artisan migrate
    ```

6.  **Instalar dependencias de Frontend (Opcional):**
    ```bash
    npm install
    npm run build
    ```

---

## 🖥️ Uso de la Aplicación

Una vez configurado, si usas Laragon, solo entra en tu navegador a:
👉 `http://finance-app.test`

*Si no usas Laragon, puedes correr el servidor interno de Laravel:*
`php artisan serve` y abrir `http://127.0.0.1:8000`

### 1. Iniciar Sesión / Registrarse
El sistema cuenta con un registro nativo de Laravel. Crea tu primer usuario para poder guardar tus datos. Tus datos son privados para tu cuenta.

### 2. Configurar tu primera Cuenta
Ve a **"Mis Cuentas"** -> **"Agregar Nueva Cuenta"**.
Si eliges **Efectivo/Billetera** o **Cuenta de Débito**, pon tu saldo actual en Lempiras.
Si eliges **Tarjeta de Crédito**, podrás ingresar:
*   El Límite Unificado (Ej. L 20,000)
*   Tu deuda actual en Lempiras (Ej. L 2,500)
*   Tu deuda actual en Dólares (Ej. $ 50)

### 3. Agregar Gastos e Ingresos
Entra a "Administrar Cuenta" en cualquiera de tus tarjetas. A tu derecha tendrás el panel para agregar Transacciones. El sistema sabrá si es un ingreso o si aumenta tu deuda dependiendo del tipo de cuenta.

---

## 💾 Comandos Útiles (Respaldos)

Este sistema incluye opciones de seguridad para evitar que pierdas tus datos financieros mediante la consola:

*   **Crear un Backup:**
    Genera un archivo `.sql` en `storage/app/backups/`:
    ```bash
    php artisan db:backup
    ```

*   **Restaurar el Último Backup:**
    Esto reconstruirá tu base de datos y traerá tu información de vuelta (Asegúrate de tener mysql en tus variables de entorno de Windows):
    ```bash
    php artisan db:restore
    ```

---

## 👨‍💻 Tecnologías Clave

*   **Backend:** Laravel Framework
*   **Base de Datos:** MySQL via Eloquent ORM
*   **Frontend:** Blade Templates, HTML5
*   **Estilos:** Bootstrap 5 (Vanilla CSS override)
*   **Íconos:** Bootstrap Icons
*   **Gráficos:** Chart.js

---
*Desarrollado con ❤️ para ayudarte a mantener tus finanzas bajo control.*
