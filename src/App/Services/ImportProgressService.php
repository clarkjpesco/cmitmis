<?php

declare(strict_types=1);

namespace App\Services;

use Framework\Database;
use InvalidArgumentException;
use Exception;

class ImportProgressService
{
    public function __construct(private Database $db) {}

    public function getImportProgress(string $importId)
    {
        $progress = $this->db->query(
            "SELECT * FROM import_progress WHERE id=:id",
            [
                'id' => $importId
            ]
        )->find();

        return $progress;
    }

    public function cleanupOldImports()
    {
        // Delete imports older than 7 days
        $this->db->query(
            "DELETE FROM import_progress 
         WHERE created_at < DATE_SUB(NOW(), INTERVAL 7 DAY)"
        );
    }
}
