<?php

namespace Tasawk\Providers;

use Spatie\Permission\Models\Role;
use Tasawk\Models\AddressBook;
use Tasawk\Models\Branch;
use Tasawk\Models\Catalog\Allergen;
use Tasawk\Models\Catalog\Category;
use Tasawk\Models\Catalog\Option;
use Tasawk\Models\Catalog\Product;
use Tasawk\Models\ContactType;
use Tasawk\Models\Content\Banner;
use Tasawk\Models\Content\Contact;
use Tasawk\Models\Content\Page;
use Tasawk\Models\Customer;
use Tasawk\Models\Manager;
use Tasawk\Models\Operator;
use Tasawk\Models\Order;
use Tasawk\Policies\AddressBookPolicy;
use Tasawk\Policies\BranchPolicy;
use Tasawk\Policies\Catalog\AllergenPolicy;
use Tasawk\Policies\Catalog\CategoryPolicy;
use Tasawk\Policies\Catalog\OptionPolicy;
use Tasawk\Policies\Catalog\ProductPolicy;
use Tasawk\Policies\ContactTypePolicy;
use Tasawk\Policies\Content\BannerPolicy;
use Tasawk\Policies\Content\ContactPolicy;
use Tasawk\Policies\Content\PagePolicy;
use Tasawk\Policies\CustomerPolicy;
use Tasawk\Policies\ManagerPolicy;
use Tasawk\Policies\OperatorPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Tasawk\Policies\OrderPolicy;
use Tasawk\Policies\RolePolicy;

class AuthServiceProvider extends ServiceProvider {
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Customer::class => CustomerPolicy::class,
        AddressBook::class => AddressBookPolicy::class,
        Branch::class => BranchPolicy::class,
        Product::class => ProductPolicy::class,
        Option::class => OptionPolicy::class,
        Category::class => CategoryPolicy::class,
        Allergen::class => AllergenPolicy::class,
        Manager::class => ManagerPolicy::class,
        Operator::class => OperatorPolicy::class,
        Page::class => PagePolicy::class,
        Banner::class => BannerPolicy::class,
        Contact::class => ContactPolicy::class,
        ContactType::class => ContactTypePolicy::class,
        Role::class=>RolePolicy::class,
        Order::class=>OrderPolicy::class,

    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void {
        //
    }
}
