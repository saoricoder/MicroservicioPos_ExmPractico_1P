# 🎬 GUIÓN ESPECÍFICO: JSON-LD + React Integration
## Victor & Betty - Implementación Detallada

---

## 📌 INFORMACIÓN DEL GUIÓN
- **Duración:** 10-12 minutos
- **Enfoque:** JSON-LD en Backend + Consumo en React
- **Presentadores:**
  - **Victor:** Backend (Models, Routes, Seeders) + Frontend (Styles, Services)
  - **Betty:** Backend (Controllers, Migrations, Factories) + Frontend (Components)
- **Objetivo:** Mostrar flujo completo de datos desde DB hasta UI

---

## 📂 DISTRIBUCIÓN DE CARPETAS Y RESPONSABILIDADES

### **VICTOR**

#### Backend (Carpetas que mostrará):
```
Backend/
├── 📂 app/Models/
│   ├── Patient.php          ← toJsonLd() método clave
│   ├── User.php (Physician) ← toJsonLd() para médicos
│   ├── Specialty.php        ← toJsonLd() para especialidades
│   └── Appointment.php      ← toJsonLd() para citas
│
├── 📂 routes/
│   └── api.php              ← Rutas que exponen JSON-LD
│       GET /api/patients
│       GET /api/doctors
│       GET /api/specialties
│       GET /api/appointments
│
└── 📂 database/seeders/
    ├── PatientSeeder.php
    ├── PhysicianSeeder.php
    ├── SpecialtySeeder.php
    └── AppointmentSeeder.php
```

#### Frontend (Carpetas que mostrará):
```
Frontend/src/
├── 📂 styles/
│   ├── index.css
│   ├── jsonld-viewer.css    ← Estilos para visualizar JSON-LD
│   └── responsive.css
│
└── 📂 services/
    ├── ApiService.js        ← Consume rutas API de Victor
    ├── JsonLdParser.js      ← Parsea JSON-LD
    └── HttpClient.js        ← Cliente HTTP
```

**Total Victor:**
- 4 modelos con JSON-LD
- 1 archivo de rutas API
- 4 seeders
- 3 servicios frontend
- Estilos para visualización

---

### **BETTY**

#### Backend (Carpetas que mostrará):
```
Backend/
├── 📂 app/Http/Controllers/
│   ├── PatientController.php
│   │   └── apiShow($id) → llama Patient.toJsonLd()
│   ├── MedicoController.php
│   │   └── apiShow($id) → llama User.toJsonLd()
│   ├── SpecialtyController.php
│   │   └── apiShow($id) → llama Specialty.toJsonLd()
│   └── AppointmentController.php
│       └── apiShow($id) → llama Appointment.toJsonLd()
│
├── 📂 database/migrations/
│   ├── create_patients_table.php
│   ├── create_users_table.php
│   ├── create_specialties_table.php
│   └── create_appointments_table.php
│
└── 📂 database/factories/
    ├── PatientFactory.php
    ├── UserFactory.php
    ├── SpecialtyFactory.php
    └── AppointmentFactory.php
```

#### Frontend (Carpetas que mostrará):
```
Frontend/src/
└── 📂 components/
    ├── 👤 Patient/
    │   ├── PatientList.js      ← Consume ApiService.getPatients()
    │   ├── PatientDetail.js    ← Muestra JSON-LD del paciente
    │   └── PatientCard.js      ← Renderiza datos
    │
    ├── 👨‍⚕️ Physician/
    │   ├── DoctorList.js       ← Consume ApiService.getDoctors()
    │   ├── DoctorDetail.js     ← Muestra JSON-LD del médico
    │   └── DoctorCard.js       ← Renderiza datos
    │
    ├── 🏥 Specialty/
    │   ├── SpecialtyList.js    ← Consume ApiService.getSpecialties()
    │   ├── SpecialtyDetail.js  ← Muestra JSON-LD
    │   └── SpecialtyCard.js    ← Renderiza datos
    │
    ├── 📅 Appointment/
    │   ├── AppointmentList.js  ← Consume ApiService.getAppointments()
    │   ├── AppointmentDetail.js← Muestra JSON-LD completo
    │   └── AppointmentForm.js  ← Envía datos
    │
    └── 🎯 Common/
        └── JsonLdViewer.js     ← Visualiza JSON-LD raw
```

**Total Betty:**
- 4 controladores
- 4 migraciones
- 4 factories
- 6 componentes principales
- 1 componente visualizador

---

## 🎥 GUIÓN DETALLADO POR ESCENAS

### **ESCENA 1: INTRODUCCIÓN - Flujo JSON-LD (0:00 - 0:45)**

**[CÁMARA: Ambos en pantalla]**

