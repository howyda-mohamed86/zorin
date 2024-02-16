<?php

namespace Tasawk\Providers;

use App\Settings\DeveloperSetting;
use Filament\Forms\Components\Field;
use Filament\Forms\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Support\Facades\FilamentView;
use Filament\Tables\Columns\Column;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use MyFatoorah\Library\PaymentMyfatoorahApiV2;
use Tasawk\Filament\Pages\Settings\ManageDeveloper;
use Tasawk\Lib\Cart;
use Tasawk\Notifications\Notification;
use Filament\Notifications\Notification as BaseNotification;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider {
    /**
     * Register any application services.
     */
    public function register(): void {
        $this->cart();
        $this->app->bind(PaymentMyfatoorahApiV2::class, function () {
            return new PaymentMyfatoorahApiV2(
                config('myfatoorah.api_key'),
                config('myfatoorah.country_iso'),
                config('myfatoorah.test_mode')
            );
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void {
//        $settings = new DeveloperSetting();
//
//        config()->set("app.debug", $settings->debug_mode);


        $this->app->bind(BaseNotification::class, Notification::class);
        $this->translateLabels();
        FilamentView::registerRenderHook(
            'panels::scripts.after',
            fn(): string => Blade::render('filament.firebase-initialization'),
        );
        FilamentView::registerRenderHook(
            'panels::body.start',
            fn(): string => Blade::render('filament.hooks.body-start'),
        );

    }


    private function translateLabels(): void {
        $translateLabelsComponents = [
            Field::class,
            Filter::class,
            SelectFilter::class,
        ];
        foreach ($translateLabelsComponents as $component) {
            $component::configureUsing(function ($c): void {
                $c->label(__('forms.fields.' . $c->getName()));
            });
        }
        Field::macro('translatable', function () {
            return $this->hint(__('forms.fields.translatable'))
                ->hintIcon('heroicon-m-language');
        });

        Table::configureUsing(function (Table $table): void {
            $table->modifyQueryUsing(function (Builder $query): void {
                if ($query->getColumns()->getModel()->getCreatedAtColumn()) {
                    $query->latest();
                }

            });
        });

        TextEntry::configureUsing(function (TextEntry $field): void {
            $field->label(__('forms.fields.' . Str::replace('.', '_', $field->getName())));
        });

        Section::configureUsing(function (Section $section): void {
            $section
                ->collapsible()
                ->heading(__('sections.' . Str::lower($section->getHeading())));

        });
        Column::configureUsing(function ($c): void {
            $c->label(fn($column): string => __("forms.fields." . Str::replace('.', '_', $column->getName())))
                ->translateLabel()
                ->toggleable();
        });

        \Filament\Infolists\Components\Section::configureUsing(function (\Filament\Infolists\Components\Section $section): void {
            $section->collapsible()->heading(__('sections.' . Str::lower($section->getHeading())));
        });


    }

    private function cart() {
        $this->app->singleton('cart', function ($app) {
            $storageClass = config('shopping_cart.storage');
            $eventsClass = config('shopping_cart.events');
            $storage = $storageClass ? new $storageClass() : $app['session'];
            $events = $eventsClass ? new $eventsClass() : $app['events'];
            $instanceName = 'cart';
            if (!session()->has('cart_id')) {
                session(['cart_id' => uniqid()]);
            }
            $session_key = session('cart_id');
            return new Cart(
                $storage,
                $events,
                $instanceName,
                $session_key,
                config('shopping_cart')
            );
        });
        app('events')->listen('cart.cleared', function ($cart) {
            /** @var Cart $coreCart */
            $coreCart = $this->app['cart'];
            session(['cart_id' => uniqid()]);
            $session_key = session('cart_id');
            $coreCart->session($session_key);
        });
    }
}
