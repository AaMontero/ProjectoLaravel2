#Ejecucion del projecto 
## Instalación de las dependencias 
```
    composer install 
```
Es necesario activar algunas extensiones en el archivo php.ini
- extension=curl
- extension=intl
- extension=zip
## Cambios para el archivo .env 
### Variables de entorno para la aplicación
```
APP_NAME=Laravel
APP_ENV=production
APP_KEY=
APP_DEBUG=true
APP_URL=
APP_LOCALE = es
```
### Conexion a la base de datos MySQL 
```
DB_CONNECTION=mysql
DB_HOST=
DB_PORT=
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=
```
### Variables para el uso de Pusher 
```
PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_HOST=
PUSHER_PORT=
PUSHER_SCHEME=
PUSHER_APP_CLUSTER=
```
### Variables de entorno para whatsapp 
```
WPP_NUM = ""
WPP_TOKEN = "" 
WPP_ID = ""
WPP_VERSION = ""
WPP_MULTIVERSION = ""    
```
## Inicialización de npm 
```
 npm install 
 (Para el desarrollo local) - npm run dev 
 (Para el despliegue) - npm run build
```