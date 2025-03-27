# Laravel Survey
[![Tests](https://github.com/matt-daneshvar/laravel-survey/actions/workflows/tests.yml/badge.svg)](https://github.com/matt-daneshvar/laravel-survey/actions/workflows/tests.yml)
![GitHub](https://img.shields.io/github/license/matt-daneshvar/laravel-survey)

Create and manage surveys within your Laravel app. 

![Demo](https://github.com/matt-daneshvar/laravel-survey/assets/10030505/1fd79b4b-5058-4049-a369-8439b0431fe2)

[This video](https://youtu.be/BA7tc-2rcWg) walks through installing this package and creating a basic survey.

## Installation
Require the package using composer.
```bash
composer require matt-daneshvar/laravel-survey
```

Publish the package migrations.
```bash
php artisan vendor:publish --provider="MattDaneshvar\Survey\SurveyServiceProvider" --tag="migrations" 
```

Run the migrations to create all the required tables.
```bash
php artisan migrate 
```

## Usage

### Creating a Survey

Creating a new `Survey` is easy! You can build your survey fluently just like 
how you create all your `Eloquent` models in your app.  
```php
$survey = Survey::create(['name' => 'Cat Population Survey']);

$survey->questions()->create([
     'content' => 'How many cats do you have?',
     'type' => 'number',
     'rules' => ['numeric', 'min:0']
 ]);

$survey->questions()->create([
    'content' => 'What\'s the name of your first cat',
]);

$survey->questions()->create([
    'content' => 'Would you want a new cat?',
    'type' => 'radio',
    'options' => ['Yes', 'Oui']
]);
```

See [the list of available question types](#question-types). 

#### Creating Multiple Sections
You may also park your questions under multiple sections.
```php
$survey = Survey::create(['name' => 'Cat Population Survey']);

$one = $survey->sections()->create(['name' => 'Part One']);

$one->questions()->create([
    'content' => 'How many cats do you have?',
    'type' => 'number',
    'rules' => ['numeric', 'min:0']
]);

$two = $survey->sections()->create(['name' => 'Part Two']);

$two->questions()->create([
    'content' => 'What\'s the name of your first cat?',
]);

$two->questions()->create([
    'content' => 'Would you want a new cat?',
    'type' => 'radio',
    'options' => ['Yes', 'Oui']
]);
```

### Creating an Entry

#### From an Array
The `Entry` model comes with a `fromArray` function.  
This is especially useful when you're creating an entry from a form submission. 
```php
(new Entry())->for($survey)->fromArray([
    'q1' => 'Yes',
    'q2' => 5
])->push();
```

The answer array should be in the format of `q + question_id => answer`, thus becoming `'q1' => 'my answer'`.

#### By a Specific User
You may fluently specify the participant using the `by()` function.
```php
(new Entry())->for($survey)->by($user)->fromArray($answers)->push();
```

### Setting Constraints
When creating your survey, you may set some constraints 
to be enforced every time a new `Entry` is being created.

#### Allowing Guest Entries
By default, `Entry` models require a `participant_id` when being created. 
If you wish to change this behaviour and accept guest entries,
set the `accept-guest-entries` option on your `Survey` model.  
```php
Survey::create(['settings' => ['accept-guest-entries' => true]]);
```

#### Adjusting Entries Per Participant Limit
All `Survey` models default to accept only **1 entry** per unique participant.
You may adjust the `limit-per-participant` option on your `Survey` model 
or set it to `-1` to remove this limit altogether.    
```php
Survey::create(['settings' => ['limit-per-participant' => 1]]);
```
*Note that this setting will be ignored if the `accept-guest-entries` option is activated.*

### Validation

#### Defining Validation Rules
Add in a `rules` attribute when you're creating your `Question` to specify the validation logic 
for the answers being received. 
```php
Question::create([
    'content' => 'How many cats do you have?', 
    'rules' => ['numeric', 'min:0']
]);
```
*Note that as opposed to the survey constraints, the question validators 
are not automatically triggered during the entry creation process. 
To validate the answers, you should manually run the validation in your controller (see below)* 

#### Validating Submissions
Validate user's input against the entire rule set of your `Survey` using Laravel's built in validator.
```php
class SurveyEntriesController extends Controller
{
    public function store(Request $request, Survey $survey)
    {
        $answers = $request->validate($survey->rules);
        
        (new Entry())->for($survey)->fromArray($answers)->push();
    }
}
```

### Views
This package comes with boilerplate Bootstrap 4.0 views 
to display the surveys and some basic question types. 
These views are meant to serve as examples, and 
may not be sufficient for your final use case.
To display a survey in a card, include the `survey` partial in your views:

```blade
@include('survey::standard', ['survey' => $survey])
``` 

#### Question Types
These are the question types included out of the box: 

- `text` - Accepting text answers
- `number` - Accepting numeric answers
- `radio` - Options presented as radio buttons, accepting 1 option for the answer
- `multiselect` - Options presented as checkboxes, accepting multiple options for the answer

#### Customizing the Views
To customize the boilerplate views shipped with this package run `package:publish` with the `views` tag.
```bash
php artisan vendor:publish --provider="MattDaneshvar\Survey\SurveyServiceProvider" --tag="views"
```
This will create a new `vendor/survey` directory 
where you can fully customize the survey views to your liking.

#### Creating New Question Types
Once you publish the views that come with this package, you can add your own custom question types
by implementing new templates for them. 

To implement a new `custom-select` type, for example, you should implement a new template under: 

```
<your-views-director>/vendor/survey/questions/types/custom-select.blade.php
```

## License
The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
