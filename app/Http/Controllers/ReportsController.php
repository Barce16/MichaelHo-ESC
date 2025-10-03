<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Customer;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;


class ReportsController extends Controller
{

    public function index()
    {
        $events = Event::all();
        $customers = Customer::all();
        $staffs = Staff::all();

        return view('reports.index', compact('events', 'customers', 'staffs'));
    }

    public function generateEventReport(Request $request)
    {
        $format = $request->query('format', 'csv');

        $events = Event::with(['package', 'guests', 'staffs'])->get();

        if ($format === 'csv') {
            return $this->generateEventCSV($events);
        } elseif ($format === 'pdf') {
            return $this->generateEventPDF($events);
        }

        return back()->with('error', 'Invalid report format.');
    }


    public function generateCustomerReport(Request $request)
    {
        $format = $request->query('format', 'csv');

        $customers = Customer::all();

        if ($format === 'csv') {
            return $this->generateCustomerCSV($customers);
        } elseif ($format === 'pdf') {
            return $this->generateCustomerPDF($customers);
        }

        return back()->with('error', 'Invalid report format.');
    }

    public function generateStaffReport(Request $request)
    {
        $format = $request->query('format', 'csv');

        $staffs = Staff::all();

        if ($format === 'csv') {
            return $this->generateStaffCSV($staffs);
        } elseif ($format === 'pdf') {
            return $this->generateStaffPDF($staffs);
        }

        return back()->with('error', 'Invalid report format.');
    }

    protected function generateEventCSV($events)
    {
        $headers = ['Event Name', 'Date', 'Package', 'Venue', 'Theme', 'Guests', 'Staffs', 'Grand Total'];

        $rows = [];

        foreach ($events as $event) {
            $grandTotal = $event->billing ? $event->billing->total_amount : 0;

            $rows[] = [
                $event->name,
                $event->event_date->format('Y-m-d'),
                $event->package->name,
                $event->venue,
                $event->theme,
                $event->guests->count(),
                $event->staffs->count(),
                number_format($grandTotal, 2),
            ];
        }

        return $this->exportCSV($headers, $rows, 'event-report.csv');
    }


    protected function generateEventPDF()
    {
        $events = Event::with(['package', 'guests', 'staffs'])->get();
        $pdf = Pdf::loadView('reports.events', ['events' => $events]);
        return $pdf->download('event-list-report.pdf');
    }

    protected function generateCustomerCSV($customers)
    {
        $headers = ['Customer Name', 'Email', 'Phone', 'Address'];

        $title = "Customer Report";

        $rows = $customers->map(function ($customer) {
            return [$customer->user->name, $customer->email, $customer->phone, $customer->address];
        });

        $content = [];

        $content[] = [$title];
        $content[] = [];
        $content[] = $headers;
        $content = array_merge($content, $rows->toArray());

        return $this->exportCSV([], $content, 'customer-report.csv');
    }


    protected function generateCustomerPDF($customers)
    {
        $pdf = Pdf::loadView('reports.customer', ['customers' => $customers]);
        return $pdf->download('customer-report.pdf');
    }

    protected function generateStaffCSV($staffs)
    {
        $headers = ['Staff Name', 'Role', 'Email', 'Contact Number'];
        $rows = $staffs->map(function ($staff) {
            return [$staff->name, $staff->role_type, $staff->user->email, $staff->contact_number];
        });

        $title = "Staff Report";
        $content = [];

        $content[] = [$title];

        $content[] = [];

        $content[] = $headers;

        $content = array_merge($content, $rows->toArray());

        return $this->exportCSV([], $content, 'staff-report.csv');
    }

    protected function generateStaffPDF($staffs)
    {
        $pdf = Pdf::loadView('reports.staff', ['staffs' => $staffs]);
        return $pdf->download('staff-report.pdf');
    }


    protected function exportCSV($headers, $rows, $filename)
    {
        return response()->streamDownload(function () use ($headers, $rows) {
            $out = fopen('php://output', 'w');
            if (!empty($headers)) fputcsv($out, $headers);
            foreach ($rows as $r) fputcsv($out, $r);
            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv']);
    }
}
