# Aplicación EICHsys

## Objetivo

A partir de un modelo de conocimiento representado en una ontología sobre la enfermedad injerto contra huésped, donde se describe el modelo de datos y el conocimiento terminológico utilizado para representar la evolución de la enfermedad y los síntomas de pacientes con enfermedad injerto contra huésped, así como las alternativas de tratamiento posibles incluso experimentales (ensayos clínicos), se implementará una herramienta de razonamiento basado en reglas y subsunciones para proporcionar planes asistenciales de tratamiento centrados en el paciente que padece EICH. Estos planes incluirán tratamientos posibles en cada momento, necesidades de autocuidado, necesidades de seguimiento clínico e identificación de candidatos para participar en ensayos clínicos.

## Puesta en marcha
Siga estos pasos para ejecutar la aplicación en Laravel Sail. Se da por supuesto que tiene Docker disponible en su sistema.
1. Clone desde Visual Studio Code (o cualquier IDE de su preferencia) este repositorio.
2. Duplique el archivo .env.example y renómbrelo a .env
3. Desde el terminal, navegue hasta el directorio donde haya descargado el proyecto y ejecute: ``docker run --rm \
   -u "$(id -u):$(id -g)" \
   -v "$(pwd):/var/www/html" \
   -w /var/www/html \
   laravelsail/php83-composer:latest \
   composer install --ignore-platform-reqs``. Más info: https://laravel.com/docs/master/sail#installing-composer-dependencies-for-existing-projects.
4. Cuando termine, compruebe que la carpeta vendor está disponible.
5. Desde el terminal, partiendo del directorio base del proyecto, ejecute: ```./vendor/bin/sail up -d```
7. Cuando termine el comando anterior, ejecute: ``./vendor/bin/sail artisan migrate:fresh --seed && ./vendor/bin/sail artisan storage:link``
8. Cuando termine el comando anterior, ejecute ``npm install``
9. Cuando termine el comando anterior, ejecute ``npm run dev``
10. Abra su navegador y escriba en la barra de navegación: http://localhost
