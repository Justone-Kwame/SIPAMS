<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

    // Dashboard Route
    Route::get('/dashboard', \App\Livewire\Dashboard\Overview::class)
        ->middleware(['auth', 'verified'])
        ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::view('profile', 'profile')->name('profile');

    // Category Routes
    Route::get('/categories', \App\Livewire\Products\CategoryList::class)->name('categories.index');
    Route::get('/categories/create', \App\Livewire\Products\CategoryForm::class)->name('categories.create');
    Route::get('/categories/{categoryId}', \App\Livewire\Products\CategoryForm::class)->name('categories.edit');

    // Product Routes
    Route::get('/products', \App\Livewire\Products\ProductList::class)->name('products.index');
    Route::get('/products/create', \App\Livewire\Products\ProductForm::class)->name('products.create');
    Route::get('/products/{productId}', \App\Livewire\Products\ProductForm::class)->name('products.edit');

    // POS Route
    Route::get('/pos', \App\Livewire\Pos\PosInterface::class)->name('pos.index');
    Route::get('/sales/{sale}/receipt', [\App\Http\Controllers\ReceiptController::class, 'download'])->name('sales.receipt');
    Route::get('/sales/{sale}/invoice', [\App\Http\Controllers\ReceiptController::class, 'invoice'])->name('sales.invoice');

    // Expense Routes
    Route::get('/expenses', \App\Livewire\Accounting\ExpenseList::class)->name('expenses.index');
    Route::get('/expenses/create', \App\Livewire\Accounting\ExpenseForm::class)->name('expenses.create');
    Route::get('/expenses/{expenseId}/edit', \App\Livewire\Accounting\ExpenseForm::class)->name('expenses.edit');

    // Inventory Routes
    Route::get('/inventory', \App\Livewire\Inventory\InventoryDashboard::class)->name('inventory.index');
    Route::get('/inventory/stock-in', \App\Livewire\Inventory\StockMovementForm::class)->name('inventory.stock-in');
    Route::get('/inventory/stock-out', \App\Livewire\Inventory\StockMovementForm::class)->name('inventory.stock-out');
    Route::get('/inventory/movements', \App\Livewire\Inventory\StockMovementsLog::class)->name('inventory.movements');
    Route::get('/inventory/batches', \App\Livewire\Inventory\ProductBatches::class)->name('inventory.batches');

    // Role & Permission Routes
    Route::get('/roles', \App\Livewire\Roles\RoleList::class)->name('roles.index');
    Route::get('/roles/create', \App\Livewire\Roles\RoleForm::class)->name('roles.create');
    Route::get('/roles/{roleId}', \App\Livewire\Roles\RoleForm::class)->name('roles.edit');

    // Report Routes
    Route::get('/reports/sales', \App\Livewire\Reports\SalesReport::class)->name('reports.sales');
    Route::get('/reports/inventory', \App\Livewire\Reports\InventoryReport::class)->name('reports.inventory');
    Route::get('/reports/financial', \App\Livewire\Reports\FinancialReport::class)->name('reports.financial');
    Route::get('/reports/profit-loss', \App\Livewire\Reports\ProfitLoss::class)->name('reports.profit-loss');
    Route::get('/reports/profit-loss/print', [\App\Http\Controllers\ReportController::class, 'profitLossPrint'])->name('reports.profit-loss.print');
    Route::get('/reports/product-movement', \App\Livewire\Reports\ProductMovementReport::class)->name('reports.product-movement');
    Route::get('/reports/audit-trail', \App\Livewire\Reports\AuditTrail::class)->name('reports.audit-trail');
    
    // Export Routes
    Route::get('/reports/sales/export/csv', [\App\Http\Controllers\ReportController::class, 'exportSalesCsv'])->name('reports.sales.export.csv');
    Route::get('/reports/inventory/export/csv', [\App\Http\Controllers\ReportController::class, 'exportInventoryCsv'])->name('reports.inventory.export.csv');
    Route::get('/reports/sales/export/pdf', [\App\Http\Controllers\ReportController::class, 'exportSalesPdf'])->name('reports.sales.export.pdf');

    // Supplier Routes
    Route::get('/suppliers', \App\Livewire\Suppliers\SupplierList::class)->name('suppliers.index');
    Route::get('/suppliers/create', \App\Livewire\Suppliers\SupplierForm::class)->name('suppliers.create');
    Route::get('/suppliers/{supplierId}/edit', \App\Livewire\Suppliers\SupplierForm::class)->name('suppliers.edit');

    // Purchase Order Routes
    Route::get('/purchases', \App\Livewire\Purchases\PurchaseOrderList::class)->name('purchases.index');
    Route::get('/purchases/create', \App\Livewire\Purchases\PurchaseOrderForm::class)->name('purchases.create');
    Route::get('/purchases/{purchaseOrderId}/edit', \App\Livewire\Purchases\PurchaseOrderForm::class)->name('purchases.edit');

    // Customer Routes
    Route::get('/customers', \App\Livewire\Customers\CustomerList::class)->name('customers.index');
    Route::get('/customers/create', \App\Livewire\Customers\CustomerForm::class)->name('customers.create');
    Route::get('/customers/{customerId}/edit', \App\Livewire\Customers\CustomerForm::class)->name('customers.edit');
});

require __DIR__.'/auth.php';
