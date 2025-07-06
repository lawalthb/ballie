<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tenant;

class CrmController extends Controller
{
    /**
     * Display the CRM dashboard
     */
    public function index(Request $request, Tenant $tenant)
    {
        $currentTenant = $tenant;
        $user = auth()->user();

        // Sample CRM data - replace with actual database queries
        $crmData = [
            'total_customers' => 1247,
            'active_customers' => 1089,
            'total_vendors' => 89,
            'pending_quotes' => 23,
            'recent_customers' => [
                [
                    'name' => 'John Doe',
                    'email' => 'john.doe@example.com',
                    'status' => 'Active',
                    'added_date' => 'today'
                ],
                [
                    'name' => 'Alice Smith',
                    'email' => 'alice.smith@company.com',
                    'status' => 'Active',
                    'added_date' => '2 days ago'
                ],
                [
                    'name' => 'Mike Brown',
                    'email' => 'mike.brown@business.com',
                    'status' => 'Pending',
                    'added_date' => '3 days ago'
                ],
                [
                    'name' => 'Sarah Johnson',
                    'email' => 'sarah.j@startup.com',
                    'status' => 'Active',
                    'added_date' => '1 week ago'
                ],
                [
                    'name' => 'Robert Wilson',
                    'email' => 'robert.w@enterprise.com',
                    'status' => 'Active',
                    'added_date' => '1 week ago'
                ]
            ],
            'recent_activities' => [
                [
                    'type' => 'customer_added',
                    'description' => 'John Doe was added as a new customer',
                    'time' => '2 hours ago',
                    'icon' => 'user'
                ],
                [
                    'type' => 'quote_sent',
                    'description' => 'Quote #QT-001 was sent to Alice Smith',
                    'time' => '4 hours ago',
                    'icon' => 'document'
                ],
                [
                    'type' => 'meeting_scheduled',
                    'description' => 'Meeting scheduled with Mike Brown',
                    'time' => '6 hours ago',
                    'icon' => 'calendar'
                ],
                [
                    'type' => 'vendor_added',
                    'description' => 'New vendor Tech Solutions Inc. was added',
                    'time' => '1 day ago',
                    'icon' => 'building'
                ],
                [
                    'type' => 'follow_up',
                    'description' => 'Follow-up reminder for Sarah Johnson',
                    'time' => '2 days ago',
                    'icon' => 'warning'
                ],
                [
                    'type' => 'deal_closed',
                    'description' => 'Deal closed with Robert Wilson - $15,000',
                    'time' => '3 days ago',
                    'icon' => 'check'
                ]
            ]
        ];

        return view('tenant.crm.index', [
            'currentTenant' => $currentTenant,
            'user' => $user,
            'tenant' => $currentTenant,
            'crmData' => $crmData,
        ]);
    }
}
