# Laravel

Laravel is a free PHP web application framework that follows the Model-View-Controller architectural pattern.

Some key features are:

1. `Blade Template Engine`: Laravel comes with its own templating engine called Blade, which allows you to create dynamic views using plain PHP code in your templates. 
     
2. `Route Model Binding`: This feature allows you to automatically resolve model instances based on the route's URI segments or route parameters. 
     
3. `Eloquent ORM`: Laravel uses Eloquent as its ORM (Object-Relational Mapping) system, which makes working with databases easy and enjoyable. It supports a wide range of SQL databases including MySQL, PostgreSQL, SQLite, and SQL Server. 
     
4. `Built-in Authentication`: Laravel comes with built-in authentication scaffolding, allowing you to easily add authentication to your application using simple commands. 
     
5. `Restful Controllers`: Laravel uses RESTful controllers by default, making it easy to create APIs or web applications that follow the REST architectural style. 
     
6. `Package Discovery`: With Laravel 5 and later, you can easily discover packages via Composer's require-dev section of your composer.json file. 
     
7. `Artisan Command Line Interface`: Laravel includes a built-in command line interface called Artisan, which provides useful commands for creating scaffolding, running tests, managing your application's cache, clearing routes, generating keys, and more. 
     
8. `Migrations`: Laravel comes with database migrations, allowing you to easily create new tables, columns, indexes, etc., while keeping track of changes over time. 
     
9. `Queue System`: Laravel has a built-in queue system that allows you to delay the processing of a time-consuming task until a later time when system resources are more available. 
     
10. `Fluent Query Builder`: Laravel includes a powerful query builder that provides a fluent, expressive, and intuitive API for constructing database queries. 

---

## Setting up Development Environment

After installing php and composer run this command to install laravel:

`composer global require laravel/installer`

To setup a laravel project run this command inside a bash terminal:

`laravel new example-app-name`

To run the application I installed node and npm via the bash first to make the workflow easier:

```
curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.40.3/install.sh | bash

\. "$HOME/.nvm/nvm.sh"

nvm install 22
```

After that I run:

`composer run dev`

inside zsh to start up my app in the browser.

## Router in Laravel

The routing is handled inside `routes/web.php`

## Blade - PHP Template Engine

Instead of duplicating code, Laravel provides layout files and components to reuse markup. Rename your view files to include the `.blade.php` suffix.

Blade is Laravel’s templating engine, a thin layer on top of PHP that offers helpers, shortcuts, directives, and layout capabilities.

The views are located inside `resources/views`
The view components are located inside `resources/views/components`

Use a file named layout.blade.php inside components to wrap up all the common wrapping HTML (like `<html>`, `<head>`, `<nav>`, etc.) into this layout file, except for the unique content of each page.

In your home.blade.php (and other views), delete all the wrapping HTML and instead reference the layout component like this:

```
<x-layout>
  <h1>Hello from the home page</h1>
</x-layout>
```

The x- prefix ensures the component tag is unique and doesn’t conflict with standard HTML tags. The `<x-layout>` tag corresponds to the layout.blade.php component.

## Blade Syntax 

### Echo Shortcut

Instead of writing PHP echo statements, Blade lets you use double curly braces:

`{{ $slot }}`

This is equivalent to:

`<?php echo $slot; ?>`

###  Attributes Object

Laravel Blade components have access to an $attributes object that contains all attributes passed to the component, such as href, id, or class.

Inside your `navlink.blade.php` component, you can output the attributes like this:

```
<a {{ $attributes }}>
    {{ $slot }}
</a>
```

This way, any attributes you pass to `<x-navlink>` will be applied to the anchor tag. For example, if you add a class or style attribute, it will be included automatically.

### Passing Variables

To make the page heading dynamic, define a named slot called heading in your layout:

```
<x-slot name="heading">
    {{ $heading }}
</x-slot>
```

In your views, pass the heading like this:

```
<x-layout>
    <x-slot name="heading">Home Page</x-slot>
    <!-- Page content here -->
</x-layout>
```

If you try to use a variable like $heading without defining it, Laravel will throw an error, so you must pass it explicitly.

### Request Helper for Active URL Check

