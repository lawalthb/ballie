<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;





class InvoiceController extends Controller
{
    /**

     * Display a listing of the invoices.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {



        // Sample data for invoices
        $invoices = [
            (object)[
                'id' => 1,
                'invoice_number' => 'INV-2023-001',
                'customer_name' => 'Acme Corporation',
                'issue_date' => now()->subDays(15),
                'due_date' => now()->addDays(15),
                'total_amount' => 2500.00,
                'status' => 'pending',
                'customer_id' => 1
            ],
            (object)[
                'id' => 2,
                'invoice_number' => 'INV-2023-002',
                'customer_name' => 'Globex Industries',
                'issue_date' => now()->subDays(30),
                'due_date' => now()->subDays(5),
                'total_amount' => 1750.50,
                'status' => 'overdue',
                'customer_id' => 2
            ],
            (object)[
                'id' => 3,
                'invoice_number' => 'INV-2023-003',
                'customer_name' => 'Wayne Enterprises',
                'issue_date' => now()->subDays(5),
                'due_date' => now()->addDays(25),
                'total_amount' => 3200.75,
                'status' => 'pending',
                'customer_id' => 3
            ],
            (object)[
                'id' => 4,
                'invoice_number' => 'INV-2023-004',
                'customer_name' => 'Stark Industries',
                'issue_date' => now()->subDays(45),
                'due_date' => now()->subDays(15),
                'total_amount' => 5000.00,
                'status' => 'paid',
                'customer_id' => 4
            ],
        ];








        // Stats for dashboard
        $stats = [
            'total_invoices' => count($invoices),
            'total_amount' => array_sum(array_column((array)$invoices, 'total_amount')),
            'pending_amount' => array_sum(array_map(function($invoice) {
                return $invoice->status === 'pending' ? $invoice->total_amount : 0;
            }, $invoices)),
            'overdue_amount' => array_sum(array_map(function($invoice) {
                return $invoice->status === 'overdue' ? $invoice->total_amount : 0;
            }, $invoices)),
        ];


        return view('tenant.invoices.index', compact('invoices', 'stats'));
    }

    /**

     * Show the form for creating a new invoice.
     *
     * @return \Illuminate\Http\Response
     */

    public function create()
    {












        // Sample data for customers dropdown
        $customers = [
            (object)['id' => 1, 'name' => 'Acme Corporation', 'email' => 'billing@acme.com'],
            (object)['id' => 2, 'name' => 'Globex Industries', 'email' => 'accounts@globex.com'],
            (object)['id' => 3, 'name' => 'Wayne Enterprises', 'email' => 'finance@wayne.com'],
            (object)['id' => 4, 'name' => 'Stark Industries', 'email' => 'invoices@stark.com'],
        ];










        // Sample data for products dropdown
        $products = [
            (object)['id' => 1, 'name' => 'Web Development', 'price' => 1000.00, 'description' => 'Custom website development'],
            (object)['id' => 2, 'name' => 'Mobile App Development', 'price' => 1500.00, 'description' => 'iOS and Android app development'],
            (object)['id' => 3, 'name' => 'SEO Services', 'price' => 500.00, 'description' => 'Search engine optimization'],
            (object)['id' => 4, 'name' => 'Graphic Design', 'price' => 300.00, 'description' => 'Logo and branding design'],
        ];










        // Get the next invoice number
        $nextInvoiceNumber = 'INV-' . date('Y') . '-' . sprintf('%03d', 5); // Assuming 4 existing invoices




        return view('tenant.invoices.create', compact('customers', 'products', 'nextInvoiceNumber'));
    }

