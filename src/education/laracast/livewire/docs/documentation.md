# Livewire

Livewire is a powerful and dynamic way to build your next web application, allowing you to create front-end UIs without leaving PHP. 

## Setup

### Creating Project

First I created a laravel project:

`laravel new 01_livewire`

Then I created a database for the project:

`CREATE DATABASE 04_livewire DEFAULT CHARACTER SET = 'utf8mb4';`

### Database Integration 

To connect my project to the database I setup the connection inside the `.env`:

```
DB_CONNECTION=mariadb
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=04_livewire
DB_USERNAME=name
DB_PASSWORD=password
```
After that I run the migrations:

`php artisan migrate`

### Running the Application

Finally I run the application:

`composer run dev`

## Livewire Components

Livewire revolves around the concept of components. Unlike JavaScript frameworks like Vue or React, Livewire components consist of a Blade view and a PHP class.

### Creating a component

You can create a Livewire component using Artisan:

`php artisan make:livewire greeter`

This command generates two files:

- PHP class at `app/Http/Livewire/Greeter.php`
- Blade view at `resources/views/livewire/greeter.blade.php`

In your Blade view you can render the component like this:

`<livewire:greeter />`

### Component Structure

The PHP class extends `Livewire\Components` and contains a `render` method that returns the component's view: 

```
namespace App\Http\Livewire;

use Livewire\Component;

class Greeter extends Component
{
    public function render()
    {
        return view('livewire.greeter');
    }
}
```

The Blade view contains the HTML markup to display.

### Passing Data to the View

You can define public properties on the component class, which become available in the Blade view. For example:

`public $name = 'John';`

In the Blade view, you can reference this property:

`Hello, {{ $name }}`

Changing the value of `$name` in the class updates the view automatically.

Note that only `public` properties are accessible in the view. If you declare a property as `private` or `protected`, the view will not be able to access it and will throw an error.

## Actions

Events are fundamental to web applications because they represent how users interact with the system. In Livewire, events are handled through `actions`, which are simply public methods on your component class.

For example you can define an action called `changeName` that updates a `name` property:

```
public $name = 'Jeremy';

public function changeName($newName)
{
    $this->name = $newName;
}
```

To trigger this action from the browser use `wire:click` directive on a button:

`<button wire:click="changeName('Jeffrey')">Greet</button>`

### How Livewire handles Actions

When you click the button, Livewire sends a POST request to `/livewire/update` with the component ID, the method to call (`changeName`), and any parameters (e.g., `'Jeffrey'`). The server processes the request, updates the component state, and returns the updated HTML to replace the component in the DOM.

This process is seamless and requires only the `wire:click` directive to wire up.

### Making an Action Dynamic

Instead of hardcoding the name in the button, you can allow users to input a new name:

```
<input id="newName" type="text" />
<button wire:click="changeName(document.getElementById('newName').value)">Greet</button>
```

This works, but it's not ideal because it relies on DOM queries.

#### Improving with Forms

A better approach is to use a form and listen for the submit event:

```
<form wire:submit.prevent="changeName">
    <input wire:model="name" type="text" />
    <button type="submit">Greet</button>
</form>
```

Here, `wire:model` binds the input to the `name` property, and submitting the form calls the `changeName` method without needing to query the DOM.

Users can type a name and press Enter or click the button to trigger the action.

## Binding Data

In Livewire, we can bind data from our PHP class directly to HTML elements using the `wire:model` attribute, enabling two-way data binding.

### Binding Input Data

By adding `wire:model="name"` to an input element, the input's value is bound to the `name` property in the Livewire component class. Changes to the input update the property, and changes to the property update the input.

This eliminates the need to manually grab input values with JavaScript.

### Updating

The bound property updates on the server when an action is triggered, such as submitting a form. By default, updates are sent only on form submission, not on every keystroke.

#### Modifier

1. `.live`:

To update the server with every keystroke, use the `.live` modifier:

`<input wire:model.live="name" />`

This sends updates on every input event but can be noisy and inefficient for many cases.

2. `.change`: Updates on the change event (e.g., when the input loses focus)
3. `.blur`: Updates specifically on blur event

### Binding Select Value

You can bind select elements similarly:

```
<select wire:model="greeting">
  <option value="hello">Hello</option>
  <option value="hi">Hi</option>
  <option value="hey">Hey</option>
  <option value="howdy">Howdy</option>
</select>
```