Laravel provides a request() helper with an is() method to check if the current URL matches a pattern. Apply this logic to each navigation link to dynamically set the active styling:

```
<a href="/" class="{{ request()->is('/') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
    Home
</a>
```

### Props

In Blade components, distinguish between:

`Attributes`: HTML attributes like href, id, class

`Props`: Custom properties passed to the component, e.g., active

Declare props at the top of your component using the `@props` directive:

`@props(['active' => false])`

### Dynamic Tag Rendering

To change the tag of an element it's best to split the logic into two parts because you may encounter error in your editor when you dynamically change the tag by using a variable. With PHP and Blade conditionals you avoid this:

```
@if ($type === 'a')
    <a {{ $attributes }}>
        {{ $slot }}
    </a>
@else
    <button {{ $attributes }}>
        {{ $slot }}
    </button>
@endif
```

Blade provides directives like `@if`, `@else`, and `@endif` that compile down to PHP but are easier to read and write.

## Passing Data to Views

In your routes file (`web.php`), you can pass a second argument to the `view()` function, which is an array of data. Each key becomes a variable available in the view:

```
Route::get('/', function () {
    return view('home', [
        'greeting' => 'Hello',
        'name' => 'Larry Robot',
    ]);
});
```

You can access these variables directly:

`<h1>{{ $greeting }}, {{ $name }}!</h1>`

You can pass more complex data structures, like arrays, to your views:

```
Route::get('/jobs', function () {
    return view('jobs', [
        'jobs' => [
            ['id' => 1, 'title' => 'Director', 'salary' => 50000],
            ['id' => 2, 'title' => 'Programmer', 'salary' => 10000],
            ['id' => 3, 'title' => 'Teacher', 'salary' => 40000],
        ],
    ]);
});
```

Use Blade’s @foreach directive to loop over the jobs:

```
<ul>
@foreach ($jobs as $job)
    <li>
        {{ $job['title'] }}: <strong>${{ $job['salary'] }}</strong> per year
    </li>
@endforeach
</ul>
```

## Dynamic Page Creation

To create individual job pages, add a unique id to each job and create a dynamic route:

```
Route::get('/jobs/{id}', function ($id) {
    $jobs = [
        ['id' => 1, 'title' => 'Director', 'salary' => 50000],
        ['id' => 2, 'title' => 'Programmer', 'salary' => 10000],
        ['id' => 3, 'title' => 'Teacher', 'salary' => 40000],
    ];

    $job = collect($jobs)->first(fn ($job) => $job['id'] == $id);

    if (!$job) {
        abort(404);
    }

    return view('job', ['job' => $job]);
});
```

Here, Laravel automatically captures the {id} parameter from the URL and passes it to the closure.

Laravel’s `collect()` helper creates a collection from the array, allowing you to use the `first()` method with a callback to find the job by ID. The callback uses a short closure syntax:

`fn ($job) => $job['id'] == $id`

## Model-View-Controller

The Job class is an example of a model in the MVC (Model-View-Controller) architecture. MVC is a design pattern that separates an application into three interconnected components:

`Model`: Represents data and business logic.

`View`: Handles presentation and user interface.

`Controller`: Manages user input and interaction, often represented by route handlers in Laravel.

The model encapsulates data persistence and business rules, such as how jobs are created, updated, or deleted.

Models belong in the `app/Models` directory. Laravel uses PSR-4 autoloading, which maps namespaces to directory structures, so the Job class is namespaced as `App\Models`.

This organization prevents class name collisions and keeps the codebase maintainable.

## Migrations

You configure your database connection in the `.env` file, which holds environment-specific settings such as database credentials, debug mode, cache drivers, and API keys. This file keeps sensitive information out of your codebase and version control.

Laravel includes a powerful CLI tool called Artisan. Among these commands are database-related ones under the db and migrate namespaces:

`php artisan migrate`: runs all pending migrations to create or update database tables.

`php artisan migrate: refresh`: rolls back all migrations and runs them again, useful during development.

`php artisan migrate:rollback`: rolls back the last batch of migrations.

Migrations are PHP classes that define the structure of your database tables. They allow you to version-control your database schema and share it with teammates.

Migrations contain two methods:

`up()`: Defines the changes to apply (e.g., creating tables, adding columns).

