# EICHsys – Sistema de Gestión Clínica y Sistema Experto para EICH

## Descripción

EICHsys es una aplicación web desarrollada como Trabajo Fin de Grado cuyo objetivo es proporcionar apoyo a la decisión clínica durante el diagnóstico y tratamiento de la Enfermedad Injerto Contra Huésped (EICH).

La aplicación integra un **sistema de gestión clínica**, un **sistema experto basado en reglas** y un **servicio externo de generación de informes clínicos basados en evidencia científica**, constituyendo una arquitectura híbrida que combina razonamiento determinista con recuperación de conocimiento científico mediante técnicas de Retrieval-Augmented Generation (RAG).

Este repositorio contiene el **Sistema de Gestión Clínica**, desarrollado en Laravel, responsable de la gestión de pacientes, el motor de inferencia clínica, la coordinación del flujo asistencial y la integración con el servicio externo de generación de informes.

---

# Arquitectura general

El sistema está compuesto por dos subsistemas principales:

- **Sistema de Gestión Clínica (Laravel)**
- **Servicio de Generación de Informes Clínicos (Flask + RAG + LLM)**

El Sistema de Gestión Clínica constituye el núcleo funcional de la aplicación y es responsable de:

- Gestión de usuarios, médicos y pacientes.
- Registro de síntomas clínicos.
- Evaluación NIH por órgano.
- Inferencia diagnóstica mediante reglas clínicas.
- Inferencia de propuestas de tratamiento.
- Gestión de diagnósticos y tratamientos.
- Comunicación con el servicio de generación de informes.
- Persistencia y visualización de informes clínicos.

El Servicio de Generación de Informes Clínicos se encuentra en un repositorio independiente y se invoca mediante una API REST.

---

# Sistema experto

El sistema experto implementa un motor de inferencia basado en reglas clínicas explícitas.

A partir de:

- síntomas activos,
- órganos afectados,
- puntuaciones NIH,
- estado clínico del paciente,

el sistema aplica reglas de decisión para inferir:

- tipo de EICH,
- gravedad clínica,
- propuesta inicial de tratamiento.

Las reglas se implementan mediante servicios de inferencia independientes, lo que permite mantener desacoplada la lógica clínica del resto de la aplicación y facilitar su evolución.

El proceso de inferencia es completamente determinista y reproducible.

---

# Generación de informes clínicos

La generación del informe clínico no forma parte del sistema experto.

Una vez obtenido el diagnóstico, el Sistema de Gestión Clínica solicita la generación de un informe basado en evidencia científica mediante un servicio externo.

Para evitar bloquear la interfaz del usuario, la petición se procesa de forma asíncrona utilizando la infraestructura de colas de Laravel.

El flujo general es el siguiente:

1. El médico solicita la generación del informe.
2. Laravel crea un trabajo en cola.
3. El trabajador procesa la petición.
4. Se invoca el servicio Flask mediante REST.
5. El informe generado se almacena en la base de datos.
6. El médico puede consultar el estado y visualizar el resultado una vez finalizado.

---

# Servicio RAG

El servicio de generación de informes se divide en dos fases claramente diferenciadas.

## 1. Retrieval

A partir del diagnóstico inferido y del contexto clínico del paciente, el servicio:

- construye una consulta clínica,
- recupera la literatura científica más relevante,
- selecciona los fragmentos documentales que servirán como evidencia.

Esta fase implementa una arquitectura Retrieval-Augmented Generation (RAG).

## 2. Generación del informe

Los fragmentos recuperados se incorporan al prompt enviado al modelo de lenguaje local.

El modelo genera un informe clínico estructurado que resume:

- diagnóstico inferido,
- justificación clínica,
- evidencia científica utilizada,
- recomendaciones para revisión médica.

El informe generado es posteriormente almacenado y mostrado desde el Sistema de Gestión Clínica.

---

# Tecnologías principales

## Backend

- Laravel 10
- PHP 8.3
- Laravel Sail
- MySQL
- Redis (colas)
- Queue Jobs

## Frontend

- Blade
- Bootstrap
- JavaScript
- Vite

## Integración

- API REST
- Procesamiento asíncrono
- JSON

---

# Estructura funcional

```
Sistema de Gestión Clínica
│
├── Gestión de pacientes
├── Gestión de síntomas
├── Evaluación NIH
├── Sistema experto
│   ├── Inferencia diagnóstica
│   └── Inferencia de tratamiento
├── Gestión de diagnósticos
├── Gestión de tratamientos
├── Informes clínicos
│   ├── Cola de procesamiento
│   ├── Cliente REST
│   └── Persistencia
└── Portal del paciente
```

---

# Puesta en marcha

Se asume que Docker se encuentra instalado en el sistema.

## 1. Clonar el repositorio

```bash
git clone <repositorio>
```

## 2. Configurar el entorno

Duplicar:

```
.env.example
```

como

```
.env
```

---

## 3. Instalar dependencias de Composer

```bash
docker run --rm \
-u "$(id -u):$(id -g)" \
-v "$(pwd):/var/www/html" \
-w /var/www/html \
laravelsail/php83-composer:latest \
composer install --ignore-platform-reqs
```

---

## 4. Iniciar Laravel Sail

```bash
./vendor/bin/sail up -d
```

---

## 5. Inicializar la base de datos

```bash
./vendor/bin/sail artisan migrate:fresh --seed
./vendor/bin/sail artisan storage:link
```

---

## 6. Instalar dependencias del frontend

```bash
npm install
```

---

## 7. Compilar recursos

```bash
npm run dev
```

---

## 8. Ejecutar el sistema

```
http://localhost
```

---

# Repositorio complementario

El servicio de generación de informes clínicos basado en RAG y modelos de lenguaje se distribuye como un proyecto independiente desarrollado en Python (Flask), encargado de la recuperación documental y generación de informes clínicos mediante evidencia científica.