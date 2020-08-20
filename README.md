# ✔️ Kakunin
## 💰 Is this useful to you?
**Consider [sponsoring me on github](https://github.com/sponsors/juhlinus)! 🙏**

## 💾 Installation
```
composer require juhlinus/kakunin
```

## 🤔 Usage
Kakunin relies on [Custom Form Requests](https://laravel.com/docs/7.x/validation#form-request-validation).

Add the `ValidatesInertiaInput` trait to your newly generated form request like so:

```php
<?php

namespace App\Http\Requests;

use Kakunin\Concerns\ValidatesInertiaInput;
use Illuminate\Foundation\Http\FormRequest;

class CustomFormRequest extends FormRequest
{
    use ValidatesInertiaInput;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }
}
```

In order for the validation to be **instant** you need to make use of a [watcher](https://vuejs.org/v2/guide/computed.html#Watchers).

Here's an example in Vue.js:
```vuejs
<template>
    <div>
        <input v-model="email">
    </div>
</template>

<script>
    export default {
        watch: {
            email: function (email) {
                this.$inertia.post('/users', {
                    email: email,
                    validate: true,
                });
            }
        }
    }
</script>
```

Note that I'm passing a `validate` parameter. If this isn't passed then Kakunin will not validate your request.

That's it! Happy validating!

## 📝 Configuration

If you wish to change the `validate` to something else, then you can add `KAKUNIN_VALIDATION_KEY` to your `.env` file. Lastly, add the following to your `config/services.php` file:

```php
'kakunin' => [
    'validation_key' => env('KAKUNIN_VALIDATION_KEY'),
],
```

## ⛩ That's a stupid name for a package

Kakunin(確認) is the Japanese verb "to validate".