```
VICTOR: "Hola, soy Victor. Hoy vamos a mostrar exactamente 
cómo implementamos JSON-LD en nuestro sistema médico."

BETTY: "Y soy Betty. En este video verán el viaje completo 
que hace un dato: desde la base de datos hasta la pantalla del usuario."

VICTOR: "Empezamos en el backend con nuestros modelos."

BETTY: "Y terminamos en React mostrando esos datos en una interfaz hermosa."

VICTOR: "Vamos a analizar 4 modelos principales: Pacientes, Médicos, 
Especialidades y Citas."

BETTY: "¡Empecemos!"
```

---

### **ESCENA 2: ARQUITECTURA JSON-LD (0:45 - 2:00)**

**[CÁMARA: Victor con diagrama en pantalla]**

```
VICTOR: "Aquí está la arquitectura de JSON-LD en nuestro proyecto.

El flujo es así:"
```

**[PANTALLA: Mostrar diagrama]**

```
DATABASE (MySQL/PostgreSQL)
        ↓
    [VICTOR] Models con toJsonLd()
        ↓
    [BETTY] Controllers que usan los Models
        ↓
    [VICTOR] Routes que exponen la API
        ↓
    HTTP Response (JSON-LD)
        ↓
    [VICTOR] Services en React consumen API
        ↓
    [BETTY] Components renderizan datos
        ↓
    UI visible al usuario
```

```
VICTOR: "Ahora, los modelos son la pieza clave. Yo voy a mostrar 
cómo cada modelo genera JSON-LD."
```

**[CÁMARA: Entra Betty]**

```
BETTY: "Y yo voy a mostrar cómo los controladores llaman 
a esos métodos toJsonLd()."

VICTOR: "Y luego cómo React lo consume."
```

---

### **ESCENA 3: MODELOS BACKEND (2:00 - 4:15)**

**[CÁMARA: Victor con código de modelos]**

```
VICTOR: "Abro la carpeta Models. Aquí tenemos 4 modelos principales.

Voy a empezar con Patient.php"
```

**[PANTALLA: Abre Backend/app/Models/Patient.php]**

```php
<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'date_of_birth',
        'gender',
        'blood_type',
        'medical_history',
        'insurance_provider',
        'insurance_policy',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
    ];

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    /**
     * Generar JSON-LD para Patient
     */
    public function toJsonLd()
    {
        return [
            "@context" => "https://schema.org",
            "@type" => "Patient",
            "@id" => route('api.patients.show', $this->id),
            "name" => $this->name,
            "email" => $this->email,
            "telephone" => $this->phone,
            "birthDate" => $this->date_of_birth?->toDateString(),
            "gender" => $this->gender,
            "bloodType" => $this->blood_type,
            "medicalHistory" => $this->medical_history,
            "healthInsurancePlan" => [
                "@type" => "HealthInsurancePlan",
                "name" => $this->insurance_provider,
                "policyNumber" => $this->insurance_policy,
            ],
        ];
    }
}
```

```
VICTOR: "Este es el modelo Patient. La magia ocurre en toJsonLd().

Analicemos:

1. @context: https://schema.org
   → Dice que usamos el vocabulario de Schema.org

2. @type: Patient
   → Es un tipo médico especializado

3. @id: route('api.patients.show', $this->id)
   → URL única del paciente en nuestra API

4. Propiedades estándar:
   - name, email, telephone: información de contacto
   - birthDate: formato ISO YYYY-MM-DD
   - gender: género
   - bloodType: tipo de sangre
   - medicalHistory: historial médico

5. healthInsurancePlan: OBJETO ANIDADO
   - También tiene @type: HealthInsurancePlan
   - Dentro contiene name y policyNumber

¿Por qué es importante? Porque Google y otros buscadores entienden 
exactamente qué es esto. Es un PACIENTE con información médica."
```

**[PANTALLA: Abre Model User.php]**

```
VICTOR: "Ahora el modelo User, que representa a los Médicos.

Voy a mostrar su toJsonLd():"
```

**[PANTALLA: Destaca el método toJsonLd()]**

```php
public function toJsonLd()
{
    return [
        "@context" => "https://schema.org",
        "@type" => "Physician",
        "@id" => route("api.doctors.show", $this->id),
        "name" => $this->name,
        "email" => $this->email,
        "telephone" => $this->phone ?? "",
        "medicalSpecialty" => $this->specialty ?? "",
        "affiliation" => [
            "@type" => "Organization",
            "name" => $this->affiliation ?? "Hospital Semántico",
        ],
        "url" => route("api.doctors.show", $this->id),
    ];
}
```