#### Initializing Select Value

If the property in the class is empty, the browser selects the first option by default, which may cause mismatches.

Two ways to fix this:

1. Set a default value in the Livewire class property:
   
`public $greeting = 'hello';`

2. Use the .fill modifier to initialize the property from the HTML's initial value:

```
<select wire:model.fill="greeting">
  <!-- options -->
</select>
```

This uses the select's initial value as the property's starting value.

### Displaying Bound Data

You can display the bound properties in your view, for example:

```
@if ($name !== '')
  {{ $greeting }}, {{ $name }}.
@endif
```

## Validation Basic 

There are several ways to validate in Livewire:

### Calling `validate` inside action method:

You can call `$this -> validate()` and pass an array where keys are property names and values are validation rules:

```
$this->validate([
    'name' => 'required|min:2',
]);
```
If the name is less than two characters or empty, validation fails and the greeting message does not update.

### Defining a `rules` method

Instead of passing rules directly to `validate()`, define a `rules()` method returning the rules array:

```
public function rules()
{
    return [
        'name' => 'required|min:2',
    ];
}
```
Then call `$this->validate()` without arguments.

### Using Livewire's `validate` attribute

You can attach validation rules directly to properties using the `validate` attribute:

```
#[\Livewire\Attributes\Validate(['required', 'min:2'])]
public $name;
```

This approach automatically validates the property on every update, providing immediate feedback.

### Displaying Validation Errors

To show validation errors in the template, use Laravel's `@error` directive between inputs and the submit button:

```
@error('name')
    <div class="error">{{ $message }}</div>
@enderror
```

This displays messages like "The name field is required" or "The name must be at least two characters."

### Ressetting Message

Each time `changeGreeting` is called, reset `greetingMessage` to an empty string or use Livewire's `reset()` method specifying the property:

`$this->reset('greetingMessage');`

This ensures the greeting message only appears when validation passes.

### Imporing Notes on Validation Behavior

- Livewire automatically validates properties with the `validate` attribute on update, but does not stop method execution if validation fails

    -> So, code inside the method still runs unless you explicitly call `$this->validate()`.

- You can disable automatic validation on update by passing `false` to the attribute. In that case, you must manually call `$this->validate()` to enforce validation.

- Typically, you want to call `$this->validate()` inside action methods to ensure validation stops execution on failure.

## Lifecycle Hooks

Livewire provides lifecycle hooks, which are events that occur throughout the lifecycle of a component.

### Mount Hook

The `mount` hook is similar to the browser's load event. It runs when the component is initialized but not necessarily loaded in the browser yet. This is an ideal place to initialize any data or state your component needs.

For example, to make greetings data-driven, you can:

1. Create `Greeting` model with a migration:
2. 
`php artisan make:model Greeting -m`

1. Create a database table for greetings with a simple string column:

Inside the generated migration file in `database/migrations/date_create_greetings.php` add:

`$table->string('greeting');`

3. Seed the database with greetings:

```
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Greeting::create(['greeting' => 'Hello']);
        Greeting::create(['greeting' => 'Hi']);
        Greeting::create(['greeting' => 'Hey']);
        Greeting::create(['greeting' => 'Howdy']);
        Greeting::create(['greeting' => 'Petri Heil']);
    }
}
```

And run the seeder:

`php artisan migrate:fresh --seed`


4. In your Livewire component, define a public property `greetings` initialized as an empty array:

`public $greetings = [];`

5. In the mount method, load all greetings from the database:

```
public $greetings = [];

public function mount()
{
    $this->greetings = Greeting::all();
}
```

6. In your Blade view, iterate over `greetings` to display them dynamically:

```
@foreach ($greetings as $item)
    <option value="{{ $item->greeting }}">{{ $item->greeting }}
    </option>
@endforeach
```

This approach allows you to initialize data in the component just like you would in a traditional controller and view setup.

### Updated Hook

The `updated` hook runs whenever any property on the component is updated. It receives two arguments:

1. The name of the property updated.
2. The new value of that property.

You can use this hook to react to changes. For example, to convert a `name` property to lowercase whenever it changes:

```
public function updated($propertyName, $value)
{
    if ($propertyName === 'name') {
        $this->name = strtolower($value);
    }
}
```

This updates both the property and the bound input field in the UI.

#### Listening to Specific Property Updates

You can also listen for updates to a specific property by defining a method named `updated{PropertyName}`:

```
public function updatedName($value)
{
    $this->name = strtolower($value);
}
```

This method runs only when the 'name' property updates, eliminating the need to check the property name manually.

## Building a Search Component

### Creating the Article Model

Start by creating an `Article` model with a migration and factory:

`php artisan make:model Article -m -f`

The migration will have two string columns: `title` and `content`:

```
Schema::create('articles', function (Blueprint $table) {
    $table->id();
    $table->text('title');
    $table->text('content');
    $table->timestamps();
});
```

In the factory, use Faker to generate realistic data:

- Title: 50 characters of text.
- Content: 500 characters of text.

```
public function definition(): array
{
    return [
        'title' => fake() -> realText(50),
        'content' => fake() -> realText(500),
    ];
}
```

Seed the database with 50 articles by wiping the database, running migrations, and seeding:

```
public function run(): void
{
    Article::factory()
        -> count(50)
        -> create();
}

```

`php artisan migrate:fresh --seed`

### Creating the Search Component

Generate a Livewire component called Search:

`php artisan make:livewire search`

In the Livewire component class:

- Define a public property `searchText` initialized as an empty string.
- Define a public property `results` initialized as an empty array.
- Add validation to ensure `searchText` is required (even a single character is valid).

### Handling Input and Searching

Bind the input to `searchText` with the `wire:model.live.debounce` modifier to debounce input and avoid running on every keystroke.

Display search results in a container below the input using a `foreach` loop over results.

Use the `updatedSearchText` lifecycle hook to perform the search:

- Reset `results` to clear previous results.
- Validate `searchText`.
- Create a search term for a SQL `LIKE` query.
- Query the `articles` table for titles matching the search term.
- Assign the results to `results`.

### Clearing Seach Results

- Add a `clear` method in the component to reset both `searchText` and `results`
- Use the `wire:click.prevent` modifier on the button to prevent form submission.

Disable the button when `searchText` is empty by conditionally adding the `disabled` attribute and styling it with a lighter background.

## Full Page Components

### Route Setup

In your routes file, define a route that points to the Livewire search component class:

```
use App\Http\Livewire\Search;

Route::get('/search', Search::class);
```

This will render the search component as a full-page Livewire component.

### Layout for Livewire Components

If you encounter an error like:

`Livewire page component layout view not found: components/layouts/app.blade.php`

It means Livewire expects a layout file at resources/views/components/layouts/app.blade.php.

You can create this layout using the Livewire command:

`php artisan livewire:make-layout app`

This creates a Blade component layout file. Customize it by copying your app's fonts, styles, and scripts into the layout's `<head>`. Then, in the `<body>`, include a slot for the component content:

```
<body>
    {{ $slot }}
</body>
```

Now your Livewire components will render within this layout.

### Linking to Individual Articles

Inside your search component's Blade view, add links to individual articles:

```
@foreach ($articles as $article)
    <a href="{{ url('articles/' . $article->id) }}">{{ $article->title }}</a>
@endforeach
```

### Creating a Show Article Component

Generate a Livewire component to display individual articles:

`php artisan livewire:make ShowArticle`

In the component's Blade view, display the article title and content:

```
<h2 class="text-2xl text-white">{{ $article->title }}</h2>
<div class="mt-4">
    {!! $article->content !!}
</div>
```

### Fetching the Article in the Component

In the `ShowArticle` component class, define a public property for the article and fetch it in the `mount` method using the ID from the route:

```
public $article;

public function mount($id)
{
    $this->article = Article::findOrFail($id);
}
```

### Defining a Route with Parameter

Add a route to handle article display:

```
use App\Http\Livewire\ShowArticle;

Route::get('/articles/{id}', ShowArticle::class);
```

### Using Route Model Binding 

To simplify, use route model binding by type-hinting the Article model in the mount method:

```
public $article;

public function mount(Article $article)
{
    $this->article = $article;
}
```

Update the route parameter to `{article}`:

`Route::get('/articles/{article}', ShowArticle::class);`

This automatically fetches the article or returns a 404 if not found.

## Nesting Components

When building applications, nesting components is common. We'll first add a search component to our layout so that the search form appears on every page using that layout. This way, users can search regardless of the current page.

In the layout file, simply include the Livewire search component:

`<livewire:search />`

This adds the search box globally. Since the search route now has two search boxes, we can comment out the standalone search route.