`down()`: Defines how to revert those changes (e.g., dropping tables, removing columns).

If you want to create a new migration run:

`php artisan make:migration create_job_listings_table`

If you edit the generated migration file to define the table schema run:

`php artisan migrate`

## Eloquent

Eloquent is an Object Relational Mapper that maps database rows to PHP objects, making it easy to work with your data in an object-oriented way.

For example, instead of manually handling arrays of job listings, you can have a Job object representing each job record with all its attributes and behaviors.

### Converting Class to an Eloquent Model

```
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    // Your model code here
}
```

This gives your class access to Eloquent’s powerful querying methods like `all()` and `find()`.

In your routes file, you can now use Eloquent methods to fetch data:

```
use App\Models\Job;

Route::get('/', function () {
    $jobs = Job::all();
    dd($jobs); 
});
```

If you see an empty collection, make sure that your database table name matches Eloquent’s conventions. By default, Eloquent expects the table name to be the plural snake_case of the model name (jobs for Job).

If your table has a different name, specify it in the model:

`protected $table = 'job_listings';`

### Using Eloquent

Eloquent returns a collection of model instances. Each item in the collection is an instance of your model class, allowing you to add methods and behaviors directly to the model.

You can access attributes like so:

`$jobs[0]->title;`

You can retrieve all records with `all()`, or find a specific record by ID with `find()`:

`$job = Job::find(1);`

Behind the scenes, Eloquent runs SQL queries, but you interact with a clean API.

You can create new records using the `create()` method:

```
Job::create([
    'title' => 'Acme Director',
    'salary' => '1000000',
]);
```

However, Laravel protects against mass assignment vulnerabilities by requiring you to specify which attributes are mass assignable in your model:

`protected $fillable = ['title', 'salary'];`

This prevents malicious users from modifying unintended fields.

### Tinker

Laravel’s Tinker is a REPL (interactive shell) for your application:

`php artisan tinker`

Use it to test Eloquent queries, create records, update data, and more.

You can generate models and their corresponding migrations using Artisan:

`php artisan make:model Post -m`

This creates a Post model and a migration file to define its database table.

Migrations allow you to define and modify your database schema in PHP, which can be version controlled and shared with your team.

You can also generate a Model file with a corresponding factory:

`php artisan make:model Employer -f`

## Model Factories

Factories allow you to scaffold or generate fake data for your models. For example, you might want to create 10 users for testing or populate your local environment with many job listings without manually entering each record.

Laravel includes a default UserFactory that defines fake data for user attributes like name, email, and timestamps. You can also define additional states, such as an unverified user, by tweaking attributes.

### Using Factories with Tinker

You can use factories anywhere in your Laravel code, but a great place to experiment is with Artisan Tinker:

`php artisan tinker`

Example of creating a user with a factory:

`User::factory()->create();`

If you encounter errors, such as missing columns, check that your database schema matches the factory attributes. For example, if you renamed name to firstName and lastName in your users table, update the factory accordingly using Faker's methods:

```
'firstName' => $this->faker->firstName(),
'lastName' => $this->faker->lastName(),
```

Remember to restart Tinker after making changes to your code.

If you want to create multiple records at once use:

`User::factory()->count(100)->create();`

### Creating a Factory

You can use the command line to generate a new factory class inside `databases/factories` using:

`php artisan make:factory JobFactory --model=Job`

In the `JobFactory`, define attributes like `title` and `salary`. Use Faker methods such as `jobTitle` for realistic data. You can hardcode values if variability isn't needed:

```
public function definition()
{
    return [
        'title' => $this->faker->jobTitle(),
        'salary' => $this->faker->numberBetween(30000, 100000),
    ];
}
```

### Implementing Factories with Relationships

If your Job model belongs to an `Employer`, you can define this relationship in your factory by creating an `EmployerFactory` and referencing it:

`'employer_id' => Employer::factory(),`

This tells Laravel to create a new employer record when generating a job and associate it accordingly.

If you get errors like `"Employer factory not found,"` ensure you have generated the factory and added the `HasFactory` trait to your model.

### States

Factories can define states to represent different variations of a model. For example, the `UserFactory` has an unverified state that sets `emailVerifiedAt` to null:

```
public function unverified()
{
    return $this->state(fn (array $attributes) => [
        'email_verified_at' => null,
    ]);
}
```

You can create a user in this state like so:

`User::factory()->unverified()->create();`

## Relationships between Models

A database schema reflects the relationship between two models with a foreign key. To also express this relationship in your PHP code you need to define methods to tell Eloquent how these relationships work:

```
public function employer()
{
    return $this->belongsTo(Employer::class);
}
```

This tells Eloquent that each job belongs to one employer.

### Relationship Types

The most common Eloquent relationship types include:

1. `belongsTo`: Defines an inverse one-to-one or many relationship (e.g., a job belongs to an employer).
2. `hasMany`: Defines a one-to-many relationship (e.g., an employer has many jobs).
3. `hasOne`
4. `belongsToMany`

### Accessing Related Models

Using the Artisan Tinker CLI, you can fetch a job and access its employer like this:

`$job = App\Models\Job::first();`

`$employer = $job->employer;`

Eloquent uses lazy loading, meaning the related employer data is only fetched when you access the `employer` property, triggering a separate SQL query.

##  Pivot Tables

A pivot table is used to manage many-to-many relationships between models. Many-to-many relationships allow you to associate multiple records from one model with multiple records from another model. The pivot table acts as an intermediate table that stores the associations between these two tables.

To connect jobs and tags, create a pivot table (commonly named by combining the singular forms of the related tables in alphabetical order, e.g., `job_tag`)-

The pivot table should include foreign ID columns for both `job_listing_id` and `tag_id`:

```
$table->foreignId('job_listing_id')->constrained()->cascadeOnDelete();
$table->foreignId('tag_id')->constrained()->cascadeOnDelete();
$table->timestamps();
```

### Defining the Many-to-Many Relationships in Models

Inside the `Job` model:

```
public function tags()
{
    return $this->belongsToMany(Tag::class, 'job_tag', 'job_listing_id', 'tag_id');
}
```

Inside the `Tag` model:

```
public function jobs()
{
    return $this->belongsToMany(Job::class, 'job_tag', 'tag_id', 'job_listing_id');
}
```

Specify the pivot table name and foreign key columns explicitly because your table names differ from Laravel's conventions.

### Using the relationship

Inside the Tinker CLI you can access it like this:

Tags for a job:

```
$job = Job::find(10);
$tags = $job->tags;
```

Jobs for a tag:

```
$tag = Tag::find(1);
$jobs = $tag->jobs;
```

Attaching a tag to a job:

`$tag->jobs()->attach($jobId);`

## N+1 Problem

The N+1 problem occurs when lazy loading relationships inside a loop causes one query to fetch the main records (N), plus one additional query per related record, resulting in many queries and poor performance.

For example, fetching 8 jobs and their employers results in 9 queries: 1 for jobs and 8 for employers.

### Detecting the N+1 Problem

You can detect this issue using tools like the Laravel Debugbar, which shows all SQL queries executed during a request.

Install it via Composer:

`composer require barryvdh/laravel-debugbar --dev`

Make sure `APP_DEBUG` is set to `true` in your `.env` file to enable the debug bar.

### Fixing the N+1 Problem

Eager loading fetches related models in a single query upfront, reducing the number of queries.

I modified the query insde the `web.php` file to eager load the employer relationship:

`$jobs = Job::with('employer')->get();`

This executes two queries regardless of the number of jobs: one for jobs and one for employers.

### Disabling Lazy Loading 

You can disable lazy loading entirely to catch unintended queries in the `app/Providers/AppServiceProvider.php` file:

```
use Illuminate\Database\Eloquent\Model;

public function boot()
{
    Model::preventLazyLoading(!app()->isProduction());
}
```

This throws an exception whenever lazy loading occurs, helping you identify and fix N+1 issues during development.

## Pagination

Pagination helps to manage large datasets by fetching and displaying records in smaller chunks instead of all at once.

Fetching thousands of records at once can overwhelm your server and browser. Pagination limits the number of records retrieved and displayed per page, improving performance and user experience.

### Implementing Pagination

Replace your query like this:

`$jobs = Job::with('employer')->paginate(3);`

This fetches 3 jobs per page along with their employers.

In your Blade view, render pagination links with:

