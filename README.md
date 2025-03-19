# Proyecto Backend

¡Bienvenido al repositorio del proyecto Backend! Este proyecto está diseñado para gestionar datos de la API de Star Wars y proporcionar una API REST JSON robusta y segura.

## 🚀 Características principales

- **Gestión de usuarios**: Registro, inicio de sesión y recuperación de contraseñas.
- **Procesamiento de datos**: Procesamiento eficiente de datos obtenidos de la API de Star Wars.
- **API RESTful**: Endpoints bien documentados para interactuar con el sistema.
- **Seguridad**: Autenticación mediante OAuth2.
- **Escalabilidad**: Arquitectura diseñada para manejar un alto tráfico.
- **Integración con API externa**: Llamadas a la API de Star Wars para obtener información sobre personajes, planetas y vehículos.

## 🛠️ Tecnologías utilizadas

- **PHP (v8.2)**: Lenguaje principal utilizado para el desarrollo del backend.
- **Laravel Framework (v11.31)**: Framework PHP utilizado para construir la API RESTful y gestionar la lógica del negocio.
- **Laravel Passport (v12.4)**: Proporciona autenticación OAuth2 para proteger los endpoints de la API.
- **Knuckleswtf/Scribe (v5.1)**: Herramienta para generar documentación automática de la API.
- **Laravel Tinker (v2.9)**: Proporciona una consola interactiva para probar y depurar el código.
- **PHPUnit (v11.0.1)**: Framework de pruebas utilizado para realizar pruebas unitarias y funcionales.
- **Base de datos**: MySQL
- **Autenticación**: OAuth2
- **Pruebas**: PHPUnit / Postman

## 📂 Estructura del proyecto