To also include the layout from the `resources/views/components/layouts/app.blade.php` file on the home page, insert this on the `ressources/views/home.blade.php`:

`<x-layouts.app />`

### Making the Search Placeholder Dynamic

To make the placeholder text in the search input customizable by the parent component, do this:

1. Add a public property $placeholder to the search component class:

`public $placeholder = 'Type something to search';`

2. Use this property in the search component's Blade view for the input's placeholder attribute:

`<input type="text" placeholder="{{ $placeholder }}">`

Now, when including the search component, you can pass a custom placeholder:

`<livewire:search placeholder="Search users..." />`

### Splitting the Search Component

To improve separation of concerns, split the search component into two:

1. `Search component`: Contains the UI elements like input and button.
2. `SearchResults component`: Responsible for displaying search results.

Create a new Livewire component called `SearchResults`:

`php artisan make:livewire SearchResults`

In the `SearchResults` component:

1. Add a public property `$results` initialized to an empty array.
2. Use this property in the view to display results.

In the `Search` component view, include the `SearchResults` component and pass the results dynamically:

`<livewire:search-results :results="$results" />`

### Making Results Reactive

To ensure the `results` property in `SearchResults` updates reactively, add the `#[\Livewire\Attributes\Reactive]` attribute (or use the `protected $listeners` array in older versions) to the `$results` property:

```
#[Reactive]
public $results = [];
```

Now, when the search results change in the parent component, the child component updates automatically.

## Using Events

Events are a fundamental concept in building interactive graphical applications, enabling communication between components across the entire page.

### Clearing Search Result via Events

Clicking the close button triggers a Livewire event named `searchClearResults`. Since the search results component is a child of the search component, it dispatches this event:

`$this->dispatch('searchClearResults');`

The parent search component listens for this event using the `on` attribute:

```
protected $listeners = [
    'searchClearResults' => 'clear',
];
```

When the event fires, the `clear` method executes, resetting the search results.

This event-driven approach decouples child and parent components, improving maintainability.

### Using Alpine.js to Dispatch Events from the Browser

Because Livewire integrates with Alpine.js, you can dispatch Livewire events directly from JavaScript in the browser.

For example, adding the following to the `<body>` tag:

`<body x-data x-on:click="$dispatch('searchClearResults')">`

makes clicking anywhere on the page dispatch the `searchClearResults` event, clearing the search results.

### Listening for Events in JavaScript

You can also listen for Livewire events in plain JavaScript:

```
document.addEventListener('searchClearResults', () => {
    console.log('Cleared results');
});
```

This logs a message whenever the event fires.

### Avoiding Duplicate Event Firing

Be cautious to avoid firing the same event multiple times unintentionally. For example, clicking the close button inside the body triggers the event twice: 

- once from the button 
- once from the body click listener.

To prevent this, consider removing the close button if clicking anywhere should clear results.

## Navigation Experience

Navigation in web apps typically involves moving between URLs via links, which causes full page reloads. While this works fine on fast connections, it can be slow and frustrating on poor networks.

Livewire 3 introduces a feature called `Navigate` that improves this experience by prefetching the content of linked URLs. This allows pages to load dynamically without a full browser navigation, giving a single-page application feel.

### Using wire:navigate

Simply add the `wire:navigate` attribute to your anchor tags:

`<a href="/users/1" wire:navigate>View User</a>`

When you click the link, Livewire fetches the page content via AJAX and updates the DOM without a full reload.

#### Prefetching Behavior

By default, Livewire starts prefetching the linked page content when the user presses down on the mouse button (`mousedown` event). This means the content is ready by the time the user releases the click.

You can also enable prefetching on `hover` by adding the hover modifier:

`<a href="/users/1" wire:navigate.hover>View User</a>`

This starts prefetching after the user hovers over the link for about 60 milliseconds. Use this judiciously to avoid excessive network requests.

### Preserving JavaScript Behavior

When content is dynamically loaded, you might want some JavaScript to run only once (e.g., event listeners or initialization code).

Livewire supports this with the `data-navigate-once` attribute:

```
<script data-navigate-once>
    console.log('Page loaded');
</script>
```

This script runs on the initial page load but will not re-run on subsequent Livewire navigations, preventing duplicate event bindings or other side effects.

## Iterating over Collections

When iterating over collections in client-side frameworks like React, Vue, or Alpine.js, it's important to include a unique key to help the framework track items efficiently.

Livewire follows the same principle. Add a `wire:key` attribute to the wrapping div using the article's unique ID:

```
@foreach ($articles as $article)
    <div wire:key="article-{{ $article->id }}" class="mt-4 p-2">
        <!-- article content -->
    </div>
@endforeach
```

This prevents potential rendering issues and improves performance.

### Passing Data to the View

Currently, we're assigning the articles inside the `mount` method, which runs only once when the component is initialized. This means any updates to articles in the database won't be reflected in the browser.

While this might be acceptable for an index page where data doesn't change often, a better approach is to provide the articles inside the `render` method. This way, the component fetches fresh data every time it renders, reflecting any updates.

```
public function render()
{
    return view('livewire.article-index', [
        'articles' => Article::all(),
    ]);
}
```

This pattern is preferable, especially when data is expected to change.

## Building a Admin Dashboard

### Setting Page Titles

To improve usability, add a `title` attribute to your Livewire components' classes. For example:

```
class ArticleIndex extends Component
{
    public $title = 'Articles';
    // ...
}
```

or like this:

```
#[Title('Manage Articles')]
class ArticleList extends AdminComponent
{
   public function render()
    {}
}
```

This sets the page title dynamically when viewing these components.

### Using Multiple Layouts

The admin dashboard may require a different layout than the default `app.blade.php`. Since Livewire components don't support specifying layouts via command-line options or parameters, create a new layout file:

1. Copy `resources/views/components/layouts/app.blade.php` to `resources/views/components/layouts/admin.blade.php`
2. Remove unnecessary elements like the search bar and home link.
3. Add admin-specific navigation links, e.g., to manage articles.

### Applaying the Admin Layout

In your dashboard component's `render` method, chain the `layout()` method to specify the admin layout:

```
public function render()
{
    return view('dashboard')->layout('components.layouts.admin');
}
```

Repeat this for other admin-related components like the article list.

### Creating a Base Admin Component 

To avoid repeating the `layout()` call in every admin component, create a base `AdminComponent` class that extends Livewire's `Component`:

```
use Livewire\Component;

class AdminComponent extends Component
{
    public $layout = 'components.layouts.admin';
}
```

Now, have your admin components extend `AdminComponent` instead of `Component`. This automatically applies the admin layout.

### Deleting Articles

In the Article List component implement:

1. `delete` function:

```
public function delete (Article $article) {
    $article-> delete();
}
```

2. passing articles to view:

```
public function render()
{
    return view('livewire.article-list',[
        'articles' => Article::all(),
    ]);
}
```

Then add a delete button with Livewire's wire:click directive in the Components view:

```
<button wire:click="delete({{ $article->id }})" class="bg-red-700 text-gray-200 px-3 py-1 rounded-sm hover:bg-red-800">
    Delete
</button>
```

### Handling Data Updates

Avoid storing the articles in a property like `$articles` that gets filled in the `mount()` function. When `$articles` changes it won't update in the render.

To ensure that that the UI updates immediately after deleting an article, pass `Article::all()` directly to the view:

```
public function render()
{
    return view('livewire.article-list',[
        'articles' => Article::all(),
    ]);
}
```

### Adding a Delete Confirmation

To prevent accidental deletions, use Livewire's `wire:confirm` directive:

```
<button wire:click="delete({{ $article->id }})" wire:confirm="Are you sure you want to delete this article?" class="...">
    Delete
</button>
```

This prompts the user to confirm before proceeding.

## Livewire Form Objects

Forms are a crucial part of applications since they are the primary way to gather user input. Livewire offers powerful features for working with forms, especially when dealing with similar forms like create and edit forms.

### Using a Form Object

You can extract all the form logic from components into a Livewire form object class like `ArticleForm`. To create this class run:

`php artisan livewire:form ArticleForm`

This creates a file in this directory:

`app/Livewire/Forms/ArticleForm.php`

This class contains properties, validation rules, and methods for storing and updating articles:

```
class ArticleForm extends Form
{
    public ?Article $article;

    #[Validate('required')]
    public $title = '';

    #[Validate('required')]
    public $content = '';

    public function setArticle(Article $article) {
        // set article
    }

    public function store() {
        // store
    }

    public function update() {
        // update
    }
}
```

Then, in your create and edit components, use this form object to handle the form state and actions, keeping components lean and focused on presentation.

Update your Blade views to bind inputs to `form.title` and `form.content`:

`<input type="text" class="" wire:model="form.title">`