`{{ $jobs->links() }}`

Laravel automatically generates styled pagination controls, assuming you use Tailwind CSS by default.

### Customizing Pagination Views

If you want to customize the pagination markup or use a different CSS framework like Bootstrap, publish the pagination views:

`php artisan vendor:publish --tag=laravel-pagination`

This copies the pagination views into your `resources/views/vendor/pagination` directory for editing.

To switch the default pagination view (e.g., to Bootstrap 5), configure it in `AppServiceProvider`:

```
use Illuminate\Pagination\Paginator;

public function boot()
{
    Paginator::useBootstrapFive();
}
```

### Pagination Types

1. `Standard Pagination`: Shows page numbers and navigation links.
2. `Simple Pagination`: Shows only "Previous" and "Next" links, reducing query complexity.
3. `Cursor Pagination`: Uses a cursor (encoded string) for efficient pagination on large datasets but lacks direct page number navigation.

Code for simple pagination:

`$jobs = Job::with('employer')->simplePaginate(3);`

Code for cursor pagination:

`$jobs = Job::with('employer')->cursorPaginate(3);`

### How Pagination Queries Work

Standard pagination uses `SQL LIMIT` and `OFFSET` to fetch the correct subset of records.

Cursor pagination uses an encoded cursor to fetch records after a certain point, avoiding the performance cost of large offsets.

## Database Seeder

Database seeding automates populating your database with test or initial data.

After running `php artisan migrate:fresh` your database table are recreated but empty. Manually inserting data each time is tedious. Seeders automate this process.

Seeders are classes located in the `database/seeders` directory. The default `DatabaseSeeder` class is your entry point to run multiple seeders.

To run seeders, use:

`php artisan db:seed`

### Creating and Running Seeders

If you encounter errors like missing columns, ensure your seeders and factories match your database schema.

You can combine migration and seeding in one command:

`php artisan migrate:fresh --seed`

This drops all tables, runs migrations, and seeds the database in one go.

### Using Factories in Seeders

Seeders often use factories to generate large amounts of fake data quickly:

`\App\Models\Job::factory(200)->create();`

This creates 200 job records using the factory definition.

You can create multiple seeder classes for different parts of your database:

`php artisan make:seeder JobSeeder`

This allows running seeders individually or in groups, useful for testing or partial data refreshes.

In your `DatabaseSeeder`, call other seeders:

```
public function run()
{
    $this->call([
        UserSeeder::class,
        JobSeeder::class,
    ]);
}
```

## Forms

### Defining Routes for Forms

When adding a route for creating a new job, use the URI `jobs/create`. Be mindful of route order: wildcard routes like `jobs/{id}` should come after specific routes like `jobs/create` to avoid conflicts.

### Organizing Views

Group related views in folders, e.g., place all job-related views in a jobs directory. Use common naming conventions:

- `index.blade.php`: for listing all jobs
- `show.blade.php`: for displaying a single job
- `create.blade.php`: for the form to create a job

Use dot notation in views references, e.g., `jobs.create`.

### Form Submission

By default, forms submit via `GET` to themselves. Change the form method to `POST` and set the action to `/jobs` to follow RESTful conventions for creating resources.

Add a POST route for `/jobs` in your routes file to handle form submissions:

`<form method="POST" action="/jobs">`

### Using the Data

Use the `request()` helper to retrieve form data:

```
$request->all();
$request->input('title');
```

Use Eloquents `create()` method with the request data to create a new record:

```
Job::create([
    'title' => $request->input('title'),
    'salary' => $request->input('salary'),
    'employer_id' => $employerId, // typically from authenticated user
]);
```

Remember to include `employer_id` in your model's `$fillable` array or disable mass assignment protection accordingly.

To redirecting after submission use:

`return redirect('/jobs');`;

## CSRF Protection

Laravel protects against CSRF attacks by requiring a token in POST requests. Add the Blade directive `@csrf` inside your form to include a hidden token input.

Without this, submitting the form results in a 419 error (page expired).

## Validation

### Server-Side-Validation

Use Laravel's built-in validation method in your POST route:

```
$request->validate([
    'title' => ['required', 'min:3'],
    'salary' => ['required'],
]);
```

If validation fails, Laravel automatically redirects back with error messages.