```
VICTOR: "Para médicos:

1. @type: Physician
   → Tipo médico para doctores

2. medicalSpecialty: La especialidad (Cardiología, etc.)

3. affiliation: ORGANIZACIÓN ANIDADA
   - @type: Organization
   - name: nombre del hospital/clínica

Esto permite que Google sepa: 'Este es un médico que trabaja en X hospital 
en la especialidad Y'."
```

**[PANTALLA: Abre Model Specialty.php]**

```
VICTOR: "Especialidades:"
```

```php
public function toJsonLd()
{
    return [
        "@context" => "https://schema.org",
        "@type" => "MedicalSpecialty",
        "@id" => route('api.specialties.show', $this->id),
        "name" => $this->name,
        "description" => $this->description,
        "url" => route('api.specialties.show', $this->id),
    ];
}
```

```
VICTOR: "MedicalSpecialty. Simple pero importante. Define 
las categorías médicas del sistema."
```

**[PANTALLA: Abre Model Appointment.php]**

```
VICTOR: "Y el más complejo: Appointment"
```

```php
public function toJsonLd()
{
    return [
        "@context" => "https://schema.org",
        "@type" => "MedicalBusiness",
        "@id" => route('api.appointments.show', $this->id),
        "name" => "Cita Médica #{$this->id}",
        "appointment" => [
            "@type" => "AppointmentRequest",
            "patient" => $this->patient?->toJsonLd(),
            "doctor" => [
                "@type" => "Physician",
                "@id" => route('api.doctors.show', $this->doctor_id),
                "name" => $this->doctor->name,
                "email" => $this->doctor->email,
                "telephone" => $this->doctor->phone,
                "medicalSpecialty" => $this->specialty->name,
            ],
            "appointmentStart" => $this->appointment_date->toDateTimeString(),
            "appointmentStatus" => match($this->status) {
                'PENDING' => 'AppointmentRequested',
                'CONFIRMED' => 'AppointmentBooked',
                'COMPLETED' => 'AppointmentDone',
                'CANCELLED' => 'AppointmentCancelled',
                default => $this->status,
            },
            "medicalSpecialty" => $this->specialty->name,
            "description" => $this->notes,
        ],
        "url" => route('api.appointments.show', $this->id),
    ];
}
```

```
VICTOR: "Aquí está lo fascinante:

1. @type: MedicalBusiness (servicio médico)

2. 'appointment' con @type AppointmentRequest
   → Contiene TODOS los datos relacionados

3. 'patient': Llama a $this->patient->toJsonLd()
   → ¡REUTILIZACIÓN! El JSON del paciente se incluye automáticamente

4. 'doctor': Datos del médico como Physician

5. 'appointmentStatus': Mapea nuestros estados internos
   - PENDING → AppointmentRequested
   - CONFIRMED → AppointmentBooked
   - COMPLETED → AppointmentDone
   - CANCELLED → AppointmentCancelled

En una ÚNICA respuesta tenemos:
- Información completa del paciente
- Información completa del médico
- Fecha y hora de la cita
- Estado de la cita
- Especialidad médica
- Notas clínicas

¡TODO en JSON-LD validado por Google!"
```

---

### **ESCENA 4: RUTAS Y SEEDERS (4:15 - 5:15)**

**[CÁMARA: Victor abre routes/api.php]**

```
VICTOR: "Las rutas son simples. Aquí en api.php defino:
```

```php
<?php

use App\Http\Controllers\PatientController;
use App\Http\Controllers\MedicoController;
use App\Http\Controllers\SpecialtyController;
use App\Http\Controllers\AppointmentController;
use Illuminate\Support\Facades\Route;

// Pacientes
Route::get('/patients', [PatientController::class, 'apiIndex'])->name('api.patients.index');
Route::get('/patients/{id}', [PatientController::class, 'apiShow'])->name('api.patients.show');

// Médicos
Route::get('/doctors', [MedicoController::class, 'apiIndex'])->name('api.doctors.index');
Route::get('/doctors/{id}', [MedicoController::class, 'apiShow'])->name('api.doctors.show');

// Especialidades
Route::get('/specialties', [SpecialtyController::class, 'apiIndex'])->name('api.specialties.index');
Route::get('/specialties/{id}', [SpecialtyController::class, 'apiShow'])->name('api.specialties.show');

// Citas
Route::get('/appointments', [AppointmentController::class, 'apiIndex'])->name('api.appointments.index');
Route::get('/appointments/{id}', [AppointmentController::class, 'apiShow'])->name('api.appointments.show');
```

```
VICTOR: "Cuatro rutas principales:
- /patients       → lista de pacientes
- /doctors        → lista de médicos
- /specialties    → lista de especialidades
- /appointments   → lista de citas

Cada una tiene un índice (lista) y un show (detalle).

Ahora, los datos. Aquí están los seeders que pueblan la BD:"
```

