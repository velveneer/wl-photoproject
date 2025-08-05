# Edira Project Directory Structure
```

├── EDIRA/
│   ├── app/                  # Contains all application-specific code
│   │   ├── Actions/
│   │   │   ├── Assessments/  
│   │   │   ├── Auth/     
│   │   │   ├── Images/
│   │   │   ├── Iso/  
│   │   │   ├── ItGrundschutz/     
│   │   │   ├── Nis/  
│   │   │   ├── Privacyreport/  
│   │   │   ├── Soa/     
│   │   │   ├── Tisax/ 
│   │   │   ├── Vvt/         
│   │   │   └── ...
│   │   ├── Console/
│   │   │   ├── Commands/         
│   │   │   └── ...
│   │   ├── Exceptions/
│   │   │   └── ...
│   │   ├── Helpers/
│   │   │   └── ...
│   │   ├── Http/
│   │   │   ├── Controllers/  # Stores PHP controllers for routing and handling requests
│   │   │   │   ├── Admin/
│   │   │   │   ├── Auth/
│   │   │   │   ├── Company/     
│   │   │   │   ├── Export/    
│   │   │   │   ├── Nis/         
│   │   │   │   └── ...
│   │   │   ├── Livewire/     # Stores Livewire components using the LivewireController trait
│   │   │   │   ├── Admin/
│   │   │   │   ├── Assessments/  
│   │   │   │   ├── Auth/
│   │   │   │   ├── Company/     
│   │   │   │   ├── Documents/
│   │   │   │   ├── Iso/  
│   │   │   │   ├── ItGrundschutz/     
│   │   │   │   ├── Nis/  
│   │   │   │   ├── Privacyreport/  
│   │   │   │   ├── Soa/
│   │   │   │   ├── Settings/     
│   │   │   │   ├── Tisax/ 
│   │   │   │   ├── Vvt/         
│   │   │   │   └── ...
│   │   │   ├── Middleware/       # Stores Eloquent models for database operations
│   │   │   │   └── ...
│   │   │   └── ...
│   │   ├── Listeners/
│   │   │   └── ...
│   │   ├── Livewire/
│   │   │   └── Forms/   # Stores Livewire components as PHP classes (e.g., File: `MyComponent.php`)
│   │   │       ├── Admin/
│   │   │       └── Nis/  
│   │   ├── Mail/
│   │   │   ├── Nis/
│   │   │   └── ...
│   │   ├── Models/
│   │   │   ├── Assessments/  
│   │   │   ├── Iso/  
│   │   │   ├── ItGrundschutz/     
│   │   │   ├── Nis/
│   │   │   ├── NisDvo/  
│   │   │   ├── Privacyreport/  
│   │   │   ├── Soa/     
│   │   │   ├── Tisax/ 
│   │   │   ├── Vvt/
│   │   │   └── ...  
│   │   ├── Notifications/
│   │   │   └── ...
│   │   ├── Policies/
│   │   │   └── ...
│   │   ├── Providers/
│   │   │   └── ...
│   │   ├── Rules/
│   │   │   └── ...
│   │   ├── Scopes/
│   │   │   └── ...
│   │   ├── Traits/
│   │   │   ├── Livewire/     
│   │   │   ├── Models/ 
│   │   │   ├── Testing/
│   │   │   └── ... 
│   │   ├── View/
│   │   │   ├── Charts/     
│   │   │   ├── Layouts/ 
│   │   │   ├── Nav/
│   │   │   └── ... 
│   ├── bootstrap/
│   │   ├── cache/         
│   │   └── ...
│   ├── config/               # Contains application configuration files
│   │   └── ...
│   ├── database/             # Contains database-related files
│   │   ├── diagrams/
│   │   ├── factories/
│   │   ├── migrations/
│   │   ├── seeders/
│   │   ├── sql/
│   │   └── ...
│   ├── docker/
│   ├── overlay/
│   ├── public/               # Contains public-facing files and directories
│   │   ├── index.php         # The entry point of your Laravel application
│   │   └── ...
│   ├── resources/            # Contains assets such as CSS, JavaScript, and view files
│   │   ├── css/
│   │   ├── js/
│   │   ├── lang/
│   │   │   ├── de/ 
│   │   │   ├── en/
│   │   │   └── ... 
│   │   ├── views/
│   │   │   ├── admin/
│   │   │   ├── auth/
│   │   │   ├── company/  
│   │   │   ├── components/     
│   │   │   ├── emails/
│   │   │   ├── layouts/  
│   │   │   ├── livewire/     
│   │   │   ├── nis/  
│   │   │   ├── pdf/  
│   │   │   ├── vendor/
│   │   │   ├── vvtTemplates/             
│   │   │   └── ...
│   │   └── ...
│   ├── routes/               # Contains route definitions for web, API, and WebSockets
│   │   └── ...
│   ├── storage/              # Contains application files generated during runtime
│   │   └── ...
│   ├── tests/                # Contains application test cases and suites for unit testing
│   │   ├── Feature/
│   │   ├── Unit/
│   │   └── ...
│   └── ...
```