```
Backend/
├── .editorconfig
├── .env.example
├── .env.testing
├── .gitattributes
├── .gitignore
├── .rnd
├── .scribe/
│   ├── .filehashes
│   ├── auth.md
│   ├── endpoints/
│   │   ├── 00.yaml
│   │   ├── 01.yaml
│   │   ├── 02.yaml
│   │   ├── 03.yaml
│   │   └── custom.0.yaml
│   ├── endpoints.cache/
│   │   ├── 00.yaml
│   │   ├── 01.yaml
│   │   ├── 02.yaml
│   │   └── 03.yaml
│   └── intro.md
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── API/
│   │   │   │   ├── Auth/
│   │   │   │   │   └── AuthController.php
│   │   │   │   ├── PeopleController.php
│   │   │   │   ├── PlanetController.php
│   │   │   │   └── VehicleController.php
│   │   │   └── Controller.php
│   │   ├── Middleware/
│   │   │   ├── CorsMiddleware.php
│   │   │   └── ExceptionMiddleware.php
│   │   ├── Requests/
│   │   │   └── API/
│   │   │       ├── LoginRequest.php
│   │   │       └── RegisterRequest.php
│   │   └── Resources/
│   │       ├── Auth/
│   │       │   ├── AuthResource.php
│   │       │   └── UserResource.php
│   │       └── SWAPI/
│   │           ├── PeopleResource.php
│   │           ├── PlanetResource.php
│   │           └── VehicleResource.php
│   ├── Models/
│   │   └── User.php
│   ├── Providers/
│   │   └── AuthServiceProvider.php
│   ├── Services/
│   │   └── Api/
│   │       ├── Auth/
│   │       │   └── AuthService.php
│   │       ├── PeopleService.php
│   │       ├── PlanetService.php
│   │       ├── SwapiService.php
│   │       └── VehicleService.php
│   └── Utils/
│       └── ApiResponseHandler.php
├── artisan
├── bootstrap/
│   ├── app.php
│   ├── cache/
│   │   ├── .gitignore
│   └── providers.php
├── composer.json
├── composer.lock
├── config/
│   ├── app.php
│   ├── auth.php
│   ├── cache.php
│   ├── database.php
│   ├── filesystems.php
│   ├── logging.php
│   ├── mail.php
│   ├── passport.php
│   ├── permission.php
│   ├── queue.php
│   ├── scribe.php
│   ├── services.php
│   └── session.php
├── database/
│   ├── .gitignore
│   ├── backend_db.sql
│   ├── factories/
│   │   └── UserFactory.php
│   ├── migrations/
│   │   ├── 0001_01_01_000000_create_users_table.php
│   │   ├── 0001_01_01_000001_create_cache_table.php
│   │   ├── 0001_01_01_000002_create_jobs_table.php
│   │   ├── 2025_03_18_140913_create_oauth_auth_codes_table.php
│   │   ├── 2025_03_18_140914_create_oauth_access_tokens_table.php
│   │   ├── 2025_03_18_140915_create_oauth_refresh_tokens_table.php
│   │   ├── 2025_03_18_140916_create_oauth_clients_table.php
│   │   └── 2025_03_18_140917_create_oauth_personal_access_clients_table.php
│   └── seeders/
│       ├── DatabaseSeeder.php
│       ├── PassportSeeder.php
│       └── UserSeeder.php
├── lang/
│   ├── en/
│   │   ├── auth.php
│   │   ├── pagination.php
│   │   ├── passwords.php
│   │   └── validation.php
│   ├── es/
│   │   ├── auth.php
│   │   ├── pagination.php
│   │   ├── passwords.php
│   │   └── validation.php
│   └── es.json
├── LICENSE
├── package.json
├── phpunit.xml
├── postcss.config.js
├── public/
│   ├── .htaccess
│   ├── favicon.ico
│   ├── index.php
│   ├── robots.txt
│   └── vendor/
│       └── scribe/
│           ├── css/
│           │   ├── theme-default.print.css
│           │   └── theme-default.style.css
│           ├── images/
│           │   └── navbar.png
│           └── js/
│               ├── theme-default-5.1.0.js
│               └── tryitout-5.1.0.js
├── README.md
├── resources/
│   ├── css/
│   │   └── app.css
│   ├── js/
│   │   ├── app.js
│   │   └── bootstrap.js
│   └── views/
│       ├── scribe/
│       │   └── index.blade.php
│       ├── vendor/
│       │   └── l5-swagger/
│       │       ├── .gitkeep
│       │       └── index.blade.php
│       └── welcome.blade.php
├── routes/
│   ├── api.php
│   ├── console.php
│   └── web.php
├── storage/
│   ├── api-docs/
│   │   └── api-docs.json
│   ├── app/
│   │   ├── .gitignore
│   ├── framework/
│   │   ├── .gitignore
│   │   ├── cache/
│   │   │   ├── .gitignore
│   │   ├── sessions/
│   │   │   └── .gitignore
│   │   ├── testing/
│   │   │   └── .gitignore
│   │   └── views/
│   │       ├── .gitignore
│   ├── logs/
│   │   ├── .gitignore
├── tailwind.config.js
├── tests/
│   ├── Feature/
│   │   ├── AuthTest.php
│   │   ├── CorsAndAuthTest.php
│   │   ├── PeopleTest.php
│   │   ├── PlanetTest.php
│   │   └── VehicleTest.php
│   ├── TestCase.php
│   └── Unit/
│       ├── AuthServiceTest.php
│       ├── PeopleServiceTest.php
│       ├── PlanetServiceTest.php
│       ├── SwapiServiceTest.php
│       └── VehicleServiceTest.php
└── vite.config.js

```

### Usuario de prueba

Para facilitar las pruebas, puedes utilizar las siguientes credenciales:

- **Correo electrónico**: `test@example.com`
- **Contraseña**: `password`

## 🚀 Cómo empezar

1. Clona este repositorio:
   ```bash
   git clone https://github.com/HGAteam/Backend.git
   ```
2. Instala las dependencias:
   ```bash
   compose install
   ```   
3. Configura las variables de entorno:
   Copia el archivo `.env.example` a `.env` y ajusta los valores según tu entorno.
4. Ejecuta las migraciones y seeders:
   ```bash
   php artisan migrate:fresh --seed
   ```
5. Inicia el servidor:
   ```bash
   php artisan serve
   ```

## 📋 Casos de prueba

Los casos de prueba están organizados en dos categorías principales: **Feature** y **Unit**.

### Pruebas de características (Feature)

1. **AuthTest.php**:
   - Verifica el registro, inicio de sesión y validación de tokens.
2. **CorsAndAuthTest.php**:
   - Comprueba la correcta configuración de CORS y la autenticación en las solicitudes.
3. **PeopleTest.php**:
   - Valida las llamadas GET All y GET by ID para el endpoint de personajes.
4. **PlanetTest.php**:
   - Valida las llamadas GET All y GET by ID para el endpoint de planetas.
