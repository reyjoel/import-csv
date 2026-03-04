<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Customer;

class ImportCustomers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-customers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import customers from CSV';

    /**
     * Execute the console command.
     */

    public function handle(): int
    {
        $path = base_path('data/customers.csv');

        if (!file_exists($path)) {
            $this->error('CSV file not found.');
            return self::FAILURE;
        }

        $file = new \SplFileObject($path);
        $file->setFlags(
            \SplFileObject::READ_CSV |
            \SplFileObject::SKIP_EMPTY |
            \SplFileObject::DROP_NEW_LINE
        );

        // Read header explicitly
        $header = $file->fgetcsv();

        if (!$header || $header === [null]) {
            $this->error('Invalid CSV header.');
            return self::FAILURE;
        }

        //validate expected header structure
        $expectedHeader = [
            'id','first_name','last_name','email',
            'gender','ip_address','company','city',
            'title','website'
        ];

        if ($header !== $expectedHeader) {
            $this->error('CSV header does not match expected format.');
            return self::FAILURE;
        }

        $batchSize = 500; // adjust if needed
        $rows = [];
        $imported = 0;
        $skipped = 0;

        DB::beginTransaction();

        // Now process remaining rows safely
        try {

            while (!$file->eof()) {

                $row = $file->fgetcsv();

                if (!$row || $row === [null]) {
                    continue;
                }

                if (count($row) !== count($header)) {
                    $skipped++;
                    continue;
                }

                $data = array_combine($header, $row);

                if (empty($data['email'])) {
                    $skipped++;
                    continue;
                }

                $rows[] = [
                    'first_name' => $data['first_name'] ?? null,
                    'last_name'  => $data['last_name'] ?? null,
                    'email'      => strtolower(trim($data['email'])),
                    'gender'     => $data['gender'] ?? null,
                    'ip_address' => $data['ip_address'] ?? null,
                    'company'    => $data['company'] ?? null,
                    'city'       => $data['city'] ?? null,
                    'title'      => $data['title'] ?? null,
                    'website'    => $data['website'] ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                // 🔥 When batch full → upsert once
                if (count($rows) >= $batchSize) {

                    Customer::upsert(
                        $rows,
                        ['email'], // unique key
                        [
                            'first_name',
                            'last_name',
                            'gender',
                            'ip_address',
                            'company',
                            'city',
                            'title',
                            'website',
                            'updated_at'
                        ]
                    );

                    $imported += count($rows);
                    $rows = []; // reset batch
                }
            }

            // 🔥 Insert remaining rows
            if (!empty($rows)) {
                Customer::upsert(
                    $rows,
                    ['email'],
                    [
                        'first_name',
                        'last_name',
                        'gender',
                        'ip_address',
                        'company',
                        'city',
                        'title',
                        'website',
                        'updated_at'
                    ]
                );

                $imported += count($rows);
            }

            DB::commit();

        } catch (\Throwable $e) {

            DB::rollBack();
            $this->error('Import failed: ' . $e->getMessage());
            return self::FAILURE;
        }

        $this->info("Import complete.");
        $this->info("Processed: {$imported}");
        $this->warn("Skipped: {$skipped}");

        return self::SUCCESS;
    }
}
