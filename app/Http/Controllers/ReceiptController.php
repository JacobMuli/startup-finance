<?php

namespace App\Http\Controllers;

use App\Models\Receipt;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReceiptController extends Controller
{
    /**
     * Securely stream a receipt file from private storage.
     */
    public function show($id)
    {
        // Simple authentication check via middleware (auth)
        // We could add more granular checks here (e.g., role:admin)
        
        $receipt = Receipt::findOrFail($id);

        if (!Storage::disk('local')->exists($receipt->file_path)) {
            abort(404, 'Receipt file not found.');
        }

        $path = storage_path('app/private/' . $receipt->file_path);
        
        // Basic mime-type detection for better streaming
        $extension = pathinfo($path, PATHINFO_EXTENSION);
        $mimeTypes = [
            'pdf' => 'application/pdf',
            'png' => 'image/png',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
        ];
        
        $contentType = $mimeTypes[strtolower($extension)] ?? 'application/octet-stream';

        return response()->file($path, [
            'Content-Type' => $contentType,
            'Content-Disposition' => 'inline; filename="' . basename($path) . '"',
        ]);
    }
}