### Displaying Validation Errors

In your Blade view, display errors globally:

```
@if ($errors->any())
    <ul>
        @foreach ($errors->all() as $error)
            <li class="text-red-500">{{ $error }}</li>
        @endforeach
    </ul>
@endif
```

Or display errors inline below each input using the `@error` directive:

```
@error('title')
    <p class="text-red-500 text-sm">{{ $message }}</p>
@enderror
```

### Client-Side Validation

Add the `required` attribute to inputs for instant browser validation, enhancing user experience:

`<input type="text" name="title" id="title" class="" placeholder="Shift Leader" required/>`

## CRUD Operations

### Patch

A patch route can look like this:

```
Route::patch('/jobs/{id}', function (Request $request, $id) {
    $request->validate([
        'title' => 'required|min:3',
        'salary' => 'required',
    ]);

    $job = Job::findOrFail($id);
    $job->update([
        'title' => $request->input('title'),
        'salary' => $request->input('salary'),
    ]);

    return redirect("/jobs/{$id}");
});
```

Use the Blade directive @method('PATCH') in your form to spoof the HTTP verb since browsers only support GET and POST:

```
<form method="POST" action="/jobs/{{ $job -> id }}">
    @csrf
    @method('PATCH')
```

### Delete

A delete route can look like this:

```
Route::delete('/jobs/{id}', function ($id) {
    $job = Job::findOrFail($id);
    $job->delete();

    return redirect('/jobs');
});
```

Since forms cannot be nested, you can create a separate hidden form for deletion and link a button to submit it using the form attribute:

Button:

```
<button form="delete-form" class="text-red-500 text-sm font-bold">
    Delete
</button>
```

Form:

```
<form method="POST" action="/jobs/{{ $job -> id }}" class="hidden" id="delete-form">
    @csrf
    @method('DELETE')
</form>
```

## Advanced Routing

### Route Model Binding

Instead of manually fetching models by ID in your route closures, Laravel offers `route model binding` to automatically inject model instances based on route parameters:

```
Route::get('/jobs/{id}', function ($id) {
    $job = Job::findOrFail($id);
});
```

Can turn to:

```
Route::get('/jobs/{job}', function (Job $job) {
});
```

The route parameter name `{job}` must match the variable name in the closure. Laravel uses the model’s primary key (`id` by default) to fetch the record. You can specify a different column (e.g., slug) by adding `:slug` to the route parameter.

### Dedicated Controller Classes

For larger applications, managing many routes with closures becomes unwieldy. Instead, use controller classes to organize your route logic. 

You can generate a controller with CLI:

`php artisan make:controller JobController`

Move your route logic into controller methods like `index`, `show`, `create`, `store`, `edit`, `update`, and `destroy`.

Update your routes to reference controller actions:

```
use App\Http\Controllers\JobController;

Route::get('/jobs', [JobController::class, 'index']);
Route::get('/jobs/create', [JobController::class, 'create']);
```

### Route View Shortcut

For simple routes that only return a view (like static pages), use the `Route::view` method:

`Route::view('/contact', 'contact');`

This avoids creating unnecessary closures or controller methods.

### Listing Routes

You can use Artisan in the CLI to list all of my routes:

`php artisan route:list`

You can exclude vendor routes to focus on your app's routes:

`php artisan route:list --except=vendor`

### Route Grouping with Controllers

To reduce repetition, group routes by controller:

```
Route::controller(JobController::class)->group(function () {
    Route::get('/jobs', 'index');
    Route::get('/jobs/create', 'create');
    // other routes...
});
```

### Route Resource

Laravel provides a convenient method to register all standard resource routes at once:

`Route::resource('jobs', JobController::class);`

This registers routes for `index`, `create`, `store`, `show`, `edit`, `update`, and `destroy` actions following RESTful conventions.

You can limit or exclude specific actions:

```
Route::resource('jobs', JobController::class)->except(['edit']);
Route::resource('jobs', JobController::class)->only(['index', 'show', 'create', 'store']);
```

## Laravel Breeze

Laravel provides starter kits like Breeze to quickly scaffold common authentication features such as registration, login, password reset and profile management.

### Using Laravel Breeze Starter Kit

Using Laravel Breeze Starter Kit

