<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tenant;
use App\Models\Invoice;
use App\Models\Customer;
use App\Models\Product;

class InvoiceController extends Controller
{
    /**
     * Display a listing of invoices
     */
    public function index(Request $request, Tenant $tenant)
    {
        $invoices = Invoice::with('customer')->paginate(15);
        return view('tenant.invoices.index', compact('invoices', 'tenant'));
    }

    /**
     * Show the form for creating a new invoice
     */
    public function create(Tenant $tenant)
    {
        $customers = Customer::all();
        $products = Product::where('is_active', true)->get();

        return view('tenant.invoices.create', compact('tenant', 'customers', 'products'));
    }

    /**
     * Store a newly created invoice
     */
    public function store(Request $request, Tenant $tenant)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'invoice_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:invoice_date',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.description' => 'nullable|string',
            'notes' => 'nullable|string',
            'terms' => 'nullable|string',
        ]);

        // Create invoice
        $invoice = Invoice::create([
            'customer_id' => $validated['customer_id'],
            'invoice_date' => $validated['invoice_date'],
            'due_date' => $validated['due_date'],
            'notes' => $validated['notes'] ?? null,
            'terms' => $validated['terms'] ?? null,
            'status' => 'draft',
        ]);

        // Add invoice items
        foreach ($validated['items'] as $item) {
            $invoice->items()->create([
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'description' => $item['description'] ?? null,
            ]);
        }

        return redirect()
            ->route('tenant.invoices.show', ['tenant' => $tenant->slug, 'invoice' => $invoice->id])
            ->with('success', 'Invoice created successfully.');
    }

    /**
     * Display the specified invoice
     */
    public function show(Tenant $tenant, Invoice $invoice)
    {
        $invoice->load(['customer', 'items.product']);
        return view('tenant.invoices.show', compact('tenant', 'invoice'));
    }

    /**
     * Show the form for editing the specified invoice
     */
    public function edit(Tenant $tenant, Invoice $invoice)
    {
        $invoice->load(['customer', 'items.product']);
        $customers = Customer::all();
        $products = Product::where('is_active', true)->get();

        return view('tenant.invoices.edit', compact('tenant', 'invoice', 'customers', 'products'));
    }

    /**
     * Update the specified invoice
     */
    public function update(Request $request, Tenant $tenant, Invoice $invoice)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'invoice_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:invoice_date',
            'items' => 'required|array|min:1',
            'items.*.id' => 'nullable|exists:invoice_items,id',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.description' => 'nullable|string',
            'notes' => 'nullable|string',
            'terms' => 'nullable|string',
        ]);

        // Update invoice
        $invoice->update([
            'customer_id' => $validated['customer_id'],
            'invoice_date' => $validated['invoice_date'],
            'due_date' => $validated['due_date'],
            'notes' => $validated['notes'] ?? null,
            'terms' => $validated['terms'] ?? null,
        ]);

        // Delete existing items not in the request
        $existingItemIds = collect($validated['items'])
            ->pluck('id')
            ->filter()
            ->toArray();

        $invoice->items()->whereNotIn('id', $existingItemIds)->delete();

        // Update or create items
        foreach ($validated['items'] as $item) {
            if (!empty($item['id'])) {
                // Update existing item
                $invoice->items()->where('id', $item['id'])->update([
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'description' => $item['description'] ?? null,
                ]);
            } else {
                // Create new item
                $invoice->items()->create([
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'description' => $item['description'] ?? null,
                ]);
            }
        }

        return redirect()
            ->route('tenant.invoices.show', ['tenant' => $tenant->slug, 'invoice' => $invoice->id])
            ->with('success', 'Invoice updated successfully.');
    }

    /**
     * Remove the specified invoice
     */
    public function destroy(Tenant $tenant, Invoice $invoice)
    {
        // Delete invoice items first
        $invoice->items()->delete();

        // Delete the invoice
        $invoice->delete();

        return redirect()
            ->route('tenant.invoices.index', ['tenant' => $tenant->slug])
            ->with('success', 'Invoice deleted successfully.');
    }

    /**
     * Generate PDF for the invoice
     */
    public function generatePdf(Tenant $tenant, Invoice $invoice)
    {
        $invoice->load(['customer', 'items.product']);

        // Here you would typically use a PDF library like DomPDF, TCPDF, or Snappy
        // For this example, we'll just return a view that could be used for PDF generation

        return view('tenant.invoices.pdf', compact('tenant', 'invoice'));
    }

    /**
     * Send invoice to customer via email
     */
    public function sendToCustomer(Request $request, Tenant $tenant, Invoice $invoice)
    {
        $invoice->load(['customer', 'items.product']);

        // Here you would typically:
        // 1. Generate the PDF
        // 2. Send an email with the PDF attached
        // 3. Update the invoice status

        // For this example, we'll just simulate success

        $invoice->update([
            'status' => 'sent',
            'sent_at' => now(),
        ]);

        return back()->with('success', 'Invoice sent to customer successfully.');
    }
}