**[PANTALLA: Abre database/seeders/PatientSeeder.php]**

```php
<?php

namespace Database\Seeders;

use App\Models\Patient;
use Illuminate\Database\Seeder;

class PatientSeeder extends Seeder
{
    public function run(): void
    {
        Patient::factory(10)->create();
    }
}
```

```
VICTOR: "Seeders simples. Usan factories para generar datos ficticios.

Por ejemplo, aquí creo 10 pacientes con datos aleatorios.
Luego tenemos seeders para médicos, especialidades y citas."
```

---

### **ESCENA 5: CONTROLADORES (5:15 - 6:30)**

**[CÁMARA: Betty abre Controllers/PatientController.php]**

```
BETTY: "Ahora los controladores. Yo manejo estos 4 archivos.

Aquí está PatientController:"
```

```php
<?php

namespace App\Http\Controllers;

use App\Models\Patient;

class PatientController extends Controller
{
    public function apiIndex()
    {
        $patients = Patient::all();

        $jsonLdData = [];
        foreach ($patients as $patient) {
            $jsonLdData[] = $patient->toJsonLd();
        }

        return response()->json([
            "@context" => "https://schema.org",
            "@type" => "ItemList",
            "itemListElement" => $jsonLdData
        ]);
    }

    public function apiShow($id)
    {
        $patient = Patient::findOrFail($id);
        return response()->json($patient->toJsonLd());
    }
}
```

```
BETTY: "Es muy simple:

1. apiIndex():
   - Obtiene todos los pacientes
   - Itera cada uno y llama toJsonLd()
   - Devuelve un ItemList (lista en JSON-LD)

2. apiShow($id):
   - Obtiene un paciente por ID
   - Llama su toJsonLd()
   - Devuelve el JSON

¿Ven? Los controladores NO generan el JSON-LD. 
Los modelos lo hacen. Los controladores solo los llaman.

Esto es arquitectura limpia: separación de responsabilidades."
```

**[PANTALLA: Muestra los otros controladores igual]**

```
BETTY: "Los otros controladores (Médicos, Especialidades, Citas) 
siguen el mismo patrón. Muy limpio y mantenible."
```

---

### **ESCENA 6: MIGRACIONES Y FACTORIES (6:30 - 7:30)**

**[CÁMARA: Betty abre database/migrations]**

```
BETTY: "Las migraciones definen la estructura de la BD.

Aquí tenemos 4 migraciones para los 4 modelos."
```

**[PANTALLA: Abre create_patients_table.php]**

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone');
            $table->date('date_of_birth');
            $table->enum('gender', ['Male', 'Female', 'Other']);
            $table->string('blood_type');
            $table->text('medical_history')->nullable();
            $table->string('insurance_provider')->nullable();
            $table->string('insurance_policy')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
```

```
BETTY: "La migración define:
- id (clave primaria)
- name, email, phone (contacto)
- date_of_birth, gender (datos personales)
- blood_type (información médica)
- medical_history (historial)
- insurance_provider, insurance_policy (seguros)
- timestamps (created_at, updated_at)

Todas estas columnas mapean directamente a propiedades en toJsonLd()."
```

**[PANTALLA: Abre database/factories/PatientFactory.php]**

```php
<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PatientFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'date_of_birth' => $this->faker->dateTimeBetween('-80 years', '-18 years'),
            'gender' => $this->faker->randomElement(['Male', 'Female', 'Other']),
            'blood_type' => $this->faker->randomElement(['O+', 'O-', 'A+', 'A-', 'B+', 'B-', 'AB+', 'AB-']),
            'medical_history' => $this->faker->sentence(),
            'insurance_provider' => $this->faker->company(),
            'insurance_policy' => 'POL-' . $this->faker->numerify('######'),
        ];
    }
}
```

```
BETTY: "Las factories generan datos ficticios para testing y seeders.

Usa Faker para:
- Nombres reales
- Emails únicos
- Teléfonos válidos
- Fechas de nacimiento realistas
- Tipos de sangre válidos
- Números de póliza generados

Con esto, cuando ejecuto:
  php artisan migrate:fresh --seed

Crea la BD y la puebla con 10 pacientes reales para probar."
```

---

### **ESCENA 7: SERVICIOS FRONTEND (7:30 - 8:45)**

**[CÁMARA: Victor abre frontend/src/services]**

```
VICTOR: "Ahora el frontend. Aquí está ApiService.js que consume 
la API que Betty y yo creamos en el backend."
```

**[PANTALLA: Abre frontend/src/services/ApiService.js]**

```javascript
const API_BASE = 'http://localhost:8000/api';

