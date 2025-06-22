<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class FileController extends Controller
{
    /**
     * Handle the file upload, rename, and store it in the public path.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadAndRenameFile(Request $request)
    {
        // Validate that the file is uploaded
        if (!$request->hasFile('uploaded_file') || !$request->file('uploaded_file')->isValid()) {
            return response()->json(['error' => 'No valid file uploaded.'], 400);
        }

        // Get the uploaded file
        $file = $request->file('uploaded_file');
        $file_name = $request->file_name;

        // Generate a meaningful file name (you can customize this function as needed)
        $newFileName = $this->generateMeaningfulFileName($file, $file_name);

        // Define the destination path in the public folder
        $destinationPath = public_path('assets/uploads'); // You can change 'uploads' to your preferred folder

        // Ensure the directory exists
        if (!File::exists($destinationPath)) {
            File::makeDirectory($destinationPath, 0777, true);
        }

        // Move the file to the public/uploads directory
        $file->move($destinationPath, $newFileName);

        // Return the file path relative to the public folder
        return 'assets/uploads/' . $newFileName;  // This will store the relative path in the database
    }

    /**
     * Generate a meaningful file name using current timestamp and original file name.
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @return string
     */
    private function generateMeaningfulFileName($file, $file_name)
    {
        // Extract the original file extension
        $extension = $file->getClientOriginalExtension();

        // Generate a new file name based on the provided file name and current timestamp
        $newFileName = $file_name . '_' . now()->format('Y_m_d_H_i_s') . '.' . $extension;

        return $newFileName;
    }


}
