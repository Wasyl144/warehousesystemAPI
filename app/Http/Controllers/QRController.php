<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QRController extends Controller
{
    public function generateQr(Request $request) {
        $path = storage_path('app/public/qr_codes/');
        File::ensureDirectoryExists($path);
        if (isset($request->itemId)) {
            try {
                $item = Item::findOrFail($request->itemId);
            } catch (ModelNotFoundException $e) {
                return response()->json([
                    'message' => 'Item not found'
                ], 404);
            }

            if (!File::exists($path . "qrcodeitem-$item->id.svg")) {
                QrCode::size(200)->encoding('UTF-8')->format('svg')->generate(json_encode([
                    'itemId' => $item->id
                ]), $path . "qrcodeitem-$item->id.svg");
            }

            if (isset($request->forceDownload) && $request->forceDownload > 0) {
                $file = $path . "qrcodeitem-$item->id.svg";
                return response()->download($file, "qrcodeitem-$item->id.svg");
            }

            return File::get($path . "qrcodeitem-$item->id.svg");

        }
        return response()->json([
            'message' => 'No params'
        ], 404);

    }
}