// Pacientes
export const getPatients = async () => {
    const response = await fetch(`${API_BASE}/patients`);
    return response.json(); // Retorna JSON-LD
};

export const getPatient = async (id) => {
    const response = await fetch(`${API_BASE}/patients/${id}`);
    return response.json(); // Retorna JSON-LD del paciente
};

// Médicos
export const getDoctors = async () => {
    const response = await fetch(`${API_BASE}/doctors`);
    return response.json(); // Retorna JSON-LD
};

export const getDoctor = async (id) => {
    const response = await fetch(`${API_BASE}/doctors/${id}`);
    return response.json();
};

// Especialidades
export const getSpecialties = async () => {
    const response = await fetch(`${API_BASE}/specialties`);
    return response.json();
};

export const getSpecialty = async (id) => {
    const response = await fetch(`${API_BASE}/specialties/${id}`);
    return response.json();
};

// Citas
export const getAppointments = async () => {
    const response = await fetch(`${API_BASE}/appointments`);
    return response.json();
};

export const getAppointment = async (id) => {
    const response = await fetch(`${API_BASE}/appointments/${id}`);
    return response.json();
};
```

```
VICTOR: "ApiService es el puente entre React y Laravel.

Cada función:
1. Hace una petición HTTP a la URL correcta
2. Recibe JSON-LD del backend
3. Lo retorna para que los componentes lo usen

Nota: NO modificamos el JSON-LD. Lo usamos tal cual viene.
Google Structured Data Validator puede validarlo directamente."
```

**[PANTALLA: Abre frontend/src/services/JsonLdParser.js]**

```javascript
export const parseJsonLd = (jsonLdData) => {
    return {
        type: jsonLdData['@type'],
        id: jsonLdData['@id'],
        context: jsonLdData['@context'],
        data: Object.entries(jsonLdData)
            .filter(([key]) => !key.startsWith('@'))
            .reduce((acc, [key, value]) => ({...acc, [key]: value}), {})
    };
};

export const displayJsonLd = (jsonLdData) => {
    return JSON.stringify(jsonLdData, null, 2);
};

export const extractProperties = (jsonLdData, type) => {
    const properties = {
        Patient: ['name', 'email', 'telephone', 'birthDate', 'bloodType'],
        Physician: ['name', 'email', 'medicalSpecialty', 'affiliation'],
        MedicalSpecialty: ['name', 'description'],
        MedicalBusiness: ['appointment'],
    };
    
    return properties[type] || [];
};
```

```
VICTOR: "JsonLdParser extrae información del JSON-LD:

1. parseJsonLd(): Separa propiedades @ de datos
2. displayJsonLd(): Formatea para visualizar
3. extractProperties(): Obtiene propiedades por tipo

Con esto, en React accedo fácilmente a los datos JSON-LD."
```

---

### **ESCENA 8: COMPONENTES REACT (8:45 - 10:00)**

**[CÁMARA: Betty abre frontend/src/components]**

```
BETTY: "Ahora los componentes React. Aquí es donde todo se ve bonito.

Voy a mostrar PatientList:"
```

**[PANTALLA: Abre frontend/src/components/Patient/PatientList.js]**

```javascript
import { useEffect, useState } from 'react';
import { getPatients } from '../../services/ApiService';
import PatientCard from './PatientCard';

export default function PatientList() {
    const [patients, setPatients] = useState([]);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        getPatients()
            .then(data => {
                // data es un ItemList con itemListElement
                setPatients(data.itemListElement);
                setLoading(false);
            })
            .catch(error => {
                console.error('Error:', error);
                setLoading(false);
            });
    }, []);

    if (loading) return <div>Cargando...</div>;

    return (
        <div className="patient-list">
            <h2>Pacientes</h2>
            <div className="card-grid">
                {patients.map(patient => (
                    <PatientCard key={patient['@id']} patient={patient} />
                ))}
            </div>
        </div>
    );
}
```

```
BETTY: "Veamos el flujo:

1. useEffect() se ejecuta al montar el componente
2. Llama getPatients() del ApiService
3. Obtiene data.itemListElement (la lista de JSON-LD)
4. Guarda en estado con setPatients()
5. Mapea cada paciente a un PatientCard

Aquí está PatientCard:"
```

**[PANTALLA: Abre PatientCard.js]**

```javascript
import '../styles/patient-card.css';