5. **VehicleTest.php**:
   - Valida las llamadas GET All y GET by ID para el endpoint de vehículos.

### Pruebas unitarias (Unit)

1. **AuthServiceTest.php**:
   - Prueba las funciones relacionadas con la autenticación, como la generación y validación de tokens.
2. **PeopleServiceTest.php**:
   - Verifica la lógica de negocio para manejar datos de personajes.
3. **PlanetServiceTest.php**:
   - Verifica la lógica de negocio para manejar datos de planetas.
4. **SwapiServiceTest.php**:
   - Prueba la integración con la API de Star Wars (SWAPI) y las llamadas externas.
5. **VehicleServiceTest.php**:
   - Verifica la lógica de negocio para manejar datos de vehículos.

### Cómo ejecutar las pruebas

1. **Ejecutar todas las pruebas**:
   Ejecuta todas las pruebas disponibles en el proyecto.
   ```bash
   php artisan test
   ```

2. **Ejecutar pruebas específicas**:
   Filtra y ejecuta una prueba específica utilizando el nombre de la clase o método de prueba.
   ```bash
   php artisan test --filter NombreDelTest
   ```

3. **Ejecutar pruebas unitarias únicamente**:
   Ejecuta solo las pruebas unitarias ubicadas en el directorio `tests/Unit`.
   ```bash
   php artisan test --testsuite=Unit
   ```

4. **Ejecutar pruebas de características únicamente**:
   Ejecuta solo las pruebas de características ubicadas en el directorio `tests/Feature`.
   ```bash
   php artisan test --testsuite=Feature
   ```

## 📖 Documentación de la API

La documentación de la API ha sido generada automáticamente utilizando **Scribe** y está disponible para consulta en formato web. Además, puedes importar la colección de Postman para realizar pruebas directamente.

### Herramientas utilizadas
- **Scribe**: Genera documentación automática basada en los comentarios de los controladores y rutas.
- **Postman**: Herramienta para realizar pruebas de los endpoints de la API.

### Acceso a la documentación
- **Documentación generada por Scribe**: [Documentación Scribe](https://hgateam.com/docs)
- **Colección de Postman**: [Postman Collection](https://app.getpostman.com/join-team?invite_code=ff51a5ed2e34d11f0dad7b9fc2931af09df457ac37da11c6f0462ff748127205)

### Endpoints principales

#### Endpoints públicos (Sin autenticación)
- **POST /api/login**: Iniciar sesión y obtener un token.
- **POST /api/register**: Registrar un nuevo usuario.

#### Endpoints protegidos (Requieren autenticación)
- **POST /api/logout**: Cerrar sesión (requiere autenticación).
- **GET /api/is-authenticated**: Verificar si el usuario está autenticado (requiere autenticación).
- **Personajes (People)**:
  - **GET /api/people**: Obtener una lista de personajes (con `limit` y `offset`).
  - **GET /api/people/{id}**: Obtener detalles de un personaje por ID.
- **Planetas (Planets)**:
  - **GET /api/planets**: Obtener una lista de planetas (con `limit` y `offset`).
  - **GET /api/planets/{id}**: Obtener detalles de un planeta por ID.
- **Vehículos (Vehicles)**:
  - **GET /api/vehicles**: Obtener una lista de vehículos (con `limit` y `offset`).
  - **GET /api/vehicles/{id}**: Obtener detalles de un vehículo por ID.

## 🤝 Contribuciones

¡Las contribuciones son bienvenidas! Por favor, sigue los pasos a continuación:

1. Haz un fork del repositorio.
2. Crea una rama para tu funcionalidad (`git checkout -b feature/nueva-funcionalidad`).
3. Realiza tus cambios y haz un commit (`git commit -m 'Añadir nueva funcionalidad'`).
4. Haz un push a tu rama (`git push origin feature/nueva-funcionalidad`).
5. Abre un Pull Request.

## 📄 Licencia

Este proyecto está bajo la licencia [MIT](LICENSE).

## 📞 Contacto

Si tienes alguna pregunta o sugerencia, no dudes en contactarme:

- **Correo**: [adrgabtu@gmail.com](mailto:adrgabtu@gmail.com)
- **GitHub**: [https://github.com/HGAteam](https://github.com/HGAteam)

---
¡Gracias por visitar este repositorio! 😊