`laravel new app`

and selecting Breeze as the starter kit during setup. Breeze assumes a fresh project and will overwrite some files like routes, views, and components.

Breeze supports multiple frontend stacks including React, Vue, Livewire, or traditional Blade with JavaScript. For this demo, we chose the Blade stack without dark mode.

After installation, run your app (`php artisan serve` or access via Herd) and you’ll see login and register links.

### Features

1. Registration and login forms
2. Dashboard accessible only to authenticated users
3. Profile editing and password update
4. Logout functionality
5. Middleware to protect routes and redirect guests to login

### How Authentication works in Breeze

Routes are protected by middleware like `auth` and `verified` to ensure only signed-in and verified users can access certain pages. The authenticated user can be accessed via the `Auth` facade or helper. Breeze uses Blade components extensively for layouts, inputs, labels and validation errors. Registration logic includes validation, password hashing, event firing, and automatic login.

Middleware acts as layers that process requests before reaching your application logic. For example, the `auth` middleware checks if a user is signed in and redirects to login if not.

## Login and Registration System from Scratch

### Displaying Authentication Links Conditionally

Use Blade directives to show login and register links only to guests:

```
@guest
    <x-nav-link href="/login">Login</x-nav-link>
    <x-nav-link href="/register">Register</x-nav-link>
@endguest
```

Use `@auth` to show content only to authenticated users.

### Registration Process

When a user submits the registration form, the following steps occur:

1. `Validation`
    : Use `$request->validate()` to ensure required fields like first name, last name, email, and password meet your criteria. Laravel provides many validation rules, including a fluent Password helper for complex password requirements.

2. `Creating the User`
    : After validation, create the user in the database. You can pass the validated attributes directly to `User::create()`.

3. `Logging In`
    : Use `Auth::login($user)` to sign in the newly registered user.

4. `Redirecting`
    : Redirect the user to a desired page, such as the jobs listing or dashboard.

#### Example Validation Rules

```
$request->validate([
    'first_name' => ['required', 'min:1'],
    'last_name' => ['required', 'min:1'],
    'email' => ['required', 'email'],
    'password' => ['required', 'confirmed', Password::min(6)],
]);
```
The `confirmed` rule requires a matching `password_confirmation` field.

Laravel automatically hashes passwords when you set the password attribute on the User model, thanks to the $casts property.

### Login Process

When a user submits the login form:

1. `Validation`
    : Validate the email and password fields.

2. `Authentication Attempt`
    : Use `Auth::attempt($credentials)` to try logging in.

3. `Session Regeneration`
    : On successful login, regenerate the session token for security.

4. `Redirect`
    : Redirect the user to the intended page.

5. `Handling Failure`
    : If login fails, throw a validation exception with an appropriate error message.

#### Preserving Input on Validation Errors

Use the `old()` helper in Blade to repopulate form fields after validation errors, improving user experience.

`<input name="email" value="{{ old('email') }}" />`

Remember to prefix with `:` in Blade to treat as an expression:

`:value="old('email')"`

### Authorization

#### Establishing Relationships for Authorization

To perform user authorization on a job, there must be a relationship between the job and a user. Currently, jobs relate to employers, but employers don’t relate to users. Fix this by adding a foreign key `user_id` to the `employers` table and updating the `Employer` factory to associate an employer with a user.

#### Inline Authorization in Controller

Add a simple authorization check in your job controller’s edit action:

- Redirect guests to login.
- Check if the authenticated user is responsible for the job.
- If not authorized, abort with a 403 status.

Use Eloquent relationships to traverse from job to employer to user, and compare with the authenticated user using the `is` method.

```
if ($job) -> employer -> user -> isNot(Auth::user())) {
    // do
}
```


```
if ($job) -> employer -> user -> isNot(Auth::user())) {
    abort()
}
```

#### Gates

Extract authorization logic into `gates` for reusability and clarity.

- Define gates in `AppServiceProvider` using the `Gate` facade.
- Gates return a boolean indicating authorization.
- Use `Gate::authorize('edit-job', $job)` in your controller to enforce authorization.

Gates automatically abort with 403 if authorization fails, but you can use Gate::allows or Gate::denies for custom handling.

#### Can and Cannot