    /**

     * Store a newly created invoice in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {


        // In a real application, we would validate and store the invoice
        // For now, just redirect with a success message
        return redirect()->route('tenant.invoices.index', ['tenant' => tenant()->slug])
            ->with('success', 'Invoice created successfully.');
    }

    /**

     * Display the specified invoice.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function show($id)
    {



        // Sample invoice data
        $invoice = (object)[
            'id' => $id,
            'invoice_number' => 'INV-2023-00' . $id,
            'customer_name' => 'Sample Customer ' . $id,
            'customer_email' => 'customer' . $id . '@example.com',
            'customer_address' => '123 Main St, City, Country',
            'issue_date' => now()->subDays(rand(5, 30)),
            'due_date' => now()->addDays(rand(5, 30)),
            'total_amount' => rand(1000, 5000) + (rand(0, 99) / 100),
            'status' => ['pending', 'paid', 'overdue'][rand(0, 2)],
            'notes' => 'Thank you for your business!',
            'items' => [
                (object)[
                    'id' => 1,
                    'description' => 'Web Development',
                    'quantity' => 1,
                    'unit_price' => 1000.00,
                    'total' => 1000.00
                ],
                (object)[
                    'id' => 2,
                    'description' => 'Hosting (Annual)',
                    'quantity' => 1,
                    'unit_price' => 200.00,
                    'total' => 200.00
                ],
                (object)[
                    'id' => 3,
                    'description' => 'SEO Services',
                    'quantity' => 2,
                    'unit_price' => 250.00,
                    'total' => 500.00
                ],
            ]
        ];


        return view('tenant.invoices.show', compact('invoice'));
    }

    /**

     * Show the form for editing the specified invoice.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function edit($id)
    {













        // Sample invoice data for editing
        $invoice = (object)[
            'id' => $id,
            'invoice_number' => 'INV-2023-00' . $id,
            'customer_id' => rand(1, 4),
            'issue_date' => now()->subDays(rand(5, 30)),
            'due_date' => now()->addDays(rand(5, 30)),
            'total_amount' => 1700.00,
            'status' => ['pending', 'paid', 'overdue'][rand(0, 2)],
            'notes' => 'Thank you for your business!',
            'items' => [
                (object)[
                    'id' => 1,
                    'product_id' => 1,
                    'description' => 'Web Development',
                    'quantity' => 1,
                    'unit_price' => 1000.00,
                    'total' => 1000.00
                ],
                (object)[
                    'id' => 2,
                    'product_id' => 2,
                    'description' => 'Hosting (Annual)',
                    'quantity' => 1,
                    'unit_price' => 200.00,
                    'total' => 200.00
                ],
                (object)[
                    'id' => 3,
                    'product_id' => 3,
                    'description' => 'SEO Services',
                    'quantity' => 2,
                    'unit_price' => 250.00,
                    'total' => 500.00
                ],
            ]
        ];









        // Sample data for customers dropdown
        $customers = [
            (object)['id' => 1, 'name' => 'Acme Corporation', 'email' => 'billing@acme.com'],
            (object)['id' => 2, 'name' => 'Globex Industries', 'email' => 'accounts@globex.com'],
            (object)['id' => 3, 'name' => 'Wayne Enterprises', 'email' => 'finance@wayne.com'],
            (object)['id' => 4, 'name' => 'Stark Industries', 'email' => 'invoices@stark.com'],
        ];






        // Sample data for products dropdown
        $products = [
            (object)['id' => 1, 'name' => 'Web Development', 'price' => 1000.00, 'description' => 'Custom website development'],
            (object)['id' => 2, 'name' => 'Mobile App Development', 'price' => 1500.00, 'description' => 'iOS and Android app development'],
            (object)['id' => 3, 'name' => 'SEO Services', 'price' => 500.00, 'description' => 'Search engine optimization'],
            (object)['id' => 4, 'name' => 'Graphic Design', 'price' => 300.00, 'description' => 'Logo and branding design'],
        ];


        return view('tenant.invoices.edit', compact('invoice', 'customers', 'products'));
    }
























    /**
     * Update the specified invoice in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // In a real application, we would validate and update the invoice
        // For now, just redirect with a success message
        return redirect()->route('tenant.invoices.index', ['tenant' => tenant()->slug])
            ->with('success', 'Invoice updated successfully.');
    }

    /**

     * Remove the specified invoice from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function destroy($id)
    {








        // In a real application, we would delete the invoice
        // For now, just redirect with a success message
        return redirect()->route('tenant.invoices.index', ['tenant' => tenant()->slug])
            ->with('success', 'Invoice deleted successfully.');
    }

    /**

     * Generate PDF for the specified invoice.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function generatePdf($id)
    {






        // In a real application, we would generate a PDF
        // For now, just redirect with a success message
        return redirect()->route('tenant.invoices.show', ['tenant' => tenant()->slug, 'invoice' => $id])
            ->with('success', 'PDF generated successfully.');
    }

    /**

     * Send the invoice to the customer via email.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function sendToCustomer($id)
    {















        // In a real application, we would send an email
        // For now, just redirect with a success message
        return redirect()->route('tenant.invoices.show', ['tenant' => tenant()->slug, 'invoice' => $id])
            ->with('success', 'Invoice sent to customer successfully.');
    }
}
