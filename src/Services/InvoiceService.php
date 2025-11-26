<?php

namespace App\Services;

use App\Repository\InvoiceRepositoryInterface;

class InvoiceService
{
    private InvoiceRepositoryInterface $repository;

    public function __construct(InvoiceRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getAll(): array
    {
        $invoices = $this->repository->findAll();

        foreach ($invoices as &$invoice) {
            $invoice['status'] = strtoupper($invoice['status']);
        }

        return $invoices;
    }

    public function getById(int $id): ?array
    {
        $invoice = $this->repository->findById($id);
        if (!$invoice) return null;

        $invoice['is_pending'] = $invoice['status'] === 'pending';

        return $invoice;
    }

    public function create(array $data): array
    {
        // Calcul automatique de tva_amount si non fourni
        if (!isset($data['tva_amount']) && isset($data['total_amount'], $data['tva_percentage'])) {
            $data['tva_amount'] = $data['total_amount'] * ($data['tva_percentage'] / 100);
        }

        // Définir le status par défaut si non fourni
        if (!isset($data['status'])) {
            $data['status'] = 'pending';
        }

        $id = $this->repository->save($data);

        return $this->getById($id);
    }

    public function update(int $id, array $data): ?array
    {
        $invoice = $this->repository->findById($id);
        if (!$invoice) return null;

        $this->repository->save(array_merge($invoice, $data));

        return $this->getById($id);
    }

    public function delete(int $id): bool
    {
        $invoice = $this->repository->findById($id);
        if (!$invoice) return false;

        return $this->repository->delete($id);
    }
}