export default function PatientCard({ patient }) {
    return (
        <div className="patient-card">
            <h3>{patient.name}</h3>
            <p><strong>Email:</strong> {patient.email}</p>
            <p><strong>Teléfono:</strong> {patient.telephone}</p>
            <p><strong>Tipo de sangre:</strong> {patient.bloodType}</p>
            <p><strong>Género:</strong> {patient.gender}</p>
            <p><strong>Historial:</strong> {patient.medicalHistory}</p>
            {patient.healthInsurancePlan && (
                <div className="insurance-info">
                    <p><strong>Aseguradora:</strong> {patient.healthInsurancePlan.name}</p>
                    <p><strong>Póliza:</strong> {patient.healthInsurancePlan.policyNumber}</p>
                </div>
            )}
        </div>
    );
}
```

```
BETTY: "PatientCard recibe un 'patient' que es el JSON-LD:

{
  "@context": "https://schema.org",
  "@type": "Patient",
  "@id": "...",
  "name": "María García",
  "email": "maria@example.com",
  "telephone": "+34 123 456 789",
  "birthDate": "1980-05-15",
  "gender": "Female",
  "bloodType": "O+",
  "medicalHistory": "...",
  "healthInsurancePlan": {
    "@type": "HealthInsurancePlan",
    "name": "...",
    "policyNumber": "..."
  }
}

Y simplemente accedo a sus propiedades:
- patient.name
- patient.email
- patient.telephone
- patient.healthInsurancePlan.name

¡Todo viniendo del JSON-LD sin modificar!"
```

**[PANTALLA: Muestra DoctorList.js y componentes de médicos]**

```
BETTY: "Médicos es similar pero con especialidades:

```javascript
export default function DoctorCard({ doctor }) {
    return (
        <div className="doctor-card">
            <h3>{doctor.name}</h3>
            <p><strong>Especialidad:</strong> {doctor.medicalSpecialty}</p>
            <p><strong>Email:</strong> {doctor.email}</p>
            <p><strong>Teléfono:</strong> {doctor.telephone}</p>
            {doctor.affiliation && (
                <p><strong>Hospital:</strong> {doctor.affiliation.name}</p>
            )}
        </div>
    );
}
```

Aquí accedo a doctor.affiliation.name 
que es el objeto Organization anidado en el JSON-LD."
```

**[PANTALLA: Muestra AppointmentDetail.js - el más complejo]**

```
BETTY: "Las citas son las más complejas porque tienen 
paciente y médico anidados:

```javascript
export default function AppointmentDetail({ appointment }) {
    const apt = appointment.appointment; // AppointmentRequest anidado

    return (
        <div className="appointment-detail">
            <h2>{appointment.name}</h2>
            
            <div className="patient-section">
                <h3>Paciente</h3>
                <p><strong>Nombre:</strong> {apt.patient.name}</p>
                <p><strong>Email:</strong> {apt.patient.email}</p>
                <p><strong>Tipo de sangre:</strong> {apt.patient.bloodType}</p>
            </div>

            <div className="doctor-section">
                <h3>Médico</h3>
                <p><strong>Dr. {apt.doctor.name}</strong></p>
                <p><strong>Especialidad:</strong> {apt.doctor.medicalSpecialty}</p>
                <p><strong>Email:</strong> {apt.doctor.email}</p>
            </div>

            <div className="appointment-info">
                <h3>Detalles de la Cita</h3>
                <p><strong>Fecha y Hora:</strong> {apt.appointmentStart}</p>
                <p><strong>Estado:</strong> {apt.appointmentStatus}</p>
                <p><strong>Especialidad:</strong> {apt.medicalSpecialty}</p>
                <p><strong>Notas:</strong> {apt.description}</p>
            </div>
        </div>
    );
}
```

En una ÚNICA petición, tengo:
- appointment.appointment.patient → Paciente completo
- appointment.appointment.doctor → Médico completo
- appointment.appointment.appointmentStart → Fecha/hora
- appointment.appointment.appointmentStatus → Estado

¡Todo en la respuesta de la API!"
```

---

### **ESCENA 9: ESTILOS FRONTEND (10:00 - 10:45)**

**[CÁMARA: Victor abre styles]**

```
VICTOR: "Ahora los estilos que hacen que todo se vea bien.

En src/styles/ tengo varios archivos CSS:"
```

**[PANTALLA: Abre styles/jsonld-viewer.css]**

```css
/* JSON-LD Viewer Styles */

.jsonld-viewer {
    background-color: #f5f5f5;
    border: 1px solid #ddd;
    border-radius: 4px;
    padding: 20px;
    margin: 20px 0;
    font-family: 'Courier New', monospace;
    font-size: 12px;
}

.jsonld-viewer pre {
    background-color: #2d2d2d;
    color: #f8f8f2;
    padding: 15px;
    border-radius: 4px;
    overflow-x: auto;
}

.jsonld-viewer .key {
    color: #e6db74;
}

.jsonld-viewer .value {
    color: #a1efe4;
}

