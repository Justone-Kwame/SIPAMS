<?php

use App\Http\Controllers\ReceiptController;
use App\Http\Controllers\ReportController;
use App\Livewire\Accounting\ExpenseForm;
use App\Livewire\Accounting\ExpenseList;
use App\Livewire\Customers\CustomerForm;
use App\Livewire\Customers\CustomerList;
use App\Livewire\Dashboard\Overview;
use App\Livewire\Inventory\InventoryDashboard;
use App\Livewire\Inventory\ProductBatches;
use App\Livewire\Inventory\StockMovementForm;
use App\Livewire\Inventory\StockMovementsLog;
use App\Livewire\Pos\PosInterface;
use App\Livewire\Products\CategoryForm;
use App\Livewire\Products\CategoryList;
use App\Livewire\Products\ProductForm;
use App\Livewire\Products\ProductList;
use App\Livewire\Purchases\PurchaseOrderForm;
use App\Livewire\Purchases\PurchaseOrderList;
use App\Livewire\Reports\AuditTrail;
use App\Livewire\Reports\FinancialReport;
use App\Livewire\Reports\InventoryReport;
use App\Livewire\Reports\ProductMovementReport;
use App\Livewire\Reports\ProfitLoss;
use App\Livewire\Reports\SalesReport;
use App\Livewire\Returns\PurchaseReturnManager;
use App\Livewire\Returns\SalesReturnManager;
use App\Livewire\Roles\RoleForm;
use App\Livewire\Roles\RoleList;
use App\Livewire\Suppliers\SupplierForm;
use App\Livewire\Suppliers\SupplierList;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

// Dashboard Route
Route::get('/dashboard', Overview::class)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::view('profile', 'profile')->name('profile');

    // Category Routes
    Route::get('/categories', CategoryList::class)->name('categories.index');
    Route::get('/categories/create', CategoryForm::class)->name('categories.create');
    Route::get('/categories/{categoryId}', CategoryForm::class)->name('categories.edit');

    // Product Routes
    Route::get('/products', ProductList::class)->name('products.index');
    Route::get('/products/create', ProductForm::class)->name('products.create');
    Route::get('/products/{productId}', ProductForm::class)->name('products.edit');

    // POS Route
    Route::get('/pos', PosInterface::class)->name('pos.index');
    Route::get('/sales/{sale}/receipt', [ReceiptController::class, 'download'])->name('sales.receipt');
    Route::get('/sales/{sale}/invoice', [ReceiptController::class, 'invoice'])->name('sales.invoice');

    // Expense Routes
    Route::get('/expenses', ExpenseList::class)->name('expenses.index');
    Route::get('/expenses/create', ExpenseForm::class)->name('expenses.create');
    Route::get('/expenses/{expenseId}/edit', ExpenseForm::class)->name('expenses.edit');

    // Inventory Routes
    Route::get('/inventory', InventoryDashboard::class)->name('inventory.index');
    Route::get('/inventory/stock-in', StockMovementForm::class)->name('inventory.stock-in');
    Route::get('/inventory/stock-out', StockMovementForm::class)->name('inventory.stock-out');
    Route::get('/inventory/movements', StockMovementsLog::class)->name('inventory.movements');
    Route::get('/inventory/batches', ProductBatches::class)->name('inventory.batches');

    // Role & Permission Routes
    Route::get('/roles', RoleList::class)->name('roles.index');
    Route::get('/roles/create', RoleForm::class)->name('roles.create');
    Route::get('/roles/{roleId}', RoleForm::class)->name('roles.edit');

    // Report Routes
    Route::get('/reports/sales', SalesReport::class)->name('reports.sales');
    Route::get('/reports/inventory', InventoryReport::class)->name('reports.inventory');
    Route::get('/reports/financial', FinancialReport::class)->name('reports.financial');
    Route::get('/reports/profit-loss', ProfitLoss::class)->name('reports.profit-loss');
    Route::get('/reports/profit-loss/print', [ReportController::class, 'profitLossPrint'])->name('reports.profit-loss.print');
    Route::get('/reports/product-movement', ProductMovementReport::class)->name('reports.product-movement');
    Route::get('/reports/audit-trail', AuditTrail::class)->name('reports.audit-trail');

    // Accounting Routes (Profit and Loss also accessible under Accounting)
    Route::get('/accounting/profit-loss', ProfitLoss::class)->name('accounting.profit-loss');
    Route::get('/accounting/profit-loss/print', [ReportController::class, 'profitLossPrint'])->name('accounting.profit-loss.print');

    // Export Routes
    Route::get('/reports/sales/export/csv', [ReportController::class, 'exportSalesCsv'])->name('reports.sales.export.csv');
    Route::get('/reports/inventory/export/csv', [ReportController::class, 'exportInventoryCsv'])->name('reports.inventory.export.csv');
    Route::get('/reports/sales/export/pdf', [ReportController::class, 'exportSalesPdf'])->name('reports.sales.export.pdf');

    // Supplier Routes
    Route::get('/suppliers', SupplierList::class)->name('suppliers.index');
    Route::get('/suppliers/create', SupplierForm::class)->name('suppliers.create');
    Route::get('/suppliers/{supplierId}/edit', SupplierForm::class)->name('suppliers.edit');

    // Purchase Order Routes
    Route::get('/purchases', PurchaseOrderList::class)->name('purchases.index');
    Route::get('/purchases/create', PurchaseOrderForm::class)->name('purchases.create');
    Route::get('/purchases/{purchaseOrderId}/edit', PurchaseOrderForm::class)->name('purchases.edit');

    // Returns Routes
    Route::get('/returns/sales', SalesReturnManager::class)->name('returns.sales');
    Route::get('/returns/purchases', PurchaseReturnManager::class)->name('returns.purchases');

    // Customer Routes
    Route::get('/customers', CustomerList::class)->name('customers.index');
    Route::get('/customers/create', CustomerForm::class)->name('customers.create');
    Route::get('/customers/{customerId}/edit', CustomerForm::class)->name('customers.edit');
});

require __DIR__.'/auth.php';
