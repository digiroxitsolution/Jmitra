<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sales; // Example model
use ConsoleTVs\Charts\Classes\Chartjs\Chart;

use Illuminate\Support\Facades\Storage;

class ChartController extends Controller
{
    public function index()
    {
        // Fetch data from the database
        $sales = Sales::selectRaw('MONTH(created_at) as month, SUM(sales_amount) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Create the chart
        $chart = new Chart;
        $chart->labels($sales->pluck('month')->map(fn($month) => date('F', mktime(0, 0, 0, $month, 1))))
              ->dataset('Monthly Sales', 'bar', $sales->pluck('total'))
              ->options([
                  'responsive' => true,
                  'maintainAspectRatio' => false,
                  'scales' => [
                      'yAxes' => [
                          [
                              'ticks' => [
                                  'beginAtZero' => true,
                              ],
                          ],
                      ],
                  ],
              ]);

        return view('charts', compact('chart'));
    }

    public function saveSeAnalysisChartImage(Request $request)
    {
        $imageData = $request->input('image');
        $image = str_replace('data:image/png;base64,', '', $imageData);
        $image = str_replace(' ', '+', $image);
        
        // Define the directory and file name
        $imageName = 'se_analysis.png';
        $directory = 'assets/generated_chart_image';
        $publicPath = public_path($directory . '/' . $imageName);

        // Ensure the directory exists
        if (!file_exists(public_path($directory))) {
            mkdir(public_path($directory), 0755, true);
        }

        // Save the image
        file_put_contents($publicPath, base64_decode($image));

        return response()->json([
            'success' => true,
            'message' => 'Chart saved successfully!',
            'path' => url($directory . '/' . $imageName), // Include the directory in the path
        ]);
    }

    public function saveTotalExpenseChartImage(Request $request)
    {
        $imageData = $request->input('image');
        $image = str_replace('data:image/png;base64,', '', $imageData);
        $image = str_replace(' ', '+', $image);
        
        // Define the directory and file name
        $imageName = 'total_expense.png';
        $directory = 'assets/generated_chart_image';
        $publicPath = public_path($directory . '/' . $imageName);

        // Ensure the directory exists
        if (!file_exists(public_path($directory))) {
            mkdir(public_path($directory), 0755, true);
        }

        // Save the image
        file_put_contents($publicPath, base64_decode($image));

        return response()->json([
            'success' => true,
            'message' => 'Total Expense Chart saved successfully!',
            'path' => url($directory . '/' . $imageName), // Include the directory in the path
        ]);

    }

    public function saveFareChartImage(Request $request)
    {
        $imageData = $request->input('image');
        $image = str_replace('data:image/png;base64,', '', $imageData);
        $image = str_replace(' ', '+', $image);
        
        // Define the directory and file name
        $imageName = 'fare.png';
        $directory = 'assets/generated_chart_image';
        $publicPath = public_path($directory . '/' . $imageName);

        // Ensure the directory exists
        if (!file_exists(public_path($directory))) {
            mkdir(public_path($directory), 0755, true);
        }

        // Save the image
        file_put_contents($publicPath, base64_decode($image));

        return response()->json([
            'success' => true,
            'message' => 'Fare Chart saved successfully!',
            'path' => url($directory . '/' . $imageName), // Include the directory in the path
        ]);

    }

    public function savePaxChartImage(Request $request)
    {
        $imageData = $request->input('image');
        $image = str_replace('data:image/png;base64,', '', $imageData);
        $image = str_replace(' ', '+', $image);
        
        // Define the directory and file name
        $imageName = 'PAX.png';
        $directory = 'assets/generated_chart_image';
        $publicPath = public_path($directory . '/' . $imageName);

        // Ensure the directory exists
        if (!file_exists(public_path($directory))) {
            mkdir(public_path($directory), 0755, true);
        }

        // Save the image
        file_put_contents($publicPath, base64_decode($image));

        return response()->json([
            'success' => true,
            'message' => 'Pax Chart saved successfully!',
            'path' => url($directory . '/' . $imageName), // Include the directory in the path
        ]);

    }
}
