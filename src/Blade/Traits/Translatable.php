<?php

namespace Ja\Tall\Blade\Traits;

use Ja\Tall\Support\Blade as JaBlade;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Lang;

trait Translatable
{
    /**
     * Define attributes that should be translated
     * 
     * @return ?array
     */
    // protected array $translatable = [];

    /**
     * Translate translatable fields
     * 
     * @var array $translatable
     * @return array
     */
    protected function getTranslatable(array $translatable = null): array
    {
        if ($this->translatable ?? false) {
            $translatable = collect($this->translatable)
                                    ->map(fn ($name) => [$name => $this->$name])
                                    ->collapse()
                                    ->union($translatable ?: [])
                                    ->all();
        }

        $translated = collect($translatable)
                        ->map(fn ($value, $name) => (
                            Str::contains($value, '.') && Lang::has($value)
                                ? Lang::get($value)
                                : $value
                        ));
        
        // Set class properties (if defined on class)
        collect($translated)
            ->filter(fn ($value, $name) => JaBlade::hasProperty($this, $name))
            ->map(fn ($value, $name) => $this->$name = $value);

        // Return translated attributes that are not properties nor in $except array
        return $translated
                    ->filter(fn ($value, $name) => (
                        //!JaBlade::hasProperty($this, $name) &&
                        !in_array($name, $this->except)
                    ))
                    ->all();
    }
}