<?php

namespace App\Controllers;

use App\Services\InvoiceService;
use App\Repository\InvoiceRepository;
use App\Database\Database;

class InvoiceController
{
    private InvoiceService $invoiceService;

    public function __construct()
    {
        $db = Database::getInstance()->getConnection();
        $repository = new InvoiceRepository($db);
        $this->invoiceService = new InvoiceService($repository);
    }

    public function index(): void
    {
        $invoices = $this->invoiceService->getAll();
        echo json_encode($invoices);
    }

    public function show(): void
    {
        $id = $_GET['id'] ?? null;

        if (!$id) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing invoice id']);
            return;
        }

        $invoice = $this->invoiceService->getById((int)$id);

        if (!$invoice) {
            http_response_code(404);
            echo json_encode(['error' => 'Invoice not found']);
            return;
        }

        echo json_encode($invoice);
    }

    public function store(): void
    {
        $data = json_decode(file_get_contents('php://input'), true);

        if (!$data) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid JSON']);
            return;
        }

        $invoice = $this->invoiceService->create($data);
        http_response_code(201);
        echo json_encode($invoice);
    }

    public function update(): void
    {
        $id = $_GET['id'] ?? null;
        $data = json_decode(file_get_contents('php://input'), true);

        if (!$id || !$data) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing ID or data']);
            return;
        }

        $invoice = $this->invoiceService->update((int)$id, $data);

        if (!$invoice) {
            http_response_code(404);
            echo json_encode(['error' => 'Invoice not found']);
            return;
        }

        echo json_encode($invoice);
    }

    // ✅ DELETE /invoices?id=1
    public function delete(): void
    {
        $id = $_GET['id'] ?? null;

        if (!$id) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing invoice id']);
            return;
        }

        $success = $this->invoiceService->delete((int)$id);

        if (!$success) {
            http_response_code(404);
            echo json_encode(['error' => 'Invoice not found']);
            return;
        }

        echo json_encode(['message' => 'Invoice deleted successfully']);
    }
}