Your User model has `can` and `cannot` methods to check permissions against gates. Use these in controllers or Blade views to conditionally allow actions.

```
if (Auth::user() -> can('edit-job', $job)) {

}
```

```
if (Auth::user() -> cannot('edit-job', $job)) {

}
```

#### Middleware Authorization 

Apply authorization at the route level using middleware:

```
Route::patch('/jobs/{job}', [JobController::class, 'update'])
    ->middleware(['auth', 'can:edit,job']);
```

This ensures users are authenticated and authorized before accessing the route.

You can specify middleware per route or group routes for cleaner code.

#### Policies

Policies are classes that encapsulate authorization logic for a model.

- Generate a policy with `php artisan make:policy JobPolicy --model=Job`
- Define methods like `edit`, `delete` to control access.
- Register policies in `AuthServiceProvider`.
- Use policies via gates or `can` methods.

Policies provide a structured, scalable way to manage complex authorization rules.

## Mailing

Laravel provides a clean, intuitive way to build and send emails. 

### Generating a Mailable

Create a mailable class with Artisan:

`php artisan make:mail JobPosted`

The parts of the class:

1. `Envelope`: Set email subject, sender, reply-to, and tags.
2. `Content`: Specify the Blade view for the email body.
3. `Attachments`: Add files if needed.

### Creating the Email View

Create a Blade view in ``resources/views/mail/job-posted.blade.php`` with your email content:

`<p>Congrats, your job is now live on our website.</p>`

### Previewing the Email

Add a temporary route to return the mailable for preview:

```
Route::get('/test', function () {
    return new \App\Mail\JobPosted();
});
```

Visit `/test` in your browser to see the email rendered.

### Sending Email 

Use the `Mail` facade to send emails:

```
use Illuminate\Support\Facades\Mail;

Mail::to('jeffrey@laracasts.com')->send(new \App\Mail\JobPosted());
```

By default, in local environments without SMTP configured, emails are logged to `storage/logs/laravel.log`.

### Configuring Mail Settings 

Edit `config/mail.php` or your `.env` file to set mail driver, SMTP host, port, username, and password.

Example for Mailtrap (a popular testing service):

```
MAIL_MAILER=smtp
MAIL_HOST=sandbox.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_FROM_ADDRESS=info@laracasts.com
MAIL_FROM_NAME="Laracasts"
```

### Passing Data to Mailables 

You can pass data to your mailable’s constructor and expose it as public properties for use in the email view:

```
public $job;

public function __construct(Job $job)
{
    $this->job = $job;
}
```

In the Blade view, access `$job` to display dynamic content.

### Generating URLs in Emails

Use Laravel’s `url()` helper to generate full URLs in emails:

```
<a href="{{ url('jobs/' . $job->id) }}">View your job listing</a>
```

This ensures URLs work in both local and production environments.

## Queues

Queues allow you to defer time-consuming tasks like sending emails to the background, improving user experience by responding immediately.

### Configuration 

Laravel supports multiple queue drivers:

- `sync`: Runs jobs synchronously (default, useful for local development).
- `database`: Stores jobs in a database table.
- `beanstalkd`, `SQS`, `Redis`: More robust queue backends.

The default queue connection is set in `.env` via `QUEUE_CONNECTION`. For example:

`QUEUE_CONNECTION=database`

The database driver uses a `jobs` table to store queued jobs and a `failed_jobs` table for failed ones.

### Dispatching Job to Queue

In your `JobController`, change:

`Mail::to($user)->send(new JobPosted($job));`

to

`Mail::to($user)->queue(new JobPosted($job));`

This queues the email instead of sending it immediately.

### Running the Queue Worjer

To process queued jobs, run:

`php artisan queue:work`

This command listens for jobs and processes them as they arrive.

### Queued Closures

You can dispatch simple queued closures:

```
dispatch(function () {
    Log::info('Hello from the queue');
})->delay(now()->addSeconds(5));
```

This runs the closure after a delay, useful for deferred tasks.

### Creating Dedicated Job Classes

Generate a job class:

`php artisan make:job TranslateJob`

Define the job’s logic in the ``handle()`` method. Dispatch it with:

`TranslateJob::dispatch($jobListing);`

Remember to restart your queue worker after code changes.