.jsonld-viewer .type-badge {
    display: inline-block;
    background-color: #61dafb;
    color: #000;
    padding: 4px 8px;
    border-radius: 3px;
    margin-right: 10px;
    font-weight: bold;
}

.jsonld-validator-link {
    margin-top: 10px;
    padding: 10px;
    background-color: #4285f4;
    color: white;
    border-radius: 4px;
    text-decoration: none;
    display: inline-block;
}

.jsonld-validator-link:hover {
    background-color: #357ae8;
}
```

```
VICTOR: "Este CSS muestra el JSON-LD de forma bonita:

- Fondo oscuro para el código
- Colores para keys y values
- Badge para el @type
- Enlace para validar en Google"
```

**[PANTALLA: Abre styles/responsive.css]**

```
VICTOR: "Responsive design para que funcione en móviles:

```css
/* Responsive Grid */

.card-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 20px;
    padding: 20px;
}

@media (max-width: 768px) {
    .card-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 480px) {
    .card-grid {
        padding: 10px;
        gap: 10px;
    }
}
```

Así, los componentes se adaptan a cualquier pantalla."
```

---

### **ESCENA 10: FLUJO COMPLETO EN ACCIÓN (10:45 - 11:30)**

**[CÁMARA: Ambos con navegador ejecutando el sistema]**

```
BETTY: "Ahora veamos todo funcionando junto.

Backend ejecutándose en puerto 8000:"
```

**[PANTALLA: Terminal mostrando 'php artisan serve']**

```
VICTOR: "Frontend ejecutándose en puerto 3000:"
```

**[PANTALLA: Terminal mostrando 'npm start']**

```
BETTY: "Abro el navegador y voy a http://localhost:3000"
```

**[PANTALLA: Muestra la aplicación React]**

```
VICTOR: "Aquí está la lista de pacientes. 

Presiono F12 para ver el Network y el HTML:"
```

**[PANTALLA: Abre DevTools Network]**

```
VICTOR: "Aquí está la petición a /api/patients.

Response:"
```

**[PANTALLA: Muestra el JSON-LD en la Response]**

```json
{
  "@context": "https://schema.org",
  "@type": "ItemList",
  "itemListElement": [
    {
      "@context": "https://schema.org",
      "@type": "Patient",
      "@id": "http://localhost:8000/api/patients/1",
      "name": "María García López",
      "email": "maria@example.com",
      "telephone": "+34 123 456 789",
      "birthDate": "1980-05-15",
      "gender": "Female",
      "bloodType": "O+",
      "medicalHistory": "Presión normal, diabetes tipo 2",
      "healthInsurancePlan": {
        "@type": "HealthInsurancePlan",
        "name": "Seguros Médicos S.A.",
        "policyNumber": "POL-2024-123456"
      }
    },
    ...más pacientes
  ]
}
```

```
BETTY: "Este JSON-LD se procesa en PatientList.js
que extrae itemListElement,
luego lo mapea a PatientCard,
y React lo renderiza."
```

**[PANTALLA: Haz clic en un paciente para ver detalle]**

```
VICTOR: "Aquí está el detalle de un paciente.

Voy a ver el JSON-LD raw en DevTools."
```

**[PANTALLA: Console muestra el objeto JSON-LD]**

```javascript
{
  "@context": "https://schema.org",
  "@type": "Patient",
  "@id": "http://localhost:8000/api/patients/1",
  "name": "María García López",
  ...
}
```

```
BETTY: "Este es el JSON-LD puro que viene de la API.

Ahora, lo importante: este JSON-LD es VALIDABLE."
```

---

### **ESCENA 11: VALIDACIÓN GOOGLE (11:30 - 12:00)**

**[CÁMARA: Victor abre Google Structured Data Validator]**

```
VICTOR: "Voy a https://search.google.com/structured-data/testing-tool/

Pego aquí el JSON-LD de la cita médica:"
```

**[PANTALLA: Copia el JSON-LD de una cita]**

```
VICTOR: "Aquí lo pego en el validator:"
```

**[PANTALLA: Pega el JSON-LD]**

```
VICTOR: "¡Validemos!"
```

**[PANTALLA: Muestra validación exitosa]**

```
VICTOR: "¡Perfecto! Google dice:
- ✅ Sin errores
- ✅ Sin advertencias
- ✅ Datos extraídos correctamente

Aquí están los datos que Google extrajo:
- name: 'Cita Médica #1'
- @type: 'MedicalBusiness'
- appointment.appointmentStart: '2025-01-15 10:30:00'
- appointment.appointmentStatus: 'AppointmentBooked'
- appointment.patient: Datos del paciente
- appointment.doctor: Datos del médico"
```

```
BETTY: "¿Por qué esto es importante?

