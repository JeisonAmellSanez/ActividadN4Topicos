# Sistema de gestión de inventario

Aplicación web para registrar, consultar y retirar productos del inventario. La solución demuestra el uso de **Laravel 12**, **React/Vite**, MongoDB y Arquitectura Hexagonal.

## Arquitectura

- `backend/app/Domain`: entidad `Product` y reglas de negocio.
- `backend/app/Application`: casos de uso y puerto `ProductRepository`.
- `backend/app/Infrastructure`: adaptador de persistencia MongoDB basado en Eloquent.
- `backend/app/Http`: adaptador de entrada HTTP.
- `frontend/src`: interfaz React que consume la API REST.

La dependencia apunta hacia el dominio: los casos de uso dependen de una interfaz, no de MongoDB. Esto permite sustituir MongoDB por otro adaptador sin modificar la lógica de negocio.

## Ejecución

### Backend

1. Copiar `backend/.env.example` como `backend/.env` y completar las credenciales de MongoDB si la instalación requiere autenticación.
2. Ejecutar:

```powershell
cd backend
php artisan key:generate
php artisan serve
```

MongoDB debe estar iniciado en `127.0.0.1:27017`. Si `8000` está ocupado o reservado en Windows, usar:

```powershell
php artisan serve --host=127.0.0.1 --port=8010
```

La API estará disponible en `http://127.0.0.1:8010/api/products`.

El backend también requiere la extensión `mongodb` habilitada en el PHP de XAMPP. Verifica con:

```powershell
C:\xampp\php\php.exe -m | Select-String mongodb
```

Si no aparece, instala la DLL de MongoDB compatible con tu versión de PHP, agrega `extension=mongodb` en `C:\xampp\php\php.ini` y reinicia la terminal.

### Frontend

```powershell
cd frontend
npm install
npm run dev
```

El frontend estará disponible en `http://localhost:5173`.

El archivo `frontend/.env.development` ya apunta la aplicación al backend en el puerto `8010`.

## Endpoints

| Método | Ruta | Descripción |
|---|---|---|
| GET | `/api/products` | Lista productos |
| POST | `/api/products` | Crea un producto |
| DELETE | `/api/products/{id}` | Elimina un producto |

## Principios evidenciados

- **Responsabilidad única:** controlador, casos de uso y repositorio tienen responsabilidades separadas.
- **Inversión de dependencias:** `ProductRepository` abstrae la persistencia.
- **Código limpio:** nombres explícitos, validación en los límites y entidad inmutable.
- **Refactoring:** la lógica de inventario está aislada del framework para facilitar evolución y pruebas.
