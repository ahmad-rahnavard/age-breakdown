<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\AgeBreakdownRequest;

class AgeController extends Controller
{
    /**
     * It receives a required CSV "file" containing "name,age" contents in each line
     * and returns a JSON response with a breakdown of the ages and percentage of repeated ages
     *
     * @param AgeBreakdownRequest $request
     *
     * @return JsonResponse
     */
    public function breakdown(AgeBreakdownRequest $request): JsonResponse
    {
        $filePath = $request->file->getPathname();
        $CSVData  = $this->getCSVData($filePath);

        // Validation error, if the file has no content
        if (count($CSVData) === 0) {
            return Response::error(
                'The file is empty!',
                ['file' => 'The file has no data!'],
                422
            );
        }

        $collection = collect($CSVData);
        $totalItems = $collection->count();
        $counts     = $collection->countBy('age');
        // The structure is [age => percentage,...] e.g. [22 => 40,...]
        $data = $counts->map(fn($item) => 100 * $item / $totalItems)->toArray();

        return Response::success(
            'The ages are broken down successfully!',
            $data
        );
    }

    /**
     * @param string $filePath
     *
     * @return array
     */
    protected function getCSVData(string $filePath): array
    {
        $file = fopen($filePath, 'r');
        $data = [];

        while ($datum = fgetcsv($file, 1000, ',')) {
            // TODO: validate data & handle the first line (header) or use a package like maatwebsite/excel
            $data[] = [
                'name' => $datum[0],
                'age'  => $datum[1]
            ];
        }

        fclose($file);

        return $data;
    }
}