1. SEO: Los buscadores indexan mejor
2. Rich Snippets: Resultados más atractivos
3. Asistentes de voz: Alexa, Google Assistant pueden usar los datos
4. Otras aplicaciones: Cualquier app que lea JSON-LD puede consumir nuestra API"
```

---

### **ESCENA 12: RESUMEN TÉCNICO (12:00 - 12:30)**

**[CÁMARA: Ambos con diagrama de arquitectura]**

```
VICTOR: "Resumamos la arquitectura técnica:

VICTOR cuida:
- Modelos con toJsonLd()
- Rutas API
- Seeders de BD
- Servicios ApiService
- Estilos CSS

BETTY cuida:
- Controladores que usan toJsonLd()
- Migraciones de BD
- Factories para datos
- Componentes React que consumen datos"
```

**[PANTALLA: Tabla de responsabilidades]**

```
┌─────────────────────────────────────────────────────────┐
│              FLUJO DE DATOS JSON-LD                     │
├─────────────────────────────────────────────────────────┤
│                                                         │
│  DATABASE (MySQL)                                       │
│    Tablas: patients, users, specialties, appointments   │
│                                                         │
│         ↓ [VICTOR Seeders]                             │
│                                                         │
│  MODELOS (toJsonLd)                                     │
│    Patient.toJsonLd()                                   │
│    User.toJsonLd()                                      │
│    Specialty.toJsonLd()                                 │
│    Appointment.toJsonLd()                               │
│                                                         │
│         ↓ [BETTY Controllers]                           │
│                                                         │
│  CONTROLADORES (llaman toJsonLd)                        │
│    PatientController.apiShow()                          │
│    MedicoController.apiShow()                           │
│    SpecialtyController.apiShow()                        │
│    AppointmentController.apiShow()                      │
│                                                         │
│         ↓ [VICTOR Routes]                              │
│                                                         │
│  API JSON-LD                                            │
│    GET /api/patients/{id}                               │
│    GET /api/doctors/{id}                                │
│    GET /api/specialties/{id}                            │
│    GET /api/appointments/{id}                           │
│                                                         │
│         ↓ [VICTOR ApiService.js]                       │
│                                                         │
│  REACT COMPONENTS                                       │
│    PatientList → PatientCard                            │
│    DoctorList → DoctorCard                              │
│    SpecialtyList → SpecialtyCard                        │
│    AppointmentDetail                                    │
│                                                         │
│         ↓ [BETTY Components]                            │
│                                                         │
│  UI RENDERIZADO                                         │
│    HTML con datos visuales                              │
│    JSON-LD en el DOM (para Google)                      │
│                                                         │
│         ↓ [VICTOR Estilos CSS]                         │
│                                                         │
│  USUARIO VE LA PÁGINA                                   │
│    Bonita, responsive, semántica                        │
│                                                         │
└─────────────────────────────────────────────────────────┘
```

```
BETTY: "Lo importante es que JSON-LD no es solo para máquinas.

Es REAL DATA que fluye de BD → API → React → UI."
```

```
VICTOR: "Y Google puede validarlo y entenderlo."
```

```
BETTY: "Esto es arquitectura moderna: datos semánticos desde el inicio."
```

---

## 📊 TABLA FINAL DE DISTRIBUCIÓN

| Componente | Victor | Betty | Descripción |
|-----------|--------|-------|-------------|
| **Patient Model** | ✅ Crea | - | toJsonLd() genera JSON-LD |
| **PatientController** | - | ✅ Llama | apiShow() usa toJsonLd() |
| **Patients Route** | ✅ Define | - | GET /api/patients/{id} |
| **Patient Migration** | - | ✅ Crea | Tabla pacients en BD |
| **PatientFactory** | - | ✅ Genera | Datos ficticios para testing |
| **PatientSeeder** | ✅ Ejecuta | - | Puebla BD con datos |
| **ApiService** | ✅ Consume API | - | getPatients() |
| **PatientList.js** | - | ✅ Renderiza | Muestra pacientes |
| **PatientCard.js** | - | ✅ Renderiza | Tarjeta individual |
| **Estilos CSS** | ✅ Crea | - | Estilos visuales |

---

## 🎬 INFORMACIÓN TÉCNICA

- **Duración total:** 12:30 minutos
- **Resolución recomendada:** 1920x1080
- **Velocidad de grabación:** 60fps para smoothness
- **Audio:** Micrófono de buena calidad

---

Este guión muestra exactamente:
✅ Cómo Victor implementa modelos JSON-LD
✅ Cómo Betty los usa en controladores
✅ Cómo se pasan al frontend
✅ Cómo React los consume y renderiza
✅ Cómo Google los valida
✅ Distribución equitativa de trabajo
