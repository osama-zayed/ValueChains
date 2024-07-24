<?php

namespace App\Providers\Filament;

use App\Filament\Pages\UserProfile;
use App\Filament\Resources\ExchangeResource\Widgets\ExchangeChart;
use App\Filament\Resources\ProductResource\Widgets\StatsOverview;
use App\Filament\Resources\SupplyResource\Widgets\SupplyChart;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use App\Filament\Pages\Auth\Login;
use App\Filament\Pages\EditProfile;
use App\Filament\Resources\CollectingMilkFromFamilyResource\Widgets\CollectingMilkFromFamilyChart;
use App\Filament\Resources\ReceiptInvoiceFromStoreResource\Widgets\ReceiptInvoiceFromStoreChart;
use App\Filament\Resources\TransferToFactoryResource\Widgets\TransferToFactoryChart;
use App\Http\Middleware\Permission;
use App\Http\Middleware\userStatus;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login(Login::class)
            ->colors([
                'primary' => Color::Blue,
                'secondary' => Color::Gray,
                'success' => Color::Green,
                'warning' => Color::Yellow,
                'danger' => Color::Red,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->databaseNotifications()
            ->profile(EditProfile::class)
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                // CollectingMilkFromFamilyChart::class,
                // ReceiptInvoiceFromStoreChart::class,
                // TransferToFactoryChart::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
                Permission::class . ":admin",
                userStatus::class,
            ]);
    }
}
